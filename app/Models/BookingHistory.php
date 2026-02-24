<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingHistory extends Model
{
    protected $fillable = [
        'booking_id', 'action', 'from_status', 'to_status', 'note',
        'actor_id', 'actor_type',
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function actor()   { return $this->morphTo(); }
}