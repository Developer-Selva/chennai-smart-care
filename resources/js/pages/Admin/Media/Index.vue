<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between w-full">
        <div>
          <h1 class="text-xl font-bold text-gray-900">Media Library</h1>
          <p class="text-sm text-gray-400 mt-0.5">Manage images used across blog posts and pages</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Collection filter -->
          <select v-model="activeCollection"
                  class="input-sm bg-white border-gray-300"
                  @change="loadMedia(1)">
            <option value="blog">📝 Blog</option>
            <option value="services">🔧 Services</option>
            <option value="team">👥 Team</option>
            <option value="general">📁 General</option>
          </select>

          <!-- Upload button -->
          <label class="flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold
                        px-4 py-2 rounded-xl cursor-pointer hover:bg-blue-700 transition-colors">
            <ArrowUpTrayIcon class="w-4 h-4" />
            Upload Images
            <input type="file" class="hidden" multiple accept="image/*" @change="onBulkUpload" />
          </label>
        </div>
      </div>
    </template>

    <!-- Upload queue progress bar (bulk uploads) -->
    <Transition enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0">
      <div v-if="uploadQueue.length" class="bg-white border border-gray-200 rounded-2xl p-4 mb-5 space-y-2">
        <div class="flex items-center justify-between mb-1">
          <p class="text-sm font-semibold text-gray-700">
            Uploading {{ uploadQueue.filter(q => q.done).length }} / {{ uploadQueue.length }} images…
          </p>
          <p class="text-xs text-gray-400">
            {{ uploadQueue.filter(q => q.error).length }} errors
          </p>
        </div>
        <div class="space-y-2 max-h-40 overflow-y-auto">
          <div v-for="q in uploadQueue" :key="q.id" class="flex items-center gap-3">
            <div class="w-28 truncate text-xs text-gray-600">{{ q.name }}</div>
            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full transition-all duration-300"
                   :class="q.error ? 'bg-red-500' : q.done ? 'bg-green-500' : 'bg-blue-500'"
                   :style="{ width: q.progress + '%' }" />
            </div>
            <div class="w-8 text-right">
              <span v-if="q.done && !q.error" class="text-green-600 text-xs">✓</span>
              <span v-else-if="q.error" class="text-red-600 text-xs" :title="q.error">✗</span>
              <span v-else class="text-xs text-gray-400">{{ q.progress }}%</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Search + view controls -->
    <div class="flex items-center justify-between gap-4 mb-5">
      <div class="relative flex-1 max-w-sm">
        <MagnifyingGlassIcon class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
        <input v-model="search" type="text" placeholder="Search by filename or alt text…"
               class="input-sm pl-9 w-full" @input="debounceSearch" />
      </div>
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-400">{{ totalMedia }} image{{ totalMedia !== 1 ? 's' : '' }}</span>
        <!-- View toggle -->
        <div class="flex border border-gray-300 rounded-lg overflow-hidden">
          <button @click="view = 'grid'"
                  :class="['px-3 py-1.5 text-xs font-medium transition-colors',
                           view === 'grid' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-50']">
            ▦ Grid
          </button>
          <button @click="view = 'list'"
                  :class="['px-3 py-1.5 text-xs font-medium transition-colors border-l border-gray-300',
                           view === 'list' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-50']">
            ≡ List
          </button>
        </div>
      </div>
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" :class="['gap-3', view === 'grid' ? 'grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6' : 'space-y-2']">
      <div v-for="n in 18" :key="n"
           :class="view === 'grid' ? 'aspect-square bg-gray-200 rounded-xl animate-pulse' : 'h-16 bg-gray-200 rounded-xl animate-pulse'" />
    </div>

    <!-- Empty state -->
    <div v-else-if="!items.length" class="text-center py-24 bg-white rounded-2xl border border-gray-200">
      <PhotoIcon class="w-14 h-14 text-gray-300 mx-auto mb-4" />
      <p class="text-gray-500 font-semibold">No images uploaded yet</p>
      <p class="text-sm text-gray-400 mt-1">Upload images using the button in the top right</p>
    </div>

    <!-- Grid view -->
    <div v-else-if="view === 'grid'"
         class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
      <div v-for="item in items" :key="item.id"
           class="group relative bg-white rounded-xl border border-gray-200 overflow-hidden
                  hover:border-blue-300 hover:shadow-md transition-all cursor-pointer"
           @click="openDetail(item)">

        <!-- Thumbnail -->
        <div class="aspect-square bg-gray-100 overflow-hidden">
          <img :src="item.thumbnail_url || item.url"
               :alt="item.alt_text || item.filename"
               class="w-full h-full object-cover transition-transform group-hover:scale-105"
               loading="lazy" />
        </div>

        <!-- Info footer -->
        <div class="px-2 py-1.5">
          <p class="text-xs font-medium text-gray-700 truncate">{{ item.filename }}</p>
          <p class="text-xs text-gray-400">{{ item.formatted_size }}</p>
        </div>

        <!-- Action overlay -->
        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity
                    flex items-center justify-center gap-2">
          <button @click.stop="copyUrl(item.url)"
                  class="bg-white text-gray-800 p-1.5 rounded-lg hover:bg-gray-100 transition"
                  title="Copy URL">
            <ClipboardDocumentIcon class="w-4 h-4" />
          </button>
          <button @click.stop="openDetail(item)"
                  class="bg-white text-gray-800 p-1.5 rounded-lg hover:bg-gray-100 transition"
                  title="Edit details">
            <PencilIcon class="w-4 h-4" />
          </button>
          <button @click.stop="confirmDelete(item)"
                  class="bg-red-600 text-white p-1.5 rounded-lg hover:bg-red-700 transition"
                  title="Delete">
            <TrashIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- List view -->
    <div v-else class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-16">Preview</th>
            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">File</th>
            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Alt Text</th>
            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Size</th>
            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Date</th>
            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="item in items" :key="item.id"
              class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3">
              <img :src="item.thumbnail_url || item.url"
                   :alt="item.alt_text || ''"
                   class="w-12 h-12 object-cover rounded-lg border border-gray-200" loading="lazy" />
            </td>
            <td class="px-4 py-3">
              <p class="font-medium text-gray-900 truncate max-w-xs">{{ item.filename }}</p>
              <p class="text-xs text-gray-400">{{ item.dimensions }}</p>
            </td>
            <td class="px-4 py-3 hidden sm:table-cell">
              <p class="text-gray-600 truncate max-w-xs text-xs">{{ item.alt_text || '—' }}</p>
            </td>
            <td class="px-4 py-3 hidden md:table-cell text-gray-500 text-xs">{{ item.formatted_size }}</td>
            <td class="px-4 py-3 hidden md:table-cell text-gray-400 text-xs whitespace-nowrap">
              {{ formatDate(item.created_at) }}
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-end gap-2">
                <button @click="copyUrl(item.url)"
                        class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition"
                        title="Copy URL">
                  <ClipboardDocumentIcon class="w-4 h-4" />
                </button>
                <button @click="openDetail(item)"
                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                        title="Edit">
                  <PencilIcon class="w-4 h-4" />
                </button>
                <button @click="confirmDelete(item)"
                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                        title="Delete">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination row -->
      <div v-if="totalPages > 1"
           class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50">
        <p class="text-xs text-gray-500">
          Showing {{ (currentPage - 1) * 30 + 1 }}–{{ Math.min(currentPage * 30, totalMedia) }}
          of {{ totalMedia }} images
        </p>
        <div class="flex gap-2">
          <button @click="loadMedia(currentPage - 1)" :disabled="currentPage === 1"
                  class="px-3 py-1.5 border border-gray-300 text-xs rounded-lg hover:bg-gray-100 disabled:opacity-40 transition">
            ← Prev
          </button>
          <button @click="loadMedia(currentPage + 1)" :disabled="currentPage >= totalPages"
                  class="px-3 py-1.5 border border-gray-300 text-xs rounded-lg hover:bg-gray-100 disabled:opacity-40 transition">
            Next →
          </button>
        </div>
      </div>
    </div>

    <!-- Load more (grid) -->
    <div v-if="view === 'grid' && hasMore" class="mt-6 text-center">
      <button @click="loadMedia(currentPage + 1)" :disabled="loading"
              class="px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 transition disabled:opacity-50">
        {{ loading ? 'Loading…' : 'Load More' }}
      </button>
    </div>

    <!-- Copied toast -->
    <Transition enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0">
      <div v-if="showCopied"
           class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm
                  px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-2 z-50">
        <CheckCircleIcon class="w-4 h-4 text-green-400" />
        URL copied to clipboard!
      </div>
    </Transition>
  </AdminLayout>

  <!-- ══════════════════════════ DETAIL / EDIT MODAL ══════════════════════════ -->
  <Teleport to="body">
    <Transition enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100">
      <div v-if="detailItem" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4"
           @click.self="detailItem = null">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">

          <!-- Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-900">Image Details</h2>
            <button @click="detailItem = null" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
              <XMarkIcon class="w-5 h-5" />
            </button>
          </div>

          <!-- Body -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6">
            <!-- Preview -->
            <div class="space-y-3">
              <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                <img :src="detailItem.url" :alt="detailItem.alt_text || ''"
                     class="w-full h-full object-contain" />
              </div>
              <div class="text-xs text-gray-500 space-y-1">
                <p><span class="font-medium">Filename:</span> {{ detailItem.filename }}</p>
                <p><span class="font-medium">Size:</span> {{ detailItem.formatted_size }}</p>
                <p v-if="detailItem.dimensions"><span class="font-medium">Dimensions:</span> {{ detailItem.dimensions }}</p>
                <p><span class="font-medium">Type:</span> {{ detailItem.mime_type }}</p>
                <p><span class="font-medium">Uploaded:</span> {{ formatDate(detailItem.created_at) }}</p>
              </div>
              <!-- Copy URL -->
              <button @click="copyUrl(detailItem.url)"
                      class="w-full flex items-center justify-center gap-2 border border-gray-300 text-sm font-medium
                             py-2 rounded-lg hover:bg-gray-50 transition">
                <ClipboardDocumentIcon class="w-4 h-4" />
                Copy Image URL
              </button>
            </div>

            <!-- Edit fields -->
            <div class="space-y-4">
              <div>
                <label class="label-sm">Alt Text</label>
                <input v-model="detailEdit.alt_text" type="text"
                       class="input-field" placeholder="Describe this image…" />
                <p class="text-xs text-gray-400 mt-1">{{ detailEdit.alt_text?.length ?? 0 }}/250</p>
              </div>
              <div>
                <label class="label-sm">Caption</label>
                <textarea v-model="detailEdit.caption" rows="3" class="input-field resize-none"
                          placeholder="Optional caption displayed below the image…"></textarea>
              </div>
              <div>
                <label class="label-sm">Title</label>
                <input v-model="detailEdit.title" type="text"
                       class="input-field" placeholder="Image title attribute…" />
              </div>

              <div class="flex gap-3 pt-2">
                <button @click="saveDetail" :disabled="saving"
                        class="flex-1 bg-blue-600 text-white text-sm font-semibold py-2.5 rounded-xl
                               hover:bg-blue-700 transition disabled:opacity-60">
                  {{ saving ? 'Saving…' : 'Save Changes' }}
                </button>
                <button @click="confirmDelete(detailItem)"
                        class="px-4 py-2.5 border border-red-200 text-red-600 text-sm font-medium
                               rounded-xl hover:bg-red-50 transition">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import {
  ArrowUpTrayIcon, MagnifyingGlassIcon, PhotoIcon,
  ClipboardDocumentIcon, PencilIcon, TrashIcon,
  CheckCircleIcon, XMarkIcon,
} from '@heroicons/vue/24/outline'

// ── State ─────────────────────────────────────────────────────
const items           = ref([])
const loading         = ref(false)
const currentPage     = ref(1)
const totalMedia      = ref(0)
const totalPages      = ref(1)
const hasMore         = ref(false)
const search          = ref('')
const activeCollection = ref('blog')
const view            = ref('grid')

const uploadQueue     = ref([])
let   uploadIdCounter = 0

const detailItem      = ref(null)
const detailEdit      = ref({})
const saving          = ref(false)

const showCopied      = ref(false)
let   copiedTimer     = null
let   searchTimeout   = null

// ── Init ──────────────────────────────────────────────────────
onMounted(() => loadMedia(1))

// ── Load ──────────────────────────────────────────────────────
async function loadMedia(page = 1) {
  loading.value = true
  try {
    const res = await axios.get('/admin/media', {
      params: {
        collection: activeCollection.value,
        search:     search.value || undefined,
        page,
      },
    })
    if (page === 1) {
      items.value = res.data.data
    } else {
      items.value.push(...res.data.data)
    }
    currentPage.value = res.data.current_page
    totalMedia.value  = res.data.total
    totalPages.value  = res.data.last_page
    hasMore.value     = !!res.data.next_page_url
  } finally {
    loading.value = false
  }
}

function debounceSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => loadMedia(1), 400)
}

// ── Bulk upload ───────────────────────────────────────────────
async function onBulkUpload(e) {
  const files = Array.from(e.target.files ?? [])
  if (!files.length) return
  e.target.value = ''

  // Add to queue
  const queueItems = files.map(f => ({
    id:       ++uploadIdCounter,
    name:     f.name,
    progress: 0,
    done:     false,
    error:    null,
    file:     f,
  }))
  uploadQueue.value.push(...queueItems)

  // Upload concurrently in batches of 3
  const batchSize = 3
  for (let i = 0; i < queueItems.length; i += batchSize) {
    await Promise.all(
      queueItems.slice(i, i + batchSize).map(q => uploadOne(q))
    )
  }

  // Refresh library after uploads
  await loadMedia(1)

  // Clear queue after 3 seconds
  setTimeout(() => {
    uploadQueue.value = uploadQueue.value.filter(q => !q.done || q.error)
  }, 3000)
}

async function uploadOne(q) {
  const fd = new FormData()
  fd.append('file', q.file)
  fd.append('collection', activeCollection.value)
  try {
    await axios.post('/admin/media', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
      onUploadProgress: (e) => {
        q.progress = Math.min(95, Math.round((e.loaded * 100) / (e.total || 1)))
      },
    })
    q.progress = 100
    q.done = true
  } catch (err) {
    q.error = err.response?.data?.message ?? 'Upload failed'
    q.done  = true
  }
}

// ── Detail / Edit ─────────────────────────────────────────────
function openDetail(item) {
  detailItem.value = item
  detailEdit.value = {
    alt_text: item.alt_text || '',
    caption:  item.caption  || '',
    title:    item.title    || '',
  }
}

async function saveDetail() {
  saving.value = true
  try {
    const res = await axios.patch(`/admin/media/${detailItem.value.id}`, detailEdit.value)
    // Update in list
    const idx = items.value.findIndex(i => i.id === detailItem.value.id)
    if (idx !== -1) items.value[idx] = res.data.media
    detailItem.value = res.data.media
  } finally {
    saving.value = false
  }
}

// ── Delete ────────────────────────────────────────────────────
async function confirmDelete(item) {
  if (!confirm(`Delete "${item.filename}"? This cannot be undone.`)) return
  await axios.delete(`/admin/media/${item.id}`)
  items.value = items.value.filter(i => i.id !== item.id)
  totalMedia.value = Math.max(0, totalMedia.value - 1)
  if (detailItem.value?.id === item.id) detailItem.value = null
}

// ── Copy URL ──────────────────────────────────────────────────
async function copyUrl(url) {
  try {
    await navigator.clipboard.writeText(url)
  } catch {
    // Fallback for older browsers
    const ta = document.createElement('textarea')
    ta.value = url
    document.body.appendChild(ta)
    ta.select()
    document.execCommand('copy')
    document.body.removeChild(ta)
  }
  showCopied.value = true
  clearTimeout(copiedTimer)
  copiedTimer = setTimeout(() => { showCopied.value = false }, 2500)
}

// ── Helpers ───────────────────────────────────────────────────
function formatDate(dateStr) {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-IN', {
    day: 'numeric', month: 'short', year: 'numeric',
  })
}
</script>

<style scoped>
.input-sm  { @apply px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent; }
.input-field { @apply w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow; }
.label-sm  { @apply block text-sm font-semibold text-gray-700 mb-1.5; }
</style>