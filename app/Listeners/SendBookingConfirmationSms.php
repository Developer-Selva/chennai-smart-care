<?php
// app/Listeners/SendBookingConfirmationSms.php
namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingConfirmationSms implements ShouldQueue
{
    public function __construct(protected SmsService $smsService) {}

    public function handle(BookingCreated|BookingConfirmed $event): void
    {
        $booking = $event->booking;
        $phone   = $booking->customer_phone;

        if ($phone) {
            $this->smsService->sendBookingConfirmation($phone, [
                'customer_name'  => $booking->customer_name,
                'booking_number' => $booking->booking_number,
                'date'           => $booking->booking_date->format('d M Y'),
                'slot'           => $booking->time_slot,
            ]);
        }
    }
}