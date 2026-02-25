<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold text-gray-900">Bookings</h1>
          <p class="text-sm text-gray-500 mt-0.5">{{ bookings.total ?? 0 }} total bookings</p>
        </div>
        <a href="/admin/bookings/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
          <PlusIcon class="w-4 h-4" /> New Booking
        </a>
      </div>
    </template>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-5">
      <div class="flex gap-2 mb-3">
        <div class="relative flex-1">
          <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
          <input v-model="search" type="text"
                 placeholder="Name, phone, booking #..."
                 class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                 @keyup.enter="applyFilters" />
        </div>
        <button @click="applyFilters"
                class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shrink-0">
          Search
        </button>
      </div>
      <div class="flex flex-wrap gap-2">
        <select v-model="statusFilter"
                class="flex-1 min-w-[130px] px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
          <option value="">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="assigned">Assigned</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="rescheduled">Rescheduled</option>
        </select>
        <input v-model="dateFilter" type="date"
               class="flex-1 min-w-[140px] px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button v-if="hasActiveFilters" @click="resetFilters"
                class="px-3 py-2 border border-gray-300 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition-colors flex items-center gap-1.5">
          <XMarkIcon class="w-3.5 h-3.5" /> Clear
        </button>
      </div>
      <!-- Active filter pills -->
      <div v-if="hasActiveFilters" class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
        <span v-if="statusFilter" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
          Status: {{ statusLabel(statusFilter) }}
          <button @click="statusFilter = ''; applyFilters()"><XMarkIcon class="w-3 h-3" /></button>
        </span>
        <span v-if="dateFilter" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
          Date: {{ formatDate(dateFilter) }}
          <button @click="dateFilter = ''; applyFilters()"><XMarkIcon class="w-3 h-3" /></button>
        </span>
        <span v-if="search" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
          "{{ search }}"
          <button @click="search = ''; applyFilters()"><XMarkIcon class="w-3 h-3" /></button>
        </span>
      </div>
    </div>

    <!-- Status quick-tabs -->
    <div class="flex gap-2 overflow-x-auto pb-1 mb-5 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
      <button v-for="tab in statusTabs" :key="tab.value"
              @click="quickFilter(tab.value)"
              :class="[
                'shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition-all whitespace-nowrap border',
                statusFilter === tab.value
                  ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                  : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300 hover:text-blue-600'
              ]">
        {{ tab.label }}
        <span v-if="tab.count" class="ml-1 opacity-70">({{ tab.count }})</span>
      </button>
    </div>

    <!-- Desktop Table -->
    <div class="hidden sm:block bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Booking #</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Customer</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden md:table-cell">Service</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden lg:table-cell">Date & Time</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden lg:table-cell">Technician</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Status</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Amount</th>
              <th class="px-5 py-3.5"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="!bookings.data?.length">
              <td colspan="8" class="text-center py-16 text-gray-400">
                <CalendarIcon class="w-10 h-10 mx-auto mb-3 opacity-30" />
                <p class="font-medium">No bookings found</p>
                <p class="text-xs mt-1">Try adjusting your filters</p>
              </td>
            </tr>
            <tr v-for="booking in bookings.data" :key="booking.id"
                class="hover:bg-gray-50 transition-colors cursor-pointer"
                @click="goToBooking(booking.id)">
              <td class="px-5 py-4">
                <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                  {{ booking.booking_number }}
                </span>
              </td>
              <td class="px-5 py-4">
                <p class="font-semibold text-gray-900">{{ booking.customer_name }}</p>
                <p class="text-gray-400 text-xs mt-0.5 flex items-center gap-1">
                  <PhoneIcon class="w-3 h-3" />{{ booking.customer_phone }}
                </p>
              </td>
              <td class="px-5 py-4 hidden md:table-cell max-w-[200px]">
                <p class="text-gray-700 truncate">
                  {{ booking.items?.map(i => i.service_name).join(', ') || '—' }}
                </p>
              </td>
              <td class="px-5 py-4 hidden lg:table-cell">
                <p class="text-gray-800 font-medium">{{ formatDate(booking.booking_date) }}</p>
                <p class="text-gray-400 text-xs mt-0.5">{{ booking.time_slot }}</p>
              </td>
              <td class="px-5 py-4 hidden lg:table-cell">
                <span v-if="booking.technician"
                      class="inline-flex items-center gap-1.5 text-gray-700 text-xs bg-gray-100 px-2.5 py-1 rounded-full font-medium">
                  <UserIcon class="w-3 h-3" />{{ booking.technician.name }}
                </span>
                <span v-else class="text-gray-300 text-xs">Unassigned</span>
              </td>
              <td class="px-5 py-4"><StatusBadge :status="booking.status" /></td>
              <td class="px-5 py-4">
                <span class="font-bold text-gray-900">
                  {{ booking.final_amount ? `₹${booking.final_amount}` : '—' }}
                </span>
              </td>
              <td class="px-5 py-4 text-right" @click.stop>
                <a :href="`/admin/bookings/${booking.id}`"
                   class="text-blue-600 hover:text-blue-800 font-semibold text-xs bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                  View →
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div v-if="bookings.last_page > 1"
           class="border-t border-gray-100 px-5 py-4 flex items-center justify-between">
        <p class="text-gray-500 text-xs">
          Showing {{ bookings.from }}–{{ bookings.to }} of {{ bookings.total }}
        </p>
        <div class="flex gap-2">
          <a v-if="bookings.prev_page_url" :href="bookings.prev_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs">← Prev</a>
          <a v-if="bookings.next_page_url" :href="bookings.next_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs">Next →</a>
        </div>
      </div>
    </div>

    <!-- Mobile Card List -->
    <div class="sm:hidden space-y-3">
      <div v-if="!bookings.data?.length"
           class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-400">
        <CalendarIcon class="w-10 h-10 mx-auto mb-3 opacity-30" />
        <p class="font-medium text-sm">No bookings found</p>
        <p class="text-xs mt-1">Try adjusting your filters</p>
      </div>

      <a v-for="booking in bookings.data" :key="booking.id"
         :href="`/admin/bookings/${booking.id}`"
         class="block bg-white rounded-2xl border border-gray-200 p-4 active:bg-gray-50 transition-colors">

        <!-- Top: number + status + amount -->
        <div class="flex items-start justify-between gap-2 mb-3">
          <div class="flex items-center gap-2 min-w-0 flex-wrap">
            <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg shrink-0">
              {{ booking.booking_number }}
            </span>
            <StatusBadge :status="booking.status" />
          </div>
          <span class="font-bold text-gray-900 text-sm shrink-0">
            {{ booking.final_amount ? `₹${booking.final_amount}` : '—' }}
          </span>
        </div>

        <!-- Customer -->
        <div class="flex items-center gap-2.5 mb-2.5">
          <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
            <UserIcon class="w-4 h-4 text-blue-500" />
          </div>
          <div class="min-w-0">
            <p class="font-semibold text-gray-900 text-sm truncate">{{ booking.customer_name }}</p>
            <a :href="`tel:${booking.customer_phone}`" @click.stop
               class="text-xs text-blue-600 flex items-center gap-1">
              <PhoneIcon class="w-3 h-3" />{{ booking.customer_phone }}
            </a>
          </div>
        </div>

        <!-- Service -->
        <div v-if="booking.items?.length" class="flex items-start gap-2 mb-2.5">
          <WrenchScrewdriverIcon class="w-3.5 h-3.5 text-gray-400 mt-0.5 shrink-0" />
          <p class="text-xs text-gray-600 line-clamp-2">
            {{ booking.items.map(i => i.service_name).join(', ') }}
          </p>
        </div>

        <!-- Date + Technician -->
        <div class="flex items-center justify-between pt-2.5 border-t border-gray-100">
          <div class="flex items-center gap-1.5 text-xs text-gray-500">
            <CalendarIcon class="w-3.5 h-3.5 shrink-0" />
            <span>{{ formatDate(booking.booking_date) }}</span>
            <span v-if="booking.time_slot" class="text-gray-400 hidden xs:inline">· {{ booking.time_slot }}</span>
          </div>
          <span v-if="booking.technician"
                class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full font-medium">
            <UserIcon class="w-3 h-3" />{{ booking.technician.name }}
          </span>
          <span v-else class="text-xs text-orange-500 font-medium bg-orange-50 px-2 py-1 rounded-full">
            Unassigned
          </span>
        </div>
      </a>

      <!-- Mobile Pagination -->
      <div v-if="bookings.last_page > 1" class="flex items-center justify-between pt-2">
        <p class="text-xs text-gray-500">{{ bookings.from }}–{{ bookings.to }} of {{ bookings.total }}</p>
        <div class="flex gap-2">
          <a v-if="bookings.prev_page_url" :href="bookings.prev_page_url"
             class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-600">← Prev</a>
          <a v-if="bookings.next_page_url" :href="bookings.next_page_url"
             class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-600">Next →</a>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  PlusIcon, MagnifyingGlassIcon, XMarkIcon,
  CalendarIcon, PhoneIcon, UserIcon, WrenchScrewdriverIcon,
} from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  bookings:    { type: Object, required: true },
  filters:     { type: Object, default: () => ({}) },
  technicians: { type: Array,  default: () => [] },
  counts:      { type: Object, default: () => ({}) },
})

const search       = ref(props.filters.search ?? '')
const statusFilter = ref(props.filters.status ?? '')
const dateFilter   = ref(props.filters.date   ?? '')

const hasActiveFilters = computed(() =>
  !!(search.value || statusFilter.value || dateFilter.value)
)

const statusTabs = computed(() => [
  { value: '',            label: 'All',         count: null },
  { value: 'pending',     label: 'Pending',     count: props.counts?.pending     ?? null },
  { value: 'confirmed',   label: 'Confirmed',   count: props.counts?.confirmed   ?? null },
  { value: 'assigned',    label: 'Assigned',    count: props.counts?.assigned    ?? null },
  { value: 'in_progress', label: 'In Progress', count: props.counts?.in_progress ?? null },
  { value: 'completed',   label: 'Completed',   count: null },
  { value: 'cancelled',   label: 'Cancelled',   count: null },
])

function applyFilters() {
  router.get('/admin/bookings', {
    search: search.value       || undefined,
    status: statusFilter.value || undefined,
    date:   dateFilter.value   || undefined,
  }, { preserveState: true, replace: true })
}

function resetFilters() {
  search.value = ''; statusFilter.value = ''; dateFilter.value = ''
  router.get('/admin/bookings')
}

function quickFilter(status) {
  statusFilter.value = status
  applyFilters()
}

function goToBooking(id) {
  router.visit(`/admin/bookings/${id}`)
}

function statusLabel(val) {
  return {
    pending: 'Pending', confirmed: 'Confirmed', assigned: 'Assigned',
    in_progress: 'In Progress', completed: 'Completed',
    cancelled: 'Cancelled', rescheduled: 'Rescheduled',
  }[val] ?? val
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>