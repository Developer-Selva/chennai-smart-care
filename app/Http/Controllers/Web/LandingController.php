<?php

namespace App\Http\Controllers\Web;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\BlogPost;
use App\Models\Consultation;
use App\Services\WhatsAppService;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LandingController extends Controller
{
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepo,
        protected WhatsAppService $whatsApp,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Landing/Home', [
            'categories'       => $this->serviceRepo->getAllActiveWithCategories(),
            'featuredServices' => $this->serviceRepo->getFeatured(),
            'testimonials'     => Review::with(['user', 'booking.items'])
                ->where('is_approved', true)
                ->where('rating', '>=', 4)
                ->latest()
                ->limit(6)
                ->get(),
            'recentPosts'      => BlogPost::published()
                ->with('category')
                ->latest('published_at')
                ->limit(3)
                ->get(['id', 'title', 'slug', 'excerpt', 'featured_image',
                       'alt_text', 'published_at', 'read_time_minutes', 'blog_category_id']),
        ]);
    }

    public function service(string $slug): Response
    {
        // Cards link to category slugs (ac, washing-machine, refrigerator)
        $category = \App\Models\ServiceCategory::where('slug', $slug)
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->first();

        // Fallback: try as a service slug
        if (! $category) {
            $service = $this->serviceRepo->findBySlug($slug);
            abort_if(! $service, 404);
            $category = $service->category()
                ->with(['services' => fn ($q) => $q->where('is_active', true)])
                ->first();
        }

        abort_if(! $category, 404);

        $otherCategories = \App\Models\ServiceCategory::where('slug', '!=', $category->slug)
            ->where('is_active', true)
            ->withCount('services')
            ->get();

        return Inertia::render('Landing/ServiceCategory', [
            'category'        => $category,
            'services'        => $category->services,
            'otherCategories' => $otherCategories,
        ]);
    }

    public function quickBooking(): Response
    {
        return Inertia::render('Landing/QuickBooking', [
            'categories' => $this->serviceRepo->getAllActiveWithCategories(),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('Landing/About');
    }

    public function contact(): Response
    {
        return Inertia::render('Landing/Contact');
    }

    public function consultationPage(): Response
    {
        return Inertia::render('Landing/Consultation');
    }

    public function freeConsultation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'phone'            => 'required|string|regex:/^[6-9]\d{9}$/',
            'email'            => 'nullable|email|max:150',
            'message'          => 'nullable|string|max:500',
            'service_interest' => 'nullable|string|max:50',
        ]);

        $consultation = Consultation::create($validated);

        // Notify customer + admin via WhatsApp (queued, non-blocking)
        dispatch(fn () => $this->whatsApp->sendConsultationCreated($consultation));

        return response()->json([
            'message' => 'Thank you! We will call you within 30 minutes.',
        ]);
    }

    public function trackBooking(string $bookingNumber): Response
    {
        return Inertia::render('Landing/TrackBooking', [
            'bookingNumber' => $bookingNumber,
        ]);
    }

    public function sitemap(): \Illuminate\Http\Response
    {
        $posts      = BlogPost::published()->latest('published_at')->get(['slug', 'updated_at']);
        $categories = $this->serviceRepo->getAllCategories();

        $content = view('seo.sitemap', compact('posts', 'categories'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): \Illuminate\Http\Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /user/\n\nSitemap: " . url('/sitemap.xml');

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}