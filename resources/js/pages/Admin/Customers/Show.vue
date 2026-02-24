<template>
  <AdminLayout>
    <div class="max-w-5xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-center gap-3">
        <a href="/admin/customers" class="text-gray-500 hover:text-gray-700 transition-colors">
          <ArrowLeftIcon class="w-5 h-5" />
        </a>
        <div class="flex-1">
          <h1 class="text-xl font-bold text-gray-900">{{ customer.name }}</h1>
          <p class="text-sm text-gray-500">Customer since {{ formatDate(customer.created_at) }}</p>
        </div>
        <span :class="[
          'text-xs font-semibold px-3 py-1 rounded-full',
          customer.bookings_count > 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600'
        ]">
          {{ customer.bookings_count > 5 ? '⭐ Loyal Customer' : 'Customer' }}
        </span>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Profile + Stats -->
        <div class="space-y-4">

          <!-- Profile Card -->
          <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex flex-col items-center text-center mb-5">
              <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-2xl font-bold text-blue-600 mb-3">
                {{ initials }}
              </div>
              <h2 class="font-bold text-gray-900">{{ customer.name }}</h2>
              <p class="text-sm text-gray-500 mt-0.5">ID #{{ customer.id }}</p>
            </div>

            <div class="space-y-3 text-sm">
              <div class="flex items-center gap-3">
                <PhoneIcon class="w-4 h-4 text-gray-400 flex-shrink-0" />
                <a :href="`tel:${customer.phone}`" class="text-blue-600 hover:underline font-medium">
                  +91 {{ customer.phone }}
                </a>
              </div>
              <div v-if="customer.email" class="flex items-center gap-3">
                <EnvelopeIcon class="w-4 h-4 text-gray-400 flex-shrink-0" />
                <a :href="`mailto:${customer.email}`" class="text-blue-600 hover:underline">
                  {{ customer.email }}
                </a>
              </div>
              <div v-if="customer.address" class="flex items-start gap-3">
                <MapPinIcon class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" />
                <span class="text-gray-600">{{ customer.address }}</span>
              </div>
            </div>

            <div class="mt-5 pt-4 border-t border-gray-100 flex gap-2">
              <a :href="`https://wa.me/91${customer.phone}`" target="_blank"
                 class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-green-50 text-green-700 rounded-xl text-xs font-semibold hover:bg-green-100 transition-colors">
                WhatsApp
              </a>
              <a :href="`tel:${customer.phone}`"
                 class="flex-1 flex items-center justify-center gap-1.5 py-2 bg-blue-50 text-blue-700 rounded-xl text-xs font-semibold hover:bg-blue-100 transition-colors">
                <PhoneIcon class="w-3.5 h-3.5" /> Call
              </a>
            </div>
          </div>

          <!-- Stats -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5 grid grid-cols-2 gap-4 text-center">
            <div>
              <p class="text-2xl font-extrabold text-gray-900">{{ customer.bookings_count }}</p>
              <p class="text-xs text-gray-500 mt-0.5">Total Bookings</p>
            </div>
            <div>
              <p class="text-2xl font-extrabold text-green-600">₹{{ totalSpend }}</p>
              <p class="text-xs text-gray-500 mt-0.5">Total Spend</p>
            </div>
            <div>
              <p class="text-2xl font-extrabold text-blue-600">{{ completedCount }}</p>
              <p class="text-xs text-gray-500 mt-0.5">Completed</p>
            </div>
            <div>
              <p class="text-2xl font-extrabold text-yellow-600">{{ customer.loyalty_points ?? 0 }}</p>
              <p class="text-xs text-gray-500 mt-0.5">Loyalty Pts</p>
            </div>
          </div>
        </div>

        <!-- Right: Booking History -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h3 class="font-semibold text-gray-900">Booking History</h3>
              <span class="text-sm text-gray-400">Last {{ customer.bookings?.length ?? 0 }} bookings</span>
            </div>

            <div v-if="customer.bookings?.length" class="divide-y divide-gray-100">
              <div v-for="booking in customer.bookings" :key="booking.id"
                   class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-sm font-semibold text-gray-900">{{ booking.booking_number }}</span>
                    <StatusBadge :status="booking.status" size="sm" />
                  </div>
                  <p class="text-xs text-gray-500">
                    {{ formatDate(booking.booking_date) }} · {{ booking.time_slot }}
                  </p>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="font-bold text-gray-900">₹{{ booking.final_amount ?? booking.total_amount ?? '—' }}</p>
                  <a :href="`/admin/bookings/${booking.id}`"
                     class="text-xs text-blue-600 hover:underline mt-0.5 block">View →</a>
                </div>
              </div>
            </div>

            <div v-else class="px-6 py-12 text-center text-gray-400">
              <CalendarIcon class="w-10 h-10 mx-auto mb-3 opacity-40" />
              <p class="text-sm">No bookings yet</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import {
  ArrowLeftIcon, PhoneIcon, EnvelopeIcon,
  MapPinIcon, CalendarIcon,
} from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  customer: { type: Object, required: true },
})

const initials = computed(() => {
  return (props.customer.name ?? 'U')
    .split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const completedCount = computed(() =>
  props.customer.bookings?.filter(b => b.status === 'completed').length ?? 0
)

const totalSpend = computed(() =>
  props.customer.bookings
    ?.filter(b => b.status === 'completed')
    ?.reduce((sum, b) => sum + parseFloat(b.final_amount ?? b.total_amount ?? 0), 0)
    ?.toFixed(0) ?? 0
)

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', {
    day: 'numeric', month: 'short', year: 'numeric',
  })
}
</script>