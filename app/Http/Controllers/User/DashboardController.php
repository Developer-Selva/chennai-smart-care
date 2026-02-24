<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(protected BookingRepositoryInterface $bookingRepo) {}

    public function index(Request $request): Response
    {
        $bookings = $this->bookingRepo->getForUser($request->user()->id);

        return Inertia::render('User/Dashboard', [
            'bookings' => $bookings,
            'stats' => [
                'total'     => $bookings->count(),
                'upcoming'  => $bookings->whereIn('status', ['pending','confirmed','assigned'])->count(),
                'completed' => $bookings->where('status','completed')->count(),
                'points'    => $request->user()->loyalty_points,
            ],
        ]);
    }

    public function show(string $bookingNumber): Response
    {
        $user    = auth('web')->user();
        $booking = $this->bookingRepo->findByNumber($bookingNumber);

        abort_if(! $booking, 404);
        abort_if($booking->user_id !== $user->id, 403);

        return Inertia::render('User/BookingDetail', [
            'booking' => $booking->load(['items.service', 'technician', 'history', 'review']),
        ]);
    }
}