<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingRescheduled;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries   = 3;
    public int $backoff = 30;

    public function __construct(protected WhatsAppService $whatsApp) {}

    public function handle(BookingCreated|BookingConfirmed|BookingRescheduled $event): void
    {
        $booking = $event->booking->fresh(['items.service', 'technician', 'user']);

        match (true) {
            $event instanceof BookingRescheduled => $this->whatsApp->sendBookingRescheduled($booking),
            $event instanceof BookingConfirmed   => $this->whatsApp->sendBookingConfirmed($booking),
            default                              => $this->whatsApp->sendBookingCreated($booking),
        };
    }

    public function failed(BookingCreated|BookingConfirmed|BookingRescheduled $event, \Throwable $e): void
    {
        Log::error('SendWhatsAppNotification failed permanently', [
            'booking' => $event->booking->booking_number,
            'error'   => $e->getMessage(),
        ]);
    }
}