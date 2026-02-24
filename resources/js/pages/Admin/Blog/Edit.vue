<template>
  <BlogForm :post="post" :categories="categories" />
</template>

<script setup>
import BlogForm from './Form.vue'
defineProps({
  post:       { type: Object, required: true },
  categories: { type: Array,  default: () => [] },
})
</script>