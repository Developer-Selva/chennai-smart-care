<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $bookings = Booking::with(['items.service', 'technician'])
            ->whereBetween('booking_date', [$from, $to])
            ->whereIn('status', ['completed', 'in_progress'])
            ->latest('booking_date')
            ->get();

        return Inertia::render('Admin/Reports/Index', [
            'bookings'      => $bookings,
            'from'          => $from,
            'to'            => $to,
            'totalRevenue'  => $bookings->sum('final_amount'),
            'totalBookings' => $bookings->count(),
        ]);
    }

    public function export(Request $request): HttpResponse
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $bookings = Booking::with(['items.service', 'technician', 'user'])
            ->whereBetween('booking_date', [$from, $to])
            ->whereIn('status', ['completed', 'cancelled', 'in_progress'])
            ->latest('booking_date')
            ->get();

        $csv = "Booking #,Customer,Phone,Services,Date,Time Slot,Status,Amount,Technician\n";

        foreach ($bookings as $b) {
            $services = $b->items->pluck('service_name')->join(' | ');
            $csv .= implode(',', [
                $b->booking_number,
                '"' . $b->customer_name . '"',
                $b->customer_phone,
                '"' . $services . '"',
                $b->booking_date->format('d/m/Y'),
                $b->time_slot,
                $b->status,
                $b->final_amount,
                '"' . ($b->technician?->name ?? 'Unassigned') . '"',
            ]) . "\n";
        }

        $filename = "bookings_{$from}_to_{$to}.csv";

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}