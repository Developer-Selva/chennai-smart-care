<template>
  <div class="min-h-screen bg-white">

    <Head>
      <title>{{ pageTitle }}</title>
      <meta name="description" :content="metaDescription" />
      <meta name="robots" content="index, follow" />
      <link rel="canonical" :href="canonicalUrl" />
      <meta name="geo.region"    content="IN-TN" />
      <meta name="geo.placename" :content="`${area.name}, Chennai`" />

      <!-- Open Graph -->
      <meta property="og:type"        content="website" />
      <meta property="og:site_name"   content="Chennai Smart Care" />
      <meta property="og:url"         :content="canonicalUrl" />
      <meta property="og:title"       :content="pageTitle" />
      <meta property="og:description" :content="metaDescription" />
      <meta property="og:image"       content="https://chennaismartcare.com/images/og-service.jpg" />
      <meta property="og:locale"      content="en_IN" />

      <!-- Twitter -->
      <meta name="twitter:card"        content="summary_large_image" />
      <meta name="twitter:title"       :content="pageTitle" />
      <meta name="twitter:description" :content="metaDescription" />
    </Head>

    <AppHeader />

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white overflow-hidden">
      <!-- Background pattern -->
      <div class="absolute inset-0 opacity-10"
           style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;"></div>
      <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>

      <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-blue-300 text-xs mb-8" aria-label="Breadcrumb">
          <a href="/" class="hover:text-white transition-colors">Home</a>
          <span>/</span>
          <a :href="`/services/${category.slug}`" class="hover:text-white transition-colors">{{ category.name }}</a>
          <span>/</span>
          <span class="text-blue-100">{{ area.name }}</span>
        </nav>

        <div class="flex flex-col sm:flex-row items-start gap-6">
          <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/10 backdrop-blur rounded-2xl flex items-center justify-center text-4xl sm:text-5xl flex-shrink-0 border border-white/20">
            {{ category.icon || '🔧' }}
          </div>
          <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-3">
              <span class="text-xs font-semibold bg-blue-500/40 text-blue-100 px-3 py-1 rounded-full border border-blue-400/30 uppercase tracking-wider">
                Local Expert Service
              </span>
              <span class="text-xs font-semibold bg-green-500/30 text-green-200 px-3 py-1 rounded-full border border-green-400/30 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                Available Today
              </span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-black leading-tight tracking-tight">
              {{ category.name }} Repair<br class="hidden sm:block" />
              <span class="text-blue-300">in {{ area.name }}</span>
            </h1>
            <p class="mt-4 text-blue-100 text-lg max-w-2xl leading-relaxed">
              Certified {{ category.name.toLowerCase() }} technicians serving {{ area.name }}, {{ area.nearbyAreas.slice(0,2).join(', ') }} and surrounding areas.
              Same-day service · 6-month warranty · Transparent pricing.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
              <a :href="`/quick-booking?category=${category.slug}&area=${area.slug}`"
                 class="bg-white text-blue-900 font-bold px-6 py-3 rounded-xl hover:bg-blue-50 transition-all hover:-translate-y-0.5 shadow-lg shadow-blue-900/30 flex items-center gap-2 text-sm">
                <CalendarIcon class="w-4 h-4" />
                Book Now — Free Visit
              </a>
              <a href="tel:+919444900470"
                 class="border-2 border-white/40 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/10 transition-colors flex items-center gap-2 text-sm">
                <PhoneIcon class="w-4 h-4" />
                +91 94449 00470
              </a>
            </div>

            <!-- Trust signals row -->
            <div class="flex flex-wrap items-center gap-5 mt-8 text-sm">
              <div class="flex items-center gap-2">
                <div class="flex text-yellow-400">★★★★★</div>
                <span class="text-blue-200">4.8/5 (247 reviews)</span>
              </div>
              <span class="text-blue-500">|</span>
              <span class="text-blue-200">✓ 30–60 min response in {{ area.name }}</span>
              <span class="text-blue-500">|</span>
              <span class="text-blue-200">✓ 10+ years serving Chennai</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-10">

          <!-- Why Locals Trust Us in this Area -->
          <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
              Why {{ area.name }} Residents Choose Chennai Smart Care
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div v-for="trust in trustPoints" :key="trust.title"
                   class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all">
                <div class="text-2xl mb-2">{{ trust.icon }}</div>
                <h3 class="font-bold text-gray-900 text-sm">{{ trust.title }}</h3>
                <p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ trust.desc }}</p>
              </div>
            </div>
          </section>

          <!-- Services -->
          <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">
              {{ category.name }} Services in {{ area.name }}
            </h2>
            <p class="text-gray-500 mb-6">All services include a doorstep visit, diagnosis, repair, and 6-month warranty.</p>
            <div class="space-y-3">
              <div v-for="service in services" :key="service.id"
                   class="bg-white rounded-2xl border-2 border-gray-100 p-5 hover:border-blue-200 hover:shadow-md transition-all group">
                <div class="flex items-start justify-between gap-4">
                  <div class="flex-1">
                    <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ service.name }}</h3>
                    <p v-if="service.description" class="text-gray-500 text-sm mt-1">{{ service.description }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-400">
                      <span v-if="service.duration_estimate" class="flex items-center gap-1">
                        <ClockIcon class="w-3.5 h-3.5" /> {{ service.duration_estimate }}
                      </span>
                      <span class="flex items-center gap-1 text-green-600">
                        <ShieldCheckIcon class="w-3.5 h-3.5" /> {{ service.warranty_days ?? 180 }}-day warranty
                      </span>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0">
                    <p v-if="service.discounted_price" class="text-xs text-gray-400 line-through">₹{{ service.base_price }}</p>
                    <p class="text-2xl font-extrabold text-gray-900">₹{{ service.effective_price ?? service.base_price }}</p>
                    <p class="text-xs text-gray-400">onwards</p>
                    <a :href="`/quick-booking?service=${service.id}&area=${area.slug}`"
                       class="mt-2 inline-block bg-blue-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors">
                      Book
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Area Coverage Map / Nearby -->
          <section class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
            <h2 class="text-xl font-bold text-gray-900 mb-1 flex items-center gap-2">
              <MapPinIcon class="w-5 h-5 text-blue-600" />
              Covering {{ area.name }} &amp; Nearby Areas
            </h2>
            <p class="text-gray-500 text-sm mb-5">Our {{ area.name }}-based technicians also serve these surrounding localities:</p>
            <div class="flex flex-wrap gap-2">
              <a v-for="nearby in area.nearbyAreas" :key="nearby"
                 :href="`/services/${category.slug}/${slugify(nearby)}`"
                 class="bg-white border border-blue-200 text-blue-700 text-sm px-3 py-1.5 rounded-full hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all font-medium">
                📍 {{ nearby }}
              </a>
            </div>
            <p class="text-xs text-gray-400 mt-4">
              Response time: typically 30–90 minutes within {{ area.name }} area.
            </p>
          </section>

          <!-- FAQ -->
          <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
              <QuestionMarkCircleIcon class="w-6 h-6 text-blue-600" />
              FAQs — {{ category.name }} Repair in {{ area.name }}
            </h2>
            <div class="space-y-3">
              <details v-for="(faq, i) in localFaqs" :key="i"
                       class="group bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                <summary class="flex items-center justify-between p-4 cursor-pointer font-semibold text-gray-900 hover:bg-gray-100 transition-colors list-none">
                  <span>{{ faq.q }}</span>
                  <ChevronDownIcon class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform group-open:rotate-180" />
                </summary>
                <p class="px-4 pb-4 pt-3 text-sm text-gray-600 leading-relaxed border-t border-gray-200">
                  {{ faq.a }}
                </p>
              </details>
            </div>
          </section>

          <!-- How We Work -->
          <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">How It Works</h2>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div v-for="(step, i) in howItWorks" :key="i" class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-xl mx-auto mb-3 relative">
                  {{ step.icon }}
                  <div class="absolute -right-4 top-1/2 -translate-y-1/2 text-gray-300 text-lg font-light hidden sm:block">→</div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Step {{ i + 1 }}</p>
                <p class="font-bold text-gray-900 text-sm">{{ step.title }}</p>
                <p class="text-gray-400 text-xs mt-1">{{ step.desc }}</p>
              </div>
            </div>
          </section>

          <!-- All Areas served by this service -->
          <section class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">
              {{ category.name }} Repair — All Areas We Cover
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
              <a v-for="a in allAreas" :key="a.slug"
                 :href="`/services/${category.slug}/${a.slug}`"
                 class="text-sm text-blue-600 hover:text-blue-800 hover:underline py-1"
                 :class="a.slug === area.slug ? 'font-bold text-blue-900 pointer-events-none' : ''">
                {{ a.slug === area.slug ? '📍 ' : '' }}{{ a.name }}
              </a>
            </div>
          </section>
        </div>

        <!-- Sticky Sidebar -->
        <div class="space-y-5">

          <!-- CTA Card -->
          <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white sticky top-20">
            <div class="text-center mb-5">
              <p class="text-3xl font-black">Free</p>
              <p class="text-blue-200 text-sm">Doorstep visit & diagnosis</p>
            </div>
            <a :href="`/quick-booking?category=${category.slug}&area=${area.slug}`"
               class="block w-full text-center bg-white text-blue-700 font-bold py-3.5 rounded-xl hover:bg-blue-50 transition-colors text-sm shadow-lg mb-3">
              📅 Book Now — Free Visit
            </a>
            <a href="tel:+919444900470"
               class="block w-full text-center border-2 border-white/40 text-white font-semibold py-3 rounded-xl hover:bg-white/10 transition-colors text-sm mb-3">
              📞 +91 94449 00470
            </a>
            <a href="/free-consultation"
               class="block w-full text-center text-blue-200 hover:text-white text-sm font-medium transition-colors">
              💬 Get Free Consultation
            </a>

            <!-- Guarantees -->
            <div class="mt-5 space-y-2 border-t border-white/20 pt-5">
              <p class="text-xs text-blue-200 font-semibold uppercase tracking-wide mb-2">Our Guarantees</p>
              <p class="text-xs text-white flex items-center gap-2">✓ 6-month service warranty</p>
              <p class="text-xs text-white flex items-center gap-2">✓ Genuine spare parts</p>
              <p class="text-xs text-white flex items-center gap-2">✓ No fix, no fee</p>
              <p class="text-xs text-white flex items-center gap-2">✓ Transparent pricing</p>
              <p class="text-xs text-white flex items-center gap-2">✓ Background-verified techs</p>
            </div>
          </div>

          <!-- Ratings -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-4">
              <div>
                <p class="text-4xl font-black text-gray-900">4.8</p>
                <div class="flex text-yellow-400 text-lg">★★★★★</div>
              </div>
              <div>
                <p class="text-sm font-bold text-gray-900">Excellent</p>
                <p class="text-xs text-gray-400">247 verified reviews</p>
                <p class="text-xs text-gray-400">across Chennai</p>
              </div>
            </div>
            <div class="space-y-1.5 text-xs">
              <div v-for="n in [5,4,3,2,1]" :key="n" class="flex items-center gap-2">
                <span class="text-gray-500 w-4">{{ n }}★</span>
                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full"
                       :style="`width: ${[78,15,5,1,1][5-n]}%`"></div>
                </div>
                <span class="text-gray-400 w-6">{{ [78,15,5,1,1][5-n] }}%</span>
              </div>
            </div>
          </div>

          <!-- Other Categories -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-bold text-gray-900 mb-3 text-sm">Other Services in {{ area.name }}</h3>
            <div class="space-y-1">
              <a v-for="cat in otherCategories" :key="cat.slug"
                 :href="`/services/${cat.slug}/${area.slug}`"
                 class="flex items-center gap-2 py-2 px-3 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-colors text-sm text-gray-700 font-medium">
                <span>{{ cat.icon }}</span> {{ cat.name }} Repair
              </a>
            </div>
          </div>

          <!-- Blog links -->
          <div v-if="relatedPosts?.length" class="bg-gray-50 rounded-2xl p-5">
            <h3 class="font-bold text-gray-900 mb-3 text-sm">Helpful Guides</h3>
            <div class="space-y-3">
              <a v-for="post in relatedPosts" :key="post.id"
                 :href="`/blog/${post.slug}`"
                 class="block text-xs text-blue-600 hover:text-blue-800 hover:underline leading-relaxed">
                → {{ post.title }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import {
  CalendarIcon, PhoneIcon, ClockIcon, ShieldCheckIcon,
  MapPinIcon, QuestionMarkCircleIcon, ChevronDownIcon,
} from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const props = defineProps({
  category:        { type: Object, required: true },
  area:            { type: Object, required: true },  // { name, slug, nearbyAreas[] }
  services:        { type: Array,  default: () => [] },
  otherCategories: { type: Array,  default: () => [] },
  relatedPosts:    { type: Array,  default: () => [] },
  aggregateRating: { type: Object, default: () => ({ value: '4.8', count: 247 }) },
})

// ── All 16 service areas ─────────────────────────────────────
const allAreas = [
  { name: 'Porur',         slug: 'porur' },
  { name: 'Anna Nagar',    slug: 'anna-nagar' },
  { name: 'Adyar',         slug: 'adyar' },
  { name: 'Velachery',     slug: 'velachery' },
  { name: 'T. Nagar',      slug: 't-nagar' },
  { name: 'Koyambedu',     slug: 'koyambedu' },
  { name: 'Maduravoyal',   slug: 'maduravoyal' },
  { name: 'Vadapalani',    slug: 'vadapalani' },
  { name: 'Ambattur',      slug: 'ambattur' },
  { name: 'Mogappair',     slug: 'mogappair' },
  { name: 'Chromepet',     slug: 'chromepet' },
  { name: 'Tambaram',      slug: 'tambaram' },
  { name: 'Guindy',        slug: 'guindy' },
  { name: 'Mylapore',      slug: 'mylapore' },
  { name: 'Kilpauk',       slug: 'kilpauk' },
  { name: 'Perambur',      slug: 'perambur' },
]

const slugify = (s) => s.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')

// ── SEO ──────────────────────────────────────────────────────
const base         = 'https://chennaismartcare.com'
const canonicalUrl = computed(() => `${base}/services/${props.category.slug}/${props.area.slug}`)
const pageTitle    = computed(() =>
  `${props.category.name} Repair in ${props.area.name} — Same-Day Service | Chennai Smart Care`
)
const metaDescription = computed(() =>
  `Certified ${props.category.name} repair in ${props.area.name}, Chennai. `+
  `Expert technicians, same-day service, 6-month warranty, transparent pricing. `+
  `Call +91 94449 00470 or book online.`
)

// ── Static content ───────────────────────────────────────────
const trustPoints = [
  { icon: '⚡', title: 'Same-Day Service',        desc: `Technicians in ${props.area.name} available 9AM–9PM, 7 days a week.` },
  { icon: '🛡️', title: '6-Month Warranty',         desc: 'Every repair backed by our comprehensive service guarantee.' },
  { icon: '💰', title: 'Transparent Pricing',     desc: 'Upfront quote before work starts — no hidden charges ever.' },
  { icon: '🔧', title: 'Certified Technicians',   desc: 'Trained, background-verified experts for all brands & models.' },
]

const howItWorks = [
  { icon: '📞', title: 'Book Online/Call', desc: 'Takes 60 seconds' },
  { icon: '🔔', title: 'Tech Assigned',    desc: 'Within 15 mins' },
  { icon: '🏠', title: 'Doorstep Visit',   desc: '30–90 mins ETA' },
  { icon: '✅', title: 'Fixed & Warranted', desc: '6-month guarantee' },
]

const localFaqs = computed(() => [
  {
    q: `How quickly can you send a ${props.category.name} technician to ${props.area.name}?`,
    a: `We typically dispatch a technician to ${props.area.name} within 30–90 minutes of booking. For same-day appointments, we have slots available 9AM–9PM, 7 days a week including weekends.`
  },
  {
    q: `What does a ${props.category.name} repair cost in ${props.area.name}?`,
    a: `Our ${props.category.name} repair charges in ${props.area.name} start from ₹299 for a service visit and diagnosis. Repair costs vary by the issue — we provide a transparent quote before any work begins, with no hidden charges.`
  },
  {
    q: `Do you offer a warranty on ${props.category.name} repairs in ${props.area.name}?`,
    a: `Yes! All our ${props.category.name} repairs in ${props.area.name} come with a 6-month service warranty. If the same issue recurs within this period, we'll fix it at no extra cost.`
  },
  {
    q: `Which ${props.category.name} brands do you service in ${props.area.name}?`,
    a: `We service all major brands in ${props.area.name} including Samsung, LG, Voltas, Daikin, Hitachi, Blue Star, Carrier, Godrej, Whirlpool, Bosch, IFB, and more.`
  },
  {
    q: `Do you also cover areas near ${props.area.name}?`,
    a: `Yes! Our ${props.area.name} technicians also cover ${props.area.nearbyAreas?.join(', ')}. If your area isn't listed, call us at +91 94449 00470.`
  },
])

// ── JSON-LD Schema ────────────────────────────────────────────
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
  const url = canonicalUrl.value

  // 1. LocalBusiness with specific area
  injectJsonLd('schema-local', {
    "@context":   "https://schema.org",
    "@type":      "HomeAndConstructionBusiness",
    "@id":        `${base}/#business`,
    "name":       "Chennai Smart Care",
    "url":        base,
    "telephone":  "+91-94449-00470",
    "image":      `${base}/images/logo.png`,
    "priceRange": "₹₹",
    "currenciesAccepted": "INR",
    "paymentAccepted": "Cash, UPI, Card",
    "address": {
      "@type":           "PostalAddress",
      "streetAddress":   "Porur",
      "addressLocality": "Chennai",
      "addressRegion":   "Tamil Nadu",
      "postalCode":      "600116",
      "addressCountry":  "IN"
    },
    "geo": { "@type": "GeoCoordinates", "latitude": 13.0368, "longitude": 80.1596 },
    "areaServed": [
      props.area.name,
      ...props.area.nearbyAreas,
      "Chennai"
    ].map(name => ({ "@type": "City", "name": name })),
    "openingHoursSpecification": [{
      "@type":     "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
      "opens":     "09:00",
      "closes":    "21:00"
    }],
    "aggregateRating": {
      "@type":       "AggregateRating",
      "ratingValue": props.aggregateRating.value,
      "reviewCount": String(props.aggregateRating.count),
      "bestRating":  "5",
      "worstRating": "1"
    }
  })

  // 2. Service schema
  injectJsonLd('schema-service', {
    "@context": "https://schema.org",
    "@type":    "Service",
    "name":     `${props.category.name} Repair in ${props.area.name}`,
    "url":      url,
    "provider": { "@type": "LocalBusiness", "@id": `${base}/#business`, "name": "Chennai Smart Care" },
    "areaServed": { "@type": "City", "name": props.area.name, "sameAs": `https://en.wikipedia.org/wiki/Chennai` },
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name":  `${props.category.name} Services in ${props.area.name}`,
      "itemListElement": props.services.map((s, i) => ({
        "@type":       "Offer",
        "position":    i + 1,
        "itemOffered": { "@type": "Service", "name": s.name, "description": s.description ?? '' },
        "price":       String(s.effective_price ?? s.base_price),
        "priceCurrency": "INR",
        "seller":      { "@type": "LocalBusiness", "name": "Chennai Smart Care" }
      }))
    }
  })

  // 3. BreadcrumbList
  injectJsonLd('schema-breadcrumb', {
    "@context": "https://schema.org",
    "@type":    "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Home",    "item": base },
      { "@type": "ListItem", "position": 2, "name": props.category.name + " Repair", "item": `${base}/services/${props.category.slug}` },
      { "@type": "ListItem", "position": 3, "name": props.area.name, "item": url },
    ]
  })

  // 4. FAQPage
  injectJsonLd('schema-faq', {
    "@context": "https://schema.org",
    "@type":    "FAQPage",
    "mainEntity": localFaqs.value.map(f => ({
      "@type":          "Question",
      "name":           f.q,
      "acceptedAnswer": { "@type": "Answer", "text": f.a }
    }))
  })
}

onMounted(injectAllSchema)
onUnmounted(() => ['schema-local','schema-service','schema-breadcrumb','schema-faq'].forEach(removeJsonLd))
</script>