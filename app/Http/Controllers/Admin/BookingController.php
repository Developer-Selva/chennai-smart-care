<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminBookingRequest;
use App\Models\Booking;
use App\Models\Technician;
use App\Services\BookingService;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService              $bookingService,
        protected BookingRepositoryInterface  $bookingRepo,
        protected ServiceRepositoryInterface  $serviceRepo,
    ) {}

    public function index(Request $request): Response
    {
        $filters  = $request->only(['status', 'date', 'search', 'technician_id', 'from', 'to']);
        $bookings = $this->bookingRepo->paginateForAdmin($filters, 20);

        return Inertia::render('Admin/Bookings/Index', [
            'bookings'    => $bookings,
            'filters'     => $filters,
            'technicians' => Technician::active()->get(['id', 'name']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Bookings/Create', [
            'categories'  => $this->serviceRepo->getAllActiveWithCategories(),
            'technicians' => Technician::active()->get(['id', 'name', 'skills']),
        ]);
    }

    public function store(AdminBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingService->createAdminBooking(
            $request->validated(),
            $request->user('admin')
        );

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('success', "Booking #{$booking->booking_number} created.");
    }

    public function show(Booking $booking): Response
    {
        $booking->load(['user', 'technician', 'items.service', 'history', 'review']);

        return Inertia::render('Admin/Bookings/Show', [
            'booking'     => $booking,
            'technicians' => Technician::active()->get(['id', 'name', 'phone']),
        ]);
    }

    public function confirm(Booking $booking): RedirectResponse
    {
        $this->bookingService->confirmBooking($booking, request()->user('admin'));

        return back()->with('success', 'Booking confirmed.');
    }

    public function assign(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate(['technician_id' => 'required|exists:technicians,id']);
        $this->bookingService->assignTechnician($booking, $request->technician_id, $request->user('admin'));

        return back()->with('success', 'Technician assigned.');
    }

    public function inProgress(Booking $booking): RedirectResponse
    {
        $this->bookingService->markInProgress($booking, request()->user('admin'));

        return back()->with('success', 'Booking marked as in progress.');
    }

    public function reschedule(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'date'      => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'note'      => 'nullable|string|max:255',
        ]);

        $this->bookingService->rescheduleBooking($booking, $data['date'], $data['time_slot'], request()->user('admin'), $data['note'] ?? null);

        return back()->with('success', 'Booking rescheduled.');
    }

    public function complete(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $this->bookingService->completeBooking($booking, request()->user('admin'), $data['admin_notes'] ?? null);

        return back()->with('success', 'Booking marked as completed.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $this->bookingService->cancelBooking($booking, $data['reason'], request()->user('admin'));

        return back()->with('success', 'Booking cancelled.');
    }

    public function notes(Request $request, Booking $booking): JsonResponse
    {
        $data = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update(['admin_notes' => $data['admin_notes']]);

        return response()->json(['success' => true]);
    }

    public function slots(string $date): JsonResponse
    {
        return response()->json([
            'slots' => $this->bookingRepo->getSlotAvailability($date),
        ]);
    }
}