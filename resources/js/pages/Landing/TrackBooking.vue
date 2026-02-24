<template>
  <div class="min-h-screen bg-gray-50">
    <Head><title>Track Booking — Chennai Smart Care</title></Head>
    <AppHeader :minimal="true" />

    <div class="max-w-lg mx-auto px-4 sm:px-6 py-12">
      <h1 class="text-2xl font-bold text-gray-900 mb-2 text-center">Track Your Booking</h1>
      <p class="text-gray-500 text-sm text-center mb-8">Enter your booking number to see the current status</p>

      <!-- Search form -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <div class="flex gap-3">
          <input v-model="inputNumber" type="text" placeholder="e.g. CSC-2024-00001"
                 class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase placeholder-normal"
                 style="text-transform:uppercase"
                 @keyup.enter="track" />
          <button @click="track" :disabled="loading"
                  class="px-5 py-3 bg-blue-600 text-white rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors disabled:opacity-60">
            {{ loading ? '...' : 'Track' }}
          </button>
        </div>
        <p v-if="notFound" class="text-red-600 text-sm mt-3 text-center">
          Booking not found. Please check the number and try again.
        </p>
      </div>

      <!-- Result -->
      <div v-if="booking" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-blue-600 px-5 py-4 text-white flex items-center justify-between">
          <div>
            <p class="font-mono font-bold text-lg">{{ booking.booking_number }}</p>
            <p class="text-blue-100 text-xs mt-0.5">{{ formatDate(booking.booking_date) }} · {{ booking.time_slot }}</p>
          </div>
          <StatusBadge :status="booking.status" size="md" class="bg-white/20 text-white border-none" />
        </div>

        <div class="p-5 space-y-4 text-sm">
          <div>
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Services</p>
            <p class="text-gray-900 font-medium">{{ booking.items?.map(i => i.service_name).join(', ') }}</p>
          </div>
          <div>
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Address</p>
            <p class="text-gray-900">{{ booking.address }}</p>
          </div>
          <div v-if="booking.technician">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Technician</p>
            <p class="text-gray-900 font-medium">{{ booking.technician.name }}</p>
            <a :href="`tel:${booking.technician.phone}`" class="text-blue-600 text-xs">{{ booking.technician.phone }}</a>
          </div>

          <!-- Timeline -->
          <div>
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-3">Status Timeline</p>
            <div class="space-y-3">
              <div v-for="(step, i) in timeline" :key="i"
                   :class="['flex gap-3 items-start', step.done ? 'opacity-100' : 'opacity-40']">
                <div :class="['w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5', step.done ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400']">
                  {{ step.done ? '✓' : i + 1 }}
                </div>
                <div>
                  <p class="font-medium text-gray-900 text-sm">{{ step.label }}</p>
                  <p class="text-gray-400 text-xs">{{ step.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="px-5 pb-5">
          <a href="/quick-booking"
             class="block w-full text-center bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors">
            Book Another Service
          </a>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import AppHeader  from '@/components/Landing/AppHeader.vue'
import AppFooter  from '@/components/Landing/AppFooter.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  bookingNumber: { type: String, default: '' },
})

const inputNumber = ref(props.bookingNumber ?? '')
const booking  = ref(null)
const loading  = ref(false)
const notFound = ref(false)

const statusOrder = ['pending', 'confirmed', 'assigned', 'in_progress', 'completed']
const timelineSteps = [
  { status: 'pending',     label: 'Booking Received',    description: 'Your request is under review' },
  { status: 'confirmed',   label: 'Booking Confirmed',   description: 'Our team confirmed your appointment' },
  { status: 'assigned',    label: 'Technician Assigned',  description: 'A technician has been assigned' },
  { status: 'in_progress', label: 'Service In Progress', description: 'Your appliance is being serviced' },
  { status: 'completed',   label: 'Service Completed',   description: 'All done! We hope you\'re satisfied' },
]

const timeline = computed(() => {
  if (!booking.value) return []
  const currentIdx = statusOrder.indexOf(booking.value.status)
  return timelineSteps.map((step, i) => ({
    ...step,
    done: i <= currentIdx,
  }))
})

async function track() {
  if (!inputNumber.value.trim()) return
  loading.value  = true
  notFound.value = false
  booking.value  = null
  try {
    const res = await axios.get(`/api/v1/bookings/track/${inputNumber.value.trim().toUpperCase()}`)
    booking.value = res.data.booking
  } catch {
    notFound.value = true
  } finally {
    loading.value = false
  }
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { weekday: 'long', day: 'numeric', month: 'long' })
}

onMounted(() => {
  if (props.bookingNumber) track()
})
</script>