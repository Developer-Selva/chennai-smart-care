<template>
  <!-- MetricCard.vue -->
  <div :class="['bg-white rounded-2xl border p-5 flex flex-col gap-3', alert ? 'border-red-200' : 'border-gray-200']">
    <div class="flex items-center justify-between">
      <p class="text-sm font-medium text-gray-500">{{ title }}</p>
      <div :class="`w-9 h-9 rounded-xl flex items-center justify-center ${iconBg}`">
        <component :is="iconComponent" :class="`w-5 h-5 ${iconColor}`" />
      </div>
    </div>

    <div>
      <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">{{ value }}</p>
      <div v-if="trend" class="flex items-center gap-1 mt-1">
        <span :class="['text-xs font-semibold', trend.positive ? 'text-green-600' : 'text-red-500']">
          {{ trend.positive ? '↑' : '↓' }} {{ Math.abs(trend.value) }}%
        </span>
        <span class="text-xs text-gray-400">vs last month</span>
      </div>
      <p v-else-if="subtitle" class="text-xs text-gray-400 mt-1">{{ subtitle }}</p>
    </div>

    <div v-if="alert" class="flex items-center gap-1.5 text-xs text-red-600 font-medium">
      <ExclamationTriangleIcon class="w-3.5 h-3.5" />
      Needs attention
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  CalendarIcon, ClockIcon, CurrencyRupeeIcon,
  UsersIcon, ExclamationTriangleIcon,
  ChartBarIcon, WrenchScrewdriverIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  title:    { type: String, required: true },
  value:    { type: [String, Number], required: true },
  subtitle: { type: String,  default: null },
  trend:    { type: Object,  default: null },
  icon:     { type: String,  default: 'CalendarIcon' },
  color:    { type: String,  default: 'blue' },
  alert:    { type: Boolean, default: false },
})

const icons = { CalendarIcon, ClockIcon, CurrencyRupeeIcon, UsersIcon, ChartBarIcon, WrenchScrewdriverIcon }
const iconComponent = computed(() => icons[props.icon] ?? CalendarIcon)

const colorMap = {
  blue:   { bg: 'bg-blue-100',   text: 'text-blue-600'   },
  yellow: { bg: 'bg-yellow-100', text: 'text-yellow-600' },
  green:  { bg: 'bg-green-100',  text: 'text-green-600'  },
  purple: { bg: 'bg-purple-100', text: 'text-purple-600' },
  red:    { bg: 'bg-red-100',    text: 'text-red-600'    },
}

const iconBg    = computed(() => colorMap[props.color]?.bg   ?? 'bg-gray-100')
const iconColor = computed(() => colorMap[props.color]?.text ?? 'text-gray-600')
</script>