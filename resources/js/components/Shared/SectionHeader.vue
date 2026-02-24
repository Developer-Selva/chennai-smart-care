<template>
  <div :class="['text-center', centerClass]">
    <span v-if="badge"
          class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wide mb-4">
      <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
      {{ badge }}
    </span>
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight"
        v-html="formattedTitle">
    </h2>
    <p v-if="subtitle"
       class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">
      {{ subtitle }}
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  badge:    { type: String,  default: null },
  title:    { type: String,  required: true },
  subtitle: { type: String,  default: null },
  center:   { type: Boolean, default: true },
})

const centerClass    = computed(() => props.center ? 'text-center' : 'text-left')
const formattedTitle = computed(() => props.title.replace(/\\n/g, '<br />'))
</script>