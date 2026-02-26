<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number', 'user_id', 'technician_id',
        'guest_name', 'guest_phone', 'guest_email',
        'address', 'area', 'city', 'pincode', 'latitude', 'longitude',
        'booking_date', 'booking_time', 'time_slot', 'status',
        'total_amount', 'discount_amount', 'final_amount',
        'customer_notes', 'admin_notes', 'technician_notes',
        'cancellation_reason', 'confirmed_at', 'completed_at', 'cancelled_at',
        'source', 'is_free_consultation',
    ];

    protected $casts = [
        'booking_date'         => 'date',
        'booking_time'         => 'datetime:H:i',
        'confirmed_at'         => 'datetime',
        'completed_at'         => 'datetime',
        'cancelled_at'         => 'datetime',
        'is_free_consultation' => 'boolean',
    ];

    // Append computed accessors so they're always included in JSON/Inertia responses
    protected $appends = ['customer_name', 'customer_phone', 'customer_email'];

    // ---- Relationships ----

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function history()
    {
        return $this->hasMany(BookingHistory::class)->latest();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function invoice()
    {
        return $this->hasOne(\App\Models\Invoice::class)->latest();
    }

    // ---- Scopes ----

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('booking_date', '>=', today());
    }

    // ---- Accessors ----

    public function getCustomerNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Guest';
    }

    public function getCustomerPhoneAttribute(): ?string
    {
        return $this->user?->phone ?? $this->guest_phone;
    }

    public function getCustomerEmailAttribute(): ?string
    {
        return $this->user?->email ?? $this->guest_email;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'     => ['color' => 'yellow',  'label' => 'Pending'],
            'confirmed'   => ['color' => 'blue',    'label' => 'Confirmed'],
            'assigned'    => ['color' => 'indigo',  'label' => 'Assigned'],
            'in_progress' => ['color' => 'purple',  'label' => 'In Progress'],
            'completed'   => ['color' => 'green',   'label' => 'Completed'],
            'cancelled'   => ['color' => 'red',     'label' => 'Cancelled'],
            'rescheduled' => ['color' => 'orange',  'label' => 'Rescheduled'],
            default       => ['color' => 'gray',    'label' => ucfirst($this->status)],
        };
    }

    // ---- Static Helpers ----

    public static function generateBookingNumber(): string
    {
        $prefix = 'CSC';
        $year   = now()->format('Y');
        $count  = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('%s-%s-%05d', $prefix, $year, $count);
    }
}