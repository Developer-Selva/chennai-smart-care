<template>
  <div class="min-h-screen bg-gray-50">
    <Head><title>My Dashboard — Chennai Smart Care</title></Head>
    <AppHeader :minimal="true" />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ user.name.split(' ')[0] }}!</h1>
        <p class="text-gray-500 text-sm mt-1">Manage your bookings and track service history</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div v-for="stat in stats" :key="stat.label"
             class="bg-white rounded-2xl border border-gray-200 p-4 text-center">
          <p class="text-2xl font-extrabold text-gray-900">{{ stat.value }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ stat.label }}</p>
        </div>
      </div>

      <!-- Active Bookings -->
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <h2 class="font-bold text-gray-900">Active Bookings</h2>
          <a href="/quick-booking" class="text-blue-600 text-sm font-semibold hover:text-blue-700">+ New Booking</a>
        </div>
        <div v-if="activeBookings?.length" class="divide-y divide-gray-100">
          <div v-for="booking in activeBookings" :key="booking.id"
               class="px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-gray-900 text-sm">{{ booking.items?.map(i => i.service_name).join(', ') }}</p>
              <p class="text-gray-400 text-xs mt-0.5 font-mono">{{ booking.booking_number }}</p>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="text-sm text-gray-600">{{ formatDate(booking.booking_date) }}</p>
              <p class="text-xs text-gray-400">{{ booking.time_slot }}</p>
            </div>
            <StatusBadge :status="booking.status" size="sm" />
            <a :href="`/user/bookings/${booking.booking_number}`"
               class="text-blue-600 text-xs font-semibold flex-shrink-0">View →</a>
          </div>
        </div>
        <div v-else class="text-center py-10 text-gray-400 text-sm">
          No active bookings.
          <a href="/quick-booking" class="text-blue-600 font-semibold ml-1">Book a service</a>
        </div>
      </div>

      <!-- Past Bookings -->
      <div v-if="pastBookings?.length" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="font-bold text-gray-900">Service History</h2>
        </div>
        <div class="divide-y divide-gray-100">
          <div v-for="booking in pastBookings" :key="booking.id"
               class="px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
              <p class="font-medium text-gray-900 text-sm">{{ booking.items?.map(i => i.service_name).join(', ') }}</p>
              <p class="text-gray-400 text-xs mt-0.5">{{ formatDate(booking.booking_date) }}</p>
            </div>
            <StatusBadge :status="booking.status" size="sm" />
            <a :href="`/user/bookings/${booking.booking_number}`"
               class="text-blue-600 text-xs font-semibold flex-shrink-0">View →</a>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppHeader  from '@/components/Landing/AppHeader.vue'
import AppFooter  from '@/components/Landing/AppFooter.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  user:     { type: Object, required: true },
  bookings: { type: Array,  default: () => [] },
})

const activeStatuses = ['pending', 'confirmed', 'assigned', 'in_progress', 'rescheduled']
const activeBookings = computed(() => props.bookings.filter(b => activeStatuses.includes(b.status)))
const pastBookings   = computed(() => props.bookings.filter(b => !activeStatuses.includes(b.status)))

const stats = computed(() => [
  { value: props.bookings.length,                              label: 'Total Bookings'  },
  { value: activeBookings.value.length,                        label: 'Active'          },
  { value: props.bookings.filter(b => b.status === 'completed').length, label: 'Completed' },
  { value: props.user.loyalty_points ?? 0,                    label: 'Loyalty Points'  },
])

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>