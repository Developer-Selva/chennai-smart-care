<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// -------------------------------------------------------
// app/Models/BlogPost.php
// -------------------------------------------------------
class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blog_category_id', 'author_id', 'title', 'slug', 'excerpt',
        'content', 'featured_image', 'alt_text', 'status', 'published_at',
        'meta_title', 'meta_description', 'focus_keyword', 'schema_markup',
        'canonical_url', 'is_indexed', 'view_count', 'read_time_minutes',
        'gtm_category', 'tags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'schema_markup' => 'array',
        'tags'          => 'array',
        'is_indexed'    => 'boolean',
    ];

    public function category() { return $this->belongsTo(BlogCategory::class, 'blog_category_id'); }
    public function author()   { return $this->belongsTo(Admin::class, 'author_id'); }

    public function scopePublished($q) {
        return $q->where('status', 'published')->where('published_at', '<=', now());
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    // Auto-calculate read time on save
    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            $wordCount = str_word_count(strip_tags($post->content));
            $post->read_time_minutes = max(1, (int) ceil($wordCount / 200));
        });
    }
}