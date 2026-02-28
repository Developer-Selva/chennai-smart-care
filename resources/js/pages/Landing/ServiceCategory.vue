<template>
  <div class="min-h-screen bg-gray-50">

    <Head>
      <title>{{ metaTitle }}</title>
      <meta name="description" :content="metaDescription" />
      <meta name="keywords"    :content="metaKeywords" />
      <meta name="robots"      content="index, follow" />
      <link rel="canonical"    :content="canonicalUrl" />
      <meta property="og:type"        content="website" />
      <meta property="og:site_name"   content="Chennai Smart Care" />
      <meta property="og:url"         :content="canonicalUrl" />
      <meta property="og:title"       :content="metaTitle" />
      <meta property="og:description" :content="metaDescription" />
      <meta property="og:image"       content="https://chennaismartcare.com/images/og-service.jpg" />
      <meta property="og:locale"      content="en_IN" />
      <meta name="twitter:card"        content="summary_large_image" />
      <meta name="twitter:title"       :content="metaTitle" />
      <meta name="twitter:description" :content="metaDescription" />
    </Head>

    <AppHeader />

    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-800 to-indigo-900 text-white py-14 px-4">
      <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center gap-6">
        <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-5xl flex-shrink-0">
          {{ category.icon || '🔧' }}
        </div>
        <div class="flex-1">
          <nav class="flex items-center gap-2 text-blue-300 text-xs mb-3" aria-label="Breadcrumb">
            <a href="/" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-blue-100">{{ category.name }} Repair</span>
          </nav>
          <div class="text-blue-300 text-sm font-semibold mb-1 uppercase tracking-wider">Expert Service · All Areas in Chennai</div>
          <h1 class="text-3xl sm:text-4xl font-extrabold">{{ category.name }} Repair in Chennai</h1>
          <p class="text-blue-200 mt-2 text-lg max-w-2xl">{{ category.description }}</p>
          <!-- Star rating — visible social proof that also feeds CTR in search results -->
          <div class="flex items-center gap-3 mt-4 flex-wrap">
            <div class="flex items-center gap-1.5 bg-white/10 border border-white/20 rounded-full px-3 py-1.5">
              <div class="flex gap-0.5">
                <span v-for="i in 5" :key="i" class="text-sm" :class="i <= Math.round(aggregateRating.value) ? 'text-yellow-400' : 'text-white/30'">★</span>
              </div>
              <span class="text-white font-bold text-sm">{{ aggregateRating.value }}</span>
              <span class="text-blue-200 text-xs">({{ aggregateRating.count }} reviews)</span>
            </div>
            <span class="bg-white/10 text-white text-sm px-3 py-1 rounded-full border border-white/20">✅ Certified Technicians</span>
            <span class="bg-white/10 text-white text-sm px-3 py-1 rounded-full border border-white/20">⚡ Same-Day Service</span>
            <span class="bg-white/10 text-white text-sm px-3 py-1 rounded-full border border-white/20">🛡️ 6-Month Warranty</span>
          </div>
        </div>
      </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-8">

        <!-- Services -->
        <section>
          <h2 class="text-xl font-bold text-gray-900 mb-5">
            {{ services.length }} Service{{ services.length !== 1 ? 's' : '' }} Available
          </h2>
          <div v-if="services.length" class="space-y-4">
            <div v-for="service in services" :key="service.id"
                 class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-blue-300 hover:shadow-md transition-all group">
              <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                  <h3 class="font-bold text-gray-900 text-lg group-hover:text-blue-600 transition-colors">{{ service.name }}</h3>
                  <p v-if="service.description" class="text-gray-500 text-sm mt-1 leading-relaxed">{{ service.description }}</p>
                  <div class="flex flex-wrap items-center gap-3 mt-3 text-sm">
                    <span v-if="service.duration_estimate" class="text-gray-400 flex items-center gap-1">
                      <ClockIcon class="w-4 h-4" /> {{ service.duration_estimate }}
                    </span>
                    <span v-if="service.warranty_days" class="text-green-600 flex items-center gap-1">
                      <ShieldCheckIcon class="w-4 h-4" /> {{ service.warranty_days }}-day warranty
                    </span>
                  </div>
                </div>
                <div class="text-right flex-shrink-0">
                  <div v-if="service.discounted_price" class="text-xs text-gray-400 line-through">₹{{ service.base_price }}</div>
                  <div class="text-2xl font-extrabold text-gray-900">₹{{ service.effective_price ?? service.base_price }}</div>
                  <div class="text-xs text-gray-400">onwards</div>
                  <a :href="`/quick-booking?service=${service.id}`"
                     class="mt-3 inline-block bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors">
                    Book Now
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-400">
            <WrenchScrewdriverIcon class="w-12 h-12 mx-auto mb-3 opacity-40" />
            <p class="font-medium">No services listed yet. Please call us to enquire.</p>
          </div>
        </section>

        <!-- Service Area Coverage — internal links to area pages -->
        <section class="bg-white rounded-2xl border border-gray-200 p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <MapPinIcon class="w-5 h-5 text-blue-600" />
            {{ category.name }} Repair — All Areas We Serve in Chennai
          </h2>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            <a v-for="area in serviceAreas" :key="area.slug"
               :href="`/services/${category.slug}/${area.slug}`"
               class="text-sm text-center py-2 px-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl font-medium transition-colors">
              {{ area.name }}
            </a>
          </div>
        </section>

        <!-- FAQ Section — drives People Also Ask in Google -->
        <section class="bg-white rounded-2xl border border-gray-200 p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
            <QuestionMarkCircleIcon class="w-5 h-5 text-blue-600" />
            Frequently Asked Questions — {{ category.name }} Repair in Chennai
          </h2>
          <div class="space-y-3">
            <details v-for="(faq, i) in categoryFaqs" :key="i"
                     class="group border border-gray-200 rounded-xl overflow-hidden"
                     :open="i === 0">
              <summary class="flex items-center justify-between gap-3 px-5 py-4 cursor-pointer list-none hover:bg-gray-50 transition-colors">
                <span class="font-semibold text-gray-900 text-sm leading-snug">{{ faq.question }}</span>
                <ChevronDownIcon class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200 group-open:rotate-180" />
              </summary>
              <div class="px-5 pb-4 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                {{ faq.answer }}
              </div>
            </details>
          </div>
        </section>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="bg-blue-600 text-white rounded-2xl p-6 sticky top-24">
          <h3 class="font-bold text-lg mb-1">Need Help Now?</h3>
          <p class="text-blue-200 text-sm mb-4">Book in 60 seconds. Same-day slots available.</p>
          <a :href="`/quick-booking?category=${category.slug}`"
             class="block text-center bg-white text-blue-700 font-bold py-3 rounded-xl hover:bg-blue-50 transition-colors">
            Book {{ category.name }} Service
          </a>
          <a href="tel:+919444900470"
             class="block text-center mt-3 border border-white/40 text-white font-semibold py-2.5 rounded-xl hover:bg-white/10 transition-colors text-sm">
            📞 Call +91 94449 00470
          </a>
          <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-2">
            <div class="flex gap-0.5 text-yellow-400 text-sm">★★★★★</div>
            <span class="text-white/90 text-xs">{{ aggregateRating.value }}/5 · {{ aggregateRating.count }} verified reviews</span>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-4">Why Choose Us?</h3>
          <ul class="space-y-3 text-sm text-gray-600">
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Background-verified certified technicians</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Transparent pricing — no surprises</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> 6-month service warranty on all repairs</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Same-day & weekend appointments</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> All major brands serviced</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Genuine spare parts used</li>
          </ul>
        </div>

        <div v-if="otherCategories.length" class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-3">Other Services</h3>
          <ul class="space-y-2">
            <li v-for="cat in otherCategories" :key="cat.id">
              <a :href="`/services/${cat.slug}`"
                 class="flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-colors text-sm font-medium text-gray-700 group">
                <span class="flex items-center gap-2"><span>{{ cat.icon || '🔧' }}</span> {{ cat.name }}</span>
                <span class="text-xs text-gray-400 group-hover:text-blue-400">{{ cat.services_count }} services →</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'
import {
  ClockIcon, ShieldCheckIcon, WrenchScrewdriverIcon,
  MapPinIcon, QuestionMarkCircleIcon, ChevronDownIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  category:        { type: Object, required: true },
  services:        { type: Array,  default: () => [] },
  otherCategories: { type: Array,  default: () => [] },
  aggregateRating: { type: Object, default: () => ({ value: '4.8', count: 247 }) },
})

const BASE = 'https://chennaismartcare.com'

const canonicalUrl     = computed(() => `${BASE}/services/${props.category.slug}`)
const lowestPrice      = computed(() => {
  const p = props.services.map(s => s.effective_price ?? s.base_price)
  return p.length ? Math.min(...p) : 299
})

const metaTitle = computed(() =>
  `${props.category.name} Repair in Chennai — Same-Day Service | Chennai Smart Care`
)
const metaDescription = computed(() =>
  `Expert ${props.category.name} repair in Chennai. ⭐ ${props.aggregateRating.value}/5 · ${props.aggregateRating.count} reviews. ` +
  `Certified technicians, transparent pricing, 6-month warranty. Same-day service 9AM–9PM. Call +91 94449 00470.`
)
const metaKeywords = computed(() => {
  const n = props.category.name
  return [`${n} repair Chennai`, `${n} service Chennai`, `${n} repair near me`,
          `best ${n} repair Chennai`, `${n} technician Chennai`, `${n} repair cost Chennai`,
          `${n} repair Anna Nagar`, `${n} repair Porur`, `${n} repair Adyar`, 'Chennai Smart Care'].join(', ')
})

const serviceAreas = [
  { name: 'Porur',      slug: 'porur' },      { name: 'Anna Nagar', slug: 'anna-nagar' },
  { name: 'Adyar',      slug: 'adyar' },      { name: 'Velachery',  slug: 'velachery' },
  { name: 'T. Nagar',   slug: 't-nagar' },    { name: 'Koyambedu',  slug: 'koyambedu' },
  { name: 'Ambattur',   slug: 'ambattur' },   { name: 'Tambaram',   slug: 'tambaram' },
  { name: 'Mylapore',   slug: 'mylapore' },   { name: 'Mogappair',  slug: 'mogappair' },
  { name: 'Chromepet',  slug: 'chromepet' },  { name: 'Guindy',     slug: 'guindy' },
]

const categoryFaqs = computed(() => {
  const n = props.category.name
  const s = props.category.slug
  const p = lowestPrice.value

  const common = [
    { question: `How much does ${n} repair cost in Chennai?`,
      answer:   `Our ${n} repair charges start from ₹${p} onwards. The exact cost depends on the on-site diagnosis. We always provide a transparent written quote before starting any work — no hidden charges.` },
    { question: `Which ${n} brands do you service in Chennai?`,
      answer:   `We service all major brands — LG, Samsung, Whirlpool, Godrej, Daikin, Voltas, Hitachi, Panasonic, Haier, Bosch, IFB, and more. Both Indian and international brands are covered.` },
    { question: `How quickly can a technician arrive for ${n} repair?`,
      answer:   `We offer same-day service across Chennai. Book before 2PM and get a technician the same day. Emergency slots available from 9AM–9PM, 7 days a week including Sundays and public holidays.` },
    { question: `Do you provide warranty on ${n} repairs?`,
      answer:   `Yes! Every repair comes with a 6-month service warranty. If the same issue recurs within 6 months, we fix it free of charge. We use genuine spare parts for lasting results.` },
    { question: `Is there a visiting or inspection charge?`,
      answer:   `We charge a minimal visiting fee of ₹199 for technician visit and diagnosis. This is fully adjusted against the repair cost if you proceed with the service.` },
    { question: `Do you provide ${n} repair at home or at a service centre?`,
      answer:   `We provide doorstep repair service. Our certified technicians come to your home with all required tools and spare parts. Most repairs are completed in a single visit.` },
  ]

  const specific = {
    ac: [
      { question: 'Why is my AC not cooling even when it is running?',
        answer:   'Common causes include low refrigerant gas, a dirty air filter, a faulty compressor, or a malfunctioning thermostat. Our technician diagnoses the exact issue and fixes it on the spot in most cases.' },
      { question: 'Do you offer AC gas refilling in Chennai?',
        answer:   'Yes. We offer AC gas refilling for R22, R32, and R410A refrigerants. Before refilling, our technician checks for leaks and seals them to prevent the gas from depleting quickly again.' },
      { question: 'How often should I service my AC?',
        answer:   'Ideally every 6 months — once before summer and once after monsoon. Regular servicing improves cooling efficiency, reduces power consumption, and extends your AC\'s life.' },
    ],
    refrigerator: [
      { question: 'Why is my refrigerator not cooling?',
        answer:   'Common causes include a faulty compressor, low refrigerant, dirty condenser coils, a blocked evaporator, or a broken thermostat. Our expert diagnoses and fixes the root cause, not just the symptom.' },
      { question: 'Do you repair both single door and double door refrigerators?',
        answer:   'Yes. We repair single door, double door, side-by-side, French door, and bottom-freezer refrigerators from all brands. Our technicians are trained on the latest models.' },
      { question: 'Why is water leaking from my refrigerator?',
        answer:   'Leakage is usually due to a blocked defrost drain, a cracked drain pan, or a faulty water line in frost-free models. Our technician identifies and repairs the source during the same visit.' },
    ],
    'washing-machine': [
      { question: 'Do you repair front load and top load washing machines?',
        answer:   'Yes. We repair front load, top load, fully automatic, and semi-automatic washing machines. We service LG, Samsung, IFB, Whirlpool, Bosch, Godrej, and all other brands.' },
      { question: 'Why is my washing machine not draining?',
        answer:   'This is usually caused by a clogged drain pump, blocked drain filter, kinked hose, or a faulty lid switch. Our technician will diagnose and clear the blockage or replace the faulty part during the visit.' },
      { question: 'Why is my washing machine making loud noises?',
        answer:   'Loud banging or rumbling sounds are caused by worn drum bearings, an unbalanced load, or foreign objects stuck in the drum. We identify and fix the source on the same visit.' },
    ],
    'microwave-oven': [
      { question: 'Why is my microwave oven not heating food?',
        answer:   'This is typically caused by a faulty magnetron, burned diode, or failed capacitor. Our technicians carry these parts and can replace them in one visit.' },
      { question: 'Is it safe to repair a microwave oven at home?',
        answer:   'Yes, when done by trained professionals. Microwave ovens store high-voltage charge even when unplugged. Our technicians follow strict safety protocols for microwave repair.' },
      { question: 'Do you repair convection microwaves and OTGs?',
        answer:   'Yes. We repair solo microwaves, grill microwaves, convection microwaves, and OTGs from Samsung, LG, Panasonic, IFB, Bajaj, and more.' },
    ],
  }

  return [...common, ...(specific[s] ?? [])]
})

// Schema injection
function injectJsonLd(id, data) {
  document.getElementById(id)?.remove()
  const el = document.createElement('script')
  el.type = 'application/ld+json'
  el.id   = id
  el.textContent = JSON.stringify(data)
  document.head.appendChild(el)
}

onMounted(() => {
  // 1. Service + AggregateRating → ⭐ in search results + pricing
  injectJsonLd('schema-service', {
    '@context':    'https://schema.org',
    '@type':       'Service',
    'name':        `${props.category.name} Repair in Chennai`,
    'url':         canonicalUrl.value,
    'description': metaDescription.value,
    'provider': { '@type': 'LocalBusiness', '@id': `${BASE}/#localbusiness`, 'name': 'Chennai Smart Care' },
    'areaServed':  serviceAreas.map(a => ({ '@type': 'Neighborhood', 'name': `${a.name}, Chennai` })),
    'hasOfferCatalog': {
      '@type': 'OfferCatalog',
      'name':  `${props.category.name} Services`,
      'itemListElement': props.services.map(s => ({
        '@type':       'Offer',
        'itemOffered': { '@type': 'Service', 'name': s.name, 'description': s.description ?? '' },
        'price':         s.effective_price ?? s.base_price,
        'priceCurrency': 'INR',
        'availability':  'https://schema.org/InStock',
        'seller':        { '@id': `${BASE}/#localbusiness` },
      })),
    },
    'aggregateRating': {
      '@type':       'AggregateRating',
      'ratingValue': String(props.aggregateRating.value),
      'reviewCount': String(props.aggregateRating.count || 5),
      'bestRating':  '5',
      'worstRating': '1',
    },
  })

  // 2. BreadcrumbList → breadcrumbs in search results
  injectJsonLd('schema-breadcrumb', {
    '@context': 'https://schema.org',
    '@type':    'BreadcrumbList',
    'itemListElement': [
      { '@type': 'ListItem', 'position': 1, 'name': 'Home',     'item': BASE },
      { '@type': 'ListItem', 'position': 2, 'name': 'Services', 'item': `${BASE}/services` },
      { '@type': 'ListItem', 'position': 3, 'name': `${props.category.name} Repair`, 'item': canonicalUrl.value },
    ],
  })

  // 3. FAQPage → People Also Ask rich results ⭐ highest CTR boost
  if (categoryFaqs.value.length) {
    injectJsonLd('schema-faq', {
      '@context':   'https://schema.org',
      '@type':      'FAQPage',
      'mainEntity': categoryFaqs.value.map(f => ({
        '@type': 'Question',
        'name':  f.question,
        'acceptedAnswer': { '@type': 'Answer', 'text': f.answer },
      })),
    })
  }
})

onUnmounted(() => {
  ['schema-service', 'schema-breadcrumb', 'schema-faq'].forEach(id => document.getElementById(id)?.remove())
})
</script>