<?php

namespace App\Services;

use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingRescheduled;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Services\WhatsAppService;
use App\Services\WarrantyService;

class BookingService
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepo,
        protected WhatsAppService $whatsApp,
        protected WarrantyService $warrantyService,
    ) {}

    public function createQuickBooking(array $validated): Booking
    {
        // If a user account exists with this phone, link the booking to them
        $existingUser = \App\Models\User::where('phone', $validated['guest_phone'])->first();

        $booking = $this->bookingRepo->createBooking([
            ...$validated,
            'user_id' => $existingUser?->id,
            'source'  => 'website',
        ]);

        event(new BookingCreated($booking->fresh(['items.service', 'user'])));

        return $booking;
    }

    public function createUserBooking(array $validated, int $userId): Booking
    {
        $user = \App\Models\User::find($userId);

        // Auto-fill guest fields from the authenticated user's profile
        $booking = $this->bookingRepo->createBooking([
            ...$validated,
            'user_id'     => $userId,
            'guest_name'  => $validated['guest_name']  ?? $user->name,
            'guest_phone' => $validated['guest_phone'] ?? $user->phone,
            'guest_email' => $validated['guest_email'] ?? $user->email,
            'source'      => 'website',
        ]);

        event(new BookingCreated($booking->fresh(['items.service', 'user'])));

        return $booking;
    }

    public function createAdminBooking(array $validated, $admin): Booking
    {
        $booking = $this->bookingRepo->createBooking([
            ...$validated,
            'source' => 'admin',
        ]);

        $this->bookingRepo->updateStatus($booking, 'confirmed', 'Created by admin', $admin);
        $booking = $booking->fresh(['items.service', 'user']);
        event(new BookingCreated($booking));

        return $booking;
    }

    public function confirmBooking(Booking $booking, $admin, ?string $note = null): Booking
    {
        $booking = $this->bookingRepo->updateStatus($booking, 'confirmed', $note ?? 'Booking confirmed', $admin);
        event(new BookingConfirmed($booking->fresh(['items.service', 'user'])));
        return $booking;
    }

    public function assignTechnician(Booking $booking, int $technicianId, $admin): Booking
    {
        $booking->update(['technician_id' => $technicianId]);
        $booking = $this->bookingRepo->updateStatus($booking, 'assigned', 'Technician assigned', $admin);
        dispatch(fn () => $this->whatsApp->sendTechnicianAssigned($booking->fresh(['technician'])));
        return $booking;
    }

    public function markInProgress(Booking $booking, $actor): Booking
    {
        return $this->bookingRepo->updateStatus($booking, 'in_progress', 'Service started', $actor);
    }

    public function rescheduleBooking(
        Booking $booking,
        string $date,
        string $timeSlot,
        $actor,
        ?string $note = null
    ): Booking {
        $booking = $this->bookingRepo->reschedule($booking, $date, $timeSlot, $actor);
        event(new BookingRescheduled($booking->fresh(['items.service', 'user'])));
        return $booking;
    }

    public function cancelBooking(Booking $booking, string $reason, $actor): Booking
    {
        $booking = $this->bookingRepo->cancelBooking($booking, $reason, $actor);
        dispatch(fn () => $this->whatsApp->sendBookingCancelled($booking, $reason));
        return $booking;
    }

    public function completeBooking(Booking $booking, $actor, ?string $note = null): Booking
    {
        $booking = $this->bookingRepo->updateStatus($booking, 'completed', $note ?? 'Service completed', $actor);

        // Award loyalty points to registered users (1 point per ₹100 spent)
        if ($booking->user_id) {
            $points = (int) floor(($booking->final_amount ?? $booking->total_amount ?? 0) / 100);
            if ($points > 0) {
                \App\Models\User::where('id', $booking->user_id)
                    ->increment('loyalty_points', $points);
            }
        }

        $booking = $booking->fresh(['items.service.category', 'technician', 'user']);

        // Create 6-month warranty + update annual spend + auto-unlock AMC
        dispatch(fn () => $this->warrantyService->handleBookingCompleted($booking));

        dispatch(fn () => $this->whatsApp->sendBookingCompleted($booking));
        return $booking;
    }

    public function getAvailableSlots(string $date): array
    {
        $targetDate = \Carbon\Carbon::parse($date);

        if ($targetDate->isPast() && ! $targetDate->isToday()) {
            return [];
        }

        $slots = $this->bookingRepo->getSlotAvailability($date);

        if ($targetDate->isToday()) {
            $currentHour = now()->setTimezone('Asia/Kolkata')->hour;
            $slots = array_filter($slots, function ($slot) use ($currentHour) {
                [$startTime] = explode('-', $slot['slot']);
                return (int) substr($startTime, 0, 2) > $currentHour;
            });
        }

        return array_values($slots);
    }
}