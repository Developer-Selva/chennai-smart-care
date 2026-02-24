<template>
  <!-- FormField.vue -->
  <div :class="wrapperClass">
    <label v-if="label" class="block text-sm font-semibold text-gray-700 mb-1.5">
      {{ label }}
    </label>
    <slot />
    <p v-if="error" class="mt-1.5 text-red-600 text-xs flex items-center gap-1">
      <ExclamationCircleIcon class="w-3.5 h-3.5 flex-shrink-0" />
      {{ error }}
    </p>
    <p v-if="hint && !error" class="mt-1.5 text-gray-400 text-xs">{{ hint }}</p>
  </div>
</template>

<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/24/outline'

defineProps({
  label:        { type: String,  default: null },
  error:        { type: String,  default: null },
  hint:         { type: String,  default: null },
  wrapperClass: { type: String,  default: '' },
})
</script>