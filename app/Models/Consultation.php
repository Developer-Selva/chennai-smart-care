<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// -------------------------------------------------------
// app/Models/Consultation.php
// -------------------------------------------------------
class Consultation extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'message',
        'service_interest', 'status', 'admin_notes',
    ];
}