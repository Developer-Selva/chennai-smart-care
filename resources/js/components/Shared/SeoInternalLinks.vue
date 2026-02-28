<template>
  <!-- ═══════════════════════════════════════════════════════════════
       SeoInternalLinks — Internal Linking Widget
       Placed at bottom of blog posts. Links back to pillar service
       pages with exact-match anchor text.
       Google uses this to understand page relationships + authority.
  ═══════════════════════════════════════════════════════════════ -->
  <section class="mt-10 border-t border-gray-100 pt-8 space-y-6">

    <!-- Pillar Service Pages — exact-match anchor text for target keywords -->
    <div v-if="serviceCategories.length" class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-5">
      <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
        <WrenchScrewdriverIcon class="w-4 h-4 text-blue-600" />
        Expert Appliance Repair Services in Chennai
      </h3>
      <div class="flex flex-wrap gap-2">
        <a v-for="cat in serviceCategories" :key="cat.slug"
           :href="`/services/${cat.slug}`"
           :title="`${cat.name} repair in Chennai — Chennai Smart Care`"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold transition-all"
           :class="cat.slug === primarySlug
             ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
             : 'bg-white border border-blue-200 text-blue-700 hover:bg-blue-600 hover:text-white hover:border-blue-600'">
          <span>{{ cat.icon || '🔧' }}</span>
          {{ cat.name }} Repair in Chennai
        </a>
      </div>
    </div>

    <!-- Area-specific links for the primary appliance (exact-match local keywords) -->
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
      <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
        <MapPinIcon class="w-4 h-4 text-blue-600" />
        {{ primaryCategoryName }} Repair — All Chennai Areas
      </h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        <a v-for="area in allAreas" :key="area.slug"
           :href="`/services/${primarySlug}/${area.slug}`"
           :title="`${primaryCategoryName} repair in ${area.name}, Chennai`"
           class="text-xs text-center py-1.5 px-2 bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 text-gray-600 hover:text-blue-700 rounded-lg font-medium transition-colors">
          {{ area.name }}
        </a>
      </div>
      <!-- Anchor-text rich summary paragraph — crawled by Google -->
      <p class="mt-4 text-xs text-gray-400 leading-relaxed">
        Chennai Smart Care provides expert
        <a :href="`/services/${primarySlug}`"
           :title="`${primaryCategoryName} repair in Chennai`"
           class="text-blue-600 hover:underline font-medium">{{ primaryCategoryName }} repair in Chennai</a>
        across all neighbourhoods including
        <span v-for="(area, i) in topAreas" :key="area.slug">
          <a :href="`/services/${primarySlug}/${area.slug}`"
             class="text-blue-600 hover:underline">{{ area.name }}</a>{{ i < topAreas.length - 1 ? ', ' : '' }}
        </span>.
        Same-day service, certified technicians, 6-month warranty.
      </p>
    </div>

    <!-- Cross-service links (don't put all eggs in one basket) -->
    <div v-if="crossServices.length" class="flex flex-wrap gap-2">
      <span class="text-xs text-gray-400 self-center">Also:</span>
      <a v-for="cat in crossServices" :key="cat.slug"
         :href="`/services/${cat.slug}`"
         class="text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 rounded-full font-medium transition-colors">
        {{ cat.name }} Repair Chennai →
      </a>
      <a href="/quick-booking"
         class="text-xs px-3 py-1.5 bg-green-600 text-white rounded-full font-semibold hover:bg-green-700 transition-colors">
        📅 Book Service Now
      </a>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { WrenchScrewdriverIcon, MapPinIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  serviceCategories: { type: Array,  default: () => [] },
  primarySlug:       { type: String, default: 'ac' },
})

// All areas for the area grid
const allAreas = [
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
  { name: 'Perungudi',      slug: 'perungudi' },
]

// Top 8 areas for the anchor-text paragraph (keeps it natural)
const topAreas = allAreas.slice(0, 8)

const primaryCategoryName = computed(() => {
  const cat = props.serviceCategories.find(c => c.slug === props.primarySlug)
  if (cat) return cat.name
  const map = { 'ac': 'AC', 'refrigerator': 'Refrigerator', 'washing-machine': 'Washing Machine', 'microwave-oven': 'Microwave Oven' }
  return map[props.primarySlug] ?? 'Appliance'
})

// Cross-service links (other categories — not the primary one)
const crossServices = computed(() =>
  props.serviceCategories.filter(c => c.slug !== props.primarySlug)
)
</script>