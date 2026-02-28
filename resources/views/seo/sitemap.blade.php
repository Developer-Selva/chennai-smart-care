<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
          http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

  {{-- ── STATIC PAGES ── --}}
  <url>
    <loc>{{ url('/') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>{{ url('/free-consultation') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/about') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  <url>
    <loc>{{ url('/contact') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <url>
    <loc>{{ url('/blog') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/quick-booking') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.85</priority>
  </url>

  {{-- ── SERVICE CATEGORY PAGES (priority 0.95 — high-intent) ── --}}
  @foreach ($categories as $category)
  <url>
    <loc>{{ url('/services/' . $category->slug) }}</loc>
    <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.95</priority>
  </url>
  @endforeach

  {{-- ── SERVICE + AREA LANDING PAGES (priority 0.90 — local SEO gold) ── --}}
  @foreach ($categories as $category)
    @foreach ($areas as $area)
  <url>
    <loc>{{ url('/services/' . $category->slug . '/' . $area['slug']) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.90</priority>
  </url>
    @endforeach
  @endforeach

  {{-- ── BLOG POSTS (with image tags) ── --}}
  @foreach ($posts as $post)
  <url>
    <loc>{{ url('/blog/' . $post->slug) }}</loc>
    <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.70</priority>
    @if ($post->featured_image)
    <image:image>
      <image:loc>{{ $post->featured_image }}</image:loc>
      <image:caption>{{ $post->title }}</image:caption>
      <image:title>{{ $post->alt_text ?? $post->title }}</image:title>
    </image:image>
    @endif
  </url>
  @endforeach

</urlset>