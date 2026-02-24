<template>
  <!-- resources/js/pages/Landing/QuickBooking.vue -->
  <div class="min-h-screen bg-gray-50">
    <AppHeader minimal />

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

      <!-- Progress Bar -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm text-gray-500">Step {{ currentStep }} of {{ totalSteps }}</span>
          <span class="text-sm font-medium text-blue-600">{{ stepLabels[currentStep - 1] }}</span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
          <div
            class="h-full bg-blue-600 rounded-full transition-all duration-500"
            :style="{ width: `${(currentStep / totalSteps) * 100}%` }"
          ></div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- ========== STEP 1: Your Info ========== -->
        <div v-if="currentStep === 1" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Your Contact Details</h2>
          <p class="text-gray-500 text-sm mb-6">No account needed — just your name and phone number.</p>

          <div class="space-y-5">
            <FormField label="Full Name *" :error="errors.guest_name">
              <input
                v-model="form.guest_name"
                type="text"
                placeholder="e.g., Rajesh Kumar"
                class="form-input"
                @blur="validateField('guest_name')"
              />
            </FormField>

            <FormField label="Phone Number *" :error="errors.guest_phone">
              <div class="flex">
                <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+91</span>
                <input
                  v-model="form.guest_phone"
                  type="tel"
                  placeholder="98765 43210"
                  maxlength="10"
                  class="form-input rounded-l-none flex-1"
                  @blur="validateField('guest_phone')"
                />
              </div>
            </FormField>

            <FormField label="Email Address (Optional)" :error="errors.guest_email">
              <input
                v-model="form.guest_email"
                type="email"
                placeholder="you@example.com"
                class="form-input"
              />
            </FormField>
          </div>

          <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
            <p class="text-sm text-blue-700">
              <strong>Already have an account?</strong>
              <a href="/login" class="ml-1 underline">Login for faster booking</a>
            </p>
          </div>
        </div>

        <!-- ========== STEP 2: Select Services ========== -->
        <div v-if="currentStep === 2" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Select Services</h2>
          <p class="text-gray-500 text-sm mb-6">Choose one or multiple services.</p>

          <div class="space-y-6">
            <div v-for="category in categories" :key="category.id">
              <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="w-6 h-6 flex items-center justify-center bg-blue-100 rounded-lg text-sm">
                  {{ category.icon || '🔧' }}
                </span>
                {{ category.name }}
              </h3>
              <div class="space-y-2">
                <button
                  v-for="service in category.services"
                  :key="service.id"
                  @click="toggleService(service)"
                  :class="[
                    'w-full flex items-center justify-between p-4 rounded-xl border-2 transition-all text-left',
                    isServiceSelected(service.id)
                      ? 'border-blue-500 bg-blue-50'
                      : 'border-gray-200 hover:border-blue-200 hover:bg-gray-50'
                  ]"
                >
                  <div class="flex items-center gap-3">
                    <div :class="[
                      'w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors',
                      isServiceSelected(service.id) ? 'border-blue-500 bg-blue-500' : 'border-gray-300'
                    ]">
                      <CheckIcon v-if="isServiceSelected(service.id)" class="w-3 h-3 text-white" />
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 text-sm">{{ service.name }}</p>
                      <p class="text-xs text-gray-500 mt-0.5">{{ service.duration_estimate }}</p>
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0 ml-4">
                    <p class="font-bold text-gray-900">
                    ₹{{ formatPrice(service) }}
                    </p>

                    <p 
                    v-if="hasDiscount(service)" 
                    class="text-xs text-gray-400 line-through"
                    >
                    ₹{{ Number(service.base_price).toFixed(0) }}
                    </p>
                  </div>
                </button>
              </div>
            </div>
          </div>

          <!-- Selected services summary -->
          <div v-if="selectedServices.length > 0"
               class="mt-6 p-4 bg-green-50 rounded-xl border border-green-200">
            <div class="flex justify-between items-center">
              <div>
                <p class="font-semibold text-green-800 text-sm">
                  {{ selectedServices.length }} service{{ selectedServices.length > 1 ? 's' : '' }} selected
                </p>
                <p class="text-green-600 text-xs mt-1">{{ selectedServiceNames.join(', ') }}</p>
              </div>
              <p class="font-bold text-green-800">₹{{ totalPrice }}</p>
            </div>
          </div>

          <p v-if="errors.services" class="mt-2 text-red-600 text-sm">{{ errors.services }}</p>
        </div>

        <!-- ========== STEP 3: Location ========== -->
        <div v-if="currentStep === 3" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Service Address</h2>
          <p class="text-gray-500 text-sm mb-6">We serve within 20km of Chennai city centre.</p>

          <!-- Detect Location Button -->
          <button
            @click="detectLocation"
            :disabled="detectingLocation"
            class="w-full flex items-center justify-center gap-3 py-3 px-4 border-2 border-dashed border-blue-300 text-blue-600 rounded-xl hover:bg-blue-50 transition-colors mb-6 font-medium"
          >
            <MapPinIcon :class="['w-5 h-5', detectingLocation && 'animate-bounce']" />
            {{ detectingLocation ? 'Detecting...' : 'Use My Current Location' }}
          </button>

          <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 right-0 flex items-center justify-center">
              <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center">
              <span class="bg-white px-3 text-sm text-gray-400">or enter manually</span>
            </div>
          </div>

          <div class="space-y-4">
            <FormField label="Full Address *" :error="errors.address">
              <textarea
                v-model="form.address"
                rows="3"
                placeholder="House/Flat No, Street, Locality..."
                class="form-input resize-none"
              ></textarea>
            </FormField>

            <div class="grid grid-cols-2 gap-4">
              <FormField label="Area / Locality" :error="errors.area">
                <input v-model="form.area" type="text" placeholder="Anna Nagar" class="form-input" />
              </FormField>
              <FormField label="Pincode" :error="errors.pincode">
                <input v-model="form.pincode" type="text" maxlength="6" placeholder="600001" class="form-input" />
              </FormField>
            </div>
          </div>

          <!-- Location validation feedback -->
          <div v-if="locationValidated" class="mt-4 flex items-center gap-2 text-green-600 text-sm">
            <CheckCircleIcon class="w-4 h-4" />
            Location is within our service area ✓
          </div>
          <div v-if="locationError" class="mt-4 flex items-center gap-2 text-red-600 text-sm">
            <XCircleIcon class="w-4 h-4" />
            {{ locationError }}
          </div>
        </div>

        <!-- ========== STEP 4: Date & Time ========== -->
        <div v-if="currentStep === 4" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Choose Date & Time</h2>
          <p class="text-gray-500 text-sm mb-6">Pick a convenient time — 9AM to 9PM, 7 days a week.</p>

          <!-- Date Picker (next 14 days) -->
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Select Date</label>
            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
              <button
                v-for="date in availableDates"
                :key="date.value"
                @click="selectDate(date.value)"
                :class="[
                  'flex flex-col items-center py-3 px-2 rounded-xl border-2 transition-all text-center',
                  form.booking_date === date.value
                    ? 'border-blue-500 bg-blue-500 text-white'
                    : 'border-gray-200 hover:border-blue-200 text-gray-700'
                ]"
              >
                <span class="text-xs font-medium">{{ date.day }}</span>
                <span class="text-lg font-bold leading-tight">{{ date.dateNum }}</span>
                <span class="text-xs">{{ date.month }}</span>
              </button>
            </div>
          </div>

          <!-- Time Slots -->
          <div v-if="form.booking_date">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Select Time Slot</label>
            <div v-if="loadingSlots" class="text-center py-8 text-gray-400">
              Loading available slots...
            </div>
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <button
                v-for="slot in timeSlots"
                :key="slot.slot"
                @click="slot.available && selectSlot(slot.slot)"
                :disabled="!slot.available"
                :class="[
                  'py-3 px-4 rounded-xl border-2 text-sm font-medium transition-all',
                  !slot.available
                    ? 'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed line-through'
                    : form.time_slot === slot.slot
                      ? 'border-blue-500 bg-blue-500 text-white'
                      : 'border-gray-200 hover:border-blue-200 text-gray-700'
                ]"
              >
                {{ slot.label }}
                <span v-if="!slot.available" class="block text-xs mt-0.5">Full</span>
              </button>
            </div>
          </div>

          <FormField label="Any Special Instructions? (Optional)" class="mt-6">
            <textarea
              v-model="form.customer_notes"
              rows="3"
              placeholder="e.g., AC model, symptoms, floor number..."
              class="form-input resize-none"
            ></textarea>
          </FormField>
        </div>

        <!-- ========== STEP 5: Confirm ========== -->
        <div v-if="currentStep === 5" class="p-6 sm:p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Confirm Your Booking</h2>
          <p class="text-gray-500 text-sm mb-6">Review your details before submitting.</p>

          <div class="space-y-4 text-sm">
            <div class="bg-gray-50 rounded-xl p-4 space-y-3">
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
                <span class="font-medium text-right max-w-[60%]">{{ form.address }}, {{ form.area }}</span>
              </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
              <p class="font-semibold text-gray-700 mb-3">Services</p>
              <div v-for="svc in selectedServices" :key="svc.id"
                   class="flex justify-between py-1">
                <span class="text-gray-600">{{ svc.name }}</span>
                <span class="font-medium">₹{{ svc.discounted_price }}</span>
              </div>
              <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-bold">
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

            <p class="text-xs text-gray-400 text-center">
              * Final price may vary based on diagnosis. We'll confirm via SMS.
            </p>
          </div>
        </div>

        <!-- ========== SUCCESS ========== -->
        <div v-if="bookingSuccess" class="p-6 sm:p-8 text-center">
          <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <CheckIcon class="w-10 h-10 text-green-500" />
          </div>
          <h2 class="text-2xl font-bold text-gray-900">Booking Confirmed!</h2>
          <p class="text-gray-500 mt-2">Your booking ID is:</p>
          <p class="text-3xl font-extrabold text-blue-600 my-3">{{ bookingNumber }}</p>
          <p class="text-sm text-gray-600">
            You'll receive an SMS confirmation on +91 {{ form.guest_phone }} shortly.
            Our team will call to confirm the appointment.
          </p>
          <div class="mt-6 space-y-3">
            <a :href="`/track/${bookingNumber}`"
              class="block w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
              Track Your Booking
            </a>
            <a href="/"
              class="block w-full border border-gray-300 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
              Back to Home
          </a>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div v-if="!bookingSuccess" class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-between gap-4">
          <button
            v-if="currentStep > 1"
            @click="prevStep"
            class="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors"
          >
            <ChevronLeftIcon class="w-4 h-4" />
            Back
          </button>
          <div v-else></div>

          <button
            v-if="currentStep < totalSteps"
            @click="nextStep"
            :disabled="!canProceed"
            :class="[
              'flex items-center gap-2 px-8 py-3 rounded-xl font-semibold transition-all',
              canProceed
                ? 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg'
                : 'bg-gray-200 text-gray-400 cursor-not-allowed'
            ]"
          >
            Continue
            <ChevronRightIcon class="w-4 h-4" />
          </button>

          <button
            v-else
            @click="submitBooking"
            :disabled="submitting"
            class="flex items-center gap-2 px-8 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 hover:shadow-lg transition-all disabled:opacity-60"
          >
            <span v-if="submitting">Submitting...</span>
            <span v-else>Confirm Booking</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import {
  CheckIcon, MapPinIcon, CalendarIcon,
  ChevronLeftIcon, ChevronRightIcon,
  CheckCircleIcon, XCircleIcon,
} from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import FormField from '@/components/Shared/FormField.vue'

const props = defineProps({
  categories: Array,
})

const currentStep = ref(1)
const totalSteps = 5
const stepLabels = ['Contact Info', 'Services', 'Location', 'Date & Time', 'Confirm']

const form = ref({
  guest_name: '',
  guest_phone: '',
  guest_email: '',
  address: '',
  area: '',
  pincode: '',
  latitude: null,
  longitude: null,
  services: [],
  booking_date: '',
  time_slot: '',
  customer_notes: '',
})

const errors = ref({})
const detectingLocation = ref(false)
const locationValidated = ref(false)
const locationError = ref('')
const loadingSlots = ref(false)
const timeSlots = ref([])
const submitting = ref(false)
const bookingSuccess = ref(false)
const bookingNumber = ref('')

// ---- Computed ----
const selectedServices = computed(() =>
  props.categories?.flatMap(c => c.services).filter(s => form.value.services.find(sel => sel.id === s.id)) || []
)

const selectedServiceNames = computed(() => selectedServices.value.map(s => s.name))

const totalPrice = computed(() =>
  selectedServices.value.reduce((sum, s) => sum + parseFloat(s.discounted_price || s.base_price), 0)
)

const isServiceSelected = (id) => form.value.services.some(s => s.id === id)

const formattedDate = computed(() => {
  if (!form.value.booking_date) return ''
  return new Date(form.value.booking_date).toLocaleDateString('en-IN', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
  })
})

// Next 14 days
const availableDates = computed(() => {
  const dates = []
  for (let i = 0; i < 14; i++) {
    const d = new Date()
    d.setDate(d.getDate() + i)
    dates.push({
      value: d.toISOString().split('T')[0],
      day: d.toLocaleDateString('en-IN', { weekday: 'short' }),
      dateNum: d.getDate(),
      month: d.toLocaleDateString('en-IN', { month: 'short' }),
    })
  }
  return dates
})

const canProceed = computed(() => {
  if (currentStep.value === 1) return form.value.guest_name && form.value.guest_phone?.length === 10
  if (currentStep.value === 2) return form.value.services.length > 0
  if (currentStep.value === 3) return form.value.address && form.value.latitude
  if (currentStep.value === 4) return form.value.booking_date && form.value.time_slot
  return true
})

// ---- Methods ----
function toggleService(service) {
  const idx = form.value.services.findIndex(s => s.id === service.id)

  if (idx >= 0) {
    form.value.services.splice(idx, 1)
  } else {
    const price = Number(
      service.discounted_price ?? service.base_price ?? 0
    )

    form.value.services.push({
      id: service.id,
      price: price,
      quantity: 1
    })
  }
}

async function detectLocation() {
  if (!navigator.geolocation) {
    locationError.value = 'Geolocation is not supported by your browser.'
    return
  }
  detectingLocation.value = true
  locationError.value = ''
  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      form.value.latitude = pos.coords.latitude
      form.value.longitude = pos.coords.longitude
      detectingLocation.value = false
      await reverseGeocode(pos.coords.latitude, pos.coords.longitude)
      locationValidated.value = true
    },
    (err) => {
      detectingLocation.value = false
      locationError.value = 'Could not detect location. Please enter manually.'
    },
    { timeout: 10000 }
  )
}

async function reverseGeocode(lat, lng) {
  try {
    const res = await axios.get(`https://nominatim.openstreetmap.org/reverse`, {
      params: { lat, lon: lng, format: 'json' }
    })
    const addr = res.data.address
    form.value.address = `${addr.road || ''}, ${addr.suburb || addr.neighbourhood || ''}`
    form.value.area = addr.suburb || addr.neighbourhood || addr.county || ''
    form.value.pincode = addr.postcode || ''
  } catch {}
}

async function selectDate(date) {
  form.value.booking_date = date
  form.value.time_slot = ''
  loadingSlots.value = true
  try {
    const res = await axios.get(`/api/v1/bookings/slots/${date}`)
    timeSlots.value = res.data.slots
  } catch {
    timeSlots.value = []
  } finally {
    loadingSlots.value = false
  }
}

function selectSlot(slot) {
  form.value.time_slot = slot
}

function nextStep() {
  if (canProceed.value) currentStep.value++
}

function prevStep() {
  if (currentStep.value > 1) currentStep.value--
}

async function submitBooking() {
  submitting.value = true
  errors.value = {}
  try {
    const res = await axios.post('/api/v1/bookings/quick', {
      ...form.value,
    })
    bookingNumber.value = res.data.booking_number
    bookingSuccess.value = true

    // GTM event
    if (window.dataLayer) {
      window.dataLayer.push({
        event: 'booking_completed',
        booking_number: res.data.booking_number,
        service_count: form.value.services.length,
        total_value: totalPrice.value,
      })
    }
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors || {}
      // Go back to first step with errors
      currentStep.value = 1
    }
  } finally {
    submitting.value = false
  }
}

function validateField(field) {
  if (field === 'guest_phone') {
    if (!form.value.guest_phone || form.value.guest_phone.length !== 10) {
      errors.value.guest_phone = 'Please enter a valid 10-digit mobile number.'
    } else {
      delete errors.value.guest_phone
    }
  }
}

function formatPrice(service) {
  const price = service.discounted_price ?? service.base_price ?? 0
  return Number(price).toFixed(0)
}

function hasDiscount(service) {
  if (!service.base_price || !service.discounted_price) return false
  return Number(service.discounted_price) < Number(service.base_price)
}

</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors text-sm;
}
</style>