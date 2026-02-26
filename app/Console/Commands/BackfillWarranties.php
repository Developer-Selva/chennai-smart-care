<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Warranty;
use App\Services\WarrantyService;
use Illuminate\Console\Command;

class BackfillWarranties extends Command
{
    protected $signature   = 'warranties:backfill {--dry-run : Preview without creating}';
    protected $description = 'Create warranty cards for all completed bookings that do not have one yet';

    public function handle(WarrantyService $warrantyService): int
    {
        $dryRun = $this->option('dry-run');

        // Find completed bookings with a user_id that have no warranty yet
        $bookings = Booking::where('status', 'completed')
            ->whereNotNull('user_id')
            ->whereNotIn('id', Warranty::pluck('booking_id'))
            ->with(['items.service.category', 'technician', 'user'])
            ->get();

        $this->info("Found {$bookings->count()} completed bookings without a warranty.");

        if ($bookings->isEmpty()) {
            $this->info('Nothing to backfill. All done!');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('[DRY RUN] — No warranties will be created.');
            $this->table(
                ['Booking #', 'Customer', 'Completed At', 'Amount'],
                $bookings->map(fn ($b) => [
                    $b->booking_number,
                    $b->customer_name,
                    $b->completed_at?->format('d M Y') ?? $b->booking_date->format('d M Y'),
                    '₹' . ($b->final_amount ?? $b->total_amount ?? '—'),
                ])
            );
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($bookings->count());
        $bar->start();

        $created = 0;
        $failed  = 0;

        foreach ($bookings as $booking) {
            try {
                $warrantyService->handleBookingCompleted($booking);
                $created++;
            } catch (\Throwable $e) {
                $this->newLine();
                $this->error("Failed for {$booking->booking_number}: {$e->getMessage()}");
                $failed++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Created: {$created} warranties");
        if ($failed > 0) {
            $this->warn("⚠️  Failed:  {$failed} bookings (check logs)");
        }

        return self::SUCCESS;
    }
}