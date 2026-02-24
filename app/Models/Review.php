<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// -------------------------------------------------------
// app/Models/Review.php
// -------------------------------------------------------
class Review extends Model
{
    protected $fillable = [
        'booking_id', 'user_id', 'technician_id',
        'rating', 'comment', 'is_approved', 'is_featured',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function booking()    { return $this->belongsTo(Booking::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function technician() { return $this->belongsTo(Technician::class); }
}
