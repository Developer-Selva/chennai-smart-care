<template>
  <BlogForm :categories="categories" />
</template>

<script setup>
import BlogForm from './Form.vue'
defineProps({ categories: { type: Array, default: () => [] } })
</script>