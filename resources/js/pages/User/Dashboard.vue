<template>
  <div class="min-h-screen bg-gray-50">
    <Head><title>My Dashboard — Chennai Smart Care</title></Head>
    <AppHeader :minimal="true" />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

      <!-- Welcome -->
      <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ firstName }}!</h1>
          <p class="text-gray-500 text-sm mt-1">Your service dashboard</p>
        </div>
        <a href="/quick-booking"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
          <PlusIcon class="w-4 h-4" /> Book a Service
        </a>
      </div>

      <!-- Stats strip -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        <div v-for="stat in stats" :key="stat.label"
             class="bg-white rounded-2xl border border-gray-200 p-4 text-center">
          <p class="text-2xl font-extrabold" :class="stat.color ?? 'text-gray-900'">{{ stat.value }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ stat.label }}</p>
        </div>
      </div>

      <!-- ═══ AMC BANNER (if active) ══════════════════════════════ -->
      <div v-if="amc?.is_active"
           class="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-5 mb-6 text-white shadow-lg">
        <!-- BG pattern -->
        <div class="absolute inset-0 opacity-10"
             style="background-image:radial-gradient(circle at 20% 50%,white 1px,transparent 1px),radial-gradient(circle at 80% 50%,white 1px,transparent 1px);background-size:30px 30px;"></div>

        <div class="relative flex flex-wrap items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
              <ShieldCheckIcon class="w-7 h-7 text-white" />
            </div>
            <div>
              <div class="flex items-center gap-2 mb-0.5">
                <span class="text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full uppercase tracking-wide">
                  {{ amc.type === 'free' ? '🎁 Free AMC' : 'AMC Active' }}
                </span>
                <span class="font-mono text-xs text-emerald-200">{{ amc.amc_number }}</span>
              </div>
              <p class="font-bold text-lg leading-tight">Annual Maintenance Contract</p>
              <p class="text-emerald-200 text-sm">Valid till {{ formatDate(amc.expires_at) }} · {{ amc.days_remaining }} days left</p>
            </div>
          </div>

          <!-- AMC benefits chips -->
          <div class="flex flex-wrap gap-2">
            <span class="bg-white/15 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
              🔧 {{ amc.free_services_remaining }}/{{ amc.free_services_total }} Free Calls
            </span>
            <span class="bg-white/15 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
              💸 {{ amc.discount_percent }}% Off All Repairs
            </span>
            <span class="bg-white/15 text-white text-xs font-semibold px-3 py-1.5 rounded-full">
              ⚡ Priority Booking
            </span>
          </div>
        </div>

        <!-- Progress bar -->
        <div class="relative mt-4">
          <div class="flex justify-between text-xs text-emerald-200 mb-1">
            <span>AMC Period</span>
            <span>{{ amc.progress_percent }}% elapsed</span>
          </div>
          <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
            <div class="h-full bg-white/70 rounded-full transition-all duration-500"
                 :style="`width:${amc.progress_percent}%`"></div>
          </div>
        </div>
      </div>

      <!-- ═══ AMC UNLOCK PROGRESS (if no AMC yet) ═════════════════ -->
      <div v-else
           class="bg-white rounded-2xl border border-gray-200 p-5 mb-6">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
            <TrophyIcon class="w-6 h-6 text-amber-500" />
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
              <p class="font-bold text-gray-900">Unlock Free AMC</p>
              <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
                ₹{{ formatCurrency(currentYearSpend) }} / ₹5,000
              </span>
            </div>
            <p class="text-sm text-gray-500 mb-3">
              <span v-if="spendToUnlockAmc > 0">
                Spend <strong class="text-gray-800">₹{{ formatCurrency(spendToUnlockAmc) }} more</strong> this year to get a FREE 1-year AMC worth ₹1,499!
              </span>
              <span v-else class="text-emerald-600 font-semibold">
                🎉 You've qualified! Contact us to activate your free AMC.
              </span>
            </p>
            <!-- Progress bar -->
            <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-700"
                   :style="`width:${spendProgressPct}%`"></div>
            </div>
            <!-- Benefits preview -->
            <div class="flex flex-wrap gap-2 mt-3">
              <span v-for="b in amcBenefits" :key="b"
                    class="text-xs text-gray-600 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-full">
                {{ b }}
              </span>
            </div>
          </div>
        </div>

        <!-- Paid AMC CTA -->
        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
          <p class="text-sm text-gray-500">Don't want to wait? Subscribe now for <strong class="text-gray-800">₹1,499/year</strong></p>
          <a href="https://wa.me/919444900470?text=I'd+like+to+subscribe+to+AMC"
             target="_blank"
             class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-800 transition-colors">
            Subscribe to AMC →
          </a>
        </div>
      </div>

      <!-- ═══ WARRANTIES ══════════════════════════════════════════ -->
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <ShieldCheckIcon class="w-5 h-5 text-blue-600" />
            <h2 class="font-bold text-gray-900">My Warranties</h2>
            <span v-if="activeWarrantyCount"
                  class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
              {{ activeWarrantyCount }} Active
            </span>
          </div>
          <span class="text-xs text-gray-400">6 months on all repairs</span>
        </div>

        <!-- No warranties -->
        <div v-if="!warranties?.length" class="text-center py-12 text-gray-400">
          <ShieldCheckIcon class="w-10 h-10 mx-auto mb-3 opacity-30" />
          <p class="text-sm font-medium">No warranties yet</p>
          <p class="text-xs mt-1">Warranties are issued after your first completed service.</p>
        </div>

        <!-- Warranty cards -->
        <div v-else class="divide-y divide-gray-100">
          <div v-for="w in warranties" :key="w.id" class="p-5">
            <div class="flex items-start justify-between gap-4 mb-3">
              <div class="flex items-center gap-3">
                <!-- Status badge -->
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     :class="w.is_active ? 'bg-green-50' : 'bg-gray-100'">
                  <ShieldCheckIcon class="w-5 h-5" :class="w.is_active ? 'text-green-600' : 'text-gray-400'" />
                </div>
                <div>
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                      {{ w.warranty_number }}
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                          :class="w.is_active
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-100 text-gray-500'">
                      {{ w.is_active ? '✓ Active' : 'Expired' }}
                    </span>
                  </div>
                  <p class="text-sm text-gray-600 mt-0.5">{{ w.appliances_covered }}</p>
                </div>
              </div>
              <!-- Days remaining pill -->
              <div v-if="w.is_active"
                   class="flex-shrink-0 text-right">
                <p class="text-2xl font-extrabold text-green-600 leading-none">{{ w.days_remaining }}</p>
                <p class="text-xs text-gray-400">days left</p>
              </div>
            </div>

            <!-- Services covered -->
            <div class="flex flex-wrap gap-1.5 mb-3">
              <span v-for="svc in w.services_list" :key="svc"
                    class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-medium">
                {{ svc }}
              </span>
            </div>

            <!-- Period + progress -->
            <div class="bg-gray-50 rounded-xl p-3">
              <div class="flex justify-between text-xs text-gray-500 mb-2">
                <span>{{ formatDate(w.starts_at) }}</span>
                <span class="font-medium" :class="w.is_active ? 'text-gray-700' : 'text-red-500'">
                  Expires {{ formatDate(w.expires_at) }}
                </span>
              </div>
              <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500"
                     :class="w.is_active ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gray-300'"
                     :style="`width:${w.progress_percent}%`"></div>
              </div>
              <div class="flex justify-between text-xs mt-1.5">
                <span class="text-gray-400">Started</span>
                <span v-if="w.is_active" class="text-gray-500">{{ w.progress_percent }}% elapsed</span>
                <span v-else class="text-red-400">Warranty expired</span>
              </div>
            </div>

            <!-- Booking link -->
            <div class="mt-3 flex items-center justify-between">
              <p class="text-xs text-gray-400">
                Serviced by {{ w.technician?.name ?? 'Our Technician' }}
              </p>
              <a v-if="w.booking?.booking_number"
                 :href="`/user/bookings/${w.booking.booking_number}`"
                 class="text-xs text-blue-600 font-semibold hover:text-blue-800">
                View Booking →
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- ═══ ACTIVE BOOKINGS ════════════════════════════════════ -->
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <h2 class="font-bold text-gray-900">Active Bookings</h2>
          <a href="/quick-booking" class="text-blue-600 text-sm font-semibold hover:text-blue-700">+ New</a>
        </div>
        <div v-if="activeBookings.length" class="divide-y divide-gray-100">
          <a v-for="booking in activeBookings" :key="booking.id"
             :href="`/user/bookings/${booking.booking_number}`"
             class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-gray-900 text-sm truncate">
                {{ booking.items?.map(i => i.service_name).join(', ') }}
              </p>
              <p class="text-gray-400 text-xs mt-0.5 font-mono">{{ booking.booking_number }}</p>
            </div>
            <div class="text-right flex-shrink-0 hidden sm:block">
              <p class="text-sm text-gray-600">{{ formatDate(booking.booking_date) }}</p>
              <p class="text-xs text-gray-400">{{ booking.time_slot }}</p>
            </div>
            <StatusBadge :status="booking.status" size="sm" />
            <ChevronRightIcon class="w-4 h-4 text-gray-300 flex-shrink-0" />
          </a>
        </div>
        <div v-else class="text-center py-10 text-gray-400 text-sm">
          No active bookings.
          <a href="/quick-booking" class="text-blue-600 font-semibold ml-1">Book a service →</a>
        </div>
      </div>

      <!-- ═══ SERVICE HISTORY ════════════════════════════════════ -->
      <div v-if="pastBookings.length" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="font-bold text-gray-900">Service History</h2>
        </div>
        <div class="divide-y divide-gray-100">
          <a v-for="booking in pastBookings" :key="booking.id"
             :href="`/user/bookings/${booking.booking_number}`"
             class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
            <div class="flex-1 min-w-0">
              <p class="font-medium text-gray-900 text-sm truncate">
                {{ booking.items?.map(i => i.service_name).join(', ') }}
              </p>
              <p class="text-gray-400 text-xs mt-0.5">{{ formatDate(booking.booking_date) }}</p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
              <span v-if="booking.status === 'completed'"
                    class="text-xs text-green-600 font-medium bg-green-50 px-2.5 py-1 rounded-full flex items-center gap-1">
                <ShieldCheckIcon class="w-3.5 h-3.5" /> Warranty
              </span>
              <StatusBadge :status="booking.status" size="sm" />
              <ChevronRightIcon class="w-4 h-4 text-gray-300" />
            </div>
          </a>
        </div>
      </div>

    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import {
  PlusIcon, ShieldCheckIcon, ChevronRightIcon, TrophyIcon,
} from '@heroicons/vue/24/outline'
import AppHeader   from '@/components/Landing/AppHeader.vue'
import AppFooter   from '@/components/Landing/AppFooter.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  user:               { type: Object, required: true },
  bookings:           { type: Array,  default: () => [] },
  warranties:         { type: Array,  default: () => [] },
  amc:                { type: Object, default: null },
  currentYearSpend:   { type: Number, default: 0 },
  spendToUnlockAmc:   { type: Number, default: 5000 },
  spendProgressPct:   { type: Number, default: 0 },
  amcThreshold:       { type: Number, default: 5000 },
  amcPaidPrice:       { type: Number, default: 1499 },
})

const activeStatuses  = ['pending', 'confirmed', 'assigned', 'in_progress', 'rescheduled']
const activeBookings  = computed(() => props.bookings.filter(b => activeStatuses.includes(b.status)))
const pastBookings    = computed(() => props.bookings.filter(b => !activeStatuses.includes(b.status)))
const firstName       = computed(() => props.user?.name?.split(' ')[0] ?? 'there')
const activeWarrantyCount = computed(() => props.warranties?.filter(w => w.is_active).length ?? 0)

const stats = computed(() => [
  { value: props.bookings.length,                                          label: 'Total Services',   color: 'text-gray-900' },
  { value: activeBookings.value.length,                                    label: 'Active',           color: 'text-blue-600' },
  { value: props.bookings.filter(b => b.status === 'completed').length,    label: 'Completed',        color: 'text-green-600' },
  { value: props.user?.loyalty_points ?? 0,                               label: 'Loyalty Points',   color: 'text-amber-600' },
])

const amcBenefits = [
  '2 Free Service Calls/year',
  '15% Off All Repairs',
  'Priority Booking',
  '6-Month Warranties',
]

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}

function formatCurrency(n) {
  return Number(n).toLocaleString('en-IN')
}
</script>