<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold text-gray-900">Bookings</h1>
          <p class="text-sm text-gray-500 mt-0.5">Manage all service bookings</p>
        </div>
        <a href="/admin/bookings/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
          <PlusIcon class="w-4 h-4" /> New Booking
        </a>
      </div>
    </template>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 flex flex-wrap gap-3">
      <input v-model="search" type="text" placeholder="Search name, phone, booking #..."
             class="flex-1 min-w-48 px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
             @keyup.enter="applyFilters" />

      <select v-model="statusFilter"
              class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
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
             class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />

      <button @click="applyFilters"
              class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
        Filter
      </button>
      <button @click="resetFilters"
              class="px-4 py-2 border border-gray-300 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-colors">
        Reset
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Booking #</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Customer</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden md:table-cell">Services</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden lg:table-cell">Date & Time</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden lg:table-cell">Technician</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600">Status</th>
              <th class="text-left px-5 py-3.5 font-semibold text-gray-600 hidden sm:table-cell">Amount</th>
              <th class="px-5 py-3.5"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="!bookings.data?.length">
              <td colspan="8" class="text-center py-16 text-gray-400">
                No bookings found.
              </td>
            </tr>
            <tr v-for="booking in bookings.data" :key="booking.id"
                class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4">
                <span class="font-mono text-xs font-semibold text-blue-600">{{ booking.booking_number }}</span>
              </td>
              <td class="px-5 py-4">
                <p class="font-medium text-gray-900">{{ booking.customer_name }}</p>
                <p class="text-gray-400 text-xs mt-0.5">{{ booking.customer_phone }}</p>
              </td>
              <td class="px-5 py-4 hidden md:table-cell">
                <p class="text-gray-700 line-clamp-1 max-w-[200px]">
                  {{ booking.items?.map(i => i.service_name).join(', ') }}
                </p>
              </td>
              <td class="px-5 py-4 hidden lg:table-cell">
                <p class="text-gray-700">{{ formatDate(booking.booking_date) }}</p>
                <p class="text-gray-400 text-xs mt-0.5">{{ booking.time_slot }}</p>
              </td>
              <td class="px-5 py-4 hidden lg:table-cell">
                <span class="text-gray-600">{{ booking.technician?.name ?? '—' }}</span>
              </td>
              <td class="px-5 py-4">
                <StatusBadge :status="booking.status" />
              </td>
              <td class="px-5 py-4 hidden sm:table-cell">
                <span class="font-semibold text-gray-900">
                  {{ booking.final_amount ? `₹${booking.final_amount}` : '—' }}
                </span>
              </td>
              <td class="px-5 py-4 text-right">
                <a :href="`/admin/bookings/${booking.id}`"
                   class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                  View →
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="bookings.last_page > 1"
           class="border-t border-gray-100 px-5 py-4 flex items-center justify-between text-sm">
        <p class="text-gray-500">
          Showing {{ bookings.from }}–{{ bookings.to }} of {{ bookings.total }} bookings
        </p>
        <div class="flex gap-2">
          <a v-if="bookings.prev_page_url" :href="bookings.prev_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">← Prev</a>
          <a v-if="bookings.next_page_url" :href="bookings.next_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">Next →</a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  bookings:    { type: Object, required: true },
  filters:     { type: Object, default: () => ({}) },
  technicians: { type: Array,  default: () => [] },
})

const search       = ref(props.filters.search ?? '')
const statusFilter = ref(props.filters.status ?? '')
const dateFilter   = ref(props.filters.date ?? '')

function applyFilters() {
  router.get('/admin/bookings', {
    search:       search.value || undefined,
    status:       statusFilter.value || undefined,
    date:         dateFilter.value || undefined,
  }, { preserveState: true, replace: true })
}

function resetFilters() {
  search.value = ''
  statusFilter.value = ''
  dateFilter.value = ''
  router.get('/admin/bookings')
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>