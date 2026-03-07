<template>
  <div class="min-h-screen bg-white">
    <Head>
      <title>Chennai Smart Care — About Us | Home Appliance Repair Service Since 2014</title>
      <meta name="description"
            content="Chennai Smart Care is Chennai's most trusted appliance repair company since 2014. AC, refrigerator & washing machine repair by certified technicians. 4.9★ · 10,000+ repairs · 6-month warranty · Same-day service." />
      <meta name="keywords"
            content="Chennai Smart Care, Chennai Smart Care appliance repair, about Chennai Smart Care, appliance repair Chennai, AC repair Chennai, refrigerator repair Chennai" />
      <meta name="robots" content="index, follow" />
      <link rel="canonical" href="https://chennaismartcare.com/about" />
      <meta property="og:type"        content="website" />
      <meta property="og:site_name"   content="Chennai Smart Care" />
      <meta property="og:url"         content="https://chennaismartcare.com/about" />
      <meta property="og:title"       content="Chennai Smart Care — About Us | Appliance Repair Since 2014" />
      <meta property="og:description" content="Chennai Smart Care is Chennai's most trusted home appliance repair service. 10 years, 10,000+ repairs, 4.9★ rating, 6-month warranty." />
      <meta property="og:image"       content="https://chennaismartcare.com/images/og-about.jpg" />
      <meta property="og:locale"      content="en_IN" />
      <meta name="twitter:card"       content="summary_large_image" />
    </Head>

    <AppHeader />

    <!-- BREADCRUMB -->
    <nav aria-label="Breadcrumb" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-0">
      <ol class="flex items-center gap-2 text-xs text-gray-400">
        <li><a href="/" class="hover:text-blue-600 transition-colors">Chennai Smart Care</a></li>
        <li class="text-gray-300">/</li>
        <li class="text-gray-600 font-medium">About Us</li>
      </ol>
    </nav>

    <!-- HERO — H1 contains brand keyword -->
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white overflow-hidden mt-3 mx-4 sm:mx-6 lg:mx-8 rounded-3xl">
      <div class="absolute inset-0 opacity-[0.04]"
           style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>
      <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[120px] pointer-events-none"></div>
      <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

      <div class="relative max-w-7xl mx-auto px-6 sm:px-10 py-20 sm:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <div class="inline-flex items-center gap-2 bg-blue-500/15 border border-blue-400/20 text-blue-300 px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-widest mb-7">
              <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
              Serving Chennai Since 2014
            </div>

            <h1 class="text-4xl sm:text-5xl font-black leading-[1.1] tracking-tight">
              About
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400 block">
                Chennai Smart Care
              </span>
            </h1>
            <p class="mt-2 text-blue-300/70 text-base font-medium tracking-wide">
              Chennai's Most Trusted Home Appliance Repair Service
            </p>

            <p class="mt-6 text-slate-300 text-lg leading-relaxed max-w-lg">
              Chennai Smart Care started with one van and a promise: fix every appliance right, the first time.
              Today we serve <strong class="text-white">10,000+ homes</strong> across Chennai with
              <strong class="text-white">same-day service</strong>, <strong class="text-white">certified technicians</strong>,
              and a <strong class="text-white">6-month warranty</strong> on every repair.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
              <button @click="openBookingPrompt"
                 class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold px-7 py-3.5 rounded-xl transition-all hover:-translate-y-0.5 shadow-lg shadow-blue-900/40 text-sm">
                <CalendarIcon class="w-4 h-4" />
                Book a Service
              </button>
              <a href="tel:+919444900470"
                 class="inline-flex items-center gap-2 border border-white/20 hover:border-white/40 text-white font-semibold px-7 py-3.5 rounded-xl transition-all hover:bg-white/5 text-sm">
                <PhoneIcon class="w-4 h-4" />
                +91 94449 00470
              </a>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
              <span v-for="badge in trustBadges" :key="badge"
                    class="bg-white/8 border border-white/10 text-xs text-blue-200 px-3 py-1.5 rounded-full font-medium">
                {{ badge }}
              </span>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div v-for="stat in heroStats" :key="stat.label"
                 class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all hover:-translate-y-1 group cursor-default">
              <div class="text-3xl sm:text-4xl font-black text-white group-hover:text-blue-300 transition-colors">{{ stat.value }}</div>
              <div class="text-slate-300 text-sm mt-1.5 font-semibold">{{ stat.label }}</div>
              <div class="text-xs text-slate-500 mt-1 leading-relaxed">{{ stat.sub }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 48" class="w-full" preserveAspectRatio="none" height="48">
          <path fill="#ffffff" d="M0,48 C480,0 960,48 1440,16 L1440,48 Z"/>
        </svg>
      </div>
    </section>

    <!-- TRUST BAR -->
    <section class="bg-white border-b border-gray-100">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-3">
          <div v-for="t in trustBar" :key="t.text" class="flex items-center gap-2 text-sm text-gray-600 font-medium">
            <span class="text-base">{{ t.icon }}</span>{{ t.text }}
          </div>
        </div>
      </div>
    </section>

    <!-- TIMELINE -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Our Journey</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">How Chennai Smart Care Grew</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm leading-relaxed">
            From a single service call in Porur to Chennai's most-trusted repair service — a decade of growth driven by one thing: doing right by every customer.
          </p>
        </div>

        <div class="relative">
          <div class="absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-blue-200 via-blue-400 to-blue-200 hidden md:block -translate-x-1/2"></div>
          <div class="space-y-12">
            <div v-for="(milestone, i) in timeline" :key="i"
                 class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center"
                 :class="i % 2 === 0 ? '' : 'md:[&>*:first-child]:order-2 md:[&>*:last-child]:order-1'">
              <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 hidden md:flex">
                <div class="w-4 h-4 rounded-full bg-blue-600 border-4 border-white shadow-md shadow-blue-200 ring-2 ring-blue-100"></div>
              </div>
              <div class="bg-gray-50 rounded-2xl p-7 border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all"
                   :class="i % 2 === 0 ? 'md:mr-8' : 'md:ml-8'">
                <div class="flex items-center gap-3 mb-3">
                  <span class="text-2xl">{{ milestone.icon }}</span>
                  <span class="text-xs font-black bg-blue-600 text-white px-3 py-1 rounded-full tracking-wider">{{ milestone.year }}</span>
                </div>
                <h3 class="font-black text-gray-900 text-lg mb-2">{{ milestone.title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ milestone.desc }}</p>
              </div>
              <div class="hidden md:block"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- MISSION/VISION/VALUES -->
    <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">What Drives Us</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">Mission. Vision. Values.</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">
            Chennai Smart Care was built on three principles that guide every technician visit, every quote, and every repair.
          </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="pillar in pillars" :key="pillar.title"
               class="relative overflow-hidden rounded-3xl p-8 group cursor-default" :class="pillar.bg">
            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full opacity-20" :class="pillar.circle"></div>
            <div class="relative">
              <div class="text-4xl mb-5">{{ pillar.icon }}</div>
              <h3 class="font-black text-xl mb-3" :class="pillar.heading">{{ pillar.title }}</h3>
              <p class="text-sm leading-relaxed" :class="pillar.body">{{ pillar.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- WHY CHOOSE CHENNAI SMART CARE -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-5">Why 10,000+ Families Choose Us</span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-10 leading-tight">
              The Chennai Smart Care<br /><span class="text-blue-600">Difference</span>
            </h2>
            <div class="space-y-5">
              <div v-for="feature in features" :key="feature.title"
                   class="flex items-start gap-5 p-5 rounded-2xl hover:bg-blue-50 transition-all group border border-transparent hover:border-blue-100">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-xl transition-transform group-hover:scale-110" :class="feature.iconBg">
                  {{ feature.icon }}
                </div>
                <div>
                  <h3 class="font-bold text-gray-900 mb-1">{{ feature.title }}</h3>
                  <p class="text-gray-500 text-sm leading-relaxed">{{ feature.desc }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-2xl shadow-blue-200">
              <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
              <div class="relative">
                <div class="text-5xl mb-6">🛡️</div>
                <h3 class="text-2xl font-black mb-3">Chennai Smart Care Promise</h3>
                <p class="text-blue-200 text-sm mb-7 leading-relaxed">
                  Every repair comes with a written service guarantee. Backed by our decade-long reputation.
                </p>
                <div class="space-y-3">
                  <div v-for="promise in promises" :key="promise"
                       class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3 text-sm font-medium">
                    <span class="w-5 h-5 bg-green-400 rounded-full flex items-center justify-center text-green-900 text-xs font-black flex-shrink-0">✓</span>
                    {{ promise }}
                  </div>
                </div>
                <div class="mt-7 p-4 bg-white/10 rounded-2xl border border-white/20">
                  <p class="text-xs text-blue-200 uppercase tracking-wider font-semibold mb-1">Customer Rating</p>
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
            <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl px-5 py-3 shadow-xl border border-gray-100 flex items-center gap-3">
              <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"><span class="text-xl">⚡</span></div>
              <div>
                <p class="font-black text-gray-900 text-sm">Same-Day Service</p>
                <p class="text-gray-400 text-xs">9AM – 9PM · 7 days a week</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Customer Reviews</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">What Chennai Says About Us</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">
            Real customers, real reviews. Chennai Smart Care's reputation is built on thousands of honest feedback.
          </p>
          <div class="flex items-center justify-center gap-3 mt-5">
            <div class="flex text-yellow-400 text-2xl tracking-tight">★★★★★</div>
            <div class="text-left">
              <p class="font-black text-gray-900 text-lg">4.9 / 5</p>
              <p class="text-xs text-gray-400">Based on 2,400+ reviews</p>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          <div v-for="review in testimonials" :key="review.name"
               class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all flex flex-col">
            <div class="flex text-yellow-400 text-sm mb-3">
              <span v-for="i in review.rating" :key="i">★</span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed flex-1 italic">"{{ review.text }}"</p>
            <div class="mt-5 flex items-center gap-3 pt-4 border-t border-gray-100">
              <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0" :class="review.avatarBg">
                {{ review.name[0] }}
              </div>
              <div>
                <p class="font-bold text-gray-900 text-sm">{{ review.name }}</p>
                <p class="text-xs text-gray-400">{{ review.location }} · {{ review.service }}</p>
              </div>
              <div class="ml-auto">
                <span class="text-xs text-gray-300 bg-gray-50 px-2 py-1 rounded-lg">{{ review.date }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-10 text-center">
          <a href="https://g.page/r/chennaismartcare/review" target="_blank" rel="noopener"
             class="inline-flex items-center gap-2 border border-gray-300 text-gray-600 hover:border-blue-400 hover:text-blue-600 font-semibold px-6 py-3 rounded-xl transition-all text-sm">
            View All Reviews on Google <span>→</span>
          </a>
        </div>
      </div>
    </section>

    <!-- TECHNICIAN TEAM -->
    <section class="py-20 bg-white">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Our Team</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">The Technicians Behind Chennai Smart Care</h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">
            Every Chennai Smart Care technician is trained, certified, and background-verified before their first job.
          </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <div v-for="tech in techPromises" :key="tech.title"
               class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all text-center group">
            <div class="w-14 h-14 mx-auto bg-blue-50 group-hover:bg-blue-100 rounded-2xl flex items-center justify-center text-2xl mb-4 transition-colors">{{ tech.icon }}</div>
            <h3 class="font-bold text-gray-900 text-sm mb-2">{{ tech.title }}</h3>
            <p class="text-gray-400 text-xs leading-relaxed">{{ tech.desc }}</p>
          </div>
        </div>
        <div class="mt-10 bg-blue-600 rounded-3xl p-8 text-white grid grid-cols-2 sm:grid-cols-4 gap-6">
          <div v-for="s in techStats" :key="s.label" class="text-center">
            <div class="text-3xl font-black">{{ s.value }}</div>
            <div class="text-blue-200 text-xs mt-1 font-medium">{{ s.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICE AREAS -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
          <div>
            <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-5">Where We Work</span>
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-5 leading-tight">
              Chennai Smart Care<br /><span class="text-blue-600">Covers All of Chennai</span>
            </h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-8">
              From Porur in the west to OMR in the east, Anna Nagar in the north to Tambaram in the south — Chennai Smart Care sends technicians to every neighbourhood. Book before 2PM for same-day service.
            </p>
            <button @click="openBookingPrompt"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl transition-all hover:-translate-y-0.5 text-sm shadow-lg shadow-blue-200">
              <CalendarIcon class="w-4 h-4" />
              Book a Repair Near You
            </button>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <a v-for="area in coverageAreas" :key="area.slug"
               :href="`/services/ac/${area.slug}`"
               :title="`Chennai Smart Care ${area.name}`"
               class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all text-sm text-gray-700 hover:text-blue-700 font-medium group bg-white">
              <MapPinIcon class="w-3.5 h-3.5 text-gray-300 group-hover:text-blue-400 flex-shrink-0" />
              {{ area.name }}
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ — brand-focused -->
    <section class="py-20 bg-white">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <span class="inline-block text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">FAQ</span>
          <h2 class="text-3xl sm:text-4xl font-black text-gray-900">About Chennai Smart Care</h2>
          <p class="text-gray-500 mt-3 text-sm">Common questions people ask about our company and services.</p>
        </div>
        <div class="space-y-3">
          <div v-for="(faq, i) in faqs" :key="i" class="border border-gray-200 rounded-2xl overflow-hidden">
            <button @click="activeFaq = activeFaq === i ? null : i"
                    class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 transition-colors"
                    :aria-expanded="activeFaq === i">
              <span class="font-bold text-gray-900 text-sm pr-4">{{ faq.q }}</span>
              <ChevronDownIcon class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200"
                               :class="activeFaq === i ? 'rotate-180 text-blue-600' : ''" />
            </button>
            <Transition
              enter-active-class="transition-all duration-200 ease-out"
              enter-from-class="opacity-0"
              enter-to-class="opacity-100"
              leave-active-class="transition-all duration-150 ease-in"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0">
              <div v-if="activeFaq === i" class="px-6 pb-5 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                {{ faq.a }}
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </section>

    <!-- BOTTOM CTA -->
    <section class="py-20 bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white relative overflow-hidden">
      <div class="absolute inset-0 opacity-[0.04]"
           style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>
      <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px]"></div>
      <div class="relative max-w-3xl mx-auto px-4 text-center">
        <div class="text-5xl mb-6">🔧</div>
        <h2 class="text-3xl sm:text-4xl font-black mb-5 leading-tight">
          Book a Chennai Smart Care<br /><span class="text-blue-400">Technician Today</span>
        </h2>
        <p class="text-slate-300 text-lg mb-10 max-w-lg mx-auto">
          Same-day slots available. Certified technicians. 6-month warranty on every repair.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button @click="openBookingPrompt"
             class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 font-black px-8 py-4 rounded-xl hover:bg-blue-50 transition-all hover:-translate-y-0.5 shadow-xl text-sm">
            <CalendarIcon class="w-5 h-5" />
            Book a Service Now
          </button>
          <a href="tel:+919444900470"
             class="inline-flex items-center justify-center gap-2 border-2 border-white/30 hover:border-white/60 text-white font-semibold px-8 py-4 rounded-xl transition-all hover:bg-white/5 text-sm">
            <PhoneIcon class="w-5 h-5" />
            Call +91 94449 00470
          </a>
        </div>
        <p class="text-slate-500 text-xs mt-8">Available 9AM–9PM · Mon–Sun · All Chennai areas</p>
      </div>
    </section>

    <AppFooter />

    <!-- STICKY BOTTOM BAR — mobile only -->
    <Transition
      enter-active-class="transition-transform duration-500 ease-out"
      enter-from-class="translate-y-full"
      enter-to-class="translate-y-0">
      <div v-if="showStickyBar && !bookingPromptVisible"
           class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-2xl sm:hidden safe-bottom">
        <div class="flex">
          <a href="tel:+919444900470"
             class="flex-1 flex items-center justify-center gap-2 py-4 bg-gray-50 text-gray-800 font-bold text-sm border-r border-gray-200">
            <PhoneIcon class="w-4 h-4 text-blue-600" />
            Call Now
          </a>
          <button @click="openBookingPrompt"
             class="flex-1 flex items-center justify-center gap-2 py-4 bg-blue-600 text-white font-bold text-sm">
            <CalendarIcon class="w-4 h-4" />
            Book Service
          </button>
        </div>
      </div>
    </Transition>
  </div>

  <!-- BOOKING INTENT POPUP -->
  <Teleport to="body">
    <Transition
      enter-active-class="transition-all duration-500 ease-out"
      enter-from-class="opacity-0 translate-y-8"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-8">
      <div v-if="bookingPromptVisible"
           class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4"
           role="dialog" aria-label="Book a service with Chennai Smart Care">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
          <div class="h-1.5 bg-gradient-to-r from-blue-500 via-cyan-400 to-blue-600"></div>
          <div class="p-5">
            <div class="flex items-start justify-between gap-4 mb-5">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">🔧</div>
                <div>
                  <p class="font-black text-gray-900 text-base leading-tight">Would you like to book a service?</p>
                  <p class="text-xs text-gray-500 mt-0.5">Same-day slots · 45 mins avg response</p>
                </div>
              </div>
              <button @click="dismissPrompt"
                      class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition flex-shrink-0 mt-0.5"
                      aria-label="Dismiss">
                <XMarkIcon class="w-5 h-5" />
              </button>
            </div>

            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">What needs fixing?</p>
            <div class="grid grid-cols-2 gap-2 mb-5">
              <a v-for="svc in quickServices" :key="svc.slug"
                 :href="`/quick-booking?category=${svc.slug}`"
                 class="flex items-center gap-2.5 px-3 py-3 rounded-xl border border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all text-sm font-medium text-gray-700 hover:text-blue-700">
                <span class="text-xl">{{ svc.icon }}</span>
                <span class="leading-tight">{{ svc.label }}</span>
              </a>
            </div>

            <div class="flex gap-3">
              <a href="/quick-booking"
                 class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm text-center transition-colors">
                Book Any Service
              </a>
              <a href="tel:+919444900470"
                 class="flex items-center gap-1.5 border border-gray-300 hover:border-blue-400 text-gray-700 hover:text-blue-600 font-semibold px-4 py-3 rounded-xl text-sm transition-all">
                <PhoneIcon class="w-4 h-4" />
                Call
              </a>
            </div>

            <button @click="dismissPrompt"
                    class="mt-3 w-full text-center text-xs text-gray-400 hover:text-gray-600 transition-colors py-1">
              Not right now
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import { CalendarIcon, PhoneIcon, MapPinIcon, ChevronDownIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const bookingPromptVisible = ref(false)
const promptDismissed      = ref(false)
const showStickyBar        = ref(false)
const activeFaq            = ref(null)
let   promptTimer          = null

function openBookingPrompt()  { bookingPromptVisible.value = true }
function dismissPrompt() {
  bookingPromptVisible.value = false
  promptDismissed.value      = true
  try { sessionStorage.setItem('csc_booking_prompt_dismissed', '1') } catch {}
}

const quickServices = [
  { icon: '❄️', label: 'AC Repair / Service', slug: 'ac' },
  { icon: '🧊', label: 'Refrigerator Repair',  slug: 'refrigerator' },
  { icon: '🫧', label: 'Washing Machine',       slug: 'washing-machine' },
  { icon: '📡', label: 'Microwave Oven',         slug: 'microwave' },
]

const trustBadges = ['✅ ISO Certified Techs', '⭐ 4.9 Google Rating', '🔒 6-Month Warranty', '⚡ Same-Day Service', '📱 Live Tracking', '💰 Transparent Pricing']

const trustBar = [
  { icon: '⭐', text: '4.9★ on Google' },
  { icon: '🏠', text: '10,000+ Homes Served' },
  { icon: '🛡️', text: '6-Month Warranty' },
  { icon: '⚡', text: 'Same-Day Available' },
  { icon: '🔧', text: '45+ Certified Techs' },
]

const heroStats = [
  { value: '10,000+', label: 'Repairs Completed',  sub: 'Across all Chennai areas' },
  { value: '4.9★',    label: 'Google Rating',       sub: '2,400+ verified reviews' },
  { value: '10 Yrs',  label: 'In Business',         sub: 'Serving Chennai since 2014' },
  { value: '6-Month', label: 'Service Warranty',    sub: 'On every single repair' },
]

const timeline = [
  { year: '2014', icon: '🚐', title: 'Started with One Van', desc: 'Our founder started Chennai Smart Care with a single service van and two technicians, covering Porur and Maduravoyal with same-day AC and refrigerator repair.' },
  { year: '2016', icon: '📈', title: 'Expanded to 8 Areas', desc: 'Word spread fast. Chennai Smart Care expanded to Anna Nagar, Vadapalani, Koyambedu, T. Nagar and Adyar — growing to a team of 12 certified technicians.' },
  { year: '2018', icon: '🏆', title: 'Crossed 1,000 Repairs', desc: 'A major milestone. Our 1,000th repair — a Samsung refrigerator in Velachery — marked the turning point from startup to a trusted appliance repair brand in Chennai.' },
  { year: '2020', icon: '💻', title: 'Launched Online Booking', desc: 'Chennai Smart Care launched chennaismartcare.com with instant online booking, digital warranty cards, and real-time technician tracking.' },
  { year: '2022', icon: '🗺️', title: 'City-Wide Coverage', desc: 'Expanded to 24+ areas including OMR, Sholinganallur, Tambaram and Chromepet — covering every major neighbourhood in Chennai.' },
  { year: '2024', icon: '⭐', title: '10,000+ Happy Families', desc: "Today Chennai Smart Care is the city's top-rated appliance repair service — 4.9★ on Google, 10,000+ repairs done, still driven by the same promise." },
]

const pillars = [
  { icon: '🎯', title: 'Our Mission', desc: 'To make quality appliance repair accessible for every Chennai family — honest pricing, reliable technicians, and a guarantee that actually means something.', bg: 'bg-blue-600 text-white', circle: 'bg-blue-400', heading: 'text-white', body: 'text-blue-100' },
  { icon: '🔭', title: 'Our Vision',  desc: 'For Chennai Smart Care to become the most trusted home services brand across Tamil Nadu — the first name every family thinks of when an appliance breaks down.', bg: 'bg-gray-900 text-white', circle: 'bg-gray-700', heading: 'text-white', body: 'text-gray-400' },
  { icon: '💎', title: 'Our Values', desc: 'Transparency before we start, honesty during the job, accountability after. Every decision — from pricing to spare parts — is guided by these three commitments.', bg: 'bg-gray-50 border border-gray-200', circle: 'bg-gray-200', heading: 'text-gray-900', body: 'text-gray-500' },
]

const features = [
  { icon: '🛡️', iconBg: 'bg-blue-100',   title: '6-Month Service Warranty',   desc: 'Every Chennai Smart Care repair is covered for 6 months. Same issue returns? We fix it free — no questions asked.' },
  { icon: '💰', iconBg: 'bg-green-100',  title: 'Transparent Pricing Always', desc: 'We quote before we touch anything. No surprise bills, no add-ons. The price we say is the price you pay.' },
  { icon: '⚡', iconBg: 'bg-yellow-100', title: 'Same-Day Service',            desc: "Book before 2PM and we'll be at your door the same day. Evening slots available for working professionals." },
  { icon: '🔧', iconBg: 'bg-purple-100', title: 'Certified & Verified Techs', desc: 'All Chennai Smart Care technicians are brand-certified, background-checked, and trained on the latest appliance models.' },
  { icon: '📱', iconBg: 'bg-orange-100', title: 'Digital Warranty Card',       desc: 'Instantly accessible in your customer dashboard. Share it, check it, claim it — all from your phone.' },
  { icon: '🏠', iconBg: 'bg-teal-100',   title: 'Doorstep Repair',             desc: 'We come to you with all tools and genuine spare parts. Most Chennai Smart Care repairs done in a single visit.' },
]

const promises = [
  '6-month service warranty on every repair',
  'Genuine spare parts — never imitation',
  'Transparent quote before work begins',
  'No fix, no fee — or we refund your visit charge',
  'Technician arrives within the booked time slot',
]

const testimonials = [
  { name: 'Priya Ramachandran', location: 'Anna Nagar', service: 'AC Service',         rating: 5, date: 'Jan 2025', avatarBg: 'bg-pink-500',   text: 'Chennai Smart Care technician came within 3 hours of booking. AC is running perfectly now. Very professional, explained everything before starting. Will definitely book again.' },
  { name: 'Suresh Kumar',       location: 'Velachery',  service: 'Refrigerator Repair', rating: 5, date: 'Dec 2024', avatarBg: 'bg-blue-500',   text: 'My Samsung fridge was not cooling for two days. Chennai Smart Care sent a technician the same evening. Fixed the compressor issue in 45 minutes. Great service, honest price.' },
  { name: 'Kavitha Sundar',     location: 'T. Nagar',   service: 'Washing Machine',     rating: 5, date: 'Feb 2025', avatarBg: 'bg-purple-500', text: 'The technician wore a uniform with ID and cleaned up after the repair. Chennai Smart Care gave me a digital 6-month warranty card. Exactly what was promised — no hidden charges.' },
  { name: 'Rajesh Patel',       location: 'OMR',        service: 'AC Gas Filling',       rating: 5, date: 'Nov 2024', avatarBg: 'bg-green-500',  text: 'Booked AC gas refilling online at 10am, technician arrived by 12:30pm. Very transparent — showed me the gas level before and after. Happy with Chennai Smart Care.' },
  { name: 'Meenakshi R.',       location: 'Chromepet',  service: 'Refrigerator Repair', rating: 5, date: 'Jan 2025', avatarBg: 'bg-amber-500',  text: 'Had a weird noise from my fridge for weeks. One Chennai Smart Care visit and it is completely silent. The technician identified the fan motor issue in 10 minutes. Impressed.' },
  { name: 'Anand Krishnan',     location: 'Adyar',      service: 'AC Installation',     rating: 5, date: 'Mar 2025', avatarBg: 'bg-teal-500',   text: 'New AC installation done neatly. No mess, no wall damage, copper pipes properly insulated. Chennai Smart Care team arrived on time and finished in under 2 hours. Highly recommended.' },
]

const techPromises = [
  { icon: '🎓', title: 'Brand Certified',     desc: 'Trained by Samsung, LG, Daikin, Voltas, Whirlpool and other major brands.' },
  { icon: '🔍', title: 'Background Verified', desc: 'Every Chennai Smart Care technician passes a thorough background check before joining.' },
  { icon: '👔', title: "Uniformed & ID'd",    desc: 'Arrive in uniform with a photo ID card for your safety and peace of mind.' },
  { icon: '📋', title: 'Documented Repairs',  desc: 'Full digital service report after every job — what was done, replaced, and what to watch for.' },
]

const techStats = [
  { value: '45+',  label: 'Active technicians' },
  { value: '100%', label: 'Background verified' },
  { value: '8+',   label: 'Years avg. experience' },
  { value: '15+',  label: 'Brand certifications' },
]

const coverageAreas = [
  { name: 'Porur', slug: 'porur' }, { name: 'Anna Nagar', slug: 'anna-nagar' }, { name: 'Adyar', slug: 'adyar' },
  { name: 'Velachery', slug: 'velachery' }, { name: 'T. Nagar', slug: 't-nagar' }, { name: 'OMR', slug: 'omr' },
  { name: 'Sholinganallur', slug: 'sholinganallur' }, { name: 'Koyambedu', slug: 'koyambedu' }, { name: 'Ambattur', slug: 'ambattur' },
  { name: 'Tambaram', slug: 'tambaram' }, { name: 'Mylapore', slug: 'mylapore' }, { name: 'Mogappair', slug: 'mogappair' },
  { name: 'Chromepet', slug: 'chromepet' }, { name: 'Guindy', slug: 'guindy' }, { name: 'Maduravoyal', slug: 'maduravoyal' },
  { name: 'Vadapalani', slug: 'vadapalani' }, { name: 'Kilpauk', slug: 'kilpauk' }, { name: 'Perambur', slug: 'perambur' },
]

const faqs = [
  { q: 'What is Chennai Smart Care?', a: 'Chennai Smart Care is a professional home appliance repair company based in Chennai, Tamil Nadu. Founded in 2014, we specialise in AC repair and service, refrigerator repair, washing machine repair, and microwave oven repair across all major areas of Chennai.' },
  { q: 'Is Chennai Smart Care a trusted service?', a: 'Yes. Chennai Smart Care has a 4.9-star rating on Google based on 2,400+ verified customer reviews. All technicians are brand-certified and background-verified. We provide a written 6-month service warranty with transparent pricing and no hidden charges.' },
  { q: 'How do I book a service with Chennai Smart Care?', a: 'Book online at chennaismartcare.com/quick-booking or call +91 94449 00470. Select your appliance, choose a time slot, and a technician is dispatched to your door. Same-day service is available if you book before 2PM.' },
  { q: 'Which areas does Chennai Smart Care cover?', a: 'Chennai Smart Care covers the entire city including Anna Nagar, Porur, T. Nagar, Adyar, Velachery, Koyambedu, Ambattur, Mogappair, Maduravoyal, Chromepet, Tambaram, Guindy, Mylapore, OMR, Sholinganallur, Perambur, Kilpauk, Vadapalani, Nungambakkam, Valasaravakkam, and more.' },
  { q: 'What appliances does Chennai Smart Care repair?', a: 'Chennai Smart Care repairs all types of air conditioners (split, window, cassette), refrigerators (single door, double door, side-by-side), washing machines (front-load, top-load, semi-automatic), and microwave ovens. We carry genuine spare parts for all major brands.' },
  { q: 'Does Chennai Smart Care offer a warranty on repairs?', a: 'Yes. Every repair done by Chennai Smart Care comes with a 6-month service warranty. If the same issue recurs within 6 months, we fix it free of charge. You receive a digital warranty card immediately after the repair.' },
  { q: 'How quickly does Chennai Smart Care respond?', a: 'Chennai Smart Care offers same-day service for bookings placed before 2PM. Average technician response time is under 45 minutes. For urgent calls, call +91 94449 00470 and we will prioritise your slot.' },
]

function injectJsonLd(id, data) {
  document.getElementById(id)?.remove()
  const el = document.createElement('script')
  el.type = 'application/ld+json'; el.id = id
  el.textContent = JSON.stringify(data)
  document.head.appendChild(el)
}

onMounted(() => {
  injectJsonLd('schema-about', {
    '@context': 'https://schema.org',
    '@graph': [
      {
        '@type': 'BreadcrumbList',
        'itemListElement': [
          { '@type': 'ListItem', 'position': 1, 'name': 'Chennai Smart Care', 'item': 'https://chennaismartcare.com' },
          { '@type': 'ListItem', 'position': 2, 'name': 'About Us',            'item': 'https://chennaismartcare.com/about' },
        ],
      },
      {
        '@type': 'AboutPage',
        'url':   'https://chennaismartcare.com/about',
        'name':  'About Chennai Smart Care',
        'description': "Chennai Smart Care is Chennai's most trusted home appliance repair service since 2014.",
        'mainEntity': { '@id': 'https://chennaismartcare.com/#organization' },
      },
      {
        '@type':        'Organization',
        '@id':          'https://chennaismartcare.com/#organization',
        'name':         'Chennai Smart Care',
        'alternateName': ['CSC', 'Chennai SmartCare'],
        'url':          'https://chennaismartcare.com',
        'logo':         'https://chennaismartcare.com/images/logo.png',
        'foundingDate': '2014',
        'telephone':    '+91-9444900470',
        'email':        'support@chennaismartcare.com',
        'address':      { '@type': 'PostalAddress', 'addressLocality': 'Chennai', 'addressRegion': 'Tamil Nadu', 'postalCode': '600001', 'addressCountry': 'IN' },
        'contactPoint': { '@type': 'ContactPoint', 'telephone': '+91-9444900470', 'contactType': 'customer service', 'areaServed': 'IN', 'availableLanguage': ['English', 'Tamil'] },
        'sameAs': ['https://www.facebook.com/chennaismartcare', 'https://www.instagram.com/chennaismartcare', 'https://g.page/chennaismartcare'],
        'aggregateRating': { '@type': 'AggregateRating', 'ratingValue': '4.9', 'reviewCount': '2400', 'bestRating': '5', 'worstRating': '1' },
        'numberOfEmployees': { '@type': 'QuantitativeValue', 'value': 45 },
      },
    ],
  })

  injectJsonLd('schema-faq-about', {
    '@context': 'https://schema.org',
    '@type':    'FAQPage',
    'mainEntity': faqs.map(f => ({ '@type': 'Question', 'name': f.q, 'acceptedAnswer': { '@type': 'Answer', 'text': f.a } })),
  })

  injectJsonLd('schema-reviews-about', {
    '@context': 'https://schema.org',
    '@type':    'LocalBusiness',
    '@id':      'https://chennaismartcare.com/#localbusiness',
    'name':     'Chennai Smart Care',
    'review':   testimonials.map(r => ({
      '@type': 'Review',
      'author': { '@type': 'Person', 'name': r.name },
      'datePublished': '2025-01-01',
      'reviewBody': r.text,
      'reviewRating': { '@type': 'Rating', 'ratingValue': r.rating, 'bestRating': '5', 'worstRating': '1' },
    })),
  })

  const alreadyDismissed = (() => { try { return !!sessionStorage.getItem('csc_booking_prompt_dismissed') } catch { return false } })()
  if (!alreadyDismissed) {
    promptTimer = setTimeout(() => { bookingPromptVisible.value = true }, 5000)
  }

  const onScroll = () => { showStickyBar.value = window.scrollY > 300 }
  window.addEventListener('scroll', onScroll, { passive: true })
  onUnmounted(() => window.removeEventListener('scroll', onScroll))
})

onUnmounted(() => {
  clearTimeout(promptTimer)
  ;['schema-about', 'schema-faq-about', 'schema-reviews-about'].forEach(id => document.getElementById(id)?.remove())
})
</script>

<style scoped>
.safe-bottom { padding-bottom: env(safe-area-inset-bottom, 0px); }
</style>