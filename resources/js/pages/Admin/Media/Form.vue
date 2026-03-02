<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <a href="/admin/blog" class="text-gray-400 hover:text-gray-600 transition-colors">
          <ChevronLeftIcon class="w-5 h-5" />
        </a>
        <div>
          <h1 class="text-xl font-bold text-gray-900">{{ isEdit ? 'Edit Post' : 'New Blog Post' }}</h1>
          <p v-if="isEdit" class="text-sm text-gray-400 font-mono mt-0.5">/blog/{{ post.slug }}</p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 items-start">

      <!-- Left: Main content -->
      <div class="space-y-5">

        <!-- Title -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
          <div>
            <label class="label">Post Title *</label>
            <input v-model="form.title" type="text" class="input text-lg font-semibold"
                   placeholder="e.g. Why Is My AC Not Cooling? 7 Common Reasons"
                   @input="autoSlug" />
            <p v-if="form.errors.title" class="err">{{ form.errors.title }}</p>
          </div>

          <div>
            <label class="label">Slug</label>
            <div class="flex items-center gap-2">
              <span class="text-gray-400 text-sm flex-shrink-0">/blog/</span>
              <input v-model="form.slug" type="text" class="input flex-1" placeholder="why-is-my-ac-not-cooling" />
            </div>
            <p v-if="form.errors.slug" class="err">{{ form.errors.slug }}</p>
          </div>

          <div>
            <label class="label">Excerpt / Summary *</label>
            <textarea v-model="form.excerpt" rows="2" class="input resize-none"
                      placeholder="A brief 1–2 sentence summary of this post..."></textarea>
            <p class="text-xs text-gray-400 mt-1">{{ form.excerpt?.length ?? 0 }}/300 chars</p>
            <p v-if="form.errors.excerpt" class="err">{{ form.errors.excerpt }}</p>
          </div>
        </div>

        <!-- Content editor -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <label class="label">Content *</label>
          <textarea v-model="form.content" rows="20" class="input resize-y font-mono text-xs leading-relaxed"
                    placeholder="Write your blog post content here. HTML is supported."></textarea>
          <p class="text-xs text-gray-400 mt-2">HTML is supported. Use &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt; etc.</p>
          <p v-if="form.errors.content" class="err">{{ form.errors.content }}</p>
        </div>

        <!-- SEO -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
          <h2 class="font-semibold text-gray-900 flex items-center gap-2">
            🔍 SEO Settings
          </h2>
          <div>
            <label class="label">Focus Keyword</label>
            <input v-model="form.focus_keyword" type="text" class="input"
                   placeholder="e.g. AC not cooling Chennai" />
          </div>
          <div>
            <label class="label">Meta Title</label>
            <input v-model="form.meta_title" type="text" class="input"
                   :placeholder="form.title || 'SEO title...'" />
            <p :class="['text-xs mt-1', (form.meta_title?.length ?? 0) > 160 ? 'text-red-500' : 'text-gray-400']">
              {{ form.meta_title?.length ?? 0 }}/160 chars
            </p>
          </div>
          <div>
            <label class="label">Meta Description</label>
            <textarea v-model="form.meta_description" rows="2" class="input resize-none"
                      :placeholder="form.excerpt || 'SEO description...'"></textarea>
            <p :class="['text-xs mt-1', (form.meta_description?.length ?? 0) > 320 ? 'text-red-500' : 'text-gray-400']">
              {{ form.meta_description?.length ?? 0 }}/320 chars
            </p>
          </div>
          <div>
            <label class="label">Tags (comma-separated)</label>
            <input v-model="tagsInput" type="text" class="input"
                   placeholder="ac repair, chennai, cooling problem" />
          </div>
        </div>
      </div>

      <!-- Right: Sidebar settings -->
      <div class="space-y-5 lg:sticky lg:top-20">

        <!-- Publish -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
          <h2 class="font-semibold text-gray-900">Publish</h2>

          <div>
            <label class="label">Status</label>
            <select v-model="form.status" class="input bg-white">
              <option value="draft">✎ Draft</option>
              <option value="published">✓ Published</option>
            </select>
          </div>

          <div>
            <label class="label">Publish Date</label>
            <input v-model="form.published_at" type="datetime-local" class="input" />
          </div>

          <label class="flex items-center gap-3 cursor-pointer">
            <input v-model="form.is_indexed" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
            <span class="text-sm text-gray-700">Allow search engine indexing</span>
          </label>

          <div class="flex gap-3 pt-2">
            <button type="submit" :disabled="form.processing"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold text-sm transition-colors disabled:opacity-60">
              {{ form.processing ? 'Saving...' : (isEdit ? 'Update Post' : 'Publish Post') }}
            </button>
          </div>
          <a href="/admin/blog"
             class="block text-center text-sm text-gray-400 hover:text-gray-600 transition-colors">
            Cancel
          </a>
        </div>

        <!-- Category & Image -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4">
          <h2 class="font-semibold text-gray-900">Details</h2>

          <div>
            <label class="label">Category *</label>
            <select v-model="form.blog_category_id" class="input bg-white">
              <option value="">Select category...</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <p v-if="form.errors.blog_category_id" class="err">{{ form.errors.blog_category_id }}</p>
          </div>

          <div>
            <label class="label">Featured Image</label>
            <ImageUpload
              v-model="form.featured_image"
              v-model:alt-text="form.alt_text"
              collection="blog"
              @media-selected="onMediaSelected"
            />
            <p v-if="form.errors.featured_image" class="err">{{ form.errors.featured_image }}</p>
          </div>

          <!-- Manual URL fallback -->
          <div>
            <details class="group">
              <summary class="label cursor-pointer flex items-center gap-1.5 list-none select-none">
                <LinkIcon class="w-3.5 h-3.5 text-gray-400" />
                Or set image URL manually
                <span class="text-xs text-gray-400 font-normal group-open:hidden">(expand)</span>
              </summary>
              <input v-model="form.featured_image" type="url" class="input mt-2"
                     placeholder="https://example.com/image.jpg" />
            </details>
          </div>
        </div>

        <!-- Delete (edit only) -->
        <div v-if="isEdit" class="bg-red-50 rounded-2xl border border-red-200 p-5">
          <p class="text-sm font-semibold text-red-700 mb-3">Danger Zone</p>
          <button type="button" @click="deletePost"
                  class="w-full border border-red-300 text-red-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-100 transition-colors">
            Delete This Post
          </button>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { ChevronLeftIcon, LinkIcon } from '@heroicons/vue/24/outline'
import AdminLayout   from '@/components/Admin/AdminLayout.vue'
import ImageUpload   from '@/components/Admin/ImageUpload.vue'

const props = defineProps({
  post:       { type: Object, default: null },
  categories: { type: Array,  default: () => [] },
})

const isEdit = !!props.post

const form = useForm({
  blog_category_id: props.post?.blog_category_id ?? '',
  title:            props.post?.title ?? '',
  slug:             props.post?.slug  ?? '',
  excerpt:          props.post?.excerpt ?? '',
  content:          props.post?.content ?? '',
  featured_image:   props.post?.featured_image ?? '',
  alt_text:         props.post?.alt_text ?? '',
  status:           props.post?.status ?? 'draft',
  published_at:     props.post?.published_at ? props.post.published_at.slice(0, 16) : '',
  meta_title:       props.post?.meta_title ?? '',
  meta_description: props.post?.meta_description ?? '',
  focus_keyword:    props.post?.focus_keyword ?? '',
  is_indexed:       props.post?.is_indexed ?? true,
  tags:             props.post?.tags ?? [],
})

// Tags as comma string for easy editing
const tagsInput = computed({
  get: () => form.tags.join(', '),
  set: (val) => { form.tags = val.split(',').map(t => t.trim()).filter(Boolean) },
})

// When a media item is selected from the library or freshly uploaded,
// also populate og_image if it's currently empty (useful for social sharing).
function onMediaSelected(media) {
  if (!form.meta_title) form.meta_title = form.title
  // Optionally auto-fill OG image too
}

function autoSlug() {
  if (!isEdit) {
    form.slug = form.title
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .trim()
      .replace(/\s+/g, '-')
      .slice(0, 100)
  }
}

function submit() {
  if (isEdit) form.put(`/admin/blog/${props.post.id}`)
  else        form.post('/admin/blog')
}

function deletePost() {
  if (!confirm('Are you sure you want to delete this post? This cannot be undone.')) return
  router.delete(`/admin/blog/${props.post.id}`)
}
</script>

<style scoped>
.label { @apply block text-sm font-semibold text-gray-700 mb-1.5; }
.input { @apply w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow; }
.err   { @apply mt-1 text-red-500 text-xs; }
</style>