<?php

namespace App\Services;

use App\Models\AmcSubscription;
use App\Models\Booking;
use App\Models\User;
use App\Models\Warranty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarrantyService
{
    const AMC_FREE_THRESHOLD = 5000;  // ₹5,000 annual spend unlocks free AMC
    const AMC_PAID_PRICE     = 1499;  // ₹1,499/year for paid subscription

    /**
     * Called when a booking is marked completed.
     * Creates warranty + updates spend + auto-unlocks free AMC if eligible.
     */
    public function handleBookingCompleted(Booking $booking): void
    {
        if (! $booking->user_id) {
            return; // Guest bookings — no account to attach warranty to
        }

        DB::transaction(function () use ($booking) {
            $this->createWarranty($booking);
            $this->updateAnnualSpend($booking);
        });
    }

    /**
     * Create a 6-month warranty card for a completed booking.
     */
    public function createWarranty(Booking $booking): Warranty
    {
        $booking->loadMissing(['items.service.category', 'technician']);

        $serviceNames = $booking->items->map(fn ($i) => $i->service_name)->toArray();
        $appliances   = $booking->items
            ->map(fn ($i) => $i->service?->category?->name ?? $i->service_name)
            ->unique()->implode(', ');

        $startsAt  = ($booking->completed_at ?? now())->toDateString();
        $expiresAt = \Carbon\Carbon::parse($startsAt)->addMonths(6)->toDateString();

        $warranty = Warranty::create([
            'warranty_number'    => Warranty::generateNumber(),
            'booking_id'         => $booking->id,
            'user_id'            => $booking->user_id,
            'technician_id'      => $booking->technician_id,
            'services_covered'   => json_encode($serviceNames),
            'appliances_covered' => $appliances,
            'starts_at'          => $startsAt,
            'expires_at'         => $expiresAt,
            'status'             => 'active',
        ]);

        Log::info("Warranty issued: {$warranty->warranty_number} for {$booking->booking_number}");

        return $warranty;
    }

    /**
     * Track annual spend and auto-unlock free AMC when threshold is hit.
     */
    public function updateAnnualSpend(Booking $booking): void
    {
        if (! $booking->user_id || ! $booking->final_amount) {
            return;
        }

        $year  = now()->year;
        $spend = DB::table('user_annual_spends')
            ->where('user_id', $booking->user_id)
            ->where('year', $year)
            ->first();

        $newTotal = ($spend?->total_spend ?? 0) + $booking->final_amount;

        DB::table('user_annual_spends')->updateOrInsert(
            ['user_id' => $booking->user_id, 'year' => $year],
            ['total_spend' => $newTotal, 'updated_at' => now(), 'created_at' => $spend ? $spend->created_at : now()]
        );

        if ($newTotal >= self::AMC_FREE_THRESHOLD && ! ($spend?->amc_unlocked)) {
            $this->unlockFreeAmc($booking->user_id, $newTotal, $year);
        }
    }

    /**
     * Auto-create a free 1-year AMC when user's spend crosses ₹5,000.
     */
    public function unlockFreeAmc(int $userId, float $qualifyingSpend, int $year): AmcSubscription
    {
        $existing = AmcSubscription::where('user_id', $userId)->active()->first();

        if ($existing) {
            if ($existing->type === 'paid') {
                $existing->update(['type' => 'free', 'price' => 0, 'qualifying_spend' => $qualifyingSpend]);
                Log::info("AMC upgraded to free for user {$userId}");
            }
            return $existing;
        }

        DB::table('user_annual_spends')
            ->where('user_id', $userId)->where('year', $year)
            ->update(['amc_unlocked' => true, 'amc_unlocked_at' => now()]);

        $amc = AmcSubscription::create([
            'amc_number'          => AmcSubscription::generateNumber(),
            'user_id'             => $userId,
            'type'                => 'free',
            'price'               => 0,
            'starts_at'           => today()->toDateString(),
            'expires_at'          => today()->addYear()->toDateString(),
            'status'              => 'active',
            'qualifying_spend'    => $qualifyingSpend,
            'free_services_total' => 2,
            'free_services_used'  => 0,
            'discount_percent'    => 15,
            'priority_booking'    => true,
        ]);

        Log::info("Free AMC unlocked: {$amc->amc_number} for user {$userId} (₹{$qualifyingSpend} spent)");

        // Notify user via WhatsApp
        try {
            $user = User::find($userId);
            if ($user) {
                app(WhatsAppService::class)->sendAmcUnlocked($user, $amc);
            }
        } catch (\Throwable $e) {
            Log::warning("AMC WhatsApp notification failed: {$e->getMessage()}");
        }

        return $amc;
    }

    /**
     * Create a paid AMC subscription.
     */
    public function createPaidAmc(int $userId): AmcSubscription
    {
        return AmcSubscription::create([
            'amc_number'          => AmcSubscription::generateNumber(),
            'user_id'             => $userId,
            'type'                => 'paid',
            'price'               => self::AMC_PAID_PRICE,
            'starts_at'           => today()->toDateString(),
            'expires_at'          => today()->addYear()->toDateString(),
            'status'              => 'active',
            'free_services_total' => 2,
            'free_services_used'  => 0,
            'discount_percent'    => 15,
            'priority_booking'    => true,
        ]);
    }

    /**
     * Get all warranty + AMC data for the user dashboard.
     */
    public function getDashboardData(int $userId): array
    {
        $warranties = Warranty::where('user_id', $userId)
            ->with(['booking:id,booking_number,booking_date', 'technician:id,name,phone'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($w) => array_merge($w->toArray(), [
                'days_remaining'   => $w->days_remaining,
                'is_active'        => $w->is_active,
                'progress_percent' => $w->progress_percent,
                'services_list'    => $w->services_list,
            ]));

        $amc = AmcSubscription::where('user_id', $userId)->active()->first();

        $spend = DB::table('user_annual_spends')
            ->where('user_id', $userId)
            ->where('year', now()->year)
            ->first();

        $currentSpend = $spend?->total_spend ?? 0;

        return [
            'warranties'         => $warranties,
            'amc'                => $amc ? array_merge($amc->toArray(), [
                'days_remaining'          => $amc->days_remaining,
                'is_active'               => $amc->is_active,
                'progress_percent'        => $amc->progress_percent,
                'free_services_remaining' => $amc->free_services_remaining,
            ]) : null,
            'current_year_spend'  => (float) $currentSpend,
            'spend_to_unlock_amc' => max(0, self::AMC_FREE_THRESHOLD - $currentSpend),
            'spend_progress_pct'  => min(100, (int) round(($currentSpend / self::AMC_FREE_THRESHOLD) * 100)),
            'amc_threshold'       => self::AMC_FREE_THRESHOLD,
            'amc_paid_price'      => self::AMC_PAID_PRICE,
        ];
    }
}