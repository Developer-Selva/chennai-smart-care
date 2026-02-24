<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-xl font-bold text-gray-900">Reports</h1>
        <p class="text-sm text-gray-500 mt-0.5">Booking and revenue reports</p>
      </div>
    </template>

    <!-- Date filter -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 flex flex-wrap items-end gap-4">
      <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">From</label>
        <input v-model="from" type="date" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">To</label>
        <input v-model="to" type="date" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <button @click="applyFilter"
              class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
        Apply
      </button>
      <a :href="`/admin/reports/export?from=${from}&to=${to}`"
         class="px-5 py-2 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors flex items-center gap-2">
        ⬇ Export CSV
      </a>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
        <p class="text-2xl font-extrabold text-gray-900">{{ totalBookings }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Bookings</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
        <p class="text-2xl font-extrabold text-green-600">₹{{ formatAmount(totalRevenue) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
        <p class="text-2xl font-extrabold text-gray-900">{{ completedCount }}</p>
        <p class="text-xs text-gray-500 mt-1">Completed</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
        <p class="text-2xl font-extrabold text-gray-900">₹{{ avgAmount }}</p>
        <p class="text-xs text-gray-500 mt-1">Avg per Booking</p>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="text-left px-5 py-3 font-semibold text-gray-600">Booking #</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600">Customer</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden md:table-cell">Services</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600">Date</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden sm:table-cell">Status</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600">Amount</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="!bookings.length">
              <td colspan="6" class="text-center py-12 text-gray-400">No bookings in this period.</td>
            </tr>
            <tr v-for="b in bookings" :key="b.id" class="hover:bg-gray-50">
              <td class="px-5 py-3 font-mono text-xs text-blue-600">{{ b.booking_number }}</td>
              <td class="px-5 py-3 text-gray-900">{{ b.customer_name }}</td>
              <td class="px-5 py-3 hidden md:table-cell text-gray-600 max-w-[200px] truncate">
                {{ b.items?.map(i => i.service_name).join(', ') }}
              </td>
              <td class="px-5 py-3 text-gray-600">{{ formatDate(b.booking_date) }}</td>
              <td class="px-5 py-3 hidden sm:table-cell">
                <StatusBadge :status="b.status" size="sm" />
              </td>
              <td class="px-5 py-3 font-semibold text-gray-900">
                {{ b.final_amount ? `₹${b.final_amount}` : '—' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  bookings:     { type: Array,  default: () => [] },
  totalRevenue: { type: Number, default: 0 },
  totalBookings:{ type: Number, default: 0 },
  from:         { type: String, default: '' },
  to:           { type: String, default: '' },
})

const from = ref(props.from)
const to   = ref(props.to)

const completedCount = computed(() => props.bookings.filter(b => b.status === 'completed').length)
const avgAmount = computed(() => {
  if (!completedCount.value) return 0
  const total = props.bookings.filter(b => b.status === 'completed').reduce((s, b) => s + (b.final_amount ?? 0), 0)
  return formatAmount(total / completedCount.value)
})

function applyFilter() {
  router.get('/admin/reports', { from: from.value, to: to.value }, { preserveState: true })
}

function formatAmount(n) {
  return new Intl.NumberFormat('en-IN').format(Math.round(n))
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short' })
}
</script>