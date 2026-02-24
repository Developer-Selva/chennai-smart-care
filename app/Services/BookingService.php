<?php
// -------------------------------------------------------
// app/Services/BookingService.php
// -------------------------------------------------------
namespace App\Services;

use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingRescheduled;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;

class BookingService
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepo,
        protected LocationService $locationService,
        protected SmsService $smsService,
    ) {}

    /**
     * Create a quick booking (no auth required).
     */
    public function createQuickBooking(array $validated): Booking
    {
        // Validate location is within 20km of Chennai
        $this->locationService->assertWithinChennai(
            $validated['latitude'],
            $validated['longitude']
        );
        
        $existingUser = \App\Models\User::where('phone', $validated['guest_phone'])->first();

        $booking = $this->bookingRepo->createBooking([
            ...$validated,
            'user_id' => $existingUser?->id,
            'source' => 'website',
        ]);

        event(new BookingCreated($booking));

        return $booking;
    }

    /**
     * Create a booking for an authenticated user.
     */
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

        return $booking;
    }

    /**
     * Admin creates a booking manually.
     */
    public function createAdminBooking(array $validated, $admin): Booking
    {
        $booking = $this->bookingRepo->createBooking([
            ...$validated,
            'source' => 'admin',
        ]);

        // Auto-confirm admin-created bookings
        $this->bookingRepo->updateStatus($booking, 'confirmed', 'Created by admin', $admin);
        event(new BookingConfirmed($booking));

        return $booking->fresh();
    }

    public function confirmBooking(Booking $booking, $admin, ?string $note = null): Booking
    {
        $booking = $this->bookingRepo->updateStatus($booking, 'confirmed', $note, $admin);
        event(new BookingConfirmed($booking));
        return $booking;
    }

    public function assignTechnician(Booking $booking, int $technicianId, $admin): Booking
    {
        $booking->update(['technician_id' => $technicianId]);
        $booking = $this->bookingRepo->updateStatus($booking, 'assigned', "Technician assigned", $admin);
        return $booking;
    }

    public function rescheduleBooking(
        Booking $booking,
        string $date,
        string $timeSlot,
        $actor,
        ?string $note = null
    ): Booking {
        $booking = $this->bookingRepo->reschedule($booking, $date, $timeSlot, $actor);
        event(new BookingRescheduled($booking));
        return $booking;
    }

    public function markInProgress(Booking $booking, $actor): Booking
    {
        return $this->bookingRepo->updateStatus($booking, 'in_progress', 'Service started', $actor);
    }

    public function cancelBooking(Booking $booking, string $reason, $actor): Booking
    {
        return $this->bookingRepo->cancelBooking($booking, $reason, $actor);
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

        return $booking->fresh();
    }

    public function getAvailableSlots(string $date): array
    {
        // Don't allow past dates or dates more than 30 days out
        $targetDate = \Carbon\Carbon::parse($date);
        if ($targetDate->isPast() && ! $targetDate->isToday()) {
            return [];
        }

        $slots = $this->bookingRepo->getSlotAvailability($date);

        // If it's today, filter out past time slots
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