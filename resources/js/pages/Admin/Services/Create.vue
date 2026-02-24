<template>
  <ServiceForm :categories="categories" />
</template>

<script setup>
import ServiceForm from './Form.vue'

defineProps({
  categories: { type: Array, default: () => [] },
})
</script>