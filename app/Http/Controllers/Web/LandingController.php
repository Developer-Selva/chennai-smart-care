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
        protected ServiceRepositoryInterface $serviceRepo
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

        // Real aggregate rating from reviews (cached 1h to avoid per-request query)
        $rating = \Illuminate\Support\Facades\Cache::remember('site_aggregate_rating', 3600, function () {
            return \App\Models\Review::where('is_approved', true)
                ->selectRaw('ROUND(AVG(rating),1) as avg_rating, COUNT(*) as total')
                ->first();
        });

        return Inertia::render('Landing/ServiceCategory', [
            'category'        => $category,
            'services'        => $category->services,
            'otherCategories' => $otherCategories,
            'aggregateRating' => [
                'value' => $rating->avg_rating ?? '4.8',
                'count' => $rating->total      ?? 247,
            ],
        ]);
    }

    public function serviceArea(string $slug, string $areaSlug): Response
    {
        $category = \App\Models\ServiceCategory::where('slug', $slug)
            ->where('is_active', true)
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->firstOrFail();

        // Area data — name, slug, nearby areas
        $areas = self::SERVICE_AREAS;
        $area  = collect($areas)->firstWhere('slug', $areaSlug);
        abort_if(! $area, 404);

        $otherCategories = \App\Models\ServiceCategory::where('slug', '!=', $slug)
            ->where('is_active', true)
            ->get(['name', 'slug', 'icon']);

        // Related blog posts for this category
        $relatedPosts = \App\Models\BlogPost::published()
            ->where('title', 'like', "%{$category->name}%")
            ->orWhere('title', 'like', "%{$area['name']}%")
            ->latest('published_at')
            ->limit(4)
            ->get(['id', 'title', 'slug']);

        // Real aggregate rating from reviews
        $rating = \App\Models\Review::where('is_approved', true)
            ->selectRaw('ROUND(AVG(rating),1) as avg_rating, COUNT(*) as total')
            ->first();

        return Inertia::render('Landing/ServiceAreaLanding', [
            'category'        => $category,
            'area'            => $area,
            'services'        => $category->services,
            'otherCategories' => $otherCategories,
            'relatedPosts'    => $relatedPosts,
            'aggregateRating' => [
                'value' => $rating->avg_rating ?? '4.8',
                'count' => $rating->total ?? 247,
            ],
        ]);
    }

    // All service areas — single source of truth used by controller + sitemap
    public const SERVICE_AREAS = [
        ['name' => 'Porur',       'slug' => 'porur',       'nearbyAreas' => ['Maduravoyal', 'Valasaravakkam', 'Koyambedu', 'Mogappair']],
        ['name' => 'Anna Nagar',  'slug' => 'anna-nagar',  'nearbyAreas' => ['Kilpauk', 'Aminjikarai', 'Koyambedu', 'Mogappair']],
        ['name' => 'Adyar',       'slug' => 'adyar',       'nearbyAreas' => ['Mylapore', 'Thiruvanmiyur', 'Velachery', 'Besant Nagar']],
        ['name' => 'Velachery',   'slug' => 'velachery',   'nearbyAreas' => ['Adyar', 'Pallikaranai', 'Tambaram', 'Chromepet']],
        ['name' => 'T. Nagar',    'slug' => 't-nagar',     'nearbyAreas' => ['Mylapore', 'Nungambakkam', 'Kodambakkam', 'Ashok Nagar']],
        ['name' => 'Koyambedu',   'slug' => 'koyambedu',   'nearbyAreas' => ['Vadapalani', 'Ashok Nagar', 'Porur', 'Anna Nagar']],
        ['name' => 'Maduravoyal', 'slug' => 'maduravoyal', 'nearbyAreas' => ['Porur', 'Ambattur', 'Mogappair', 'Valasaravakkam']],
        ['name' => 'Vadapalani',  'slug' => 'vadapalani',  'nearbyAreas' => ['Koyambedu', 'Ashok Nagar', 'Arumbakkam', 'Virugambakkam']],
        ['name' => 'Ambattur',    'slug' => 'ambattur',    'nearbyAreas' => ['Maduravoyal', 'Mogappair', 'Padi', 'Kolathur']],
        ['name' => 'Mogappair',   'slug' => 'mogappair',   'nearbyAreas' => ['Anna Nagar', 'Ambattur', 'Maduravoyal', 'Padi']],
        ['name' => 'Chromepet',   'slug' => 'chromepet',   'nearbyAreas' => ['Tambaram', 'Pallavaram', 'Velachery', 'Pallikaranai']],
        ['name' => 'Tambaram',    'slug' => 'tambaram',    'nearbyAreas' => ['Chromepet', 'Pallavaram', 'Mudichur', 'Vandalur']],
        ['name' => 'Guindy',      'slug' => 'guindy',      'nearbyAreas' => ['Ashok Nagar', 'Kodambakkam', 'Vadapalani', 'T. Nagar']],
        ['name' => 'Mylapore',    'slug' => 'mylapore',    'nearbyAreas' => ['T. Nagar', 'Adyar', 'Alwarpet', 'Abhiramapuram']],
        ['name' => 'Kilpauk',     'slug' => 'kilpauk',     'nearbyAreas' => ['Anna Nagar', 'Perambur', 'Purasawalkam', 'Chetpet']],
        ['name' => 'Perambur',       'slug' => 'perambur',       'nearbyAreas' => ['Kilpauk', 'Kolathur', 'Villivakkam', 'Tondiarpet']],
        // ── OMR / South Chennai Corridor (IT belt — high-density) ──
        ['name' => 'OMR',             'slug' => 'omr',            'nearbyAreas' => ['Sholinganallur', 'Perungudi', 'Thoraipakkam', 'Navalur']],
        ['name' => 'Sholinganallur',  'slug' => 'sholinganallur', 'nearbyAreas' => ['OMR', 'Perungudi', 'Navalur', 'Karapakkam']],
        ['name' => 'Perungudi',       'slug' => 'perungudi',      'nearbyAreas' => ['OMR', 'Sholinganallur', 'Velachery', 'Pallikaranai']],
        ['name' => 'Thoraipakkam',    'slug' => 'thoraipakkam',   'nearbyAreas' => ['OMR', 'Perungudi', 'Sholinganallur', 'Karapakkam']],
        ['name' => 'Pallikaranai',    'slug' => 'pallikaranai',   'nearbyAreas' => ['Velachery', 'Perungudi', 'Medavakkam', 'Chromepet']],
        ['name' => 'Medavakkam',      'slug' => 'medavakkam',     'nearbyAreas' => ['Pallikaranai', 'Sholinganallur', 'Chromepet', 'Tambaram']],
        // ── North / Central Chennai ──
        ['name' => 'Nungambakkam',    'slug' => 'nungambakkam',   'nearbyAreas' => ['T. Nagar', 'Chetpet', 'Egmore', 'Kodambakkam']],
        ['name' => 'Valasaravakkam',  'slug' => 'valasaravakkam', 'nearbyAreas' => ['Porur', 'Koyambedu', 'Virugambakkam', 'Mogappair']],
        // ── West Chennai localities ──
        ['name' => 'Mangadu',        'slug' => 'mangadu',        'nearbyAreas' => ['Porur', 'Kovur', 'Kundrathur', 'Pammal']],
        ['name' => 'Kovur',          'slug' => 'kovur',          'nearbyAreas' => ['Mangadu', 'Porur', 'Kundrathur', 'Pammal']],
        ['name' => 'Korattur',       'slug' => 'korattur',       'nearbyAreas' => ['Ambattur', 'Villivakkam', 'Kolathur', 'Perambur']],
        // ── South-West Chennai ──
        ['name' => 'Pammal',         'slug' => 'pammal',         'nearbyAreas' => ['Tambaram', 'Chromepet', 'Pallavaram', 'Kovur']],
    ];

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
        $posts      = BlogPost::published()->latest('published_at')->get(['slug', 'updated_at', 'featured_image', 'alt_text', 'title']);
        $categories = $this->serviceRepo->getAllCategories();
        $areas      = self::SERVICE_AREAS;

        $content = view('seo.sitemap', compact('posts', 'categories', 'areas'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapIndex(): \Illuminate\Http\Response
    {
        $content = view('seo.sitemap-index')->render();
        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): \Illuminate\Http\Response
    {
        $lines = [
            "User-agent: *",
            "Allow: /",
            "Disallow: /admin/",
            "Disallow: /user/",
            "Disallow: /api/",
            "Disallow: /track/",
            "Disallow: /*?",            # block query strings
            "",
            "# Crawl-delay for bots",
            "Crawl-delay: 1",
            "",
            "Sitemap: " . url('/sitemap-index.xml'),
            "Sitemap: " . url('/sitemap.xml'),
        ];
        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}