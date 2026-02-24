<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepo,
        protected ServiceRepositoryInterface $serviceRepo,
        protected BookingService $bookingService,
    ) {}

    public function create(): Response
    {
        return Inertia::render('User/BookingCreate', [
            'categories' => $this->serviceRepo->getAllActiveWithCategories(),
            'auth_user'  => auth('web')->user()->only(['name', 'phone', 'email']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'services'       => 'required|array|min:1',
            'services.*.id'  => 'required|exists:services,id',
            'services.*.price'    => 'required|numeric|min:0',
            'services.*.quantity' => 'nullable|integer|min:1',
            'address'        => 'required|string|max:500',
            'area'           => 'nullable|string|max:100',
            'pincode'        => 'nullable|string|max:10',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'booking_date'   => 'required|date|after_or_equal:today',
            'time_slot'      => 'required|string',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $booking = $this->bookingService->createUserBooking($data, auth()->id());

        return redirect("/user/bookings/{$booking->booking_number}")
            ->with('success', 'Booking confirmed! We\'ll be in touch soon.');
    }

    public function cancel(Request $request, string $bookingNumber): RedirectResponse
    {
        $booking = $this->bookingRepo->findByNumber($bookingNumber);

        abort_if(! $booking, 404);
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if(in_array($booking->status, ['completed', 'cancelled']), 422);
        abort_if(
            in_array($booking->status, ['assigned', 'in_progress']) || $booking->technician_id,
            422,
            'Cancellation is not allowed once a technician has been assigned. Please call us to cancel.'
        );

        $data = $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $this->bookingService->cancelBooking(
            $booking,
            $data['reason'] ?? 'Cancelled by customer',
            auth()->user()
        );

        return back()->with('success', 'Booking cancelled.');
    }

    public function review(Request $request, string $bookingNumber): RedirectResponse
    {
        $booking = $this->bookingRepo->findByNumber($bookingNumber);

        abort_if(! $booking, 404);
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'completed', 422);

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $booking->review()->create([
            'user_id'    => auth()->id(),
            'rating'     => $data['rating'],
            'comment'    => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}