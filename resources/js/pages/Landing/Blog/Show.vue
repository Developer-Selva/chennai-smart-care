<template>
  <div class="min-h-screen bg-white">

    <Head>
      <title>{{ post.meta_title ?? post.title }}</title>
      <meta name="description" :content="post.meta_description ?? post.excerpt" />
      <meta name="keywords"    :content="post.focus_keyword" />
      <meta name="robots"      :content="post.is_indexed === false ? 'noindex,nofollow' : 'index,follow'" />
      <link rel="canonical"    :href="canonicalUrl" />

      <!-- Open Graph — Article -->
      <meta property="og:type"                content="article" />
      <meta property="og:site_name"           content="Chennai Smart Care" />
      <meta property="og:url"                 :content="canonicalUrl" />
      <meta property="og:title"              :content="post.meta_title ?? post.title" />
      <meta property="og:description"        :content="post.meta_description ?? post.excerpt" />
      <meta property="og:image"              :content="ogImage" />
      <meta property="og:image:alt"          :content="post.alt_text ?? post.title" />
      <meta property="og:image:width"         content="1200" />
      <meta property="og:image:height"        content="630" />
      <meta property="og:locale"              content="en_IN" />
      <meta property="article:published_time" :content="post.published_at" />
      <meta property="article:modified_time"  :content="post.updated_at" />
      <meta property="article:author"         content="Chennai Smart Care" />
      <meta property="article:section"        :content="post.category?.name ?? 'Appliance Repair'" />
      <meta property="article:tag" v-for="tag in (post.tags ?? [])" :key="tag" :content="tag" />

      <!-- Twitter Cards -->
      <meta name="twitter:card"        :content="post.twitter_card ?? 'summary_large_image'" />
      <meta name="twitter:site"         content="@ChennaiSmartCare" />
      <meta name="twitter:title"       :content="post.meta_title ?? post.title" />
      <meta name="twitter:description" :content="post.meta_description ?? post.excerpt" />
      <meta name="twitter:image"       :content="ogImage" />
      <meta name="twitter:image:alt"   :content="post.alt_text ?? post.title" />
    </Head>

    <AppHeader :minimal="true" />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

      <!-- Breadcrumb -->
      <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs text-gray-400 mb-8">
        <a href="/" class="hover:text-gray-600 transition-colors">Home</a>
        <span>/</span>
        <a href="/blog" class="hover:text-gray-600 transition-colors">Blog</a>
        <span v-if="post.category">/</span>
        <a v-if="post.category" :href="`/blog/category/${post.category.slug}`"
           class="hover:text-gray-600 transition-colors">{{ post.category.name }}</a>
        <span>/</span>
        <span class="text-gray-600 truncate max-w-[200px]">{{ post.title }}</span>
      </nav>

      <!-- Article Header -->
      <header class="mb-8">
        <span v-if="post.category"
              class="inline-flex items-center text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full mb-4">
          {{ post.category.name }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight tracking-tight">
          {{ post.title }}
        </h1>
        <p class="mt-4 text-xl text-gray-500 leading-relaxed">{{ post.excerpt }}</p>
        <div class="flex flex-wrap items-center gap-4 mt-6 text-sm text-gray-400">
          <span class="flex items-center gap-1.5">
            <CalendarIcon class="w-4 h-4" />
            <time :datetime="post.published_at">{{ formatDate(post.published_at) }}</time>
          </span>
          <span class="flex items-center gap-1.5">
            <ClockIcon class="w-4 h-4" />{{ post.read_time_minutes }} min read
          </span>
          <span class="flex items-center gap-1.5">
            <EyeIcon class="w-4 h-4" />{{ post.view_count ?? 0 }} views
          </span>
          <span v-if="post.author" class="flex items-center gap-1.5">
            <UserIcon class="w-4 h-4" />{{ post.author.name }}
          </span>
        </div>
      </header>

      <!-- Featured Image -->
      <div v-if="post.featured_image"
           class="rounded-2xl overflow-hidden mb-10 aspect-[16/9] bg-gray-100">
        <img :src="post.featured_image" :alt="post.alt_text ?? post.title"
             width="800" height="450"
             class="w-full h-full object-cover" loading="eager" fetchpriority="high" />
      </div>

      <!-- Content + Sidebar -->
      <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10">

        <!-- Article -->
        <article>
          <div class="prose prose-lg prose-gray max-w-none
                      prose-headings:font-bold prose-headings:text-gray-900
                      prose-h2:text-2xl prose-h3:text-xl
                      prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                      prose-img:rounded-xl prose-img:shadow-md
                      prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50
                      prose-blockquote:rounded-r-xl prose-strong:text-gray-900"
               v-html="post.content">
          </div>

          <!-- Tags -->
          <div v-if="post.tags?.length" class="mt-8 flex flex-wrap gap-2">
            <span v-for="tag in post.tags" :key="tag"
                  class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">
              #{{ tag }}
            </span>
          </div>

          <!-- FAQ Accordion -->
          <div v-if="post.faq_items?.length" class="mt-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-5 flex items-center gap-2">
              <QuestionMarkCircleIcon class="w-6 h-6 text-blue-600" />
              Frequently Asked Questions
            </h2>
            <div class="space-y-3">
              <details v-for="(faq, i) in post.faq_items" :key="i"
                       class="group bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                <summary class="flex items-center justify-between p-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-100 transition-colors list-none">
                  <span>{{ faq.question }}</span>
                  <ChevronDownIcon class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform group-open:rotate-180" />
                </summary>
                <div class="px-4 pb-4 text-sm text-gray-700 leading-relaxed border-t border-gray-200 pt-3">
                  {{ faq.answer }}
                </div>
              </details>
            </div>
          </div>

          <!-- CTA -->
          <div class="mt-10 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white">
            <h3 class="text-xl font-bold mb-2">Need an Expert to Fix Your Appliance?</h3>
            <p class="text-blue-100 text-sm mb-4">
              Certified technicians across all Chennai areas. Same-day service available.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
              <a href="/quick-booking"
                 class="inline-flex items-center justify-center bg-white text-blue-700 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition-colors">
                📅 Book Service Now
              </a>
              <a href="/free-consultation"
                 class="inline-flex items-center justify-center border-2 border-white/60 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-white/10 transition-colors">
                💬 Free Consultation
              </a>
            </div>
          </div>

          <!-- Internal Linking — Bottom of Article (body links > sidebar links for SEO) -->
          <SeoInternalLinks
            :service-categories="serviceCategories"
            :primary-slug="primarySlug"
          />
        </article>

        <!-- Sidebar -->
        <aside class="space-y-6">

          <!-- Table of Contents -->
          <div v-if="headings.length" class="bg-blue-50 rounded-2xl p-5 border border-blue-100">
            <h3 class="font-bold text-blue-900 mb-3 text-sm uppercase tracking-wide">In This Article</h3>
            <nav>
              <ul class="space-y-1.5">
                <li v-for="h in headings" :key="h.id" :class="h.level === 3 ? 'pl-3' : ''">
                  <a :href="`#${h.id}`"
                     class="text-xs text-blue-700 hover:text-blue-900 hover:underline transition-colors leading-relaxed block">
                    {{ h.text }}
                  </a>
                </li>
              </ul>
            </nav>
          </div>

          <!-- Related Posts -->
          <div v-if="relatedPosts?.length" class="bg-gray-50 rounded-2xl p-5">
            <h3 class="font-bold text-gray-900 mb-4">Related Articles</h3>
            <div class="space-y-4">
              <a v-for="related in relatedPosts" :key="related.id"
                 :href="`/blog/${related.slug}`" class="flex gap-3 group">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex-shrink-0 overflow-hidden">
                  <img v-if="related.featured_image" :src="related.featured_image"
                       :alt="related.title" width="64" height="64"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy" />
                  <div v-else class="w-full h-full flex items-center justify-center text-2xl">🔧</div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                    {{ related.title }}
                  </p>
                  <p class="text-xs text-gray-400 mt-1">{{ related.read_time_minutes }} min read</p>
                </div>
              </a>
            </div>
          </div>

          <!-- Sticky CTA -->
          <div class="bg-blue-600 rounded-2xl p-5 text-white sticky top-20">
            <p class="font-bold text-lg mb-1">Book a Repair</p>
            <p class="text-blue-100 text-xs mb-4">Expert service · 6-month warranty · Transparent pricing.</p>
            <a href="/quick-booking"
               class="block w-full text-center bg-white text-blue-700 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-50 transition-colors">
              Book Now →
            </a>
            <a href="tel:+919444900470"
               class="block w-full text-center border border-white/40 text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-white/10 transition-colors mt-2">
              📞 +91 94449 00470
            </a>
          </div>

          <!-- Pillar Internal Links — SEO critical -->
          <SeoInternalLinks
            :service-categories="serviceCategories"
            :primary-slug="primarySlug"
          />

          <!-- Service Area Quick Links -->
          <div class="bg-white rounded-2xl p-5 border border-gray-200">
            <h3 class="font-bold text-gray-900 mb-3 text-sm">We Serve</h3>
            <div class="flex flex-wrap gap-1.5">
              <a v-for="area in serviceAreas" :key="area.slug"
                 :href="`/services/${categorySlug}/${area.slug}`"
                 class="text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full hover:bg-blue-50 hover:text-blue-700 transition-colors">
                {{ area.name }}
              </a>
            </div>
          </div>
        </aside>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import {
  CalendarIcon, ClockIcon, EyeIcon, UserIcon,
  QuestionMarkCircleIcon, ChevronDownIcon,
} from '@heroicons/vue/24/outline'
import SeoInternalLinks from '@/components/Shared/SeoInternalLinks.vue'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const props = defineProps({
  post:         { type: Object, required: true },
  relatedPosts:      { type: Array,  default: () => [] },
  serviceCategories: { type: Array,  default: () => [] },
  primarySlug:       { type: String, default: 'ac' },
})

const appUrl       = window.location.origin
const canonicalUrl = computed(() => props.post.canonical_url || `${appUrl}/blog/${props.post.slug}`)
const ogImage      = computed(() =>
  props.post.og_image || props.post.featured_image || `${appUrl}/images/ac-washing-machine-refrigerator-service-repair-in-chennai.jpg`
)

const categorySlug = computed(() => {
  const name = props.post.category?.name?.toLowerCase() ?? ''
  if (name.includes('ac') || name.includes('air')) return 'ac'
  if (name.includes('refrigerator') || name.includes('fridge')) return 'refrigerator'
  if (name.includes('washing')) return 'washing-machine'
  if (name.includes('microwave')) return 'microwave-oven'
  return 'ac'
})

const serviceAreas = [
  { name: 'Porur',       slug: 'porur' },
  { name: 'Anna Nagar',  slug: 'anna-nagar' },
  { name: 'Adyar',       slug: 'adyar' },
  { name: 'Velachery',   slug: 'velachery' },
  { name: 'T. Nagar',    slug: 't-nagar' },
  { name: 'Koyambedu',   slug: 'koyambedu' },
  { name: 'Maduravoyal', slug: 'maduravoyal' },
  { name: 'Vadapalani',  slug: 'vadapalani' },
  { name: 'Ambattur',    slug: 'ambattur' },
  { name: 'Mogappair',   slug: 'mogappair' },
  { name: 'Chromepet',   slug: 'chromepet' },
  { name: 'Tambaram',    slug: 'tambaram' },
]

const headings = ref([])
function buildToc() {
  const container = document.querySelector('.prose')
  if (!container) return
  container.querySelectorAll('h2, h3').forEach((el, i) => {
    if (!el.id) el.id = `h-${i}-${el.textContent.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]/g, '').slice(0, 40)}`
    headings.value.push({ id: el.id, text: el.textContent.trim(), level: parseInt(el.tagName[1]) })
  })
}

function injectJsonLd(id, data) {
  document.getElementById(id)?.remove()
  const el = document.createElement('script')
  el.type = 'application/ld+json'
  el.id = id
  el.textContent = JSON.stringify(data)
  document.head.appendChild(el)
}
function removeJsonLd(id) { document.getElementById(id)?.remove() }

function injectAllSchema() {
  const base = 'https://chennaismartcare.com'
  const url  = canonicalUrl.value

  // 1. BlogPosting
  injectJsonLd('schema-article', {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "mainEntityOfPage": { "@type": "WebPage", "@id": url },
    "headline":         props.post.meta_title ?? props.post.title,
    "description":      props.post.meta_description ?? props.post.excerpt,
    "image": { "@type": "ImageObject", "url": ogImage.value, "width": 1200, "height": 630 },
    "datePublished":    props.post.published_at,
    "dateModified":     props.post.updated_at,
    "author": {
      "@type": "Organization",
      "name":  props.post.author?.name ?? "Chennai Smart Care",
      "url":   base,
    },
    "publisher": {
      "@type": "Organization",
      "name":  "Chennai Smart Care",
      "url":   base,
      "logo":  { "@type": "ImageObject", "url": `${base}/images/logo.png`, "width": 300, "height": 60 },
    },
    "wordCount":      (props.post.read_time_minutes ?? 5) * 200,
    "articleSection": props.post.category?.name ?? "Appliance Repair",
    "keywords":       (props.post.tags ?? []).join(', '),
    "inLanguage":     "en-IN",
  })

  // 2. BreadcrumbList
  const crumbs = [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": base },
    { "@type": "ListItem", "position": 2, "name": "Blog", "item": `${base}/blog` },
  ]
  if (props.post.category) {
    crumbs.push({ "@type": "ListItem", "position": 3, "name": props.post.category.name, "item": `${base}/blog/category/${props.post.category.slug}` })
    crumbs.push({ "@type": "ListItem", "position": 4, "name": props.post.title, "item": url })
  } else {
    crumbs.push({ "@type": "ListItem", "position": 3, "name": props.post.title, "item": url })
  }
  injectJsonLd('schema-breadcrumb', { "@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": crumbs })

  // 3. FAQPage
  const faqs = props.post.faq_items?.length
    ? props.post.faq_items
    : (props.post.schema_markup?.faqItems ?? [])
  if (faqs.length) {
    injectJsonLd('schema-faq', {
      "@context": "https://schema.org",
      "@type":    "FAQPage",
      "mainEntity": faqs.map(f => ({
        "@type":          "Question",
        "name":           f.question,
        "acceptedAnswer": { "@type": "Answer", "text": f.answer },
      }))
    })
  }

  // 4. HowTo
  if (props.post.how_to_steps?.length) {
    injectJsonLd('schema-howto', {
      "@context":   "https://schema.org",
      "@type":      "HowTo",
      "name":       props.post.title,
      "description": props.post.excerpt,
      "step": props.post.how_to_steps.map((s, i) => ({
        "@type": "HowToStep", "position": i + 1, "name": s.name, "text": s.text,
      }))
    })
  }
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' })
}

onMounted(() => {
  injectAllSchema()
  buildToc()
  axios.post(`/api/v1/blog/${props.post.slug}/view`).catch(() => {})
  window.dataLayer?.push({ event: 'article_view', article_title: props.post.title, article_slug: props.post.slug })
})
onUnmounted(() => ['schema-article','schema-breadcrumb','schema-faq','schema-howto'].forEach(removeJsonLd))
</script>