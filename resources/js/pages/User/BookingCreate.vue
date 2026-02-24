<template>
  <div class="min-h-screen bg-gray-50">
    <AppHeader :minimal="true" />

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

      <!-- Progress -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm text-gray-500">Step {{ currentStep }} of {{ totalSteps }}</span>
          <span class="text-sm font-medium text-blue-600">{{ stepLabels[currentStep - 1] }}</span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
          <div class="h-full bg-blue-600 rounded-full transition-all duration-500"
               :style="{ width: `${(currentStep / totalSteps) * 100}%` }"></div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- STEP 1: Contact — pre-filled, read-only for logged-in users -->
        <div v-if="currentStep === 1" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Your Contact Details</h2>
          <p class="text-gray-500 text-sm mb-6">Filled from your account — edit if needed.</p>

          <!-- Account banner -->
          <div class="mb-5 flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100 text-sm">
            <CheckCircleIcon class="w-5 h-5 text-blue-500 flex-shrink-0" />
            <span class="text-blue-700">Booking will be linked to your account for easy tracking.</span>
          </div>

          <div class="space-y-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
              <input v-model="form.guest_name" type="text" class="form-input" />
              <p v-if="errors.guest_name" class="text-red-500 text-xs mt-1">{{ errors.guest_name }}</p>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number *</label>
              <div class="flex">
                <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+91</span>
                <input v-model="form.guest_phone" type="tel" maxlength="10" class="form-input rounded-l-none flex-1" />
              </div>
              <p v-if="errors.guest_phone" class="text-red-500 text-xs mt-1">{{ errors.guest_phone }}</p>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email (Optional)</label>
              <input v-model="form.guest_email" type="email" class="form-input" />
            </div>
          </div>
        </div>

        <!-- STEP 2: Services -->
        <div v-if="currentStep === 2" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Select Services</h2>
          <p class="text-gray-500 text-sm mb-6">Choose one or more services.</p>

          <div class="space-y-6">
            <div v-for="category in categories" :key="category.id">
              <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">
                {{ category.icon || '🔧' }} {{ category.name }}
              </h3>
              <div class="space-y-2">
                <button v-for="service in category.services" :key="service.id"
                        @click="toggleService(service)"
                        :class="[
                          'w-full flex items-center justify-between p-4 rounded-xl border-2 transition-all text-left',
                          isSelected(service.id) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-200'
                        ]">
                  <div class="flex items-center gap-3">
                    <div :class="['w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0',
                                  isSelected(service.id) ? 'border-blue-500 bg-blue-500' : 'border-gray-300']">
                      <CheckIcon v-if="isSelected(service.id)" class="w-3 h-3 text-white" />
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 text-sm">{{ service.name }}</p>
                      <p class="text-xs text-gray-500">{{ service.duration_estimate }}</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0 ml-4">
                    <p class="font-bold text-gray-900">₹{{ service.discounted_price ?? service.base_price }}</p>
                    <p v-if="service.discounted_price" class="text-xs text-gray-400 line-through">₹{{ service.base_price }}</p>
                  </div>
                </button>
              </div>
            </div>
          </div>

          <div v-if="form.services.length > 0"
               class="mt-5 p-4 bg-green-50 rounded-xl border border-green-200 flex justify-between items-center">
            <div>
              <p class="font-semibold text-green-800 text-sm">{{ form.services.length }} service(s) selected</p>
              <p class="text-green-600 text-xs mt-0.5">{{ selectedNames.join(', ') }}</p>
            </div>
            <p class="font-bold text-green-800">₹{{ totalPrice }}</p>
          </div>
        </div>

        <!-- STEP 3: Address -->
        <div v-if="currentStep === 3" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Service Address</h2>
          <p class="text-gray-500 text-sm mb-6">Where should our technician come?</p>

          <button @click="detectLocation" :disabled="detectingLocation"
                  class="w-full flex items-center justify-center gap-3 py-3 px-4 border-2 border-dashed border-blue-300 text-blue-600 rounded-xl hover:bg-blue-50 transition-colors mb-5 font-medium text-sm">
            <MapPinIcon :class="['w-5 h-5', detectingLocation && 'animate-bounce']" />
            {{ detectingLocation ? 'Detecting...' : 'Use My Current Location' }}
          </button>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Address *</label>
              <textarea v-model="form.address" rows="3" class="form-input resize-none"
                        placeholder="House/Flat No, Street, Locality..."></textarea>
              <p v-if="errors.address" class="text-red-500 text-xs mt-1">{{ errors.address }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Area</label>
                <input v-model="form.area" type="text" placeholder="Anna Nagar" class="form-input" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pincode</label>
                <input v-model="form.pincode" type="text" maxlength="6" placeholder="600001" class="form-input" />
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 4: Date & Time -->
        <div v-if="currentStep === 4" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Date & Time</h2>
          <p class="text-gray-500 text-sm mb-6">Pick your preferred slot.</p>

          <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Select Date</label>
            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
              <button v-for="date in availableDates" :key="date.value"
                      @click="selectDate(date.value)"
                      :class="['flex flex-col items-center py-3 px-2 rounded-xl border-2 transition-all text-center',
                               form.booking_date === date.value
                                 ? 'border-blue-500 bg-blue-500 text-white'
                                 : 'border-gray-200 hover:border-blue-200 text-gray-700']">
                <span class="text-xs font-medium">{{ date.day }}</span>
                <span class="text-lg font-bold leading-tight">{{ date.dateNum }}</span>
                <span class="text-xs">{{ date.month }}</span>
              </button>
            </div>
          </div>

          <div v-if="form.booking_date">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Time Slot</label>
            <div v-if="loadingSlots" class="text-center py-6 text-gray-400 text-sm">Loading slots...</div>
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <button v-for="slot in timeSlots" :key="slot.slot"
                      @click="slot.available && (form.time_slot = slot.slot)"
                      :disabled="!slot.available"
                      :class="['py-3 px-4 rounded-xl border-2 text-sm font-medium transition-all',
                               !slot.available ? 'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed line-through'
                               : form.time_slot === slot.slot ? 'border-blue-500 bg-blue-500 text-white'
                               : 'border-gray-200 hover:border-blue-200 text-gray-700']">
                {{ slot.label }}
                <span v-if="!slot.available" class="block text-xs">Full</span>
              </button>
            </div>
          </div>

          <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Special Instructions (Optional)</label>
            <textarea v-model="form.customer_notes" rows="3" class="form-input resize-none"
                      placeholder="AC model, floor number, symptoms..."></textarea>
          </div>
        </div>

        <!-- STEP 5: Confirm -->
        <div v-if="currentStep === 5" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Confirm Booking</h2>
          <p class="text-gray-500 text-sm mb-6">Review before submitting.</p>

          <div class="space-y-4 text-sm">
            <div class="bg-gray-50 rounded-xl p-4 space-y-2.5">
              <div class="flex justify-between">
                <span class="text-gray-500">Name</span>
                <span class="font-medium">{{ form.guest_name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Phone</span>
                <span class="font-medium">+91 {{ form.guest_phone }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Address</span>
                <span class="font-medium text-right max-w-[60%]">{{ form.address }}{{ form.area ? ', ' + form.area : '' }}</span>
              </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
              <p class="font-semibold text-gray-700 mb-2">Services</p>
              <div v-for="svc in selectedServices" :key="svc.id" class="flex justify-between py-1">
                <span class="text-gray-600">{{ svc.name }}</span>
                <span class="font-medium">₹{{ svc.price }}</span>
              </div>
              <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between font-bold">
                <span>Total Estimate</span>
                <span class="text-blue-600">₹{{ totalPrice }}</span>
              </div>
            </div>

            <div class="bg-blue-50 rounded-xl p-4 flex items-center gap-3">
              <CalendarIcon class="w-5 h-5 text-blue-600 flex-shrink-0" />
              <div>
                <p class="font-semibold text-blue-900">{{ formattedDate }}</p>
                <p class="text-blue-700 text-xs">{{ form.time_slot }}</p>
              </div>
            </div>

            <!-- Loyalty points earn preview -->
            <div class="bg-yellow-50 rounded-xl p-4 flex items-center gap-3 border border-yellow-100">
              <StarIcon class="w-5 h-5 text-yellow-500 flex-shrink-0" />
              <p class="text-yellow-800 text-xs">
                You'll earn <strong>{{ loyaltyPointsToEarn }} loyalty points</strong> (₹{{ totalPrice }} ÷ 100) when this service is completed.
              </p>
            </div>
          </div>
        </div>

        <!-- Nav Buttons -->
        <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-between gap-4">
          <button v-if="currentStep > 1" @click="currentStep--"
                  class="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
            <ChevronLeftIcon class="w-4 h-4" /> Back
          </button>
          <div v-else></div>

          <button v-if="currentStep < totalSteps" @click="nextStep" :disabled="!canProceed"
                  :class="['flex items-center gap-2 px-8 py-3 rounded-xl font-semibold transition-all',
                           canProceed ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-200 text-gray-400 cursor-not-allowed']">
            Continue <ChevronRightIcon class="w-4 h-4" />
          </button>

          <button v-else @click="submitBooking" :disabled="submitting"
                  class="flex items-center gap-2 px-8 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-all disabled:opacity-60">
            {{ submitting ? 'Submitting...' : 'Confirm Booking' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import {
  CheckIcon, CheckCircleIcon, MapPinIcon, CalendarIcon,
  StarIcon, ChevronLeftIcon, ChevronRightIcon,
} from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'

const props = defineProps({
  categories: { type: Array, default: () => [] },
  auth_user:  { type: Object, default: () => ({}) },
})

const currentStep  = ref(1)
const totalSteps   = 5
const stepLabels   = ['Contact', 'Services', 'Address', 'Date & Time', 'Confirm']
const submitting   = ref(false)
const loadingSlots = ref(false)
const timeSlots    = ref([])
const detectingLocation = ref(false)
const errors       = ref({})

// Pre-fill from auth user
const form = ref({
  guest_name:     props.auth_user?.name  ?? '',
  guest_phone:    props.auth_user?.phone ?? '',
  guest_email:    props.auth_user?.email ?? '',
  services:       [],
  address:        '',
  area:           '',
  pincode:        '',
  latitude:       null,
  longitude:      null,
  booking_date:   '',
  time_slot:      '',
  customer_notes: '',
})

// ---- Services ----
function toggleService(service) {
  const idx = form.value.services.findIndex(s => s.id === service.id)

  if (idx >= 0) {
    form.value.services.splice(idx, 1)
  } else {
    form.value.services.push({
      id: service.id,
      name: service.name,
      price: service.discounted_price ?? service.base_price ?? 0,
      quantity: 1,
    })
  }
}
function isSelected(id) { return form.value.services.some(s => s.id === id) }

const allServices   = computed(() => props.categories.flatMap(c => c.services ?? []))
const selectedServices = computed(() => form.value.services.map(s => ({
  ...s,
  name: allServices.value.find(a => a.id === s.id)?.name ?? s.name ?? '',
})))
const selectedNames = computed(() => selectedServices.value.map(s => s.name))
const totalPrice    = computed(() =>
  form.value.services.reduce((sum, s) => sum + parseFloat(s.price ?? 0) * (s.quantity ?? 1), 0)
)
const loyaltyPointsToEarn = computed(() => Math.floor(totalPrice.value / 100))

// ---- Dates ----
const availableDates = computed(() => {
  const dates = []
  for (let i = 0; i < 14; i++) {
    const d = new Date(); d.setDate(d.getDate() + i)
    dates.push({
      value:   d.toISOString().split('T')[0],
      day:     d.toLocaleDateString('en-IN', { weekday: 'short' }),
      dateNum: d.getDate(),
      month:   d.toLocaleDateString('en-IN', { month: 'short' }),
    })
  }
  return dates
})

const formattedDate = computed(() => {
  if (!form.value.booking_date) return ''
  return new Date(form.value.booking_date).toLocaleDateString('en-IN', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
  })
})

async function selectDate(date) {
  form.value.booking_date = date
  form.value.time_slot    = ''
  loadingSlots.value      = true
  try {
    const res = await axios.get(`/api/v1/bookings/slots/${date}`)
    timeSlots.value = res.data.slots
  } catch { timeSlots.value = [] }
  finally { loadingSlots.value = false }
}

// ---- Location ----
async function detectLocation() {
  if (!navigator.geolocation) return
  detectingLocation.value = true
  navigator.geolocation.getCurrentPosition(async (pos) => {
    form.value.latitude  = pos.coords.latitude
    form.value.longitude = pos.coords.longitude
    try {
      const res = await axios.get('https://nominatim.openstreetmap.org/reverse', {
        params: { lat: pos.coords.latitude, lon: pos.coords.longitude, format: 'json' }
      })
      const addr = res.data.address
      form.value.address = `${addr.road ?? ''}, ${addr.suburb ?? addr.neighbourhood ?? ''}`
      form.value.area    = addr.suburb ?? addr.neighbourhood ?? addr.county ?? ''
      form.value.pincode = addr.postcode ?? ''
    } catch {}
    detectingLocation.value = false
  }, () => { detectingLocation.value = false })
}

// ---- Navigation ----
const canProceed = computed(() => {
  if (currentStep.value === 1) return form.value.guest_name && form.value.guest_phone?.length === 10
  if (currentStep.value === 2) return form.value.services.length > 0
  if (currentStep.value === 3) return !!form.value.address
  if (currentStep.value === 4) return !!(form.value.booking_date && form.value.time_slot)
  return true
})

function nextStep() { if (canProceed.value) currentStep.value++ }

// ---- Submit via Inertia (session auth, not axios) ----
function submitBooking() {
  submitting.value = true
  errors.value     = {}
  router.post('/user/bookings', form.value, {
    onError: (errs) => { errors.value = errs; currentStep.value = 1 },
    onFinish: () => { submitting.value = false },
  })
}
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors text-sm;
}
</style>