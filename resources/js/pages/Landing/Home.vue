<template>
  <div class="min-h-screen bg-gray-50">
    <Head>
      <title>AC, Fridge & Washing Machine Repair in Chennai | Chennai Smart Care</title>
      <meta name="description" content="Top-rated AC, Refrigerator & Washing Machine repair in Chennai — Porur, Vadapalani, Maduravoyal, Koyambedu, Valasaravakkam, Guindy. Certified technicians, same-day service, 30-day warranty. ☎ +91 94449 00470" />
      <meta name="keywords" content="AC repair Chennai, refrigerator repair Chennai, washing machine repair Chennai, AC repair Porur, AC repair Vadapalani, AC repair Koyambedu, appliance repair Chennai, home appliance service Chennai" />
      <meta name="robots" content="index, follow" />
      <meta name="geo.region" content="IN-TN" />
      <meta name="geo.placename" content="Chennai" />
      <link rel="canonical" href="https://chennaismartcare.com/" />

      <!-- Open Graph -->
      <meta property="og:title" content="Chennai Smart Care — Expert Appliance Repair in Chennai" />
      <meta property="og:description" content="Same-day AC, Refrigerator & Washing Machine repair across Chennai. Certified techs, transparent pricing, 30-day warranty." />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="https://chennaismartcare.com/" />
      <meta property="og:locale" content="en_IN" />
    </Head>

    <AppHeader />

    <!-- ==================== HERO ==================== -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white overflow-hidden">
      <div class="absolute inset-0 opacity-10 animate-slow-spin"
           style="background-image:url('data:image/svg+xml,%3Csvg width=60 height=60 xmlns=http://www.w3.org/2000/svg%3E%3Cg fill=%23ffffff fill-opacity=0.4%3E%3Cpath d=M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z/%3E%3C/g%3E%3C/svg%3E')">
      </div>

      <!-- Floating icons -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute floating-animation-1" style="top:15%;left:5%">
          <svg class="w-16 h-16 text-white/5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h12v12H6z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <div class="absolute floating-animation-2" style="top:60%;right:8%">
          <svg class="w-20 h-20 text-white/5" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="4" y1="8" x2="20" y2="8" stroke="currentColor" stroke-width="0.5"/></svg>
        </div>
        <div class="absolute floating-animation-3" style="bottom:20%;left:10%">
          <svg class="w-16 h-16 text-white/5" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1"/></svg>
        </div>
        <div class="absolute floating-animation-4" style="top:30%;right:15%">
          <svg class="w-12 h-12 text-white/5" viewBox="0 0 24 24" fill="currentColor"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-blue-700/50 backdrop-blur-sm border border-blue-500/30 text-blue-100 px-4 py-2 rounded-full text-sm font-medium mb-6 animate-pulse-slow">
              <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
              Available Today · 9AM – 9PM · Serving All Chennai Areas
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">
              Expert Appliance<br />
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-200">
                Repair in Chennai
              </span>
            </h1>

            <!-- Area pills for SEO signal + UX -->
            <div class="mt-4 flex flex-wrap gap-2">
              <span v-for="area in serviceAreas" :key="area.name"
                    class="text-xs bg-white/10 border border-white/20 text-blue-100 px-3 py-1 rounded-full">
                📍 {{ area.name }}
              </span>
            </div>

            <p class="mt-5 text-lg sm:text-xl text-blue-100 leading-relaxed max-w-lg">
              Certified technicians for <strong class="text-white">AC, Refrigerator & Washing Machine</strong> repair.
              <span class="font-semibold text-white">Same-day service</span>, transparent pricing,
              and a <span class="font-semibold text-white">30-day service warranty</span>.
            </p>

            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div v-for="badge in trustBadges" :key="badge.text"
                   class="flex items-center gap-2 text-sm bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/20">
                <span class="w-5 h-5 flex items-center justify-center bg-green-500 rounded-full text-white text-xs shadow-lg flex-shrink-0">
                  <CheckIcon class="w-3 h-3" />
                </span>
                <span class="text-blue-50">{{ badge.text }}</span>
              </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row gap-4">
              <a href="/quick-booking"
                 class="group inline-flex items-center justify-center gap-2 bg-white text-blue-900 px-8 py-4 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:bg-blue-50"
                 @click="trackCTA('hero_book_now')">
                <CalendarIcon class="w-5 h-5" />
                Book Service Now
              </a>
              <button @click="showConsultationModal = true"
                      class="group inline-flex items-center justify-center gap-2 border-2 border-white/60 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/10 transition-all duration-300 hover:border-white">
                <PhoneIcon class="w-5 h-5" />
                Free Consultation
              </button>
            </div>

            <p class="mt-4 text-blue-200 text-sm flex items-center gap-2">
              <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
              Or call us:
              <a href="tel:+919876543210" class="text-white font-semibold hover:underline">+91 94449 00470</a>
            </p>
          </div>

          <div class="hidden lg:block">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 space-y-6 shadow-2xl">
              <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <SparklesIcon class="w-5 h-5 text-yellow-300" />
                Why 10,000+ Chennai Families Choose Us
              </h3>
              <div class="grid grid-cols-2 gap-4">
                <div v-for="stat in heroStats" :key="stat.label"
                     class="bg-white/10 rounded-2xl p-4 text-center hover:bg-white/20 transition-all duration-300 hover:scale-105">
                  <div class="text-3xl font-extrabold text-white">{{ stat.value }}</div>
                  <div class="text-blue-200 text-sm mt-1 font-medium">{{ stat.label }}</div>
                </div>
              </div>
              <div class="bg-white rounded-2xl p-4 group hover:shadow-xl transition-all">
                <p class="text-gray-700 font-semibold text-sm mb-3 flex items-center gap-2">
                  <ClockIcon class="w-4 h-4 text-blue-600" />
                  Quick Book in 60 Seconds
                </p>
                <a href="/quick-booking"
                   class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-3 rounded-xl font-semibold transition-all hover:scale-[1.02]">
                  Start Booking →
                </a>
              </div>
              <div class="flex items-center gap-2 text-sm text-blue-200">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                12 people booking right now
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 60" class="w-full" preserveAspectRatio="none" height="60">
          <path fill="rgb(249,250,251)" d="M0,60 C360,0 1080,60 1440,20 L1440,60 Z"/>
        </svg>
      </div>
    </section>

    <!-- ==================== SERVICE AREAS (SEO SECTION) ==================== -->
    <section id="service-areas" class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
          <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3">Coverage Area</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
            Appliance Repair Across Chennai
          </h2>
          <p class="text-gray-500 mt-2 max-w-2xl mx-auto text-sm sm:text-base">
            Same-day technicians in every major Chennai neighbourhood — book now and we'll be at your door today.
          </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
          <a v-for="area in serviceAreas" :key="area.name"
             :href="`/services/${area.slug}`"
             class="group bg-white rounded-2xl border border-gray-200 p-4 text-center hover:border-blue-400 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="text-3xl mb-2">{{ area.icon }}</div>
            <h3 class="font-bold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">{{ area.name }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ area.tagline }}</p>
            <div class="mt-2 flex flex-wrap gap-1 justify-center">
              <span v-for="svc in area.services.slice(0,2)" :key="svc"
                    class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">{{ svc }}</span>
            </div>
          </a>
        </div>

        <!-- Area × Service matrix — rich text for SEO crawlers -->
        <div class="mt-10 bg-white rounded-2xl border border-gray-100 p-6 sm:p-8">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Services Available by Area</h3>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-100">
                  <th class="text-left py-2 pr-4 font-semibold text-gray-600 whitespace-nowrap">Area</th>
                  <th v-for="svc in matrixServices" :key="svc" class="text-center py-2 px-3 font-semibold text-gray-600 whitespace-nowrap">{{ svc }}</th>
                  <th class="text-center py-2 px-3 font-semibold text-gray-600">Response</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="area in serviceAreas" :key="area.name" class="hover:bg-blue-50/30 transition-colors">
                  <td class="py-3 pr-4 font-semibold text-gray-900 whitespace-nowrap">
                    {{ area.icon }} {{ area.name }}
                  </td>
                  <td v-for="svc in matrixServices" :key="svc" class="py-3 px-3 text-center">
                    <span class="text-green-500 text-base">✓</span>
                  </td>
                  <td class="py-3 px-3 text-center">
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Same Day</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <p class="text-xs text-gray-400 mt-4">* Same-day slots available for bookings placed before 2 PM. Evening slots available for all areas.</p>
        </div>
      </div>
    </section>

    <!-- ==================== SERVICES ==================== -->
    <section id="services" class="py-16 sm:py-24 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader
          badge="Our Services"
          title="All Appliance Repairs, One Trusted Team"
          subtitle="From routine maintenance to emergency repairs — every appliance, every Chennai area."
        />
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
          <ServiceCard v-for="category in categories" :key="category.id"
            :category="category" @book="handleBookService"
            class="transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl" />
        </div>
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="feature in serviceFeatures" :key="feature.text"
               class="bg-white rounded-xl p-4 text-center shadow-md hover:shadow-lg transition-all">
            <div class="w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-3">
              <component :is="feature.icon" class="w-6 h-6" />
            </div>
            <p class="font-semibold text-gray-800">{{ feature.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== HOW IT WORKS ==================== -->
    <section id="how-it-works" class="py-16 sm:py-24 bg-white relative overflow-hidden">
      <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, gray 1px, transparent 1px); background-size: 40px 40px;"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader badge="Simple Process" title="Book a Repair in 3 Easy Steps"
          subtitle="No complicated forms, no waiting — get your appliance fixed today." />
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="(step, i) in steps" :key="i" class="text-center group">
            <div class="relative">
              <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-2xl flex items-center justify-center mx-auto shadow-xl group-hover:shadow-2xl transition-all duration-300 group-hover:scale-110 text-3xl font-extrabold">
                {{ i + 1 }}
              </div>
              <div v-if="i < steps.length - 1" class="hidden md:block absolute top-12 left-[calc(50%+3rem)] w-[calc(100%-6rem)] h-0.5 bg-gradient-to-r from-blue-300 to-transparent"></div>
            </div>
            <h3 class="mt-6 text-xl font-bold text-gray-900">{{ step.title }}</h3>
            <p class="mt-3 text-gray-600 leading-relaxed">{{ step.description }}</p>
          </div>
        </div>
        <div class="mt-12 text-center">
          <a href="/quick-booking"
             class="group inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
            Book Now — It's Free to Schedule
            <ArrowRightIcon class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
          </a>
        </div>
      </div>
    </section>

    <!-- ==================== FREE CONSULTATION CTA ==================== -->
    <section id="free-consultation" class="py-16 sm:py-20 bg-gradient-to-r from-blue-600 to-indigo-700 text-white relative overflow-hidden">
      <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">
          🔧 Not Sure What's Wrong with Your Appliance?
        </h2>
        <p class="mt-4 text-xl text-blue-100">
          Get a <strong class="text-white bg-white/20 px-3 py-1 rounded-full">FREE expert consultation</strong> within 30 minutes.
          Available across all Chennai areas.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
          <button @click="showConsultationModal = true"
                  class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-8 py-4 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
            Request Free Consultation
          </button>
          <a href="https://wa.me/919876543210" target="_blank" rel="noopener"
             class="inline-flex items-center justify-center gap-2 border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/10 transition-all">
            <WhatsAppIcon size="sm" />
            Chat on WhatsApp
          </a>
        </div>
        <div class="mt-6 inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
          <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
          <span class="text-sm">Average response time: 2 minutes · Porur to Guindy, we cover all</span>
        </div>
      </div>
    </section>

    <!-- ==================== TESTIMONIALS ==================== -->
    <section id="testimonials" class="py-16 sm:py-24 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
      <div class="absolute inset-0 opacity-30">
        <div class="absolute top-0 left-0 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader badge="Customer Reviews" title="Trusted by 5000+ Chennai Families"
          subtitle="Real reviews from real customers across Porur, Vadapalani, Koyambedu and more." />

        <div v-if="props.testimonials?.length" class="mt-12">
          <div v-if="featuredTestimonial" class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white">
              <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl font-bold">
                  {{ featuredTestimonial.initials }}
                </div>
                <div>
                  <p class="font-semibold">{{ featuredTestimonial.name }}</p>
                  <div class="flex items-center gap-1 mt-1">
                    <StarIcon v-for="n in 5" :key="n" class="w-4 h-4 text-yellow-300 fill-current" />
                  </div>
                </div>
              </div>
              <p class="text-lg italic">"{{ featuredTestimonial.comment }}"</p>
            </div>
          </div>

          <!-- Carousel -->
          <div class="relative px-4 sm:px-0">
            <button @click="prevSlide" :disabled="isFirstSlide"
                    :class="['absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 sm:-translate-x-6 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-xl transition-all', isFirstSlide && 'opacity-50 cursor-not-allowed']">
              <ChevronLeftIcon class="w-5 h-5" />
            </button>
            <button @click="nextSlide" :disabled="isLastSlide"
                    :class="['absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 sm:translate-x-6 z-10 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-blue-600 hover:shadow-xl transition-all', isLastSlide && 'opacity-50 cursor-not-allowed']">
              <ChevronRightIcon class="w-5 h-5" />
            </button>
            <div class="overflow-hidden">
              <div class="flex transition-transform duration-500 ease-out" :style="{ transform: `translateX(-${currentSlide * 100}%)` }">
                <div v-for="(group, gi) in testimonialGroups" :key="gi"
                     class="w-full flex-shrink-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <TestimonialCard v-for="(review, ri) in group" :key="review.id"
                    :review="review" :is-active="gi === currentSlide && ri === 0" class="h-full" />
                </div>
              </div>
            </div>
            <div class="mt-8 flex items-center justify-center gap-4">
              <div class="flex gap-2">
                <button v-for="(_, i) in testimonialGroups" :key="i" @click="goToSlide(i)">
                  <div :class="['h-2 rounded-full transition-all duration-300', currentSlide === i ? 'w-8 bg-blue-600' : 'w-2 bg-gray-300 hover:bg-gray-400']"></div>
                </button>
              </div>
              <span class="text-sm text-gray-500 font-medium">{{ currentSlide + 1 }} / {{ testimonialGroups.length }}</span>
            </div>
          </div>
        </div>

        <!-- Empty state -->
        <div v-else class="mt-12 text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
          <StarIcon class="w-10 h-10 text-gray-400 mx-auto mb-3" />
          <p class="text-gray-500 text-sm">Reviews loading...</p>
        </div>

        <!-- Rating summary -->
        <div class="mt-12 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            <div class="text-center md:text-left">
              <div class="text-5xl font-bold text-gray-900">4.9</div>
              <div class="text-sm text-gray-500 mt-1">out of 5</div>
              <div class="flex items-center justify-center md:justify-start gap-1 mt-2">
                <StarIcon v-for="n in 5" :key="n" class="w-5 h-5 text-yellow-400 fill-current" />
              </div>
            </div>
            <div class="space-y-2">
              <div v-for="rating in [5,4,3,2,1]" :key="rating" class="flex items-center gap-2">
                <span class="text-sm text-gray-600 w-8">{{ rating }} ★</span>
                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" :style="{ width: `${ratingBreakdown[rating]}%` }"></div>
                </div>
                <span class="text-sm text-gray-500 w-12">{{ ratingBreakdown[rating] }}%</span>
              </div>
            </div>
            <div class="text-center md:text-right">
              <div class="text-2xl font-bold text-gray-900">2,400+</div>
              <div class="text-sm text-gray-500 mb-4">Verified Reviews</div>
              <a href="https://g.page/chennaismartcare/reviews" target="_blank"
                 class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold text-sm">
                Read Google Reviews →
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== FAQ (SEO) ==================== -->
    <section id="faq" class="py-16 bg-white">
      <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <SectionHeader badge="FAQs" title="Common Questions"
          subtitle="Everything you need to know about appliance repair in Chennai." />
        <div class="mt-10 space-y-3">
          <div v-for="faq in faqs" :key="faq.q"
               class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden">
            <button @click="faq.open = !faq.open"
                    class="w-full flex items-center justify-between px-5 py-4 text-left">
              <span class="font-semibold text-gray-900 text-sm sm:text-base">{{ faq.q }}</span>
              <ChevronDownIcon :class="['w-5 h-5 text-gray-400 transition-transform flex-shrink-0 ml-3', faq.open && 'rotate-180']" />
            </button>
            <div v-if="faq.open" class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">{{ faq.a }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ==================== BLOG PREVIEW ==================== -->
    <section v-if="props.recentPosts?.length" id="blog" class="py-16 sm:py-24 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader badge="Expert Tips" title="Appliance Care & Repair Tips"
          subtitle="Advice from our Chennai technicians to keep your appliances running longer." />
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
          <BlogCard v-for="post in props.recentPosts" :key="post.id" :post="post" />
        </div>
        <div class="mt-8 text-center">
          <a href="/blog" class="group inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700">
            View All Articles
            <ArrowRightIcon class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </a>
        </div>
      </div>
    </section>

    <!-- Emergency CTA -->
    <div class="fixed bottom-6 right-6 z-50 animate-bounce-slow">
      <a href="tel:+919876543210"
         class="flex items-center gap-2 bg-red-600 text-white px-4 py-3 rounded-full shadow-lg hover:shadow-xl transition-all hover:bg-red-700">
        <PhoneIcon class="w-5 h-5 animate-pulse" />
        <span class="font-semibold">Emergency?</span>
      </a>
    </div>

    <AppFooter />

    <ConsultationModal :is-open="showConsultationModal" @close="showConsultationModal = false" />
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'
import ServiceCard from '@/components/Landing/ServiceCard.vue'
import TestimonialCard from '@/components/Landing/TestimonialCard.vue'
import BlogCard from '@/components/Landing/BlogCard.vue'
import SectionHeader from '@/components/Shared/SectionHeader.vue'
import ConsultationModal from '@/components/Landing/ConsultationModal.vue'
import WhatsAppIcon from '@/components/Shared/WhatsAppIcon.vue'

import {
  CheckIcon, CalendarIcon, PhoneIcon, SparklesIcon, ClockIcon,
  ArrowRightIcon, StarIcon, WrenchIcon, ShieldCheckIcon, TruckIcon,
  CreditCardIcon, ChevronLeftIcon, ChevronRightIcon, ChevronDownIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  categories:       { type: Array, default: () => [] },
  featuredServices: { type: Array, default: () => [] },
  testimonials:     { type: Array, default: () => [] },
  recentPosts:      { type: Array, default: () => [] },
})

const showConsultationModal = ref(false)
const currentSlide = ref(0)
const testimonialsPerPage = 3

// --- Service Areas (SEO-targeted) ---
const serviceAreas = [
  { name: 'Porur',           slug: 'ac', icon: '🏘️', tagline: 'Fast same-day service', services: ['AC', 'Fridge', 'Washing Machine'] },
  { name: 'Vadapalani',      slug: 'ac', icon: '🏙️', tagline: 'Express technicians',   services: ['AC', 'Fridge', 'Washing Machine'] },
  { name: 'Maduravoyal',     slug: 'ac', icon: '🏡', tagline: 'Doorstep service',       services: ['AC', 'Fridge', 'Washing Machine'] },
  { name: 'Koyambedu',       slug: 'ac', icon: '🛒', tagline: 'Available all week',     services: ['AC', 'Fridge', 'Washing Machine'] },
  { name: 'Valasaravakkam',  slug: 'ac', icon: '🏠', tagline: 'Same-day slots',         services: ['AC', 'Fridge', 'Washing Machine'] },
  { name: 'Guindy',          slug: 'ac', icon: '🏢', tagline: '30-day warranty',        services: ['AC', 'Fridge', 'Washing Machine'] },
]

const matrixServices = ['AC Repair', 'Refrigerator', 'Washing Machine', 'Microwave']

// --- FAQ (maps to JSON-LD schema above) ---
const faqs = reactive([
  { q: 'How quickly can you send a technician in Chennai?', a: 'We offer same-day service across all Chennai areas — Porur, Vadapalani, Koyambedu, Guindy, Valasaravakkam and Maduravoyal. Book before 2 PM for a same-day slot.', open: false },
  { q: 'What is the service charge for AC repair in Chennai?', a: 'AC repair starts from ₹299 for basic service. All prices are transparent — you get a full estimate before we begin any work. No hidden charges.', open: false },
  { q: 'Do you offer a warranty on appliance repairs?', a: 'Yes. All repairs come with a 30-day service warranty. If the same issue recurs within 30 days, we fix it free of charge.', open: false },
  { q: 'Which areas in Chennai do you serve?', a: 'We serve all major Chennai areas including Porur, Vadapalani, Maduravoyal, Koyambedu, Valasaravakkam, Guindy, Anna Nagar, T. Nagar, Velachery and more.', open: false },
  { q: 'Do you service all appliance brands?', a: 'Yes — LG, Samsung, Voltas, Daikin, Hitachi, Whirlpool, IFB, Bosch, Godrej and all other major brands. Our technicians are brand-certified.', open: false },
  { q: 'Can I book a service on weekends?', a: 'Absolutely. We operate 7 days a week, 9 AM to 9 PM including Sundays and public holidays.', open: false },
])

// --- Static content ---
const trustBadges = [
  { text: 'Certified Technicians' },
  { text: '30-Day Warranty'       },
  { text: 'Same-Day Service'      },
  { text: 'Transparent Pricing'   },
]

const serviceFeatures = [
  { text: 'Certified Experts', icon: WrenchIcon },
  { text: 'Warranty Included', icon: ShieldCheckIcon },
  { text: 'Same-Day Service',  icon: TruckIcon },
  { text: 'Fair Pricing',      icon: CreditCardIcon },
]

const heroStats = [
  { value: '10,000+', label: 'Repairs Done'   },
  { value: '4.9★',   label: 'Google Rating'  },
  { value: '30 min',  label: 'Avg Response'   },
  { value: '30-Day',  label: 'Warranty'       },
]

const steps = [
  { title: 'Book Online or Call',   description: 'Select your service, pick a date & time — takes 60 seconds.' },
  { title: 'Technician Visits You', description: 'A certified technician arrives at your doorstep on schedule.' },
  { title: 'Repair & Pay',          description: 'Transparent pricing. No hidden charges, ever.' },
]

const ratingBreakdown = { 5: 75, 4: 15, 3: 5, 2: 3, 1: 2 }

// --- Testimonials carousel ---
let testimonialInterval
// Inject JSON-LD structured data into <head>
function injectJsonLd(id, data) {
  const el = document.createElement('script')
  el.type = 'application/ld+json'
  el.id = id
  el.textContent = JSON.stringify(data)
  document.head.appendChild(el)
}
function removeJsonLd(id) {
  document.getElementById(id)?.remove()
}

onMounted(() => {
  injectJsonLd('schema-local-business', {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "@id": "https://chennaismartcare.com/#business",
    "name": "Chennai Smart Care",
    "description": "Expert AC, Refrigerator and Washing Machine repair service in Chennai with certified technicians and 30-day service warranty.",
    "url": "https://chennaismartcare.com",
    "telephone": "+919876543210",
    "priceRange": "₹₹",
    "openingHours": "Mo-Su 09:00-21:00",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "100 Feet Road",
      "addressLocality": "Vadapalani",
      "addressRegion": "Tamil Nadu",
      "postalCode": "600026",
      "addressCountry": "IN"
    },
    "geo": { "@type": "GeoCoordinates", "latitude": "13.0827", "longitude": "80.2707" },
    "areaServed": [
      {"@type": "City", "name": "Chennai"},
      {"@type": "Neighborhood", "name": "Porur"},
      {"@type": "Neighborhood", "name": "Vadapalani"},
      {"@type": "Neighborhood", "name": "Maduravoyal"},
      {"@type": "Neighborhood", "name": "Koyambedu"},
      {"@type": "Neighborhood", "name": "Valasaravakkam"},
      {"@type": "Neighborhood", "name": "Guindy"}
    ],
    "hasOfferCatalog": {
      "@type": "OfferCatalog",
      "name": "Appliance Repair Services",
      "itemListElement": [
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "AC Repair & Service"}},
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Refrigerator Repair"}},
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Washing Machine Repair"}}
      ]
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.9",
      "reviewCount": "2400",
      "bestRating": "5"
    }
  })

  injectJsonLd('schema-faq', {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "How quickly can you send a technician in Chennai?",
        "acceptedAnswer": {"@type": "Answer", "text": "We offer same-day service across Chennai including Porur, Vadapalani, Koyambedu, Guindy, Valasaravakkam and Maduravoyal. Book before 2 PM for a same-day slot."}
      },
      {
        "@type": "Question",
        "name": "What is the service charge for AC repair in Chennai?",
        "acceptedAnswer": {"@type": "Answer", "text": "AC repair in Chennai starts from ₹299 for basic service. All prices are transparent — you see the full estimate before we begin work."}
      },
      {
        "@type": "Question",
        "name": "Do you offer a warranty on repairs?",
        "acceptedAnswer": {"@type": "Answer", "text": "Yes, all repairs come with a 30-day service warranty. Same issue within 30 days? We fix it free."}
      },
      {
        "@type": "Question",
        "name": "Which areas in Chennai do you serve?",
        "acceptedAnswer": {"@type": "Answer", "text": "We serve Porur, Vadapalani, Maduravoyal, Koyambedu, Valasaravakkam, Guindy, Anna Nagar, T. Nagar, Velachery and all major Chennai areas."}
      }
    ]
  })

  
  testimonialInterval = setInterval(() => {
    if (props.testimonials?.length > testimonialsPerPage) {
      currentSlide.value = (currentSlide.value + 1) % Math.ceil(props.testimonials.length / testimonialsPerPage)
    }
  }, 5000)
})
onUnmounted(() => {
  if (testimonialInterval) clearInterval(testimonialInterval)
  removeJsonLd('schema-local-business')
  removeJsonLd('schema-faq')
})

const testimonialGroups = computed(() => {
  if (!props.testimonials?.length) return []
  const groups = []
  for (let i = 0; i < props.testimonials.length; i += testimonialsPerPage) {
    groups.push(props.testimonials.slice(i, i + testimonialsPerPage))
  }
  return groups
})

const isFirstSlide = computed(() => currentSlide.value === 0)
const isLastSlide  = computed(() => currentSlide.value === testimonialGroups.value.length - 1)
function nextSlide() { if (!isLastSlide.value)  currentSlide.value++ }
function prevSlide() { if (!isFirstSlide.value) currentSlide.value-- }
function goToSlide(i) { currentSlide.value = i }

const featuredTestimonial = computed(() => {
  if (!props.testimonials?.length) return null
  const t = props.testimonials[0]
  return {
    name:     t.user?.name ?? 'Verified Customer',
    initials: (t.user?.name ?? 'VC').split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2),
    comment:  t.comment,
    rating:   t.rating,
  }
})

function handleBookService(category) { window.location.href = `/quick-booking?category=${category.slug}` }
function trackCTA(label) {
  if (typeof window !== 'undefined' && window.dataLayer) {
    window.dataLayer.push({ event: 'cta_click', event_label: label })
  }
}
</script>

<style scoped>
@keyframes slow-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes pulse-slow { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }
@keyframes blob { 0% { transform: translate(0,0) scale(1); } 33% { transform: translate(30px,-50px) scale(1.1); } 66% { transform: translate(-20px,20px) scale(.9); } 100% { transform: translate(0,0) scale(1); } }
@keyframes float-1 { 0%,100% { transform: translate(0,0) rotate(0deg); } 25% { transform: translate(10px,-15px) rotate(5deg); } 50% { transform: translate(20px,0) rotate(0deg); } 75% { transform: translate(10px,15px) rotate(-5deg); } }
@keyframes float-2 { 0%,100% { transform: translate(0,0); } 33% { transform: translate(-15px,-10px); } 66% { transform: translate(-5px,15px); } }
@keyframes float-3 { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(15px,-20px) scale(1.1); } }
@keyframes float-4 { 0%,100% { transform: translate(0,0); } 40% { transform: translate(-20px,10px); } 80% { transform: translate(-10px,-15px); } }

.animate-slow-spin { animation: slow-spin 60s linear infinite; }
.animate-bounce-slow { animation: bounce-slow 2s ease-in-out infinite; }
.animate-pulse-slow { animation: pulse-slow 2s ease-in-out infinite; }
.animate-blob { animation: blob 7s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
.floating-animation-1 { animation: float-1 20s ease-in-out infinite; }
.floating-animation-2 { animation: float-2 18s ease-in-out infinite; }
.floating-animation-3 { animation: float-3 22s ease-in-out infinite; }
.floating-animation-4 { animation: float-4 19s ease-in-out infinite; }
</style>