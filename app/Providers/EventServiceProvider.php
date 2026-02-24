<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Events
use App\Events\BookingCreated;
use App\Events\BookingConfirmed;
use App\Events\BookingRescheduled;

// Listeners
use App\Listeners\SendBookingConfirmationSms;
use App\Listeners\SendBookingConfirmationEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * Both SMS and Email listeners are queued (ShouldQueue),
     * so they run asynchronously and don't slow down the request.
     */
    protected $listen = [

        // ---- Booking Created (pending state) ----
        BookingCreated::class => [
            SendBookingConfirmationSms::class,    // SMS: "We received your booking"
            SendBookingConfirmationEmail::class,  // Email: full booking details
        ],

        // ---- Booking Confirmed by Admin ----
        BookingConfirmed::class => [
            SendBookingConfirmationSms::class,    // SMS: "Your booking is confirmed"
            SendBookingConfirmationEmail::class,  // Email: confirmed with technician info
        ],

        // ---- Booking Rescheduled ----
        BookingRescheduled::class => [
            SendBookingConfirmationSms::class,    // SMS: new date/time
            SendBookingConfirmationEmail::class,  // Email: updated appointment details
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be auto-discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}