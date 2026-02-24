<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'image',
        'color', 'sort_order', 'is_active',
        'meta_title', 'meta_description',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function services() { return $this->hasMany(Service::class, 'category_id'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}