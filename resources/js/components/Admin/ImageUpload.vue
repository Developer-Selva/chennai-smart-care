<template>
  <div class="space-y-3">

    <!-- Current image preview (when URL already set) -->
    <div v-if="modelValue && !uploading" class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
      <img :src="modelValue" :alt="altText || 'Featured image'"
           class="w-full aspect-video object-cover" @error="onImageError" />

      <!-- Overlay actions -->
      <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
        <button type="button" @click="triggerFilePicker"
                class="bg-white text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-100 transition flex items-center gap-1.5">
          <ArrowUpTrayIcon class="w-3.5 h-3.5" />
          Replace
        </button>
        <button type="button" @click="openLibrary"
                class="bg-white text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-100 transition flex items-center gap-1.5">
          <PhotoIcon class="w-3.5 h-3.5" />
          Library
        </button>
        <button type="button" @click="clearImage"
                class="bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-700 transition flex items-center gap-1.5">
          <TrashIcon class="w-3.5 h-3.5" />
          Remove
        </button>
      </div>

      <!-- Image info pill -->
      <div v-if="uploadedMedia" class="absolute bottom-2 left-2 bg-black/60 text-white text-xs px-2 py-1 rounded-lg flex items-center gap-2">
        <span v-if="uploadedMedia.dimensions">{{ uploadedMedia.dimensions }}</span>
        <span v-if="uploadedMedia.formatted_size">{{ uploadedMedia.formatted_size }}</span>
      </div>
    </div>

    <!-- Drop zone (when no image) -->
    <div v-else-if="!uploading"
         @dragover.prevent="dragover = true"
         @dragleave.prevent="dragover = false"
         @drop.prevent="onDrop"
         @click="triggerFilePicker"
         :class="[
           'relative border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition-all',
           dragover
             ? 'border-blue-500 bg-blue-50'
             : 'border-gray-300 hover:border-blue-400 hover:bg-gray-50',
         ]">

      <div class="flex flex-col items-center gap-3">
        <div :class="['w-12 h-12 rounded-full flex items-center justify-center transition-colors',
                       dragover ? 'bg-blue-100' : 'bg-gray-100']">
          <PhotoIcon :class="['w-6 h-6 transition-colors', dragover ? 'text-blue-600' : 'text-gray-400']" />
        </div>
        <div>
          <p class="text-sm font-semibold text-gray-700">
            {{ dragover ? 'Drop image here' : 'Drag & drop an image' }}
          </p>
          <p class="text-xs text-gray-400 mt-0.5">or click to browse · JPG, PNG, WebP, GIF · Max 5 MB</p>
        </div>
        <button type="button"
                class="mt-1 px-4 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
          Choose File
        </button>
      </div>

      <!-- Library button -->
      <button type="button" @click.stop="openLibrary"
              class="absolute top-2 right-2 text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-blue-50 transition">
        <PhotoIcon class="w-3.5 h-3.5" />
        Media Library
      </button>
    </div>

    <!-- Upload progress state -->
    <div v-if="uploading" class="border-2 border-blue-200 rounded-xl p-6 bg-blue-50">
      <div class="flex items-center gap-3 mb-3">
        <div class="relative w-10 h-10 flex-shrink-0">
          <svg class="w-10 h-10 -rotate-90" viewBox="0 0 36 36">
            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#dbeafe" stroke-width="3" />
            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#2563eb" stroke-width="3"
                    stroke-dasharray="100" :stroke-dashoffset="100 - uploadProgress"
                    class="transition-all duration-300" />
          </svg>
          <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-blue-700">
            {{ uploadProgress }}%
          </span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-blue-900 truncate">{{ uploadingFileName }}</p>
          <p class="text-xs text-blue-600 mt-0.5">
            {{ uploadProgress < 100 ? 'Uploading…' : 'Processing…' }}
          </p>
        </div>
      </div>
      <div class="h-1.5 bg-blue-200 rounded-full overflow-hidden">
        <div class="h-full bg-blue-600 rounded-full transition-all duration-300"
             :style="{ width: uploadProgress + '%' }" />
      </div>
    </div>

    <!-- Upload error -->
    <div v-if="uploadError" class="flex items-start gap-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
      <ExclamationCircleIcon class="w-4 h-4 flex-shrink-0 mt-0.5" />
      <span>{{ uploadError }}</span>
    </div>

    <!-- Alt text input (shown when image is set) -->
    <div v-if="modelValue && !uploading" class="space-y-1">
      <label class="text-xs font-semibold text-gray-600 flex items-center gap-1">
        <span>Alt Text</span>
        <span class="text-gray-400 font-normal">(for accessibility & SEO)</span>
      </label>
      <input v-model="localAltText"
             type="text"
             class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
             placeholder="Describe this image for screen readers and Google Images"
             @input="$emit('update:altText', localAltText)" />
      <p class="text-xs text-gray-400">{{ localAltText?.length ?? 0 }}/250 characters</p>
    </div>

    <!-- Hidden file input -->
    <input ref="fileInputRef" type="file"
           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml"
           class="hidden"
           @change="onFileChange" />
  </div>

  <!-- ══════════════════════════════════════════════════
       MEDIA LIBRARY MODAL
  ══════════════════════════════════════════════════ -->
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="showLibrary"
           class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4"
           @click.self="showLibrary = false">

        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
        >
          <div v-if="showLibrary"
               class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">

            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 flex-shrink-0">
              <div>
                <h2 class="text-lg font-bold text-gray-900">Media Library</h2>
                <p class="text-xs text-gray-400 mt-0.5">Click an image to select it</p>
              </div>
              <div class="flex items-center gap-3">
                <!-- Search -->
                <div class="relative">
                  <MagnifyingGlassIcon class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                  <input v-model="librarySearch" type="text" placeholder="Search images…"
                         class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-52"
                         @input="debounceSearch" />
                </div>
                <!-- Upload new from modal -->
                <button type="button" @click="triggerFilePickerFromLibrary"
                        class="flex items-center gap-1.5 bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                  <ArrowUpTrayIcon class="w-4 h-4" />
                  Upload New
                </button>
                <button type="button" @click="showLibrary = false"
                        class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                  <XMarkIcon class="w-5 h-5" />
                </button>
              </div>
            </div>

            <!-- Library grid -->
            <div class="flex-1 overflow-y-auto p-6">

              <!-- Loading state -->
              <div v-if="libraryLoading" class="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-6 gap-3">
                <div v-for="n in 18" :key="n"
                     class="aspect-square bg-gray-200 rounded-xl animate-pulse" />
              </div>

              <!-- Empty state -->
              <div v-else-if="!libraryItems.length" class="text-center py-16">
                <PhotoIcon class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 font-medium">No images yet</p>
                <p class="text-xs text-gray-400 mt-1">Upload your first image using the button above</p>
              </div>

              <!-- Image grid -->
              <div v-else class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                <button v-for="item in libraryItems" :key="item.id"
                        type="button"
                        @click="selectFromLibrary(item)"
                        :class="[
                          'relative group aspect-square rounded-xl overflow-hidden border-2 transition-all',
                          selectedLibraryId === item.id
                            ? 'border-blue-500 ring-2 ring-blue-200'
                            : 'border-transparent hover:border-blue-300',
                        ]">
                  <!-- Thumbnail or full image -->
                  <img :src="item.thumbnail_url || item.url"
                       :alt="item.alt_text || item.filename"
                       class="w-full h-full object-cover transition-transform group-hover:scale-105"
                       loading="lazy" />

                  <!-- Hover overlay -->
                  <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent
                              opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-2">
                    <p class="text-white text-xs font-medium truncate">{{ item.filename }}</p>
                    <p class="text-gray-300 text-xs">{{ item.formatted_size }}</p>
                  </div>

                  <!-- Selected tick -->
                  <div v-if="selectedLibraryId === item.id"
                       class="absolute top-2 right-2 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                    <CheckIcon class="w-3.5 h-3.5 text-white" />
                  </div>
                </button>
              </div>

              <!-- Load more -->
              <div v-if="libraryMeta?.next_page_url" class="mt-6 text-center">
                <button type="button" @click="loadMoreLibrary"
                        :disabled="libraryLoadingMore"
                        class="px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 transition disabled:opacity-50">
                  {{ libraryLoadingMore ? 'Loading…' : 'Load More' }}
                </button>
              </div>
            </div>

            <!-- Modal footer with selected image actions -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 flex-shrink-0 bg-gray-50">
              <div v-if="selectedLibraryItem" class="flex items-center gap-3 min-w-0">
                <img :src="selectedLibraryItem.thumbnail_url || selectedLibraryItem.url"
                     class="w-10 h-10 rounded-lg object-cover border border-gray-200 flex-shrink-0" />
                <div class="min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ selectedLibraryItem.filename }}</p>
                  <p class="text-xs text-gray-400">
                    {{ selectedLibraryItem.dimensions }} · {{ selectedLibraryItem.formatted_size }}
                  </p>
                </div>
              </div>
              <div v-else class="text-sm text-gray-400">No image selected</div>

              <div class="flex gap-3 flex-shrink-0 ml-4">
                <button type="button" @click="showLibrary = false"
                        class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl hover:bg-gray-100 transition">
                  Cancel
                </button>
                <button type="button" @click="confirmLibrarySelection"
                        :disabled="!selectedLibraryItem"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition disabled:opacity-40">
                  Insert Image
                </button>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import axios from 'axios'
import {
  PhotoIcon, ArrowUpTrayIcon, TrashIcon,
  ExclamationCircleIcon, MagnifyingGlassIcon,
  XMarkIcon, CheckIcon,
} from '@heroicons/vue/24/outline'

// ── Props & emits ──────────────────────────────────────────────
const props = defineProps({
  modelValue:   { type: String, default: '' },    // URL of the selected image
  altText:      { type: String, default: '' },
  collection:   { type: String, default: 'blog' },
})

const emit = defineEmits(['update:modelValue', 'update:altText', 'media-selected'])

// ── Upload state ───────────────────────────────────────────────
const fileInputRef       = ref(null)
const uploading          = ref(false)
const uploadProgress     = ref(0)
const uploadError        = ref('')
const uploadingFileName  = ref('')
const uploadedMedia      = ref(null)
const dragover           = ref(false)
const localAltText       = ref(props.altText)

watch(() => props.altText, (v) => { localAltText.value = v })

// ── Library state ──────────────────────────────────────────────
const showLibrary         = ref(false)
const libraryItems        = ref([])
const libraryMeta         = ref(null)
const libraryLoading      = ref(false)
const libraryLoadingMore  = ref(false)
const librarySearch       = ref('')
const selectedLibraryId   = ref(null)
const selectedLibraryItem = ref(null)
let searchTimeout         = null

// ── File picker ────────────────────────────────────────────────
function triggerFilePicker() {
  fileInputRef.value?.click()
}

function triggerFilePickerFromLibrary() {
  // Upload new then auto-select
  fileInputRef.value?.click()
}

function onFileChange(e) {
  const file = e.target.files?.[0]
  if (file) uploadFile(file)
  e.target.value = '' // reset so same file can be re-selected
}

function onDrop(e) {
  dragover.value = false
  const file = e.dataTransfer.files?.[0]
  if (file) uploadFile(file)
}

// ── Core upload ────────────────────────────────────────────────
async function uploadFile(file) {
  // Client-side validation
  const ALLOWED = ['image/jpeg','image/jpg','image/png','image/gif','image/webp','image/svg+xml']
  if (!ALLOWED.includes(file.type)) {
    uploadError.value = 'Unsupported file type. Please upload a JPG, PNG, WebP, GIF, or SVG.'
    return
  }
  if (file.size > 5 * 1024 * 1024) {
    uploadError.value = `File too large (${(file.size / 1048576).toFixed(1)} MB). Maximum allowed size is 5 MB.`
    return
  }

  uploading.value         = true
  uploadProgress.value    = 0
  uploadError.value       = ''
  uploadingFileName.value = file.name

  const fd = new FormData()
  fd.append('file', file)
  fd.append('collection', props.collection)
  if (localAltText.value) fd.append('alt_text', localAltText.value)

  try {
    const res = await axios.post('/admin/media', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
      onUploadProgress: (e) => {
        // Progress reaches 95% during upload; jumps to 100 once server responds
        uploadProgress.value = Math.min(95, Math.round((e.loaded * 100) / (e.total || 1)))
      },
    })

    uploadProgress.value = 100
    const media = res.data.media

    uploadedMedia.value = media
    localAltText.value  = media.alt_text || ''

    // Notify parent
    emit('update:modelValue', media.url)
    emit('update:altText', localAltText.value)
    emit('media-selected', media)

    await nextTick()
    uploading.value = false

  } catch (err) {
    uploading.value = false
    const msg = err.response?.data?.errors?.file?.[0]
             ?? err.response?.data?.message
             ?? 'Upload failed. Please try again.'
    uploadError.value = msg
  }
}

// ── Clear image ────────────────────────────────────────────────
function clearImage() {
  emit('update:modelValue', '')
  emit('update:altText', '')
  localAltText.value  = ''
  uploadedMedia.value = null
  uploadError.value   = ''
}

function onImageError() {
  // Image URL is broken — clear it so the drop zone shows
  clearImage()
}

// ── Media library ──────────────────────────────────────────────
async function openLibrary() {
  showLibrary.value       = true
  selectedLibraryId.value = null
  selectedLibraryItem.value = null
  await loadLibrary()
}

async function loadLibrary(page = 1) {
  libraryLoading.value = true
  try {
    const res = await axios.get('/admin/media', {
      params: {
        collection: props.collection,
        search:     librarySearch.value || undefined,
        page,
      },
    })
    if (page === 1) {
      libraryItems.value = res.data.data
    } else {
      libraryItems.value.push(...res.data.data)
    }
    libraryMeta.value = res.data
  } catch {
    // silent
  } finally {
    libraryLoading.value = false
  }
}

async function loadMoreLibrary() {
  if (!libraryMeta.value?.next_page_url) return
  libraryLoadingMore.value = true
  const nextPage = (libraryMeta.value.current_page ?? 1) + 1
  await loadLibrary(nextPage)
  libraryLoadingMore.value = false
}

function debounceSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => loadLibrary(1), 400)
}

function selectFromLibrary(item) {
  selectedLibraryId.value   = item.id
  selectedLibraryItem.value = item
}

function confirmLibrarySelection() {
  if (!selectedLibraryItem.value) return
  const item = selectedLibraryItem.value

  uploadedMedia.value = item
  localAltText.value  = item.alt_text || ''

  emit('update:modelValue', item.url)
  emit('update:altText', localAltText.value)
  emit('media-selected', item)

  showLibrary.value = false
}
</script>