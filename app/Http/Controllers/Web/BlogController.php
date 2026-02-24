<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Models\BlogCategory;
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

        return Inertia::render('Landing/Blog/Show', [
            'post'         => $post,
            'relatedPosts' => $this->blogRepo->getRelated($post->id, $post->blog_category_id, 3),
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