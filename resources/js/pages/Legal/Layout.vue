<template>
  <div class="min-h-screen bg-white">
    <Head>
      <title>{{ title }} | Chennai Smart Care</title>
      <meta name="description" :content="metaDesc" />
      <meta name="robots" content="index, follow" />
      <link rel="canonical" :href="`https://chennaismartcare.com${slug}`" />
    </Head>

    <AppHeader :minimal="true" />

    <!-- Page Header -->
    <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14">
        <nav class="flex items-center gap-2 text-blue-400 text-xs mb-6">
          <a href="/" class="hover:text-white transition-colors">Home</a>
          <span class="text-blue-700">/</span>
          <span class="text-blue-200">{{ title }}</span>
        </nav>
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl border border-white/10 flex-shrink-0">
            {{ icon }}
          </div>
          <div>
            <h1 class="text-3xl sm:text-4xl font-black leading-tight">{{ title }}</h1>
            <p class="text-blue-300 text-sm mt-1">Last updated: {{ lastUpdated }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 lg:py-16">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

        <!-- Sidebar TOC -->
        <aside class="hidden lg:block">
          <div class="sticky top-24">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Contents</p>
            <nav class="space-y-1">
              <a v-for="section in sections" :key="section.id"
                 :href="`#${section.id}`"
                 class="block text-sm text-gray-500 hover:text-blue-600 transition-all py-1 border-l-2 border-transparent hover:border-blue-400 pl-3 hover:pl-4">
                {{ section.title }}
              </a>
            </nav>
            <div class="mt-8 bg-blue-50 rounded-2xl p-4 border border-blue-100">
              <p class="text-xs font-bold text-blue-800 mb-2">Questions?</p>
              <p class="text-xs text-blue-600 leading-relaxed mb-3">Email us and we'll respond within 24 hours.</p>
              <a href="mailto:support@chennaismartcare.com"
                 class="block text-center text-xs bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Contact Support
              </a>
            </div>
          </div>
        </aside>

        <!-- Main content -->
        <main class="lg:col-span-3 prose prose-gray max-w-none
                     prose-headings:font-bold prose-headings:text-gray-900
                     prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-3 prose-h2:pb-3 prose-h2:border-b prose-h2:border-gray-100
                     prose-h3:text-base prose-h3:mt-6 prose-h3:mb-2
                     prose-p:text-gray-600 prose-p:leading-relaxed prose-p:text-sm
                     prose-li:text-gray-600 prose-li:leading-relaxed prose-li:text-sm
                     prose-strong:text-gray-800 prose-strong:font-semibold
                     prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline">
          <slot />
        </main>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

defineProps({
  title:       { type: String, required: true },
  slug:        { type: String, required: true },
  icon:        { type: String, default: '📄' },
  metaDesc:    { type: String, default: '' },
  lastUpdated: { type: String, default: 'March 2025' },
  sections:    { type: Array,  default: () => [] },
})
</script>