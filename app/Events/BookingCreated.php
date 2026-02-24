<?php
// app/Events/BookingCreated.php
namespace App\Events;

use App\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(public readonly Booking $booking) {}
}