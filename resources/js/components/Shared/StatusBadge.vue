<template>
  <span :class="['inline-flex items-center gap-1.5 font-semibold rounded-full', sizeClasses, colorClasses]">
    <span :class="['rounded-full flex-shrink-0', dotSize, dotColor]"></span>
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true },
  size:   { type: String, default: 'sm' }, // 'sm' | 'md' | 'lg'
})

const map = {
  pending:     { label: 'Pending',     color: 'yellow' },
  confirmed:   { label: 'Confirmed',   color: 'blue'   },
  assigned:    { label: 'Assigned',    color: 'indigo' },
  in_progress: { label: 'In Progress', color: 'purple' },
  completed:   { label: 'Completed',   color: 'green'  },
  cancelled:   { label: 'Cancelled',   color: 'red'    },
  rescheduled: { label: 'Rescheduled', color: 'orange' },
  no_show:     { label: 'No Show',     color: 'gray'   },
}

const colorClasses = computed(() => {
  const c = map[props.status]?.color ?? 'gray'
  return {
    yellow: 'bg-yellow-100 text-yellow-800',
    blue:   'bg-blue-100 text-blue-800',
    indigo: 'bg-indigo-100 text-indigo-800',
    purple: 'bg-purple-100 text-purple-800',
    green:  'bg-green-100 text-green-800',
    red:    'bg-red-100 text-red-800',
    orange: 'bg-orange-100 text-orange-800',
    gray:   'bg-gray-100 text-gray-700',
  }[c]
})

const dotColor = computed(() => {
  const c = map[props.status]?.color ?? 'gray'
  return {
    yellow: 'bg-yellow-500', blue:   'bg-blue-500',
    indigo: 'bg-indigo-500', purple: 'bg-purple-500',
    green:  'bg-green-500',  red:    'bg-red-500',
    orange: 'bg-orange-500', gray:   'bg-gray-400',
  }[c]
})

const label = computed(() => map[props.status]?.label ?? props.status)

const sizeClasses = computed(() => ({
  sm: 'text-xs px-2.5 py-1',
  md: 'text-sm px-3 py-1.5',
  lg: 'text-sm px-4 py-2',
}[props.size]))

const dotSize = computed(() => ({
  sm: 'w-1.5 h-1.5', md: 'w-2 h-2', lg: 'w-2 h-2',
}[props.size]))
</script>