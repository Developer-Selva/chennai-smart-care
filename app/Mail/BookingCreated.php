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

    public function __construct(
        public readonly Booking $booking,
        public bool $isAdminCopy = false // Flag to identify admin copy
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->isAdminCopy 
            ? "🔔 NEW BOOKING: #{$this->booking->booking_number} | {$this->booking->customer_name}"
            : "Booking Received — #{$this->booking->booking_number} | Chennai Smart Care";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $view = $this->isAdminCopy ? 'emails.bookings.admin-created' : 'emails.bookings.created';

        return new Content(
            view: $view,
            with: [
                'booking'      => $this->booking,
                'customerName' => $this->booking->customer_name,
                'services'     => $this->booking->items->map(fn ($i) => $i->service_name)->join(', '),
                'date'         => $this->booking->booking_date->format('l, d M Y'),
                'slot'         => $this->booking->time_slot,
                'address'      => $this->booking->address,
                'trackUrl'     => route('booking.track', $this->booking->booking_number),
                'bookingUrl'   => route('quick-booking'),
                'phone'        => config('app.support_phone', '+91 94449 00470'),
                'adminUrl'     => $this->isAdminCopy ? route('admin.bookings.show', $this->booking->id) : null,
            ],
        );
    }
}