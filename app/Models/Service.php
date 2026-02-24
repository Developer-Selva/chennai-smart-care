<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'description',
        'base_price', 'discounted_price', 'duration_estimate',
        'features', 'image', 'sort_order', 'is_active', 'is_featured',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'features'         => 'array',
        'is_active'        => 'boolean',
        'is_featured'      => 'boolean',
        'base_price'       => 'decimal:2',
        'discounted_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function bookingItems()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discounted_price ?? $this->base_price;
    }
}