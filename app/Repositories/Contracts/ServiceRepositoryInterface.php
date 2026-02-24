<?php
namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

// -------------------------------------------------------
// app/Repositories/Contracts/ServiceRepositoryInterface.php
// -------------------------------------------------------
interface ServiceRepositoryInterface
{
    public function getAllActiveWithCategories(): Collection;
    public function findBySlug(string $slug);
    public function getFeatured(): Collection;
    public function getByCategory(int $categoryId): Collection;
    public function getAllCategories(): Collection;
}