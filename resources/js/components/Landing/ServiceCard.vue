<template>
  <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group flex flex-col">
    <!-- Top colour bar -->
    <div :class="`h-1.5 bg-gradient-to-r ${gradient}`"></div>

    <div class="p-6 flex flex-col flex-1">
      <!-- Icon + badge -->
      <div class="flex items-start justify-between mb-4">
        <div :class="`w-14 h-14 ${iconBg} rounded-2xl flex items-center justify-center text-2xl shadow-sm`">
          {{ category.icon || '🔧' }}
        </div>
        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
          {{ category.services?.length || 0 }} services
        </span>
      </div>

      <!-- Title & description -->
      <h3 class="text-xl font-bold text-gray-900 mb-2">{{ category.name }}</h3>
      <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ category.description }}</p>

      <!-- Service list -->
      <ul class="space-y-2 mb-6 flex-1">
        <li v-for="service in category.services?.slice(0, 4)" :key="service.id"
            class="flex items-center justify-between text-sm">
          <span class="text-gray-600 flex items-center gap-2">
            <span :class="`w-1.5 h-1.5 rounded-full bg-gradient-to-r ${gradient}`"></span>
            {{ service.name }}
          </span>
          <span class="font-semibold text-gray-900 flex-shrink-0 ml-2">
            ₹{{ service.effective_price ?? service.base_price }}
          </span>
        </li>
      </ul>

      <!-- Actions -->
      <div class="flex gap-3 mt-auto">
        <button
          @click="$emit('book', category)"
          :class="`flex-1 py-3 rounded-xl font-semibold text-sm text-white transition-colors bg-gradient-to-r ${gradient} hover:opacity-90 shadow-sm`"
        >
          Book Now
        </button>
        <Link :href="`/services/${category.slug}`"
          class="px-4 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-colors flex-shrink-0">
          Details
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  category: { type: Object, required: true },
})

defineEmits(['book'])

const gradientMap = {
  ac:              'from-blue-500 to-cyan-500',
  refrigerator:    'from-teal-500 to-green-500',
  'washing-machine': 'from-purple-500 to-indigo-500',
}

const iconBgMap = {
  ac:              'bg-blue-100',
  refrigerator:    'bg-teal-100',
  'washing-machine': 'bg-purple-100',
}

const gradient = computed(() => gradientMap[props.category.slug] ?? 'from-gray-400 to-gray-500')
const iconBg   = computed(() => iconBgMap[props.category.slug]   ?? 'bg-gray-100')
</script>