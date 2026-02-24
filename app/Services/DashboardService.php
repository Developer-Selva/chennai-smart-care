<?php

namespace App\Services;

// -------------------------------------------------------
// app/Services/DashboardService.php
// -------------------------------------------------------
use App\Models\Booking;
use App\Models\User;
use App\Models\Technician;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getAdminMetrics(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        return [
            'bookings' => [
                'today'         => Booking::whereDate('booking_date', today())->count(),
                'pending'       => Booking::where('status', 'pending')->count(),
                'confirmed'     => Booking::where('status', 'confirmed')->count(),
                'in_progress'   => Booking::where('status', 'in_progress')->count(),
                'completed_today' => Booking::where('status', 'completed')
                                          ->whereDate('completed_at', today())->count(),
                'this_month'    => Booking::whereMonth('created_at', now()->month)->count(),
                'last_month'    => Booking::whereMonth('created_at', now()->subMonth()->month)->count(),
            ],
            'revenue' => [
                'this_month' => Booking::completed()
                    ->whereMonth('completed_at', now()->month)->sum('final_amount'),
                'last_month' => Booking::completed()
                    ->whereMonth('completed_at', now()->subMonth()->month)->sum('final_amount'),
                'total'      => Booking::completed()->sum('final_amount'),
            ],
            'customers' => [
                'total'      => User::count(),
                'new_month'  => User::whereMonth('created_at', now()->month)->count(),
            ],
            'technicians' => [
                'total'     => Technician::where('is_active', true)->count(),
                'available' => Technician::where('is_available', true)->count(),
            ],
            'consultations' => [
                'new' => Consultation::where('status', 'new')->count(),
            ],
            'upcoming_bookings' => Booking::with(['items.service', 'user', 'technician'])
                ->whereIn('status', ['confirmed', 'assigned'])
                ->whereDate('booking_date', '>=', today())
                ->orderBy('booking_date')
                ->orderBy('booking_time')
                ->limit(10)
                ->get(),
            'recent_bookings' => Booking::with(['items.service', 'user'])
                ->latest()
                ->limit(5)
                ->get(),
            'bookings_chart' => $this->getBookingsChart(),
            'revenue_chart'  => $this->getRevenueChart(),
        ];
    }

    private function getBookingsChart(): array
    {
        return Booking::select(
                DB::raw('DATE(booking_date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('booking_date', [now()->subDays(29), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => [
                'date'  => $row->date,
                'count' => $row->count,
            ])
            ->toArray();
    }

    private function getRevenueChart(): array
    {
        return Booking::completed()
            ->select(
                DB::raw("DATE_FORMAT(completed_at, '%Y-%m') as month"),
                DB::raw('SUM(final_amount) as revenue')
            )
            ->whereBetween('completed_at', [now()->subMonths(5)->startOfMonth(), now()])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }
}