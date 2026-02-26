<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\WarrantyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBookingCompletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 30;

    public function __construct(public readonly Booking $booking) {}

    public function handle(WarrantyService $warrantyService): void
    {
        // Reload fresh to get completed_at etc.
        $booking = $this->booking->fresh(['items.service.category', 'technician', 'user']);

        if ($booking->status !== 'completed') {
            Log::warning("ProcessBookingCompletion: booking {$booking->booking_number} is not completed, skipping.");
            return;
        }

        // Skip if warranty already exists for this booking
        if (\App\Models\Warranty::where('booking_id', $booking->id)->exists()) {
            Log::info("ProcessBookingCompletion: warranty already exists for {$booking->booking_number}");
            return;
        }

        $warrantyService->handleBookingCompleted($booking);

        Log::info("ProcessBookingCompletion: warranty issued for {$booking->booking_number}");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("ProcessBookingCompletion failed for booking #{$this->booking->id}: {$e->getMessage()}");
    }
}