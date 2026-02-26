<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmcSubscription extends Model
{
    protected $table = 'amc_subscriptions';

    protected $fillable = [
        'amc_number', 'user_id', 'type', 'price',
        'starts_at', 'expires_at', 'status',
        'qualifying_spend', 'free_services_total',
        'free_services_used', 'discount_percent',
        'priority_booking', 'notes',
    ];

    protected $casts = [
        'starts_at'        => 'date',
        'expires_at'       => 'date',
        'price'            => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'qualifying_spend' => 'decimal:2',
        'priority_booking' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('expires_at', '>=', today());
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && $this->expires_at->gte(today());
    }

    public function getDaysRemainingAttribute(): int
    {
        return max(0, today()->diffInDays($this->expires_at, false));
    }

    public function getFreeServicesRemainingAttribute(): int
    {
        return max(0, $this->free_services_total - $this->free_services_used);
    }

    public function getProgressPercentAttribute(): int
    {
        $total   = $this->starts_at->diffInDays($this->expires_at);
        $elapsed = $this->starts_at->diffInDays(today());
        return min(100, (int) round(($elapsed / max(1, $total)) * 100));
    }

    // ── Static helpers ────────────────────────────────────────────

    public static function generateNumber(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('AMC-%s-%05d', $year, $count);
    }
}