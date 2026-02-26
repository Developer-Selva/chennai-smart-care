<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'booking_id', 'user_id', 'admin_id',
        'type', 'status',
        'customer_name', 'customer_phone', 'customer_email', 'customer_address',
        'business_name', 'business_phone', 'business_email', 'business_address', 'business_gstin',
        'subtotal', 'discount_amount', 'discount_percent', 'tax_amount', 'tax_percent', 'total',
        'payment_method', 'payment_reference', 'paid_at',
        'notes', 'terms', 'sent_at', 'due_date',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_amount'       => 'decimal:2',
        'tax_percent'      => 'decimal:2',
        'total'            => 'decimal:2',
        'paid_at'          => 'datetime',
        'sent_at'          => 'datetime',
        'due_date'         => 'date',
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

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeDraft($query)     { return $query->where('status', 'draft'); }
    public function scopeSent($query)      { return $query->where('status', 'sent'); }
    public function scopePaid($query)      { return $query->where('status', 'paid'); }

    // ── Accessors ─────────────────────────────────────────────────

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function getIsSentAttribute(): bool
    {
        return in_array($this->status, ['sent', 'paid']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'  => 'Draft',
            'sent'   => 'Sent',
            'paid'   => 'Paid',
            'void'   => 'Void',
            default  => ucfirst($this->status),
        };
    }

    // ── Static helpers ────────────────────────────────────────────

    public static function generateNumber(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('INV-%s-%05d', $year, $count);
    }
}