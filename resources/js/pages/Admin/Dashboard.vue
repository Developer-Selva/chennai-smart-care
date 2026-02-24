<template>
  <!-- resources/js/pages/Admin/Dashboard.vue -->
  <AdminLayout>
    <div class="space-y-6">

      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
          <p class="text-gray-500 text-sm mt-1">{{ todayDate }} · Chennai Smart Care</p>
        </div>
        <div class="flex gap-3">
          <Link href="/admin/bookings/create"
            class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm">
            <PlusIcon class="w-4 h-4" />
            New Booking
          </Link>
        </div>
      </div>

      <!-- ======== METRIC CARDS ======== -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <MetricCard
          title="Today's Bookings"
          :value="metrics.bookings.today"
          :trend="null"
          icon="CalendarIcon"
          color="blue"
        />
        <MetricCard
          title="Pending"
          :value="metrics.bookings.pending"
          subtitle="Needs attention"
          icon="ClockIcon"
          color="yellow"
          :alert="metrics.bookings.pending > 5"
        />
        <MetricCard
          title="Monthly Revenue"
          :value="`₹${formatAmount(metrics.revenue.this_month)}`"
          :trend="revenueTrend"
          icon="CurrencyRupeeIcon"
          color="green"
        />
        <MetricCard
          title="Total Customers"
          :value="metrics.customers.total"
          :subtitle="`+${metrics.customers.new_month} this month`"
          icon="UsersIcon"
          color="purple"
        />
      </div>

      <!-- ======== SECONDARY METRICS ======== -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div v-for="stat in secondaryStats" :key="stat.label"
             class="bg-white rounded-xl border border-gray-200 p-4 text-center">
          <p class="text-2xl font-bold text-gray-900">{{ stat.value }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ stat.label }}</p>
        </div>
      </div>

      <!-- ======== CHARTS ROW ======== -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bookings Chart -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Bookings (Last 30 Days)</h3>
            <span class="text-xs text-gray-400">Daily count</span>
          </div>
          <BookingsChart :data="metrics.bookings_chart" />
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Monthly Revenue</h3>
            <span class="text-xs text-gray-400">Last 6 months</span>
          </div>
          <RevenueChart :data="metrics.revenue_chart" />
        </div>
      </div>

      <!-- ======== UPCOMING BOOKINGS ======== -->
      <div class="bg-white rounded-2xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-semibold text-gray-900">Upcoming Bookings</h3>
          <Link href="/admin/bookings"
            class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            View All →
          </Link>
        </div>
        <div class="divide-y divide-gray-50">
          <div v-for="booking in metrics.upcoming_bookings" :key="booking.id"
               class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
            <!-- Status dot -->
            <div :class="['w-3 h-3 rounded-full flex-shrink-0', statusColors[booking.status]]"></div>

            <!-- Booking info -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <p class="font-semibold text-gray-900 text-sm truncate">{{ booking.customer_name }}</p>
                <span class="text-xs text-gray-400 font-mono">{{ booking.booking_number }}</span>
              </div>
              <p class="text-xs text-gray-500 mt-0.5 truncate">
                {{ booking.items?.map(i => i.service_name).join(', ') }}
              </p>
            </div>

            <!-- Date / Time -->
            <div class="text-right flex-shrink-0">
              <p class="text-sm font-medium text-gray-700">
                {{ formatDate(booking.booking_date) }}
              </p>
              <p class="text-xs text-gray-500">{{ booking.time_slot }}</p>
            </div>

            <!-- Status badge -->
            <StatusBadge :status="booking.status" />

            <!-- Action -->
            <Link  :href="`/admin/bookings/${booking.id}`"
              class="text-blue-600 hover:text-blue-700 flex-shrink-0">
              <ArrowRightIcon class="w-4 h-4" />
            </Link>
          </div>
        </div>
        <div v-if="!metrics.upcoming_bookings?.length"
             class="px-6 py-12 text-center text-gray-400">
          No upcoming bookings
        </div>
      </div>

      <!-- ======== BOTTOM ROW: New Consultations + Technicians ======== -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- New Consultations -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">New Consultations</h3>
            <span v-if="metrics.consultations.new > 0"
                  class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full">
              {{ metrics.consultations.new }} new
            </span>
          </div>
          <p v-if="metrics.consultations.new === 0" class="text-gray-400 text-sm">No new consultation requests.</p>
          <div v-else class="space-y-2">
            <Link href="/admin/consultations"
              class="block w-full text-center bg-blue-50 text-blue-700 py-3 rounded-xl text-sm font-semibold hover:bg-blue-100 transition-colors">
              Review {{ metrics.consultations.new }} Consultation{{ metrics.consultations.new > 1 ? 's' : '' }}
            </Link>
          </div>
        </div>

        <!-- Technician Availability -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
          <h3 class="font-semibold text-gray-900 mb-4">Technician Status</h3>
          <div class="flex items-center gap-4">
            <div class="flex-1">
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Available</span>
                <span class="font-semibold text-green-600">{{ metrics.technicians.available }}</span>
              </div>
              <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div
                  class="h-full bg-green-500 rounded-full transition-all"
                  :style="{ width: `${(metrics.technicians.available / metrics.technicians.total) * 100}%` }"
                ></div>
              </div>
            </div>
            <div class="text-center">
              <p class="text-2xl font-bold text-gray-900">{{ metrics.technicians.available }}/{{ metrics.technicians.total }}</p>
              <p class="text-xs text-gray-500">available</p>
            </div>
          </div>
          <Link href="/admin/technicians"
            class="mt-4 block text-center text-blue-600 text-sm font-medium hover:text-blue-700">
            Manage Technicians →
          </Link>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { PlusIcon, ArrowRightIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import MetricCard from '@/components/Admin/MetricCard.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'
import BookingsChart from '@/components/Admin/BookingsChart.vue'
import RevenueChart from '@/components/Admin/RevenueChart.vue'

const props = defineProps({
  metrics: Object,
})

const todayDate = new Date().toLocaleDateString('en-IN', {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
})

const statusColors = {
  pending: 'bg-yellow-400',
  confirmed: 'bg-blue-500',
  assigned: 'bg-indigo-500',
  in_progress: 'bg-purple-500',
  completed: 'bg-green-500',
  cancelled: 'bg-red-500',
}

const revenueTrend = computed(() => {
  const curr = props.metrics?.revenue?.this_month || 0
  const prev = props.metrics?.revenue?.last_month || 1
  const pct = (((curr - prev) / prev) * 100).toFixed(1)
  return { value: pct, positive: pct >= 0 }
})

const secondaryStats = computed(() => [
  { value: props.metrics?.bookings?.confirmed, label: 'Confirmed Today' },
  { value: props.metrics?.bookings?.in_progress, label: 'In Progress' },
  { value: props.metrics?.bookings?.completed_today, label: 'Completed Today' },
  { value: props.metrics?.bookings?.this_month, label: 'Bookings This Month' },
])

function formatAmount(amount) {
  if (!amount) return '0'
  return new Intl.NumberFormat('en-IN').format(Math.round(amount))
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('en-IN', { day: 'numeric', month: 'short' })
}
</script>