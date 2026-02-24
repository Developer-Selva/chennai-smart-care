<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// -------------------------------------------------------
// app/Models/Otp.php
// -------------------------------------------------------
class Otp extends Model
{
    protected $fillable = ['phone', 'code', 'purpose', 'is_used', 'expires_at'];

    protected $casts = [
        'is_used'    => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool { return now()->isAfter($this->expires_at); }
    public function isValid(): bool   { return ! $this->is_used && ! $this->isExpired(); }
}