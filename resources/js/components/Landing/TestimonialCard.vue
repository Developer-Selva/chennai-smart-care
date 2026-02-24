<template>
  <div 
    class="group relative bg-white rounded-2xl border border-gray-100 p-6 transition-all duration-500 hover:shadow-xl hover:-translate-y-1 flex flex-col h-full"
    :class="[isActive ? 'ring-2 ring-blue-500 ring-offset-2' : '']"
  >
    <!-- Quote icon decoration -->
    <div class="absolute top-4 right-4 opacity-5 group-hover:opacity-10 transition-opacity">
      <svg class="w-12 h-12 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
      </svg>
    </div>

    <!-- Stars with animation -->
    <div class="flex gap-1 mb-4 relative">
      <div v-for="n in 5" :key="n" class="relative">
        <StarIcon 
          :class="[
            'w-5 h-5 transition-all duration-300',
            n <= review.rating 
              ? 'text-yellow-400 fill-current drop-shadow-md' 
              : 'text-gray-200 fill-current'
          ]" 
        />
        <!-- Animated sparkle for top ratings -->
        <div v-if="n <= review.rating && review.rating >= 4" 
             class="absolute inset-0 animate-ping-slow">
          <StarIcon class="w-5 h-5 text-yellow-300 fill-current opacity-50" />
        </div>
      </div>
      
      <!-- Rating badge -->
      <span class="ml-auto text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
        {{ review.rating }}.0
      </span>
    </div>

    <!-- Comment with gradient fade -->
    <div class="relative flex-1 mb-4">
      <p class="text-gray-700 text-sm leading-relaxed italic">
        "{{ review.comment }}"
      </p>
      <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
    </div>

    <!-- Service tag with icon -->
    <div v-if="serviceName" class="mb-4">
      <div class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100">
        <WrenchIcon class="w-3.5 h-3.5" />
        {{ serviceName }}
      </div>
    </div>

    <!-- Author with verified badge -->
    <div class="flex items-center gap-3 pt-4 border-t border-gray-100 mt-auto">
      <div 
        :class="[
          'w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-md transition-transform group-hover:scale-110',
          avatarColor
        ]"
      >
        {{ initials }}
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-1.5">
          <p class="font-semibold text-gray-900 text-sm truncate">{{ authorName }}</p>
          <CheckBadgeIcon v-if="review.is_verified" class="w-4 h-4 text-blue-500 flex-shrink-0" />
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500">
          <span>{{ formatDate(review.created_at) }}</span>
          <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
          <span>Verified Customer</span>
        </div>
      </div>
    </div>

    <!-- Hover effect overlay -->
    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-600/0 via-blue-600/0 to-blue-600/0 group-hover:from-blue-600/5 group-hover:via-transparent pointer-events-none transition-all duration-700"></div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { StarIcon, WrenchScrewdriverIcon as WrenchIcon, CheckBadgeIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  review: { type: Object, required: true },
  isActive: { type: Boolean, default: false }
})

const colors = [
  'bg-gradient-to-br from-blue-500 to-blue-600',
  'bg-gradient-to-br from-purple-500 to-purple-600',
  'bg-gradient-to-br from-green-500 to-green-600',
  'bg-gradient-to-br from-indigo-500 to-indigo-600',
  'bg-gradient-to-br from-pink-500 to-pink-600',
  'bg-gradient-to-br from-teal-500 to-teal-600',
]

const authorName = computed(() => {
  return props.review.user?.name ?? 'Verified Customer'
})

const initials = computed(() => {
  const name = authorName.value
  if (name === 'Verified Customer') return 'VC'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const avatarColor = computed(() => {
  const i = (props.review.id ?? 0) % colors.length
  return colors[i]
})

const serviceName = computed(() =>
  props.review.booking?.items?.[0]?.service_name ?? null
)

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  const now = new Date()
  const diffTime = Math.abs(now - date)
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffDays < 7) {
    return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`
  } else if (diffDays < 30) {
    const weeks = Math.floor(diffDays / 7)
    return `${weeks} week${weeks > 1 ? 's' : ''} ago`
  } else {
    return date.toLocaleDateString('en-IN', { month: 'short', year: 'numeric' })
  }
}
</script>