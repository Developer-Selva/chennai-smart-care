<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingRescheduled;
use App\Models\Booking;
use App\Mail\BookingCreated as BookingCreatedMail;
use App\Mail\BookingConfirmed as BookingConfirmedMail;
use App\Mail\BookingRescheduled as BookingRescheduledMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Admin email address from config
     */
    protected string $adminEmail;

    public function __construct()
    {
        $this->adminEmail = config('mail.from.address');
    }

    /**
     * Handle the event.
     * Listens to BookingCreated, BookingConfirmed, and BookingRescheduled.
     */
    public function handle(BookingCreated|BookingConfirmed|BookingRescheduled $event): void
    {
        $booking = $event->booking->load(['items.service', 'technician', 'user']);

        // Send email to customer
        $this->sendCustomerEmail($booking, $event);

        // Send email to admin (only for new bookings)
        if ($event instanceof BookingCreated) {
            $this->sendAdminEmail($booking);
        }
    }

    /**
     * Send email to customer
     */
    private function sendCustomerEmail(Booking $booking, $event): void
    {
        $email = $this->resolveEmail($booking);

        if (! $email) {
            Log::info("BookingConfirmationEmail skipped — no email for booking #{$booking->booking_number}");
            return;
        }

        $emailClass = $this->resolveMailable($event, $booking, false);

        try {
            Mail::to($email)->send($emailClass);
            Log::info("Customer email sent for booking #{$booking->booking_number}");
        } catch (\Throwable $e) {
            Log::error('Customer email failed', [
                'booking' => $booking->booking_number,
                'email'   => $email,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Send email to admin
     */
    private function sendAdminEmail(Booking $booking): void
    {
        try {
            $adminMailable = new BookingCreatedMail($booking, true); // true = admin copy
            
            Mail::to($this->adminEmail)
                ->cc(config('mail.admin_cc', [])) // Optional CC
                ->send($adminMailable);
            
            Log::info("Admin notification sent for booking #{$booking->booking_number}");
        } catch (\Throwable $e) {
            Log::error('Admin email failed', [
                'booking' => $booking->booking_number,
                'error'   => $e->getMessage(),
            ]);
            // Don't throw here - admin email failure shouldn't break the main flow
        }
    }

    /**
     * Resolve recipient email from user account or guest email.
     */
    private function resolveEmail(Booking $booking): ?string
    {
        return $booking->user?->email ?? $booking->guest_email;
    }

    /**
     * Return the correct Mailable based on the event type.
     */
    private function resolveMailable($event, Booking $booking, bool $isAdminCopy = false): \Illuminate\Mail\Mailable
    {
        return match (true) {
            $event instanceof BookingRescheduled => new BookingRescheduledMail($booking),
            $event instanceof BookingConfirmed   => new BookingConfirmedMail($booking),
            default                              => new BookingCreatedMail($booking, $isAdminCopy),
        };
    }

    /**
     * Handle a job failure.
     */
    public function failed(BookingCreated|BookingConfirmed|BookingRescheduled $event, \Throwable $exception): void
    {
        Log::critical('BookingConfirmationEmail permanently failed', [
            'booking' => $event->booking->booking_number,
            'error'   => $exception->getMessage(),
        ]);
    }
}