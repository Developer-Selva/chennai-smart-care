<template>
  <div class="min-h-screen bg-white">

    <Head>
      <title>{{ post.meta_title ?? post.title }}</title>
      <meta name="description" :content="post.meta_description ?? post.excerpt" />
      <meta name="keywords" :content="post.focus_keyword" />
      <link rel="canonical" :href="`${appUrl}/blog/${post.slug}`" />
      <meta property="og:title"       :content="post.meta_title ?? post.title" />
      <meta property="og:description" :content="post.meta_description ?? post.excerpt" />
      <meta property="og:image"       :content="post.featured_image" />
      <meta property="og:type"        content="article" />
    </Head>

    <AppHeader :minimal="true" />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-gray-400 mb-8">
        <a href="/" class="hover:text-gray-600 transition-colors">Home</a>
        <span>/</span>
        <a href="/blog" class="hover:text-gray-600 transition-colors">Blog</a>
        <span>/</span>
        <a v-if="post.category" :href="`/blog/category/${post.category.slug}`"
           class="hover:text-gray-600 transition-colors">{{ post.category.name }}</a>
        <span v-if="post.category">/</span>
        <span class="text-gray-600 truncate max-w-[200px]">{{ post.title }}</span>
      </nav>

      <!-- Article Header -->
      <header class="mb-8">
        <span v-if="post.category"
              class="inline-flex items-center text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full mb-4">
          {{ post.category.name }}
        </span>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight tracking-tight">
          {{ post.title }}
        </h1>

        <p class="mt-4 text-xl text-gray-500 leading-relaxed">{{ post.excerpt }}</p>

        <div class="flex flex-wrap items-center gap-4 mt-6 text-sm text-gray-400">
          <span class="flex items-center gap-1.5">
            <CalendarIcon class="w-4 h-4" />
            {{ formatDate(post.published_at) }}
          </span>
          <span class="flex items-center gap-1.5">
            <ClockIcon class="w-4 h-4" />
            {{ post.read_time_minutes }} min read
          </span>
          <span class="flex items-center gap-1.5">
            <EyeIcon class="w-4 h-4" />
            {{ post.view_count ?? 0 }} views
          </span>
        </div>
      </header>

      <!-- Featured Image -->
      <div v-if="post.featured_image"
           class="rounded-2xl overflow-hidden mb-10 aspect-[16/9] bg-gray-100">
        <img :src="post.featured_image" :alt="post.alt_text ?? post.title"
             class="w-full h-full object-cover" />
      </div>

      <!-- Content + Sidebar -->
      <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10">

        <!-- Article Content -->
        <article>
          <div class="prose prose-lg prose-gray max-w-none
                      prose-headings:font-bold prose-headings:text-gray-900
                      prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                      prose-img:rounded-xl prose-img:shadow-md"
               v-html="post.content">
          </div>

          <!-- Tags -->
          <div v-if="post.tags?.length" class="mt-8 flex flex-wrap gap-2">
            <span v-for="tag in post.tags" :key="tag"
                  class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium">
              #{{ tag }}
            </span>
          </div>

          <!-- CTA Box -->
          <div class="mt-10 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white">
            <h3 class="text-xl font-bold mb-2">Need an Expert to Fix Your Appliance?</h3>
            <p class="text-blue-100 text-sm mb-4">
              Our certified technicians are available 9AM–9PM across Chennai.
              Same-day service available.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
              <a href="/quick-booking"
                 class="inline-flex items-center justify-center bg-white text-blue-700 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition-colors">
                📅 Book Service Now
              </a>
              <a href="/free-consultation"
                 class="inline-flex items-center justify-center border-2 border-white/60 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-white/10 transition-colors">
                💬 Free Consultation
              </a>
            </div>
          </div>
        </article>

        <!-- Sidebar -->
        <aside class="space-y-6">

          <!-- Related Posts -->
          <div v-if="relatedPosts?.length" class="bg-gray-50 rounded-2xl p-5">
            <h3 class="font-bold text-gray-900 mb-4">Related Articles</h3>
            <div class="space-y-4">
              <a v-for="related in relatedPosts" :key="related.id"
                 :href="`/blog/${related.slug}`"
                 class="flex gap-3 group">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex-shrink-0 overflow-hidden">
                  <img v-if="related.featured_image" :src="related.featured_image"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" />
                  <div v-else class="w-full h-full flex items-center justify-center text-2xl">🔧</div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                    {{ related.title }}
                  </p>
                  <p class="text-xs text-gray-400 mt-1">{{ related.read_time_minutes }} min read</p>
                </div>
              </a>
            </div>
          </div>

          <!-- Sticky CTA -->
          <div class="bg-blue-600 rounded-2xl p-5 text-white sticky top-20">
            <p class="font-bold text-lg mb-1">Book a Repair</p>
            <p class="text-blue-100 text-xs mb-4">Expert service, 30-day warranty, transparent pricing.</p>
            <a href="/quick-booking"
               class="block w-full text-center bg-white text-blue-700 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-50 transition-colors">
              Book Now →
            </a>
            <a href="tel:+919876543210"
               class="block w-full text-center border border-white/40 text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-white/10 transition-colors mt-2">
              📞 Call Us
            </a>
          </div>
        </aside>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { Head }      from '@inertiajs/vue3'
import axios         from 'axios'
import { CalendarIcon, ClockIcon, EyeIcon } from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const props = defineProps({
  post:         { type: Object, required: true },
  relatedPosts: { type: Array,  default: () => [] },
})

const appUrl = window.location.origin

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' })
}

onMounted(() => {
  // Track view
  axios.post(`/api/v1/blog/${props.post.slug}/view`).catch(() => {})

  // GTM article view
  if (window.dataLayer) {
    window.dataLayer.push({
      event:          'article_view',
      article_title:  props.post.title,
      article_slug:   props.post.slug,
      article_category: props.post.category?.name,
    })
  }
})
</script>