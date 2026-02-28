<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Models\BlogCategory;
use App\Models\ServiceCategory;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function __construct(protected BlogRepositoryInterface $blogRepo) {}

    public function index(): Response
    {
        return Inertia::render('Landing/Blog/Index', [
            'posts'      => $this->blogRepo->getPublished(12),
            'categories' => BlogCategory::active()->get(['id', 'name', 'slug']),
        ]);
    }

    public function show(string $slug): Response
    {
        $post = $this->blogRepo->findBySlug($slug);
        abort_if(! $post, 404);

        $this->blogRepo->incrementViews($post->id);

        // Service categories for bottom internal linking widget
        $serviceCategories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['name', 'slug', 'icon']);

        // Detect primary appliance from post title/category for targeted links
        $title    = strtolower($post->title . ' ' . ($post->category?->name ?? ''));
        $detected = 'ac'; // default
        if (str_contains($title, 'refrigerator') || str_contains($title, 'fridge')) $detected = 'refrigerator';
        elseif (str_contains($title, 'washing'))   $detected = 'washing-machine';
        elseif (str_contains($title, 'microwave')) $detected = 'microwave-oven';

        return Inertia::render('Landing/Blog/Show', [
            'post'             => $post,
            'relatedPosts'     => $this->blogRepo->getRelated($post->id, $post->blog_category_id, 3),
            'serviceCategories' => $serviceCategories,
            'primarySlug'      => $detected,
        ]);
    }

    public function category(string $slug): Response
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        return Inertia::render('Landing/Blog/Index', [
            'posts'           => $this->blogRepo->getByCategory($slug, 12),
            'categories'      => BlogCategory::active()->get(['id', 'name', 'slug']),
            'currentCategory' => $slug,
            'categoryName'    => $category->name,
        ]);
    }
}