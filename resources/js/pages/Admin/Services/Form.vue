<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <a href="/admin/services" class="text-gray-400 hover:text-gray-600 transition-colors">
          <ChevronLeftIcon class="w-5 h-5" />
        </a>
        <div>
          <h1 class="text-xl font-bold text-gray-900">{{ isEdit ? 'Edit Service' : 'Add Service' }}</h1>
          <p class="text-sm text-gray-500 mt-0.5">{{ isEdit ? service.name : 'Create a new service' }}</p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="max-w-2xl space-y-6">

      <!-- Basic Info -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Basic Information</h2>

        <div>
          <label class="label">Category *</label>
          <select v-model="form.category_id" class="input bg-white">
            <option value="">Select category...</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <p v-if="form.errors.category_id" class="err">{{ form.errors.category_id }}</p>
        </div>

        <div>
          <label class="label">Service Name *</label>
          <input v-model="form.name" type="text" class="input" placeholder="e.g. AC Gas Refilling"
                 @input="autoSlug" />
          <p v-if="form.errors.name" class="err">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="label">Slug *</label>
          <input v-model="form.slug" type="text" class="input" placeholder="ac-gas-refilling" />
          <p class="text-xs text-gray-400 mt-1">Used in URLs. Auto-generated from name.</p>
          <p v-if="form.errors.slug" class="err">{{ form.errors.slug }}</p>
        </div>

        <div>
          <label class="label">Short Description</label>
          <textarea v-model="form.short_description" rows="2" class="input resize-none"
                    placeholder="Brief one-liner about this service..."></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="label">Duration Estimate</label>
            <input v-model="form.duration_estimate" type="text" class="input" placeholder="e.g. 1–2 hours" />
          </div>
          <div>
            <label class="label">Sort Order</label>
            <input v-model="form.sort_order" type="number" min="0" class="input" placeholder="0" />
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Pricing</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="label">Base Price (₹) *</label>
            <input v-model="form.base_price" type="number" min="0" step="0.01" class="input" placeholder="999" />
            <p v-if="form.errors.base_price" class="err">{{ form.errors.base_price }}</p>
          </div>
          <div>
            <label class="label">Discounted Price (₹)</label>
            <input v-model="form.discounted_price" type="number" min="0" step="0.01" class="input" placeholder="Optional" />
          </div>
        </div>
        <div v-if="form.base_price && form.discounted_price"
             class="text-sm text-green-600 font-medium">
          Saving: ₹{{ (form.base_price - form.discounted_price).toFixed(0) }}
          ({{ Math.round((1 - form.discounted_price / form.base_price) * 100) }}% off)
        </div>
      </div>

      <!-- Features -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="font-semibold text-gray-900">Features / Inclusions</h2>
          <button type="button" @click="addFeature"
                  class="text-blue-600 text-xs font-semibold hover:text-blue-800">+ Add Feature</button>
        </div>
        <div v-for="(feature, i) in form.features" :key="i" class="flex gap-2">
          <input v-model="form.features[i]" type="text" class="input flex-1"
                 :placeholder="`Feature ${i + 1}, e.g. Gas top-up included`" />
          <button type="button" @click="removeFeature(i)"
                  class="text-red-400 hover:text-red-600 px-2 transition-colors">✕</button>
        </div>
        <p v-if="!form.features.length" class="text-sm text-gray-400">No features added yet.</p>
      </div>

      <!-- SEO -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">SEO</h2>
        <div>
          <label class="label">Meta Title</label>
          <input v-model="form.meta_title" type="text" class="input"
                 placeholder="AC Gas Refilling in Chennai — Chennai Smart Care" />
          <p class="text-xs text-gray-400 mt-1">{{ form.meta_title?.length ?? 0 }}/160 chars</p>
        </div>
        <div>
          <label class="label">Meta Description</label>
          <textarea v-model="form.meta_description" rows="2" class="input resize-none"
                    placeholder="Professional AC gas refilling service in Chennai..."></textarea>
          <p class="text-xs text-gray-400 mt-1">{{ form.meta_description?.length ?? 0 }}/320 chars</p>
        </div>
      </div>

      <!-- Visibility -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Visibility</h2>
        <div class="space-y-3">
          <label class="flex items-center gap-3 cursor-pointer">
            <input v-model="form.is_active" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
            <div>
              <p class="text-sm font-medium text-gray-900">Active</p>
              <p class="text-xs text-gray-400">Visible to customers on the website</p>
            </div>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input v-model="form.is_featured" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
            <div>
              <p class="text-sm font-medium text-gray-900">Featured</p>
              <p class="text-xs text-gray-400">Highlighted on the homepage</p>
            </div>
          </label>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex gap-4">
        <button type="submit" :disabled="form.processing"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors disabled:opacity-60">
          {{ form.processing ? 'Saving...' : (isEdit ? 'Update Service' : 'Create Service') }}
        </button>
        <a href="/admin/services"
           class="px-8 py-3 border border-gray-300 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
          Cancel
        </a>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ChevronLeftIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  service:    { type: Object, default: null },
  categories: { type: Array,  default: () => [] },
})

const isEdit = !!props.service

const form = useForm({
  category_id:       props.service?.category_id ?? '',
  name:              props.service?.name ?? '',
  slug:              props.service?.slug ?? '',
  short_description: props.service?.short_description ?? '',
  base_price:        props.service?.base_price ?? '',
  discounted_price:  props.service?.discounted_price ?? '',
  duration_estimate: props.service?.duration_estimate ?? '',
  features:          props.service?.features ?? [],
  sort_order:        props.service?.sort_order ?? 0,
  is_active:         props.service?.is_active ?? true,
  is_featured:       props.service?.is_featured ?? false,
  meta_title:        props.service?.meta_title ?? '',
  meta_description:  props.service?.meta_description ?? '',
})

function autoSlug() {
  if (!isEdit) {
    form.slug = form.name
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .trim()
      .replace(/\s+/g, '-')
  }
}

function addFeature()       { form.features.push('') }
function removeFeature(i)   { form.features.splice(i, 1) }

function submit() {
  if (isEdit) {
    form.put(`/admin/services/${props.service.id}`)
  } else {
    form.post('/admin/services')
  }
}
</script>

<style scoped>
.label { @apply block text-sm font-semibold text-gray-700 mb-1.5; }
.input { @apply w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow; }
.err   { @apply mt-1 text-red-500 text-xs; }
</style>