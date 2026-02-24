<template>
  <div class="min-h-screen bg-gray-50">

    <Head>
      <title>Appliance Repair Tips & Guides — Chennai Smart Care Blog</title>
      <meta name="description" content="Expert tips on AC, refrigerator and washing machine maintenance and repair in Chennai." />
    </Head>

    <AppHeader :minimal="true" />

    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-900 to-indigo-900 text-white py-14">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-4xl font-extrabold">Appliance Care & Repair Tips</h1>
        <p class="mt-3 text-blue-200 text-lg">Expert guides to keep your appliances running longer</p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

      <!-- Category tabs -->
      <div v-if="categories?.length" class="flex flex-wrap gap-2 mb-8">
        <a href="/blog"
           :class="['px-4 py-2 rounded-full text-sm font-semibold transition-colors',
             !currentCategory ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-blue-300']">
          All
        </a>
        <a v-for="cat in categories" :key="cat.id"
           :href="`/blog/category/${cat.slug}`"
           :class="['px-4 py-2 rounded-full text-sm font-semibold transition-colors',
             currentCategory === cat.slug ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-blue-300']">
          {{ cat.name }}
        </a>
      </div>

      <!-- Posts grid -->
      <div v-if="posts.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <BlogCard v-for="post in posts.data" :key="post.id" :post="post" />
      </div>
      <div v-else class="text-center py-16 text-gray-400">No articles yet. Check back soon!</div>

      <!-- Pagination -->
      <div v-if="posts.last_page > 1" class="mt-10 flex justify-center gap-3">
        <a v-if="posts.prev_page_url" :href="posts.prev_page_url"
           class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 hover:bg-white transition-colors">← Previous</a>
        <span class="px-5 py-2.5 text-sm text-gray-500">
          Page {{ posts.current_page }} of {{ posts.last_page }}
        </span>
        <a v-if="posts.next_page_url" :href="posts.next_page_url"
           class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 hover:bg-white transition-colors">Next →</a>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { Head }  from '@inertiajs/vue3'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'
import BlogCard  from '@/components/Landing/BlogCard.vue'

defineProps({
  posts:           { type: Object, required: true },
  categories:      { type: Array,  default: () => [] },
  currentCategory: { type: String, default: null },
})
</script>