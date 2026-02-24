<?php
// -------------------------------------------------------
// app/Providers/RepositoryServiceProvider.php
// -------------------------------------------------------
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\BlogRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
    }
}