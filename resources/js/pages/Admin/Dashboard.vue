<template>
  <AdminLayout>
    <div class="space-y-6 px-4 sm:px-0">

      <!-- Page Header with Welcome Message -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in-down">
        <div>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
              <CalendarIcon class="w-6 h-6" />
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Welcome back, Admin</h1>
              <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                <CalendarIcon class="w-4 h-4" />
                {{ todayDate }} · Chennai Smart Care
              </p>
            </div>
          </div>
        </div>
        
        <!-- Action Buttons Group -->
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
          <Link href="/admin/bookings/create"
            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 sm:py-2.5 rounded-xl font-semibold text-sm hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-200 hover:-translate-y-0.5">
            <PlusIcon class="w-4 h-4" />
            New Booking
          </Link>
          <button @click="refreshDashboard" 
                  class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-3 sm:py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-all">
            <ArrowPathIcon class="w-4 h-4" :class="{ 'animate-spin': isRefreshing }" />
            Refresh
          </button>
        </div>
      </div>

      <!-- Quick Stats Summary (Mobile View) -->
      <div class="grid grid-cols-2 gap-3 sm:hidden">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4">
          <p class="text-2xl font-bold text-blue-600">{{ metrics.bookings.today }}</p>
          <p class="text-xs text-gray-600">Today's Bookings</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-4">
          <p class="text-2xl font-bold text-yellow-600">{{ metrics.bookings.pending }}</p>
          <p class="text-xs text-gray-600">Pending</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4">
          <p class="text-2xl font-bold text-green-600">₹{{ formatAmount(metrics.revenue.this_month) }}</p>
          <p class="text-xs text-gray-600">Monthly Revenue</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4">
          <p class="text-2xl font-bold text-purple-600">{{ metrics.customers.total }}</p>
          <p class="text-xs text-gray-600">Total Customers</p>
        </div>
      </div>

      <!-- ======== METRIC CARDS (Desktop) ======== -->
      <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
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
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div v-for="stat in secondaryStats" :key="stat.label"
             class="bg-white rounded-xl border border-gray-200 p-3 sm:p-4 text-center hover:shadow-md transition-all hover:-translate-y-0.5">
          <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ stat.value }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ stat.label }}</p>
          <p v-if="stat.subtext" class="text-xs text-gray-400 mt-0.5">{{ stat.subtext }}</p>
        </div>
      </div>

      <!-- ======== CHARTS ROW ======== -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Bookings Chart -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-all">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <CalendarIcon class="w-4 h-4 text-blue-600" />
              </div>
              <h3 class="font-semibold text-gray-900">Bookings Trend</h3>
            </div>
            <span class="text-xs text-gray-400">Last 30 days</span>
          </div>
          <BookingsChart :data="metrics.bookings_chart" />
          <div class="mt-3 text-xs text-gray-400 flex items-center justify-between">
            <span>Total: {{ metrics.bookings.this_month }}</span>
            <span class="flex items-center gap-1">
              <ArrowTrendingUpIcon v-if="bookingTrend > 0" class="w-3 h-3 text-green-500" />
              <ArrowTrendingDownIcon v-else class="w-3 h-3 text-red-500" />
              {{ Math.abs(bookingTrend) }}% vs last month
            </span>
          </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-all">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                <CurrencyRupeeIcon class="w-4 h-4 text-green-600" />
              </div>
              <h3 class="font-semibold text-gray-900">Revenue</h3>
            </div>
            <span class="text-xs text-gray-400">Last 6 months</span>
          </div>
          <RevenueChart :data="metrics.revenue_chart" />
          <div class="mt-3 text-xs text-gray-400 flex items-center justify-between">
            <span>Avg: ₹{{ formatAmount(averageRevenue) }}/month</span>
            <span class="flex items-center gap-1">
              <ArrowTrendingUpIcon v-if="revenueTrend.value > 0" class="w-3 h-3 text-green-500" />
              <ArrowTrendingDownIcon v-else class="w-3 h-3 text-red-500" />
              {{ revenueTrend.value }}% vs last month
            </span>
          </div>
        </div>
      </div>

      <!-- ======== UPCOMING BOOKINGS with Mobile Optimization ======== -->
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                <CalendarIcon class="w-4 h-4 text-amber-600" />
              </div>
              <h3 class="font-semibold text-gray-900">Upcoming Bookings</h3>
            </div>
            <Link href="/admin/bookings"
              class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
              View All
              <ArrowRightIcon class="w-3 h-3" />
            </Link>
          </div>
        </div>

        <!-- Mobile View: Card Layout -->
        <div v-if="metrics.upcoming_bookings?.length" class="block sm:hidden divide-y divide-gray-100">
          <div v-for="booking in metrics.upcoming_bookings.slice(0, 5)" :key="booking.id"
               class="p-4 hover:bg-gray-50 transition-colors">
            <Link :href="`/admin/bookings/${booking.id}`" class="block">
              <div class="flex items-start gap-3">
                <div :class="['w-2 h-2 rounded-full mt-2 flex-shrink-0', statusColors[booking.status]]"></div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <p class="font-semibold text-gray-900 text-sm truncate">{{ booking.customer_name }}</p>
                    <span class="text-xs text-gray-400 font-mono">{{ booking.booking_number }}</span>
                  </div>
                  <p class="text-xs text-gray-500 mb-2 line-clamp-2">
                    {{ booking.items?.map(i => i.service_name).join(', ') }}
                  </p>
                  <div class="flex items-center gap-2 text-xs">
                    <span class="flex items-center gap-1 text-gray-600">
                      <CalendarIcon class="w-3 h-3" />
                      {{ formatDate(booking.booking_date) }}
                    </span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span class="flex items-center gap-1 text-gray-600">
                      <ClockIcon class="w-3 h-3" />
                      {{ booking.time_slot }}
                    </span>
                  </div>
                </div>
                <StatusBadge :status="booking.status" size="sm" />
              </div>
            </Link>
          </div>
        </div>

        <!-- Desktop View: Table Layout -->
        <div v-if="metrics.upcoming_bookings?.length" class="hidden sm:block divide-y divide-gray-50">
          <div v-for="booking in metrics.upcoming_bookings" :key="booking.id"
               class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
            <div :class="['w-3 h-3 rounded-full flex-shrink-0', statusColors[booking.status]]"></div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <p class="font-semibold text-gray-900 text-sm truncate">{{ booking.customer_name }}</p>
                <span class="text-xs text-gray-400 font-mono">{{ booking.booking_number }}</span>
              </div>
              <p class="text-xs text-gray-500 mt-0.5 truncate">
                {{ booking.items?.map(i => i.service_name).join(', ') }}
              </p>
            </div>
            <div class="text-right flex-shrink-0">
              <p class="text-sm font-medium text-gray-700">{{ formatDate(booking.booking_date) }}</p>
              <p class="text-xs text-gray-500">{{ booking.time_slot }}</p>
            </div>
            <StatusBadge :status="booking.status" />
            <Link :href="`/admin/bookings/${booking.id}`"
              class="text-blue-600 hover:text-blue-700 flex-shrink-0 p-2 hover:bg-blue-50 rounded-lg transition-colors">
              <ArrowRightIcon class="w-4 h-4" />
            </Link>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="!metrics.upcoming_bookings?.length"
             class="px-6 py-12 text-center">
          <CalendarIcon class="w-12 h-12 text-gray-300 mx-auto mb-3" />
          <p class="text-gray-500 font-medium">No upcoming bookings</p>
          <p class="text-xs text-gray-400 mt-1">New bookings will appear here</p>
          <Link href="/admin/bookings/create"
                class="inline-block mt-4 text-blue-600 text-sm font-medium hover:text-blue-700">
            Create your first booking →
          </Link>
        </div>

        <!-- View All Link (Mobile) -->
        <div v-if="metrics.upcoming_bookings?.length > 5" 
             class="block sm:hidden px-4 py-3 border-t border-gray-100 text-center">
          <Link href="/admin/bookings"
                class="text-sm text-blue-600 font-medium hover:text-blue-700">
            View all {{ metrics.upcoming_bookings.length }} bookings
          </Link>
        </div>
      </div>

      <!-- ======== BOTTOM ROW: New Consultations + Technicians ======== -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- New Consultations -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-all">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                <ChatBubbleLeftRightIcon class="w-4 h-4 text-red-600" />
              </div>
              <h3 class="font-semibold text-gray-900">New Consultations</h3>
            </div>
            <span v-if="metrics.consultations.new > 0"
                  class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full animate-pulse">
              {{ metrics.consultations.new }} new
            </span>
          </div>

          <div v-if="metrics.consultations.new === 0" class="text-center py-6">
            <ChatBubbleLeftRightIcon class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-gray-500 text-sm">No new consultation requests</p>
          </div>

          <div v-else class="space-y-3">
            <!-- Quick preview of latest consultations -->
            <div v-for="consult in recentConsultations" :key="consult.id"
                 class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
              <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                <UserIcon class="w-4 h-4 text-gray-600" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ consult.name }}</p>
                <p class="text-xs text-gray-500">{{ consult.service_interest }}</p>
              </div>
              <span class="text-xs text-gray-400">{{ formatTime(consult.created_at) }}</span>
            </div>
            
            <Link href="/admin/consultations"
              class="block w-full text-center bg-gradient-to-r from-red-50 to-orange-50 text-red-700 py-3 rounded-xl text-sm font-semibold hover:from-red-100 hover:to-orange-100 transition-all border border-red-200">
              Review {{ metrics.consultations.new }} Consultation{{ metrics.consultations.new > 1 ? 's' : '' }}
              <ArrowRightIcon class="w-3 h-3 inline ml-1" />
            </Link>
          </div>
        </div>

        <!-- Technician Availability with Enhanced UI -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 hover:shadow-lg transition-all">
          <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
              <UserGroupIcon class="w-4 h-4 text-purple-600" />
            </div>
            <h3 class="font-semibold text-gray-900">Technician Status</h3>
          </div>

          <!-- Availability Gauge -->
          <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
            <div class="relative w-24 h-24 sm:w-32 sm:h-32">
              <svg class="w-full h-full" viewBox="0 0 100 100">
                <circle
                  class="text-gray-200 stroke-current"
                  stroke-width="10"
                  fill="transparent"
                  r="40"
                  cx="50"
                  cy="50"
                />
                <circle
                  class="text-green-500 stroke-current transition-all duration-1000"
                  stroke-width="10"
                  stroke-linecap="round"
                  fill="transparent"
                  r="40"
                  cx="50"
                  cy="50"
                  :stroke-dasharray="`${2 * Math.PI * 40}`"
                  :stroke-dashoffset="2 * Math.PI * 40 * (1 - availabilityPercentage / 100)"
                  transform="rotate(-90 50 50)"
                />
                <text x="50" y="50" text-anchor="middle" dy=".3em" class="text-2xl font-bold fill-gray-900">
                  {{ availabilityPercentage }}%
                </text>
              </svg>
            </div>

            <div class="flex-1 w-full">
              <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Available Technicians</span>
                <span class="font-bold text-green-600">{{ metrics.technicians.available }}/{{ metrics.technicians.total }}</span>
              </div>
              <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div
                  class="h-full bg-gradient-to-r from-green-400 to-green-500 rounded-full transition-all duration-500"
                  :style="{ width: `${availabilityPercentage}%` }"
                ></div>
              </div>

              <!-- Status breakdown -->
              <div class="grid grid-cols-2 gap-2 mt-4">
                <div class="bg-green-50 rounded-lg p-2 text-center">
                  <p class="text-lg font-bold text-green-700">{{ metrics.technicians.available }}</p>
                  <p class="text-xs text-green-600">Available</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-2 text-center">
                  <p class="text-lg font-bold text-yellow-700">{{ metrics.technicians.on_break || 0 }}</p>
                  <p class="text-xs text-yellow-600">On Break</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-2 text-center">
                  <p class="text-lg font-bold text-blue-700">{{ metrics.technicians.on_leave || 0 }}</p>
                  <p class="text-xs text-blue-600">On Leave</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-2 text-center">
                  <p class="text-lg font-bold text-gray-700">{{ metrics.technicians.busy || 0 }}</p>
                  <p class="text-xs text-gray-600">Busy</p>
                </div>
              </div>
            </div>
          </div>

          <Link href="/admin/technicians"
            class="mt-4 block text-center text-blue-600 text-sm font-medium hover:text-blue-700 py-2 border-t border-gray-100 pt-4">
            Manage Technicians
            <ArrowRightIcon class="w-3 h-3 inline ml-1" />
          </Link>
        </div>
      </div>

      <!-- Recent Activity Feed (Optional) -->
      <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
            <ClockIcon class="w-4 h-4 text-blue-600" />
          </div>
          <h3 class="font-semibold text-gray-900">Recent Activity</h3>
        </div>
        
        <div class="space-y-3">
          <div v-for="activity in recentActivities" :key="activity.id"
               class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                 :class="activityIconBg[activity.type]">
              <component :is="activityIcon[activity.type]" class="w-4 h-4" :class="activityIconColor[activity.type]" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-900">
                <span class="font-medium">{{ activity.user }}</span>
                {{ activity.action }}
              </p>
              <p class="text-xs text-gray-500 mt-1">{{ activity.details }}</p>
            </div>
            <span class="text-xs text-gray-400 whitespace-nowrap">{{ formatTimeAgo(activity.time) }}</span>
          </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
  PlusIcon, ArrowRightIcon, CalendarIcon, ClockIcon,
  CurrencyRupeeIcon, UsersIcon, UserGroupIcon,
  ChatBubbleLeftRightIcon, UserIcon, ArrowPathIcon,
  ArrowTrendingUpIcon, ArrowTrendingDownIcon,
  CheckCircleIcon, XCircleIcon, BellAlertIcon
} from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
import MetricCard from '@/components/Admin/MetricCard.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'
import BookingsChart from '@/components/Admin/BookingsChart.vue'
import RevenueChart from '@/components/Admin/RevenueChart.vue'

const props = defineProps({
  metrics: Object,
})

const isRefreshing = ref(false)

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

// Revenue trend calculation
const revenueTrend = computed(() => {
  const curr = props.metrics?.revenue?.this_month || 0
  const prev = props.metrics?.revenue?.last_month || 1
  const pct = (((curr - prev) / prev) * 100).toFixed(1)
  return { value: parseFloat(pct), positive: pct >= 0 }
})

// Booking trend
const bookingTrend = computed(() => {
  const curr = props.metrics?.bookings?.this_month || 0
  const prev = props.metrics?.bookings?.last_month || 1
  return (((curr - prev) / prev) * 100).toFixed(1)
})

// Average revenue
const averageRevenue = computed(() => {
  const chartData = props.metrics?.revenue_chart || []
  if (chartData.length === 0) return 0
  const sum = chartData.reduce((acc, item) => acc + (item.value || 0), 0)
  return sum / chartData.length
})

// Secondary stats with subtext
const secondaryStats = computed(() => [
  { 
    value: props.metrics?.bookings?.confirmed || 0, 
    label: 'Confirmed Today',
    subtext: getPercentageChange('confirmed')
  },
  { 
    value: props.metrics?.bookings?.in_progress || 0, 
    label: 'In Progress',
    subtext: 'Active services'
  },
  { 
    value: props.metrics?.bookings?.completed_today || 0, 
    label: 'Completed Today',
    subtext: '✓ Done'
  },
  { 
    value: props.metrics?.bookings?.this_month || 0, 
    label: 'Bookings This Month',
    subtext: `+${props.metrics?.bookings?.this_month_prev || 0} vs last month`
  },
])

// Technician availability percentage
const availabilityPercentage = computed(() => {
  const total = props.metrics?.technicians?.total || 1
  const available = props.metrics?.technicians?.available || 0
  return Math.round((available / total) * 100)
})

// Recent consultations (first 3)
const recentConsultations = computed(() => {
  return props.metrics?.consultations?.recent?.slice(0, 3) || []
})

// Recent activities (mock data - replace with real data from backend)
const recentActivities = computed(() => {
  return props.metrics?.recent_activities || [
    { id: 1, type: 'booking', user: 'John Doe', action: 'created a new booking', details: 'AC Repair - Today 2PM', time: new Date(Date.now() - 15 * 60000) },
    { id: 2, type: 'payment', user: 'Jane Smith', action: 'completed payment', details: 'Invoice #INV-2024-001', time: new Date(Date.now() - 45 * 60000) },
    { id: 3, type: 'technician', user: 'Rajesh Kumar', action: 'assigned to booking', details: '#CSC-2024-0012', time: new Date(Date.now() - 2 * 60 * 60000) },
  ]
})

// Activity icons mapping
const activityIcon = {
  booking: CalendarIcon,
  payment: CurrencyRupeeIcon,
  technician: UserIcon,
  consultation: ChatBubbleLeftRightIcon,
  system: BellAlertIcon
}

const activityIconBg = {
  booking: 'bg-blue-50',
  payment: 'bg-green-50',
  technician: 'bg-purple-50',
  consultation: 'bg-yellow-50',
  system: 'bg-gray-50'
}

const activityIconColor = {
  booking: 'text-blue-600',
  payment: 'text-green-600',
  technician: 'text-purple-600',
  consultation: 'text-yellow-600',
  system: 'text-gray-600'
}

// Helper function for percentage change
function getPercentageChange(type) {
  const today = props.metrics?.bookings?.[type] || 0
  const yesterday = props.metrics?.bookings?.[`${type}_yesterday`] || 0
  if (yesterday === 0) return today > 0 ? '+100%' : '0%'
  const change = ((today - yesterday) / yesterday * 100).toFixed(0)
  return change > 0 ? `+${change}%` : `${change}%`
}

// Formatting functions
function formatAmount(amount) {
  if (!amount) return '0'
  return new Intl.NumberFormat('en-IN').format(Math.round(amount))
}

function formatDate(date) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-IN', { day: 'numeric', month: 'short' })
}

function formatTime(date) {
  if (!date) return ''
  return new Date(date).toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })
}

function formatTimeAgo(date) {
  const now = new Date()
  const diffMs = now - new Date(date)
  const diffMins = Math.round(diffMs / 60000)
  
  if (diffMins < 1) return 'Just now'
  if (diffMins < 60) return `${diffMins}m ago`
  if (diffMins < 1440) return `${Math.round(diffMins / 60)}h ago`
  return `${Math.round(diffMins / 1440)}d ago`
}

// Refresh dashboard
function refreshDashboard() {
  isRefreshing.value = true
  // Implement your refresh logic here
  setTimeout(() => {
    isRefreshing.value = false
  }, 1000)
}

// Auto-refresh every 5 minutes
onMounted(() => {
  const interval = setInterval(refreshDashboard, 300000)
  return () => clearInterval(interval)
})
</script>

<style scoped>
/* Animations */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-down {
  animation: fadeInDown 0.6s ease-out;
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Mobile optimizations */
@media (max-width: 640px) {
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}

/* Hover effects */
.hover\:shadow-lg:hover {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
}

.hover\:-translate-y-0\.5:hover {
  transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>