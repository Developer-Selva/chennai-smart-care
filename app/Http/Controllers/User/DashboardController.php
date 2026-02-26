<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Services\WarrantyService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepo,
        protected WarrantyService $warrantyService,
    ) {}

    public function index(): Response
    {
        $user        = auth('web')->user();
        $bookings    = $this->bookingRepo->getForUser($user->id);
        $warrantyData = $this->warrantyService->getDashboardData($user->id);

        return Inertia::render('User/Dashboard', [
            'user'     => array_merge($user->toArray(), [
                'loyalty_points' => $user->loyalty_points ?? 0,
            ]),
            'bookings'           => $bookings,
            'warranties'         => $warrantyData['warranties'],
            'amc'                => $warrantyData['amc'],
            'current_year_spend' => $warrantyData['current_year_spend'],
            'spend_to_unlock_amc'=> $warrantyData['spend_to_unlock_amc'],
            'spend_progress_pct' => $warrantyData['spend_progress_pct'],
            'amc_threshold'      => $warrantyData['amc_threshold'],
            'amc_paid_price'     => $warrantyData['amc_paid_price'],
        ]);
    }

    public function show(string $bookingNumber): Response
    {
        $user    = auth('web')->user();
        $booking = $this->bookingRepo->findByNumber($bookingNumber);

        abort_if(! $booking, 404);
        abort_if($booking->user_id !== $user->id, 403);

        // Load warranty for this specific booking if completed
        $warranty = null;
        if ($booking->status === 'completed') {
            $warranty = \App\Models\Warranty::where('booking_id', $booking->id)->first();
            if ($warranty) {
                $warranty = array_merge($warranty->toArray(), [
                    'days_remaining'   => $warranty->days_remaining,
                    'is_active'        => $warranty->is_active,
                    'progress_percent' => $warranty->progress_percent,
                    'services_list'    => $warranty->services_list,
                ]);
            }
        }

        return Inertia::render('User/BookingDetail', [
            'booking'  => $booking->load(['items.service', 'technician', 'history', 'review']),
            'warranty' => $warranty,
        ]);
    }
}