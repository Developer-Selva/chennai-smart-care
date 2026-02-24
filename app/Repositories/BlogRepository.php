<?php
namespace App\Repositories;

use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Support\Collection;

// -------------------------------------------------------
// app/Repositories/BlogRepository.php
// -------------------------------------------------------
use App\Models\BlogPost;

class BlogRepository implements BlogRepositoryInterface
{
    public function getPublished(int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return BlogPost::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?BlogPost
    {
        return BlogPost::published()->where('slug', $slug)->with(['category', 'author'])->first();
    }

    public function getRelated(int $postId, int $categoryId, int $limit = 3): Collection
    {
        return BlogPost::published()
            ->where('id', '!=', $postId)
            ->where('blog_category_id', $categoryId)
            ->limit($limit)
            ->get();
    }

    public function getByCategory(string $categorySlug, int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return BlogPost::published()
            ->whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function incrementViews(int $id): void
    {
        BlogPost::where('id', $id)->increment('view_count');
    }
}
