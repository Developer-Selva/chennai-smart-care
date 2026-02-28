<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {

            // Get booking IDs once
            $bookingIds = DB::table('bookings')
                ->whereBetween('booking_number', [
                    'CSC-2026-00001',
                    'CSC-2026-00007'
                ])
                ->pluck('id');

            if ($bookingIds->isEmpty()) {
                return;
            }

            // Get invoice IDs linked to those bookings
            $invoiceIds = DB::table('invoices')
                ->whereIn('booking_id', $bookingIds)
                ->pluck('id');

            // 1. Delete invoice items
            if ($invoiceIds->isNotEmpty()) {
                DB::table('invoice_items')
                    ->whereIn('invoice_id', $invoiceIds)
                    ->delete();
            }

            // 2. Delete invoices
            DB::table('invoices')
                ->whereIn('booking_id', $bookingIds)
                ->delete();

            // 3. Delete warranties
            DB::table('warranties')
                ->whereIn('booking_id', $bookingIds)
                ->delete();

            // 4. Delete reviews
            DB::table('reviews')
                ->whereIn('booking_id', $bookingIds)
                ->delete();

            // 5. Delete booking histories
            DB::table('booking_histories')
                ->whereIn('booking_id', $bookingIds)
                ->delete();

            // 6. Delete booking items
            DB::table('booking_items')
                ->whereIn('booking_id', $bookingIds)
                ->delete();

            // 7. Delete bookings
            DB::table('bookings')
                ->whereIn('id', $bookingIds)
                ->delete();

            // 8. Clear all consultations
            DB::table('consultations')->delete();
        });
    }

    public function down(): void
    {
        // Irreversible cleanup
        // You may optionally throw an exception:
        // throw new \Exception("This migration cannot be rolled back.");
    }
};