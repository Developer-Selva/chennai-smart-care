<?php

namespace App\Http\Controllers\Api;

use App\Events\BookingCreated;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuickBookingController extends Controller
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepo,
        protected ServiceRepositoryInterface $serviceRepo,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'guest_name'     => 'required|string|max:100',
            'guest_phone'    => 'required|string|regex:/^[6-9]\d{9}$/',
            'guest_email'    => 'nullable|email',
            'address'        => 'required|string|max:500',
            'area'           => 'nullable|string|max:100',
            'pincode'        => 'nullable|string|max:10',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'location_accuracy' => 'nullable|numeric|min:0',
            'location_source'   => 'nullable|string|in:gps,network,manual',
            'services'       => 'required|array|min:1',
            'services.*.id'  => 'required|exists:services,id',
            'services.*.price'    => 'required|numeric|min:0',
            'services.*.quantity' => 'nullable|integer|min:1',
            'booking_date'   => 'required|date|after_or_equal:today',
            'time_slot'      => 'required|string',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $data['source'] = 'website';
        $data['status'] = 'pending';

        $booking = $this->bookingRepo->createBooking($data);

        // Fire event — triggers WhatsApp notification to customer + admin
        event(new BookingCreated($booking->fresh(['items.service', 'user'])));

        return response()->json([
            'message'        => 'Booking created successfully.',
            'booking_number' => $booking->booking_number,
            'booking_id'     => $booking->id,
        ], 201);
    }

    public function track(string $number): JsonResponse
    {
        $booking = $this->bookingRepo->findByNumber(strtoupper($number));

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        return response()->json(['booking' => $booking]);
    }

    public function slots(string $date): JsonResponse
    {
        return response()->json([
            'slots' => $this->bookingRepo->getSlotAvailability($date),
        ]);
    }
}