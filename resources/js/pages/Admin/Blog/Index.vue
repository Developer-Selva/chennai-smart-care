<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold text-gray-900">Blog Posts</h1>
          <p class="text-sm text-gray-500 mt-0.5">{{ posts.total }} posts total</p>
        </div>
        <a href="/admin/blog/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
          <PlusIcon class="w-4 h-4" /> New Post
        </a>
      </div>
    </template>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Title</th>
            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden md:table-cell">Category</th>
            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden lg:table-cell">Author</th>
            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden sm:table-cell">Views</th>
            <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Status</th>
            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden lg:table-cell">Published</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-if="!posts.data?.length">
            <td colspan="7" class="text-center py-14 text-gray-400">
              No blog posts yet.
              <a href="/admin/blog/create" class="text-blue-600 font-semibold ml-1">Write the first one</a>
            </td>
          </tr>
          <tr v-for="post in posts.data" :key="post.id"
              class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-4">
              <p class="font-medium text-gray-900 line-clamp-1 max-w-[280px]">{{ post.title }}</p>
              <p class="text-gray-400 text-xs mt-0.5 font-mono">/blog/{{ post.slug }}</p>
            </td>
            <td class="px-5 py-4 hidden md:table-cell">
              <span class="text-xs bg-blue-50 text-blue-700 font-semibold px-2.5 py-1 rounded-full">
                {{ post.category?.name ?? '—' }}
              </span>
            </td>
            <td class="px-5 py-4 hidden lg:table-cell text-gray-600">
              {{ post.author?.name ?? '—' }}
            </td>
            <td class="px-5 py-4 hidden sm:table-cell text-gray-600">
              {{ post.view_count ?? 0 }}
            </td>
            <td class="px-5 py-4">
              <span :class="[
                'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                post.status === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700',
              ]">
                {{ post.status === 'published' ? '✓ Published' : '✎ Draft' }}
              </span>
            </td>
            <td class="px-5 py-4 hidden lg:table-cell text-gray-500 text-xs">
              {{ post.published_at ? formatDate(post.published_at) : '—' }}
            </td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-3">
                <a v-if="post.status === 'published'"
                   :href="`/blog/${post.slug}`" target="_blank"
                   class="text-gray-400 hover:text-gray-600 text-xs">
                  View ↗
                </a>
                <a :href="`/admin/blog/${post.id}/edit`"
                   class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                  Edit →
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="posts.last_page > 1"
           class="border-t border-gray-100 px-5 py-4 flex items-center justify-between text-sm">
        <p class="text-gray-500">
          Showing {{ posts.from }}–{{ posts.to }} of {{ posts.total }} posts
        </p>
        <div class="flex gap-2">
          <a v-if="posts.prev_page_url" :href="posts.prev_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">← Prev</a>
          <a v-if="posts.next_page_url" :href="posts.next_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Next →</a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

defineProps({
  posts: { type: Object, required: true },
})

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>