<?php
namespace App\Repositories;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Support\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function getAllActiveWithCategories(): Collection
    {
        return ServiceCategory::active()
            ->with(['services' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();
    }

    public function findBySlug(string $slug): ?Service
    {
        return Service::active()->where('slug', $slug)->with('category')->first();
    }

    public function getFeatured(): Collection
    {
        return Service::active()->featured()->with('category')->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return Service::active()->where('category_id', $categoryId)->orderBy('sort_order')->get();
    }

    public function getAllCategories(): Collection
    {
        return ServiceCategory::active()->orderBy('sort_order')->get();
    }
}