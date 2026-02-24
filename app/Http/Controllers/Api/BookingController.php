<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(protected BookingRepositoryInterface $bookingRepo) {}

    public function track(string $number): JsonResponse
    {
        $booking = $this->bookingRepo->findByNumber($number);

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