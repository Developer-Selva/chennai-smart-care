<template>
  <a :href="`/blog/${post.slug}`"
     class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col no-underline">

    <!-- Featured Image -->
    <div class="aspect-[16/9] bg-gradient-to-br from-blue-100 to-indigo-100 overflow-hidden flex-shrink-0">
      <img v-if="post.featured_image"
           :src="post.featured_image"
           :alt="post.alt_text ?? post.title"
           class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
           loading="lazy" />
      <div v-else class="w-full h-full flex items-center justify-center text-4xl">
        {{ categoryIcon }}
      </div>
    </div>

    <div class="p-5 flex flex-col flex-1">
      <!-- Category badge -->
      <span v-if="post.category"
            class="inline-flex items-center text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full w-fit mb-3">
        {{ post.category.name }}
      </span>

      <!-- Title -->
      <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-blue-600 transition-colors line-clamp-2 mb-2 flex-1">
        {{ post.title }}
      </h3>

      <!-- Excerpt -->
      <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-4">
        {{ post.excerpt }}
      </p>

      <!-- Meta -->
      <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-100">
        <span class="flex items-center gap-1">
          <CalendarIcon class="w-3.5 h-3.5" />
          {{ formatDate(post.published_at) }}
        </span>
        <span class="flex items-center gap-1">
          <ClockIcon class="w-3.5 h-3.5" />
          {{ post.read_time_minutes }} min read
        </span>
      </div>
    </div>
  </a>
</template>

<script setup>
import { computed } from 'vue'
import { CalendarIcon, ClockIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  post: { type: Object, required: true },
})

const iconMap = {
  'ac-repair-tips':       '❄️',
  'refrigerator-care':    '🧊',
  'washing-machine-guide':'👕',
}

const categoryIcon = computed(() => iconMap[props.post.category?.slug] ?? '🔧')

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('en-IN', {
    day: 'numeric', month: 'short', year: 'numeric',
  })
}
</script>