<?php
namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BlogRepositoryInterface
{
    public function getPublished(int $perPage = 10): LengthAwarePaginator;
    public function findBySlug(string $slug);
    public function getRelated(int $postId, int $categoryId, int $limit = 3): Collection;
    public function getByCategory(string $categorySlug, int $perPage = 10): LengthAwarePaginator;
    public function incrementViews(int $id): void;
}