<template>
  <div class="min-h-screen bg-white">
    <Head>
      <title>About Us — Chennai Smart Care | Expert Appliance Repair Since 2014</title>
      <meta name="description" content="Chennai Smart Care — Chennai's most trusted home appliance repair service. 10,000+ repairs, 4.9★ rating, certified technicians, 6-month warranty. Learn our story." />
      <meta name="robots" content="index, follow" />
      <link rel="canonical" href="https://chennaismartcare.com/about" />
      <meta property="og:type"        content="website" />
      <meta property="og:site_name"   content="Chennai Smart Care" />
      <meta property="og:url"         content="https://chennaismartcare.com/about" />
      <meta property="og:title"       content="About Chennai Smart Care — Expert Appliance Repair" />
      <meta property="og:description" content="Meet the team behind Chennai's most trusted appliance repair service. 10 years, 10,000+ repairs, 4.9★ rating." />
      <meta property="og:image"       content="https://chennaismartcare.com/images/og-default.jpg" />
      <meta property="og:locale"      content="en_IN" />
    </Head>

    <AppHeader />

    <!-- ══════════════════════════════════════════════
         HERO — Split layout: text left, stat grid right
    ══════════════════════════════════════════════ -->
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white overflow-hidden">
      <!-- Subtle grid texture -->
      <div class="absolute inset-0 opacity-[0.04]"
           style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>

      <!-- Glowing orbs -->
      <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
      <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

          <!-- Left: Story -->
          <div>
            <div class="inline-flex items-center gap-2 bg-blue-500/15 border border-blue-400/20 text-blue-300 px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-widest mb-7">
              <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
              Serving Chennai Since 2014
            </div>

            <h1 class="text-4xl sm:text-5xl font-black leading-[1.1] tracking-tight">
              Chennai's Most<br />
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400">
                Trusted Repair
              </span><br />
              Team
            </h1>

            <p class="mt-6 text-slate-300 text-lg leading-relaxed max-w-lg">
              We started with a single technician and a van. Today we're Chennai's
              go-to repair service — <strong class="text-white">10,000+ homes</strong> served,
              <strong class="text-white">4.9★ rated</strong>, and still committed to the
              same promise: fix it right, fix it fast, stand behind every repair.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
              <a href="/quick-booking"
                 class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold px-7 py-3.5 rounded-xl transition-all hover:-translate-y-0.5 shadow-lg shadow-blue-900/40 text-sm">
                <CalendarIcon class="w-4 h-4" />
                Book a Service
              </a>
              <a href="tel:+919444900470"
                 class="inline-flex items-center gap-2 border border-white/20 hover:border-white/40 text-white font-semibold px-7 py-3.5 rounded-xl transition-all hover:bg-white/5 text-sm">
                <PhoneIcon class="w-4 h-4" />
                +91 94449 00470
              </a>
            </div>
          </div>

          <!-- Right: Stat cards -->
          <div class="grid grid-cols-2 gap-4">
            <div v-for="stat in heroStats" :key="stat.label"
                 class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all hover:-translate-y-1 group">
              <div class="text-3xl sm:text-4xl font-black text-white group-hover:text-blue-300 transition-colors">
                {{ stat.value }}
              </div>
              <div class="text-slate-400 text-sm mt-1.5 font-medium">{{ stat.label }}</div>
              <div class="text-xs text-slate-500 mt-1 leading-relaxed">{{ stat.sub }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Wave bottom -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 48" class="w-full" preserveAspectRatio="none" height="48">
          <path fill="#ffffff" d="M0,48 C480,0 960,48 1440,16 L1440,48 Z"/>
        </svg>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════
         OUR STORY — Timeline
    ══════════════════════════════════════════════ -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Our Journey</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">10 Years. One Mission.</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto">From a single service call in Porur to serving all of Chennai — here's how we grew.</p>
        </div>

        <div class="relative">
          <!-- Timeline spine -->
          <div class="absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-blue-200 via-blue-400 to-blue-200 hidden md:block -translate-x-1/2"></div>

          <div class="space-y-12">
            <div v-for="(milestone, i) in timeline" :key="i"
                 class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center"
                 :class="i % 2 === 0 ? '' : 'md:[&>*:first-child]:order-2 md:[&>*:last-child]:order-1'">

              <!-- Dot on spine -->
              <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 hidden md:flex">
                <div class="w-4 h-4 rounded-full bg-blue-600 border-4 border-white shadow-md shadow-blue-200 ring-2 ring-blue-100"></div>
              </div>

              <!-- Card -->
              <div class="bg-gray-50 rounded-2xl p-7 border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all"
                   :class="i % 2 === 0 ? 'md:mr-8' : 'md:ml-8'">
                <div class="flex items-center gap-3 mb-3">
                  <span class="text-2xl">{{ milestone.icon }}</span>
                  <span class="text-xs font-black bg-blue-600 text-white px-3 py-1 rounded-full tracking-wider">{{ milestone.year }}</span>
                </div>
                <h3 class="font-black text-gray-900 text-lg mb-2">{{ milestone.title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ milestone.desc }}</p>
              </div>

              <!-- Spacer for opposite side -->
              <div class="hidden md:block"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════
         MISSION, VISION, VALUES — 3-column
    ══════════════════════════════════════════════ -->
    <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">What Drives Us</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Mission. Vision. Values.</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="pillar in pillars" :key="pillar.title"
               class="relative overflow-hidden rounded-3xl p-8 group cursor-default"
               :class="pillar.bg">
            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full opacity-20"
                 :class="pillar.circle"></div>
            <div class="relative">
              <div class="text-4xl mb-5">{{ pillar.icon }}</div>
              <h3 class="font-black text-xl mb-3" :class="pillar.heading">{{ pillar.title }}</h3>
              <p class="text-sm leading-relaxed" :class="pillar.body">{{ pillar.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════
         WHY CHOOSE US — Feature grid with icons
    ══════════════════════════════════════════════ -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

          <!-- Left: Features -->
          <div>
            <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-5">Why Customers Choose Us</span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-10 leading-tight">
              The Chennai Smart Care<br />Difference
            </h2>

            <div class="space-y-6">
              <div v-for="feature in features" :key="feature.title"
                   class="flex items-start gap-5 p-5 rounded-2xl hover:bg-blue-50 transition-all group border border-transparent hover:border-blue-100">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-xl transition-transform group-hover:scale-110"
                     :class="feature.iconBg">
                  {{ feature.icon }}
                </div>
                <div>
                  <h3 class="font-bold text-gray-900 mb-1">{{ feature.title }}</h3>
                  <p class="text-gray-500 text-sm leading-relaxed">{{ feature.desc }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Promise card -->
          <div class="relative">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-2xl shadow-blue-200">
              <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>

              <div class="relative">
                <div class="text-5xl mb-6">🛡️</div>
                <h3 class="text-2xl font-black mb-3">Our Promise to You</h3>
                <p class="text-blue-200 text-sm mb-7 leading-relaxed">
                  Every repair we do comes with a written service guarantee.
                  No vague promises — just a clear commitment, in writing.
                </p>

                <div class="space-y-3">
                  <div v-for="promise in promises" :key="promise"
                       class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 text-sm font-medium">
                    <span class="w-5 h-5 bg-green-400 rounded-full flex items-center justify-center text-green-900 text-xs font-black flex-shrink-0">✓</span>
                    {{ promise }}
                  </div>
                </div>

                <div class="mt-7 p-4 bg-white/10 rounded-2xl border border-white/20">
                  <p class="text-xs text-blue-200 uppercase tracking-wider font-semibold mb-1">Aggregate Rating</p>
                  <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black">4.9</span>
                    <div>
                      <div class="flex text-yellow-400 text-base">★★★★★</div>
                      <p class="text-blue-200 text-xs">2,400+ verified reviews</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Floating badge -->
            <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl px-5 py-3 shadow-xl border border-gray-100 flex items-center gap-3">
              <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">⚡</span>
              </div>
              <div>
                <p class="font-black text-gray-900 text-sm">Same-Day Service</p>
                <p class="text-gray-400 text-xs">9AM – 9PM · 7 days a week</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════
         TEAM — Technician highlights
    ══════════════════════════════════════════════ -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">The People Behind Every Repair</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Our Technician Promise</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">
            Every Chennai Smart Care technician is trained, certified, and background-verified before their first job.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <div v-for="tech in techPromises" :key="tech.title"
               class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all text-center group">
            <div class="w-14 h-14 mx-auto bg-blue-50 group-hover:bg-blue-100 rounded-2xl flex items-center justify-center text-2xl mb-4 transition-colors">
              {{ tech.icon }}
            </div>
            <h3 class="font-bold text-gray-900 text-sm mb-2">{{ tech.title }}</h3>
            <p class="text-gray-400 text-xs leading-relaxed">{{ tech.desc }}</p>
          </div>
        </div>

        <!-- Technician Stats Banner -->
        <div class="mt-10 bg-blue-600 rounded-3xl p-8 text-white grid grid-cols-2 sm:grid-cols-4 gap-6">
          <div v-for="s in techStats" :key="s.label" class="text-center">
            <div class="text-3xl font-black">{{ s.value }}</div>
            <div class="text-blue-200 text-xs mt-1 font-medium">{{ s.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════
         SERVICE AREAS WE COVER
    ══════════════════════════════════════════════ -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
          <div>
            <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-5">Where We Work</span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-5 leading-tight">
              All of Chennai.<br />
              <span class="text-blue-600">Every Neighbourhood.</span>
            </h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-8">
              From Porur in the west to OMR in the east, Anna Nagar in the north to Tambaram in the south —
              if you're in Chennai, we're near you. Same-day technicians in every area.
            </p>
            <a href="/quick-booking"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl transition-all hover:-translate-y-0.5 text-sm shadow-lg shadow-blue-200">
              <CalendarIcon class="w-4 h-4" />
              Book a Repair Near You
            </a>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <a v-for="area in coverageAreas" :key="area.slug"
               :href="`/services/ac/${area.slug}`"
               :title="`AC repair in ${area.name}`"
               class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-gray-100 hover:border-blue-300 hover:bg-blue-50 transition-all text-sm text-gray-700 hover:text-blue-700 font-medium group">
              <MapPinIcon class="w-3.5 h-3.5 text-gray-300 group-hover:text-blue-400 flex-shrink-0" />
              {{ area.name }}
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════════════════════════════════════════
         CTA BOTTOM
    ══════════════════════════════════════════════ -->
    <section class="py-20 bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white relative overflow-hidden">
      <div class="absolute inset-0 opacity-[0.04]"
           style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>
      <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px]"></div>

      <div class="relative max-w-3xl mx-auto px-4 text-center">
        <div class="text-5xl mb-6">🔧</div>
        <h2 class="text-3xl sm:text-4xl font-black mb-5 leading-tight">
          Ready to Experience<br />
          <span class="text-blue-400">the Difference?</span>
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-lg mx-auto">
          Join 10,000+ Chennai families who trust us with their appliances.
          Book today — same-day slots available.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="/quick-booking"
             class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 font-black px-8 py-4 rounded-xl hover:bg-blue-50 transition-all hover:-translate-y-0.5 shadow-xl text-sm">
            <CalendarIcon class="w-5 h-5" />
            Book a Service Now
          </a>
          <a href="/free-consultation"
             class="inline-flex items-center justify-center gap-2 border-2 border-white/30 hover:border-white/60 text-white font-semibold px-8 py-4 rounded-xl transition-all hover:bg-white/5 text-sm">
            <PhoneIcon class="w-5 h-5" />
            Free Consultation
          </a>
        </div>
        <p class="text-slate-500 text-xs mt-8">
          Available 9AM–9PM · Mon–Sun · All Chennai areas
        </p>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import { CalendarIcon, PhoneIcon, MapPinIcon } from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

// ── Hero stats ─────────────────────────────────────────────────
const heroStats = [
  { value: '10,000+', label: 'Repairs Completed',  sub: 'Across all Chennai areas' },
  { value: '4.9★',    label: 'Google Rating',       sub: '2,400+ verified reviews' },
  { value: '10 Yrs',  label: 'In Business',         sub: 'Serving Chennai since 2014' },
  { value: '6-Month', label: 'Service Warranty',    sub: 'On every single repair' },
]

// ── Timeline ───────────────────────────────────────────────────
const timeline = [
  {
    year:  '2014',
    icon:  '🚐',
    title: 'Started with One Van',
    desc:  'Our founder Karthik started Chennai Smart Care with a single service van and a small team of two technicians, covering Porur and Maduravoyal.',
  },
  {
    year:  '2016',
    icon:  '📈',
    title: 'Expanded to 8 Areas',
    desc:  'Word spread fast. Within two years, we expanded to Anna Nagar, Vadapalani, Koyambedu, T. Nagar and Adyar — a team of 12 technicians strong.',
  },
  {
    year:  '2018',
    icon:  '🏆',
    title: 'Crossed 1,000 Repairs',
    desc:  'A milestone moment. Our 1,000th repair — a Samsung refrigerator in Velachery — marked the turning point from startup to trusted service.',
  },
  {
    year:  '2020',
    icon:  '💻',
    title: 'Launched Online Booking',
    desc:  'We launched chennaismartcare.com with instant online booking, digital warranty cards, and real-time technician tracking for our customers.',
  },
  {
    year:  '2022',
    icon:  '🗺️',
    title: 'City-Wide Coverage',
    desc:  'Expanded to 24+ areas including OMR, Sholinganallur, Tambaram and Chromepet — covering the full north-south-east-west sweep of Chennai.',
  },
  {
    year:  '2024',
    icon:  '⭐',
    title: '10,000+ Happy Families',
    desc:  'Today we\'re Chennai\'s top-rated appliance repair service — 4.9★ on Google, 10,000+ repairs done, and still driven by the same passion from 2014.',
  },
]

// ── Mission / Vision / Values ──────────────────────────────────
const pillars = [
  {
    icon:    '🎯',
    title:   'Our Mission',
    desc:    'To make quality appliance repair accessible and affordable for every Chennai family — with honest pricing, reliable technicians, and a guarantee that means something.',
    bg:      'bg-blue-600 text-white',
    circle:  'bg-blue-400',
    heading: 'text-white',
    body:    'text-blue-100',
  },
  {
    icon:    '🔭',
    title:   'Our Vision',
    desc:    'To become the most trusted home services brand across Tamil Nadu — a name every family thinks of first when an appliance breaks down.',
    bg:      'bg-gray-900 text-white',
    circle:  'bg-gray-700',
    heading: 'text-white',
    body:    'text-gray-400',
  },
  {
    icon:    '💎',
    title:   'Our Values',
    desc:    'Transparency before we start, honesty during the job, and accountability after. Every decision we make — from pricing to spare parts — is guided by these three commitments.',
    bg:      'bg-gray-50 border border-gray-200',
    circle:  'bg-gray-200',
    heading: 'text-gray-900',
    body:    'text-gray-500',
  },
]

// ── Why Choose Us features ─────────────────────────────────────
const features = [
  {
    icon:   '🛡️',
    iconBg: 'bg-blue-100',
    title:  '6-Month Service Warranty',
    desc:   'Every repair is covered for 6 months. Same issue returns? We fix it free of charge — no questions asked.',
  },
  {
    icon:   '💰',
    iconBg: 'bg-green-100',
    title:  'Transparent Pricing Always',
    desc:   'We quote before we touch anything. No surprise bills. No add-ons. The price we say is the price you pay.',
  },
  {
    icon:   '⚡',
    iconBg: 'bg-yellow-100',
    title:  'Same-Day Service',
    desc:   'Book before 2PM and we\'ll be at your door the same day. Evening slots available for working professionals.',
  },
  {
    icon:   '🔧',
    iconBg: 'bg-purple-100',
    title:  'Certified & Verified Techs',
    desc:   'All technicians are brand-certified, background-checked, and trained on the latest appliance models.',
  },
  {
    icon:   '📱',
    iconBg: 'bg-orange-100',
    title:  'Digital Warranty Card',
    desc:   'Instantly accessible in your customer dashboard. Share it, check it, claim it — all from your phone.',
  },
  {
    icon:   '🏠',
    iconBg: 'bg-teal-100',
    title:  'Doorstep Repair',
    desc:   'We come to you with all tools and genuine spare parts. Most repairs done in a single visit.',
  },
]

const promises = [
  '6-month service warranty on every repair',
  'Genuine spare parts — never imitation',
  'Transparent quote before work begins',
  'No fix, no fee — or we refund your visit charge',
  'Technician arrives on the booked time slot',
]

// ── Technician promises ────────────────────────────────────────
const techPromises = [
  { icon: '🎓', title: 'Brand Certified',      desc: 'Trained and certified by major appliance manufacturers including Samsung, LG, Daikin and more.' },
  { icon: '🔍', title: 'Background Verified',   desc: 'Every technician passes a thorough background check before joining our team.' },
  { icon: '👔', title: 'Uniformed & ID\'d',     desc: 'Arrive in uniform with a photo ID card for your security and peace of mind.' },
  { icon: '📋', title: 'Documented Repairs',    desc: 'Full digital service report after every job — what was done, what was replaced, what to watch for.' },
]

const techStats = [
  { value: '45+',  label: 'Active technicians' },
  { value: '100%', label: 'Background verified' },
  { value: '8+',   label: 'Years avg. experience' },
  { value: '15+',  label: 'Brand certifications' },
]

// ── Coverage areas ─────────────────────────────────────────────
const coverageAreas = [
  { name: 'Porur',          slug: 'porur' },
  { name: 'Anna Nagar',     slug: 'anna-nagar' },
  { name: 'Adyar',          slug: 'adyar' },
  { name: 'Velachery',      slug: 'velachery' },
  { name: 'T. Nagar',       slug: 't-nagar' },
  { name: 'OMR',            slug: 'omr' },
  { name: 'Sholinganallur', slug: 'sholinganallur' },
  { name: 'Koyambedu',      slug: 'koyambedu' },
  { name: 'Ambattur',       slug: 'ambattur' },
  { name: 'Tambaram',       slug: 'tambaram' },
  { name: 'Mylapore',       slug: 'mylapore' },
  { name: 'Mogappair',      slug: 'mogappair' },
  { name: 'Chromepet',      slug: 'chromepet' },
  { name: 'Guindy',         slug: 'guindy' },
  { name: 'Maduravoyal',    slug: 'maduravoyal' },
  { name: 'Vadapalani',     slug: 'vadapalani' },
  { name: 'Kilpauk',        slug: 'kilpauk' },
  { name: 'Perambur',       slug: 'perambur' },
]

// ── Schema ─────────────────────────────────────────────────────
function injectJsonLd(id, data) {
  document.getElementById(id)?.remove()
  const el = document.createElement('script')
  el.type = 'application/ld+json'
  el.id   = id
  el.textContent = JSON.stringify(data)
  document.head.appendChild(el)
}

onMounted(() => {
  injectJsonLd('schema-about', {
    '@context': 'https://schema.org',
    '@type':    'AboutPage',
    'url':      'https://chennaismartcare.com/about',
    'name':     'About Chennai Smart Care',
    'description': 'Chennai Smart Care is Chennai\'s most trusted home appliance repair service. AC, Refrigerator, Washing Machine and Microwave Oven repair with 6-month warranty.',
    'mainEntity': {
      '@type':       'LocalBusiness',
      '@id':         'https://chennaismartcare.com/#localbusiness',
      'name':        'Chennai Smart Care',
      'foundingDate': '2014',
      'description': 'Expert AC, Refrigerator, Washing Machine and Microwave Oven repair services across Chennai.',
      'url':         'https://chennaismartcare.com',
      'telephone':   '+91-9444900470',
      'numberOfEmployees': { '@type': 'QuantitativeValue', 'value': 45 },
      'aggregateRating': {
        '@type':       'AggregateRating',
        'ratingValue': '4.9',
        'reviewCount': '2400',
        'bestRating':  '5',
        'worstRating': '1',
      },
    },
  })
})

onUnmounted(() => document.getElementById('schema-about')?.remove())
</script>