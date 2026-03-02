<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $table = 'media_library';

    protected $fillable = [
        'uploaded_by', 'filename', 'disk', 'path', 'url',
        'mime_type', 'file_size', 'width', 'height',
        'alt_text', 'caption', 'title',
        'thumbnail_path', 'thumbnail_url',
        'collection', 'search_text',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width'     => 'integer',
        'height'    => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    // ── Scopes ──────────────────────────────────────────────────
    public function scopeInCollection($query, string $collection)
    {
        return $query->where('collection', $collection);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('search_text', 'like', "%{$term}%");
    }

    // ── Helpers ─────────────────────────────────────────────────
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024)       return "{$bytes} B";
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }

    public function getDimensionsAttribute(): ?string
    {
        if (! $this->width || ! $this->height) return null;
        return "{$this->width}×{$this->height}";
    }
}