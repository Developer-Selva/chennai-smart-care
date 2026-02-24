<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Booking Received — #{$this->booking->booking_number} | Chennai Smart Care",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bookings.created',
            with: [
                'booking'      => $this->booking,
                'customerName' => $this->booking->customer_name,
                'services'     => $this->booking->items->map(fn ($i) => $i->service_name)->join(', '),
                'date'         => $this->booking->booking_date->format('l, d M Y'),
                'slot'         => $this->booking->time_slot,
                'address'      => $this->booking->address,
                'trackUrl'     => route('booking.track', $this->booking->booking_number),
                'bookingUrl'   => route('quick-booking'),
                'phone'        => config('app.support_phone', '+91 98765 43210'),
            ],
        );
    }
}