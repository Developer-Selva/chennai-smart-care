<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingRescheduled;
use App\Models\Booking;
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
     * Handle the event.
     * Listens to BookingCreated, BookingConfirmed, and BookingRescheduled.
     */
    public function handle(BookingCreated|BookingConfirmed|BookingRescheduled $event): void
    {
        $booking = $event->booking->load(['items.service', 'technician', 'user']);

        $email = $this->resolveEmail($booking);

        if (! $email) {
            Log::info("BookingConfirmationEmail skipped — no email for booking #{$booking->booking_number}");
            return;
        }

        $emailClass = $this->resolveMailable($event, $booking);

        try {
            Mail::to($email)->send($emailClass);
        } catch (\Throwable $e) {
            Log::error('BookingConfirmationEmail failed', [
                'booking' => $booking->booking_number,
                'email'   => $email,
                'error'   => $e->getMessage(),
            ]);

            // Re-throw so the queue retries
            throw $e;
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
    private function resolveMailable(
        BookingCreated|BookingConfirmed|BookingRescheduled $event,
        Booking $booking
    ): \Illuminate\Mail\Mailable {
        return match (true) {
            $event instanceof BookingRescheduled => new \App\Mail\BookingRescheduled($booking),
            $event instanceof BookingConfirmed   => new \App\Mail\BookingConfirmed($booking),
            default                              => new \App\Mail\BookingCreated($booking),
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