<template>
  <div class="min-h-screen bg-gray-50">
    <AppHeader :minimal="true" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

      <!-- Header -->
      <div class="flex items-center gap-3 mb-6">
        <a href="/user/dashboard" class="text-gray-500 hover:text-gray-700 transition-colors">
          <ArrowLeftIcon class="w-5 h-5" />
        </a>
        <div class="flex-1">
          <h1 class="text-xl font-bold text-gray-900 font-mono">{{ booking.booking_number }}</h1>
          <p class="text-sm text-gray-500">Booked on {{ formatDate(booking.created_at) }}</p>
        </div>
        <StatusBadge :status="booking.status" size="lg" />
      </div>

      <div class="space-y-4">

        <!-- Appointment -->
        <div class="bg-blue-600 rounded-2xl p-5 text-white">
          <div class="flex items-center gap-3 mb-2">
            <CalendarIcon class="w-5 h-5 opacity-80" />
            <span class="font-semibold text-sm opacity-90">Appointment</span>
          </div>
          <p class="text-2xl font-bold">{{ formatDate(booking.booking_date) }}</p>
          <p class="text-blue-200 text-sm mt-1">{{ booking.time_slot }}</p>
        </div>

        <!-- Services -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-4">Services Booked</h3>
          <div class="space-y-3">
            <div v-for="item in booking.items" :key="item.id"
                 class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
              <div>
                <p class="font-medium text-gray-900 text-sm">{{ item.service_name }}</p>
                <p class="text-xs text-gray-500">Qty: {{ item.quantity }} × ₹{{ item.unit_price }}</p>
              </div>
              <p class="font-bold text-gray-900">₹{{ item.total_price }}</p>
            </div>
          </div>
          <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
            <span class="font-semibold text-gray-700">Total Estimate</span>
            <span class="text-xl font-bold text-blue-600">₹{{ booking.final_amount ?? booking.total_amount }}</span>
          </div>
          <p class="text-xs text-gray-400 mt-2">* Final price may vary after diagnosis.</p>
        </div>

        <!-- Address -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-3">Service Address</h3>
          <div class="flex items-start gap-3 text-sm">
            <MapPinIcon class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" />
            <p class="text-gray-700">
              {{ booking.address }}{{ booking.area ? ', ' + booking.area : '' }}{{ booking.pincode ? ' - ' + booking.pincode : '' }}
            </p>
          </div>
        </div>

        <!-- Technician -->
        <div v-if="booking.technician" class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-3">Your Technician</h3>
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-lg font-bold text-indigo-600">
              {{ booking.technician.name?.[0] ?? 'T' }}
            </div>
            <div class="flex-1">
              <p class="font-semibold text-gray-900">{{ booking.technician.name }}</p>
              <p class="text-sm text-gray-500">Certified Technician</p>
            </div>
            <a :href="`tel:${booking.technician.phone}`"
               class="flex items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-sm font-semibold hover:bg-blue-100 transition-colors">
              <PhoneIcon class="w-3.5 h-3.5" /> Call
            </a>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="booking.customer_notes" class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-2">Your Notes</h3>
          <p class="text-sm text-gray-600 bg-gray-50 rounded-xl p-3">{{ booking.customer_notes }}</p>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-4">Status Timeline</h3>
          <div v-if="booking.history?.length" class="space-y-4">
            <div v-for="(entry, i) in booking.history" :key="entry.id" class="flex gap-3">
              <div class="flex flex-col items-center">
                <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <ClockIcon class="w-3.5 h-3.5 text-blue-600" />
                </div>
                <div v-if="i < booking.history.length - 1" class="w-0.5 flex-1 bg-gray-200 my-1"></div>
              </div>
              <div class="flex-1 pb-3">
                <p class="text-sm font-medium text-gray-900 capitalize">
                  {{ entry.action.replace(/_/g, ' ') }}
                  <span v-if="entry.to_status" class="text-gray-400 font-normal">→ {{ entry.to_status }}</span>
                </p>
                <p v-if="entry.note" class="text-xs text-gray-500 mt-0.5">{{ entry.note }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ formatDateTime(entry.created_at) }}</p>
              </div>
            </div>
          </div>
          <p v-else class="text-sm text-gray-400">No history yet.</p>
        </div>

        <!-- Review (post-completion) -->
        <div v-if="booking.status === 'completed' && !booking.review" class="bg-white rounded-2xl border border-gray-200 p-5">
          <h3 class="font-semibold text-gray-900 mb-1">Rate Your Experience</h3>
          <p class="text-sm text-gray-500 mb-4">Your feedback helps us improve.</p>

          <div class="flex gap-2 mb-4">
            <button v-for="star in 5" :key="star"
                    @click="reviewForm.rating = star"
                    :class="['text-2xl transition-transform hover:scale-110', star <= reviewForm.rating ? 'text-yellow-400' : 'text-gray-300']">
              ★
            </button>
          </div>

          <textarea v-model="reviewForm.comment" rows="3"
                    placeholder="Tell us about your experience..."
                    class="form-input resize-none mb-3"></textarea>

          <button @click="submitReview" :disabled="!reviewForm.rating || submittingReview"
                  class="w-full bg-yellow-400 text-yellow-900 py-2.5 rounded-xl font-bold text-sm hover:bg-yellow-500 transition-colors disabled:opacity-50">
            {{ submittingReview ? 'Submitting...' : 'Submit Review' }}
          </button>
        </div>

        <!-- Existing review -->
        <div v-if="booking.review" class="bg-yellow-50 rounded-2xl border border-yellow-100 p-5">
          <h3 class="font-semibold text-gray-900 mb-1">Your Review</h3>
          <div class="text-yellow-400 text-xl mb-2">
            {{ '★'.repeat(booking.review.rating) }}{{ '☆'.repeat(5 - booking.review.rating) }}
          </div>
          <p v-if="booking.review.comment" class="text-sm text-gray-600">{{ booking.review.comment }}</p>
        </div>

        <!-- Cancel Button -->
        <button v-if="canCancel"
                @click="showCancelConfirm = true"
                class="w-full border-2 border-red-300 text-red-600 py-3 rounded-xl font-semibold text-sm hover:bg-red-50 transition-colors">
          Cancel Booking
        </button>

        <!-- Cancellation blocked notice -->
        <div v-else-if="!['completed','cancelled'].includes(booking.status)"
             class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm">
          <ExclamationTriangleIcon class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" />
          <div>
            <p class="font-semibold text-amber-800">Cancellation not available</p>
            <p class="text-amber-700 mt-0.5">A technician has been assigned to your booking. To cancel, please call us at
              <a href="tel:+914422001234" class="font-semibold underline">044-2200-1234</a>.
            </p>
          </div>
        </div>

        <!-- Cancel Confirm -->
        <div v-if="showCancelConfirm" class="bg-red-50 rounded-2xl border border-red-200 p-5">
          <p class="font-semibold text-red-900 mb-1">Cancel this booking?</p>
          <p class="text-sm text-red-700 mb-4">This action cannot be undone.</p>
          <textarea v-model="cancelReason" rows="2" placeholder="Reason (optional)"
                    class="form-input resize-none mb-3"></textarea>
          <div class="flex gap-3">
            <button @click="showCancelConfirm = false"
                    class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50">
              Keep Booking
            </button>
            <button @click="cancelBooking" :disabled="cancelling"
                    class="flex-1 bg-red-600 text-white py-2 rounded-xl text-sm font-semibold hover:bg-red-700 disabled:opacity-60">
              {{ cancelling ? 'Cancelling...' : 'Yes, Cancel' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ArrowLeftIcon, CalendarIcon, ClockIcon,
  MapPinIcon, PhoneIcon, ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline'
import AppHeader   from '@/components/Landing/AppHeader.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  booking: { type: Object, required: true },
})

const showCancelConfirm = ref(false)
const cancelReason      = ref('')
const cancelling        = ref(false)
const submittingReview  = ref(false)
const reviewForm        = ref({ rating: 0, comment: '' })

const canCancel = computed(() =>
  !['completed', 'cancelled', 'assigned', 'in_progress'].includes(props.booking.status)
  && !props.booking.technician_id
)

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric', weekday: 'short' })
}
function formatDateTime(d) {
  return new Date(d).toLocaleString('en-IN', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function cancelBooking() {
  cancelling.value = true
  router.post(`/user/bookings/${props.booking.booking_number}/cancel`,
    { reason: cancelReason.value },
    { onFinish: () => { cancelling.value = false; showCancelConfirm.value = false } }
  )
}

function submitReview() {
  if (!reviewForm.value.rating) return
  submittingReview.value = true
  router.post(`/user/bookings/${props.booking.booking_number}/review`,
    reviewForm.value,
    { onFinish: () => { submittingReview.value = false } }
  )
}
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors text-sm;
}
</style>