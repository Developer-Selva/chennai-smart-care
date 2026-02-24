<template>
  <div class="min-h-screen bg-gray-50">
    <Head>
      <title>{{ category.name }} Repair in Chennai — Chennai Smart Care</title>
      <meta name="description" :content="`Expert ${category.name} repair in Chennai. Certified technicians, same-day service, 30-day warranty. Book online now!`" />
    </Head>
    <AppHeader />

    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-800 to-indigo-900 text-white py-14 px-4">
      <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center gap-6">
        <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-5xl flex-shrink-0">
          {{ category.icon || '🔧' }}
        </div>
        <div>
          <div class="text-blue-300 text-sm font-semibold mb-1 uppercase tracking-wider">Expert Service</div>
          <h1 class="text-3xl sm:text-4xl font-extrabold">{{ category.name }} Repair in Chennai</h1>
          <p class="text-blue-200 mt-2 text-lg max-w-2xl">{{ category.description }}</p>
          <div class="flex flex-wrap gap-3 mt-4">
            <span class="bg-white/10 text-white text-sm px-3 py-1 rounded-full border border-white/20">✅ Certified Technicians</span>
            <span class="bg-white/10 text-white text-sm px-3 py-1 rounded-full border border-white/20">⚡ Same-Day Service</span>
            <span class="bg-white/10 text-white text-sm px-3 py-1 rounded-full border border-white/20">🛡️ 30-Day Warranty</span>
          </div>
        </div>
      </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Services List -->
      <div class="lg:col-span-2 space-y-4">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
          {{ services.length }} Service{{ services.length !== 1 ? 's' : '' }} Available
        </h2>

        <div v-if="services.length" class="space-y-4">
          <div v-for="service in services" :key="service.id"
               class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-blue-300 hover:shadow-md transition-all group">
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 text-lg group-hover:text-blue-600 transition-colors">
                  {{ service.name }}
                </h3>
                <p v-if="service.description" class="text-gray-500 text-sm mt-1 leading-relaxed">
                  {{ service.description }}
                </p>
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
                <div v-if="service.discounted_price" class="text-xs text-gray-400 line-through">
                  ₹{{ service.base_price }}
                </div>
                <div class="text-2xl font-extrabold text-gray-900">
                  ₹{{ service.effective_price ?? service.base_price }}
                </div>
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
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">

        <!-- Quick Book CTA -->
        <div class="bg-blue-600 text-white rounded-2xl p-6">
          <h3 class="font-bold text-lg mb-1">Need Help Now?</h3>
          <p class="text-blue-200 text-sm mb-4">Book in 60 seconds. Same-day slots available.</p>
          <a :href="`/quick-booking?category=${category.slug}`"
             class="block text-center bg-white text-blue-700 font-bold py-3 rounded-xl hover:bg-blue-50 transition-colors">
            Book {{ category.name }} Service
          </a>
          <a :href="`tel:+919444900470`"
             class="block text-center mt-3 border border-white/40 text-white font-semibold py-2.5 rounded-xl hover:bg-white/10 transition-colors text-sm">
            📞 Call Us Instead
          </a>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-4">Why Choose Us?</h3>
          <ul class="space-y-3 text-sm text-gray-600">
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Background-verified certified technicians</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Transparent pricing — no surprises</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> 30-day service warranty on all repairs</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> Same-day & weekend appointments</li>
            <li class="flex items-start gap-2"><span class="text-green-500 mt-0.5">✓</span> All major brands serviced</li>
          </ul>
        </div>

        <!-- Other Categories -->
        <div v-if="otherCategories.length" class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-3">Other Services</h3>
          <ul class="space-y-2">
            <li v-for="cat in otherCategories" :key="cat.id">
              <a :href="`/services/${cat.slug}`"
                 class="flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-colors text-sm font-medium text-gray-700 group">
                <span class="flex items-center gap-2">
                  <span>{{ cat.icon || '🔧' }}</span>
                  {{ cat.name }}
                </span>
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
import { Head } from '@inertiajs/vue3'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'
import { ClockIcon, ShieldCheckIcon, WrenchScrewdriverIcon } from '@heroicons/vue/24/outline'

defineProps({
  category:        { type: Object, required: true },
  services:        { type: Array,  default: () => [] },
  otherCategories: { type: Array,  default: () => [] },
})
</script>