<template>
  <ServiceForm :service="service" :categories="categories" />
</template>

<script setup>
import ServiceForm from './Form.vue'

defineProps({
  service:    { type: Object, required: true },
  categories: { type: Array,  default: () => [] },
})
</script>