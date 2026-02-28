<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Google Tag Manager --}}
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TF7JP7GT');</script>
    {{-- End Google Tag Manager --}}

    {{-- SEO: Title managed by Inertia Head component in Vue --}}
    <title inertia>{{ config('app.name', 'Chennai Smart Care') }}</title>

    {{-- Default SEO meta (overridden per-page via Inertia <Head>) --}}
    <meta name="description" content="Expert AC, Refrigerator & Washing Machine repair in Chennai. Same-day service, certified technicians, 6-month warranty on all repairs. Call +91 94449 00470." />
    <meta name="keywords"    content="AC repair Chennai, refrigerator repair Chennai, washing machine repair Chennai, appliance repair Chennai, home appliance service Chennai, AC service Porur, fridge repair Vadapalani" />
    <meta name="robots"      content="index, follow" />
    <meta name="author"      content="Chennai Smart Care" />
    <meta name="geo.region"      content="IN-TN" />
    <meta name="geo.placename"   content="Chennai, Tamil Nadu" />
    <meta name="geo.position"    content="13.0827;80.2707" />
    <meta name="ICBM"            content="13.0827, 80.2707" />
    <link rel="canonical" href="{{ url()->current() }}" />

    {{-- Open Graph --}}
    <meta property="og:type"        content="website" />
    <meta property="og:site_name"   content="Chennai Smart Care" />
    <meta property="og:url"         content="{{ url()->current() }}" />
    <meta property="og:title"       content="{{ config('app.name') }}" />
    <meta property="og:description" content="Expert AC, Refrigerator & Washing Machine repair in Chennai. Same-day service, 6-month warranty." />
    <meta property="og:image"       content="{{ asset('images/ac-washing-machine-refrigerator-service-repair-in-chennai.jpg') }}" />
    <meta property="og:image:width"  content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt"    content="Chennai Smart Care — Expert Appliance Repair" />
    <meta property="og:locale"       content="en_IN" />

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:site"        content="@ChennaiSmartCare" />
    <meta name="twitter:title"       content="{{ config('app.name') }}" />
    <meta name="twitter:description" content="Expert appliance repair in Chennai — AC, Refrigerator, Washing Machine. Same-day service." />
    <meta name="twitter:image"       content="{{ asset('images/ac-washing-machine-refrigerator-service-repair-in-chennai.jpg') }}" />

    {{-- Favicon + PWA --}}
    <link rel="icon" type="image/png" href="https://www.chennaismartcare.com/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon/favicon.svg') }}" />
    <link rel="icon" href="https://www.chennaismartcare.com/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}" />
    <meta name="theme-color" content="#1d4ed8" />
    <meta name="mobile-web-app-capable"      content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />

    {{-- ══════════════════════════════════════════════════════════════
         SCHEMA.ORG — multiple types for maximum rich result coverage
    ══════════════════════════════════════════════════════════════ --}}

    {{-- 1. LocalBusiness + AggregateRating → ⭐ stars in search results --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type":    ["LocalBusiness", "HomeAndConstructionBusiness"],
        "@id":      "{{ config('app.url') }}/#localbusiness",
        "name":        "Chennai Smart Care",
        "legalName":   "Chennai Smart Care",
        "description": "Expert AC, Refrigerator, Washing Machine and Microwave Oven repair services in Chennai, Tamil Nadu. Certified technicians, same-day service, 6-month warranty.",
        "url":       "{{ config('app.url') }}",
        "logo":      "{{ asset('images/logo.png') }}",
        "image":     "{{ asset('images/ac-washing-machine-refrigerator-service-repair-in-chennai.jpg') }}",
        "telephone": "{{ config('app.support_phone', '+91-9444900470') }}",
        "email":     "support@chennaismartcare.com",
        "priceRange": "₹₹",
        "currenciesAccepted": "INR",
        "paymentAccepted": "Cash, UPI, Credit Card, Debit Card",
        "address": {
            "@type":           "PostalAddress",
            "streetAddress":   "Chennai",
            "addressLocality": "Chennai",
            "addressRegion":   "Tamil Nadu",
            "postalCode":      "600001",
            "addressCountry":  "IN"
        },
        "geo": {
            "@type":     "GeoCoordinates",
            "latitude":  13.0827,
            "longitude": 80.2707
        },
        "areaServed": [
            {"@type": "City",       "name": "Chennai"},
            {"@type": "Neighborhood", "name": "Anna Nagar"},
            {"@type": "Neighborhood", "name": "Porur"},
            {"@type": "Neighborhood", "name": "Adyar"},
            {"@type": "Neighborhood", "name": "Velachery"},
            {"@type": "Neighborhood", "name": "T. Nagar"},
            {"@type": "Neighborhood", "name": "Koyambedu"},
            {"@type": "Neighborhood", "name": "Ambattur"},
            {"@type": "Neighborhood", "name": "Tambaram"},
            {"@type": "Neighborhood", "name": "Mylapore"},
            {"@type": "Neighborhood", "name": "Chromepet"},
            {"@type": "Neighborhood", "name": "Mogappair"},
            {"@type": "Neighborhood", "name": "Vadapalani"},
            {"@type": "Neighborhood", "name": "Guindy"},
            {"@type": "Neighborhood", "name": "Kilpauk"},
            {"@type": "Neighborhood", "name": "Perambur"},
            {"@type": "Neighborhood", "name": "Maduravoyal"},
            {"@type": "Neighborhood", "name": "Valasaravakkam"}
        ],
        "serviceType": [
            "AC Repair", "Refrigerator Repair", "Washing Machine Repair", "Microwave Oven Repair",
            "AC Gas Refilling", "AC Installation", "Appliance Service", "AC Service",
            "Fridge Repair", "Washing Machine Service", "Microwave Repair"
        ],
        "openingHoursSpecification": [{
            "@type":      "OpeningHoursSpecification",
            "dayOfWeek":  ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
            "opens":  "09:00",
            "closes": "21:00"
        }],
        "aggregateRating": {
            "@type":       "AggregateRating",
            "ratingValue": "4.9",
            "reviewCount": "2400",
            "bestRating":  "5",
            "worstRating": "1"
        },
        "hasMap": "https://maps.google.com/?q=Chennai+Smart+Care+Chennai",
        "sameAs": [
            "https://www.facebook.com/chennaismartcare",
            "https://www.instagram.com/chennaismartcare",
            "https://www.youtube.com/@chennaismartcare",
            "https://g.page/chennaismartcare"
        ]
    }
    </script>

    {{-- 2. WebSite + SearchAction → Google Sitelinks Search Box --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type":    "WebSite",
        "@id":      "{{ config('app.url') }}/#website",
        "name":     "Chennai Smart Care",
        "url":      "{{ config('app.url') }}",
        "description": "Chennai's trusted home appliance repair service — AC, Refrigerator, Washing Machine, Microwave Oven.",
        "publisher": {"@id": "{{ config('app.url') }}/#localbusiness"},
        "inLanguage": "en-IN",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type":       "EntryPoint",
                "urlTemplate": "{{ config('app.url') }}/blog?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    {{-- 3. Organization → brand authority signal + sameAs social profiles --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type":    "Organization",
        "@id":      "{{ config('app.url') }}/#organization",
        "name":     "Chennai Smart Care",
        "url":      "{{ config('app.url') }}",
        "logo": {
            "@type":  "ImageObject",
            "url":    "{{ asset('images/logo.png') }}",
            "width":  "200",
            "height": "60"
        },
        "contactPoint": [{
            "@type":             "ContactPoint",
            "telephone":         "+91-9444900470",
            "contactType":       "customer service",
            "areaServed":        "IN",
            "availableLanguage": ["English", "Tamil"],
            "contactOption":     "TollFree",
            "hoursAvailable": {
                "@type":      "OpeningHoursSpecification",
                "dayOfWeek":  ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
                "opens":  "09:00",
                "closes": "21:00"
            }
        }],
        "sameAs": [
            "https://www.facebook.com/chennaismartcare",
            "https://www.instagram.com/chennaismartcare",
            "https://www.youtube.com/@chennaismartcare",
            "https://g.page/chennaismartcare"
        ]
    }
    </script>

    {{-- 4. FAQ Schema for common questions (if on FAQ page) --}}
    @if(Route::currentRouteName() === 'faq')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "How quickly can you send a technician in Chennai?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "We offer same-day service across all Chennai areas including Porur, Vadapalani, Koyambedu, Guindy, Valasaravakkam and Maduravoyal. Book before 2 PM for a same-day slot."
                }
            },
            {
                "@type": "Question",
                "name": "What is the service charge for AC repair in Chennai?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "AC repair starts from ₹299 for basic service. All prices are transparent with no hidden charges."
                }
            },
            {
                "@type": "Question",
                "name": "Do you offer a warranty on appliance repairs?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. All repairs come with a 6-month service warranty. If the same issue recurs within 6 months, we fix it free of charge."
                }
            }
        ]
    }
    </script>
    @endif

    {{-- Preconnect + Preload for performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}" />
    <link rel="preload" as="image" href="{{ asset('images/ac-washing-machine-refrigerator-service-repair-in-chennai.jpg') }}" />

    {{-- Vite: CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inertia Head (allows Vue pages to inject <title>, <meta> etc.) --}}
    @inertiaHead
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-inter">

    {{-- Google Tag Manager (noscript fallback) --}}
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TF7JP7GT"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    {{-- Inertia app mount point --}}
    @inertia

    {{-- WhatsApp Floating Button with Pulse Animation --}}
    <a href="https://wa.me/{{ preg_replace('/\D/', '', config('app.support_phone', '919444900470')) }}?text=Hi%20Chennai%20Smart%20Care%2C%20I%20need%20appliance%20repair%20help"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Chat with us on WhatsApp"
       class="fixed bottom-6 right-6 z-50 group"
    >
        <div class="relative">
            <div class="absolute inset-0 rounded-full bg-green-500 animate-ping opacity-75 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative w-14 h-14 bg-green-500 hover:bg-green-600 active:bg-green-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full"></span>
        </div>
        <span class="absolute right-16 top-1/2 -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg">
            Chat with us
        </span>
    </a>

    {{-- Back to Top Button (appears on scroll) --}}
    <button
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-6 left-6 z-50 w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all opacity-0 invisible hover:scale-110"
        id="back-to-top"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    {{-- Script for Back to Top button --}}
    <script>
        // Back to Top button visibility
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('back-to-top');
            if (window.scrollY > 500) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        });

        // PWA service worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js').then(
                    function(registration) {
                        console.log('Service Worker registered with scope:', registration.scope);
                    },
                    function(err) {
                        console.log('Service Worker registration failed:', err);
                    }
                );
            });
        }

        // Performance monitoring
        window.addEventListener('load', function() {
            if (window.performance && window.performance.timing) {
                const timing = window.performance.timing;
                const pageLoadTime = timing.loadEventEnd - timing.navigationStart;
                console.log('Page load time:', pageLoadTime, 'ms');
                
                // Send to analytics if needed
                if (window.dataLayer) {
                    window.dataLayer.push({
                        event: 'performance',
                        page_load_time: pageLoadTime
                    });
                }
            }
        });
    </script>

    {{-- Structured data for breadcrumbs (if applicable) --}}
    @if(isset($breadcrumbs) && count($breadcrumbs) > 1)
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            @foreach($breadcrumbs as $index => $crumb)
            {
                "@type": "ListItem",
                "position": {{ $index + 1 }},
                "name": "{{ $crumb['name'] }}",
                "item": "{{ $crumb['url'] }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
    @endif

    {{-- iOS splash screen meta tags --}}
    <meta name="apple-mobile-web-app-title" content="Chennai Smart Care">
    <link rel="apple-touch-startup-image" href="{{ asset('images/splash/launch-iphone14.png') }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)">
    <link rel="apple-touch-startup-image" href="{{ asset('images/splash/launch-ipad.png') }}" media="(device-width: 768px) and (device-height: 1024px)">

    {{-- Microsoft Tile for Windows --}}
    <meta name="msapplication-TileColor" content="#1d4ed8">
    <meta name="msapplication-TileImage" content="{{ asset('images/favicon/mstile-150x150.png') }}">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">

    {{-- DNS Prefetch for external domains --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://connect.facebook.net">

    {{-- Preconnect for critical third-party origins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://www.google-analytics.com">

    {{-- Security Headers (CSP, etc.) --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">

</body>
</html>