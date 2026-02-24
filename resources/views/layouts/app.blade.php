{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- SEO Meta Tags (overrideable via Inertia shared props) --}}
    <title inertia>{{ config('app.name', 'Chennai Smart Care') }}</title>
    <meta name="description" content="@yield('meta_description', 'Expert AC, Refrigerator & Washing Machine repair service in Chennai. Same-day service, certified technicians, 6-Month warranty.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'AC repair Chennai, refrigerator repair Chennai, washing machine repair Chennai, appliance repair')" />
    <meta name="robots" content="@yield('robots', 'index, follow')" />
    <link rel="canonical" href="@yield('canonical', url()->current())" />

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:title" content="@yield('og_title', config('app.name'))" />
    <meta property="og:description" content="@yield('og_description', 'Expert appliance repair in Chennai')" />
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('og_title', config('app.name'))" />
    <meta name="twitter:description" content="@yield('og_description', 'Expert appliance repair in Chennai')" />

    {{-- Local Business Schema --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Chennai Smart Care",
        "description": "Expert AC, Refrigerator and Washing Machine repair services in Chennai",
        "url": "{{ config('app.url') }}",
        "telephone": "+91-9XXXXXXXXX",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Chennai",
            "addressRegion": "Tamil Nadu",
            "addressCountry": "IN"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 13.0827,
            "longitude": 80.2707
        },
        "areaServed": {
            "@type": "GeoCircle",
            "geoMidpoint": {
                "@type": "GeoCoordinates",
                "latitude": 13.0827,
                "longitude": 80.2707
            },
            "geoRadius": "20000"
        },
        "openingHoursSpecification": [{
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
            "opens": "09:00",
            "closes": "21:00"
        }],
        "priceRange": "₹₹",
        "image": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "https://www.facebook.com/chennaismarcare",
            "https://www.instagram.com/chennaismarcare"
        ]
    }
    </script>

    {{-- Dynamic page schema --}}
    @yield('schema_markup')

    {{-- Google Tag Manager --}}
    @if(config('analytics.gtm_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ config("analytics.gtm_id") }}');</script>
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet" />

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}" />
    <meta name="theme-color" content="#1d4ed8" />

    {{-- Inertia + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-inter">
    {{-- Google Tag Manager (noscript) --}}
    @if(config('analytics.gtm_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('analytics.gtm_id') }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @inertia

    {{-- WhatsApp Float Button --}}
    <a href="https://wa.me/{{ config('app.whatsapp_number') }}?text=Hi%20Chennai%20Smart%20Care%2C%20I%20need%20help%20with%20my%20appliance"
       target="_blank"
       rel="noopener"
       class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition-transform hover:scale-110"
       aria-label="Chat on WhatsApp"
       onclick="gtag('event', 'whatsapp_click', {'event_category': 'CTA'})">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>
</html>


{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title inertia>Admin — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
    @inertiaHead
</head>
<body class="antialiased bg-gray-100 font-inter">
    @inertia
</body>
</html>