<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingItem extends Model
{
    protected $fillable = [
        'booking_id', 'service_id', 'service_name',
        'unit_price', 'quantity', 'total_price', 'notes',
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function service() { return $this->belongsTo(Service::class); }
}