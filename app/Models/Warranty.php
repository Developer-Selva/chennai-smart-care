<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Warranty extends Model
{
    protected $fillable = [
        'warranty_number', 'booking_id', 'user_id', 'technician_id',
        'services_covered', 'appliances_covered',
        'starts_at', 'expires_at', 'status',
        'claimed_at', 'claim_notes',
    ];

    protected $casts = [
        'starts_at'   => 'date',
        'expires_at'  => 'date',
        'claimed_at'  => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('expires_at', '>=', today());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', today());
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getDaysRemainingAttribute(): int
    {
        return max(0, today()->diffInDays($this->expires_at, false));
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && $this->expires_at->gte(today());
    }

    public function getProgressPercentAttribute(): int
    {
        $total   = $this->starts_at->diffInDays($this->expires_at);
        $elapsed = $this->starts_at->diffInDays(today());
        return min(100, (int) round(($elapsed / max(1, $total)) * 100));
    }

    public function getServicesListAttribute(): array
    {
        return json_decode($this->services_covered, true) ?? [];
    }

    // ── Static helpers ────────────────────────────────────────────

    public static function generateNumber(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('WRN-%s-%05d', $year, $count);
    }
}