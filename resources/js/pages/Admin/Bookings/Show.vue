<template>
  <AdminLayout>
    <div class="max-w-5xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
          <a href="/admin/bookings" class="text-gray-500 hover:text-gray-700 transition-colors">
            <ArrowLeftIcon class="w-5 h-5" />
          </a>
          <div>
            <h1 class="text-xl font-bold text-gray-900 font-mono">{{ booking.booking_number }}</h1>
            <p class="text-sm text-gray-500">Created {{ formatDateTime(booking.created_at) }}</p>
          </div>
        </div>
        <StatusBadge :status="booking.status" size="lg" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ======== LEFT COLUMN ======== -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Customer Info -->
          <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Customer Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
              <div>
                <label class="text-gray-400 text-xs uppercase tracking-wide">Name</label>
                <p class="font-semibold text-gray-900 mt-1">{{ booking.customer_name }}</p>
              </div>
              <div>
                <label class="text-gray-400 text-xs uppercase tracking-wide">Phone</label>
                <div class="flex items-center gap-2 mt-1">
                  <a :href="`tel:${booking.customer_phone}`"
                     class="font-semibold text-blue-600 hover:underline">
                    {{ booking.customer_phone }}
                  </a>
                  <a :href="`https://wa.me/91${booking.customer_phone}`" target="_blank"
                     class="text-green-500 hover:text-green-600 text-xs font-medium">
                    WhatsApp
                  </a>
                </div>
              </div>
              <div class="sm:col-span-2">
                <label class="text-gray-400 text-xs uppercase tracking-wide">Service Address</label>
                <p class="font-semibold text-gray-900 mt-1">
                  {{ booking.address }}{{ booking.area ? `, ${booking.area}` : '' }}{{ booking.pincode ? ` - ${booking.pincode}` : '' }}
                </p>
              </div>
              <div v-if="booking.customer_notes" class="sm:col-span-2">
                <label class="text-gray-400 text-xs uppercase tracking-wide">Customer Notes</label>
                <p class="text-gray-700 mt-1 bg-gray-50 rounded-lg p-3">{{ booking.customer_notes }}</p>
              </div>
            </div>
          </div>

          <!-- Services -->
          <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Services Booked</h3>
            <div class="space-y-3">
              <div v-for="item in booking.items" :key="item.id"
                   class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                <div>
                  <p class="font-medium text-gray-900">{{ item.service_name }}</p>
                  <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
                </div>
                <div class="text-right">
                  <p class="font-bold text-gray-900">₹{{ item.total_price }}</p>
                  <p class="text-xs text-gray-400">₹{{ item.unit_price }} each</p>
                </div>
              </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
              <span class="font-semibold text-gray-700">Total</span>
              <span class="text-xl font-bold text-blue-600">₹{{ booking.final_amount }}</span>
            </div>
          </div>

          <!-- Activity Timeline -->
          <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Activity Timeline</h3>
            <div v-if="booking.history?.length" class="space-y-4">
              <div v-for="(entry, i) in booking.history" :key="entry.id" class="flex gap-4">
                <div class="flex flex-col items-center">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <ClockIcon class="w-4 h-4 text-blue-600" />
                  </div>
                  <div v-if="i < booking.history.length - 1"
                       class="w-0.5 flex-1 bg-gray-200 my-2"></div>
                </div>
                <div class="flex-1 pb-4">
                  <p class="text-sm font-medium text-gray-900 capitalize">
                    {{ entry.action.replace(/_/g, ' ') }}
                    <span v-if="entry.from_status && entry.to_status" class="text-gray-400 font-normal">
                      · {{ entry.from_status }} → {{ entry.to_status }}
                    </span>
                  </p>
                  <p v-if="entry.note" class="text-sm text-gray-500 mt-1">{{ entry.note }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ formatDateTime(entry.created_at) }}</p>
                </div>
              </div>
            </div>
            <p v-else class="text-sm text-gray-400">No activity yet.</p>
          </div>
        </div>


          <!-- Warranty Card -->
          <div v-if="warranty" class="rounded-2xl border-2 overflow-hidden"
               :class="warranty.is_active ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50'">

            <div class="px-5 py-3 border-b flex items-center justify-between"
                 :class="warranty.is_active ? 'border-green-200 bg-green-100/50' : 'border-gray-200 bg-gray-100/50'">
              <div class="flex items-center gap-2">
                <ShieldCheckIcon class="w-4 h-4" :class="warranty.is_active ? 'text-green-600' : 'text-gray-400'" />
                <span class="font-semibold text-sm text-gray-900">Service Warranty</span>
                <span class="font-mono text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ warranty.warranty_number }}</span>
              </div>
              <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                    :class="warranty.is_active ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600'">
                {{ warranty.is_active ? '✓ ACTIVE' : 'EXPIRED' }}
              </span>
            </div>

            <div class="p-5">
              <!-- Services covered -->
              <div class="flex flex-wrap gap-1.5 mb-4">
                <span v-for="svc in warranty.services_list" :key="svc"
                      class="text-xs px-2.5 py-1 rounded-full font-medium"
                      :class="warranty.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                  ✓ {{ svc }}
                </span>
              </div>

              <!-- Period + progress -->
              <div class="bg-white rounded-xl p-3 mb-3">
                <div class="flex justify-between text-xs text-gray-600 font-medium mb-2">
                  <span>From: {{ formatDate(warranty.starts_at) }}</span>
                  <span :class="warranty.is_active ? 'text-gray-700' : 'text-red-500'">
                    Expires: {{ formatDate(warranty.expires_at) }}
                  </span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full transition-all"
                       :class="warranty.is_active ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gray-300'"
                       :style="`width:${warranty.progress_percent}%`"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-1.5">
                  <span>{{ warranty.progress_percent }}% elapsed</span>
                  <span v-if="warranty.is_active" class="text-green-600 font-semibold">
                    {{ warranty.days_remaining }} days remaining
                  </span>
                  <span v-else class="text-red-400">Warranty expired</span>
                </div>
              </div>

              <p class="text-xs text-gray-500">
                Customer can claim warranty by calling
                <a href="tel:+919444900470" class="text-blue-600 font-semibold">+91 94449 00470</a>.
                Same issue recurrence within 6 months = free re-service.
              </p>
            </div>
          </div>

        <!-- ======== RIGHT COLUMN ======== -->
        <div class="space-y-4">

          <!-- Appointment -->
          <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <div class="flex items-center gap-3 mb-3">
              <CalendarIcon class="w-5 h-5 text-blue-600" />
              <h3 class="font-semibold text-blue-900">Appointment</h3>
            </div>
            <p class="text-2xl font-bold text-blue-900">{{ formatDate(booking.booking_date) }}</p>
            <p class="text-blue-700 text-sm mt-1 font-medium">{{ booking.time_slot }}</p>
          </div>

          <!-- Technician -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Assigned Technician</h3>
            <div v-if="booking.technician" class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <UserIcon class="w-5 h-5 text-indigo-600" />
              </div>
              <div>
                <p class="font-semibold text-gray-900">{{ booking.technician.name }}</p>
                <a :href="`tel:${booking.technician.phone}`"
                   class="text-sm text-blue-600 hover:underline">{{ booking.technician.phone }}</a>
              </div>
            </div>
            <p v-else class="text-gray-400 text-sm mb-4">No technician assigned yet.</p>

            <div v-if="canAssign">
              <select v-model="selectedTechnician"
                      class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                <option value="">Select Technician...</option>
                <option v-for="tech in technicians" :key="tech.id" :value="tech.id">
                  {{ tech.name }}
                </option>
              </select>
              <button @click="assignTechnician"
                      :disabled="!selectedTechnician || actionLoading"
                      class="w-full mt-2 bg-indigo-600 text-white py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                {{ actionLoading ? 'Assigning...' : 'Assign' }}
              </button>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-2.5">
            <h3 class="font-semibold text-gray-900 mb-3">Actions</h3>

            <button v-if="booking.status === 'pending'"
                    @click="doAction('confirm')" :disabled="actionLoading"
                    class="w-full bg-green-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors disabled:opacity-60 flex items-center justify-center gap-2">
              <CheckIcon class="w-4 h-4" /> Confirm Booking
            </button>

            <button v-if="booking.status === 'confirmed'"
                    @click="doAction('assign')" :disabled="!selectedTechnician || actionLoading"
                    class="w-full bg-indigo-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors disabled:opacity-60">
              Confirm & Assign
            </button>

            <button v-if="booking.status === 'assigned'"
                    @click="doAction('in-progress')" :disabled="actionLoading"
                    class="w-full bg-purple-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-purple-700 transition-colors disabled:opacity-60">
              Mark In Progress
            </button>

            <button v-if="['in_progress', 'assigned'].includes(booking.status)"
                    @click="doAction('complete')" :disabled="actionLoading"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors disabled:opacity-60 flex items-center justify-center gap-2">
              <CheckCircleIcon class="w-4 h-4" /> Mark Completed
            </button>

            <!-- Invoice button - available any time -->
            <a :href="`/admin/bookings/${booking.id}/invoice/create`"
               class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition-all flex items-center justify-center gap-2">
              <DocumentTextIcon class="w-4 h-4" />
              {{ invoice ? 'View / Edit Invoice' : 'Generate Invoice' }}
            </a>

            <!-- View existing invoice -->
            <a v-if="invoice"
               :href="`/admin/bookings/${booking.id}/invoice/${invoice.id}`"
               class="w-full border-2 border-blue-200 text-blue-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
              <span class="w-2 h-2 rounded-full flex-shrink-0"
                    :class="{
                      'bg-green-500': invoice.status === 'paid',
                      'bg-amber-500': invoice.status === 'sent',
                      'bg-gray-400':  invoice.status === 'draft',
                    }"></span>
              Invoice {{ invoice.invoice_number }}
              <span class="text-xs font-bold capitalize">({{ invoice.status }})</span>
            </a>

            <button v-if="!['completed','cancelled'].includes(booking.status)"
                    @click="showRescheduleModal = true"
                    class="w-full border-2 border-orange-400 text-orange-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-orange-50 transition-colors">
              Reschedule
            </button>

            <button v-if="!['completed','cancelled'].includes(booking.status)"
                    @click="showCancelModal = true"
                    class="w-full border-2 border-red-300 text-red-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-50 transition-colors">
              Cancel Booking
            </button>
          </div>

          <!-- Admin Notes -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Admin Notes</h3>
            <textarea v-model="adminNotes" rows="3"
                      placeholder="Internal notes..."
                      class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"></textarea>
            <button @click="saveNotes" :disabled="savingNotes"
                    class="mt-2 w-full bg-gray-800 text-white py-2 rounded-xl text-sm font-semibold hover:bg-gray-900 transition-colors disabled:opacity-60">
              {{ savingNotes ? 'Saving...' : 'Save Notes' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <RescheduleModal
      :is-open="showRescheduleModal"
      :booking-id="booking.id"
      @close="showRescheduleModal = false"
      @rescheduled="onRescheduled"
    />
    <CancelModal
      :is-open="showCancelModal"
      @close="showCancelModal = false"
      @cancelled="onCancelled"
    />
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ArrowLeftIcon, CalendarIcon, ClockIcon, ShieldCheckIcon, DocumentTextIcon,
  CheckIcon, CheckCircleIcon, UserIcon,
} from '@heroicons/vue/24/outline'
import AdminLayout     from '@/components/Admin/AdminLayout.vue'
import StatusBadge     from '@/components/Shared/StatusBadge.vue'
import RescheduleModal from '@/components/Admin/Modals/RescheduleModal.vue'
import CancelModal     from '@/components/Admin/Modals/CancelModal.vue'

const props = defineProps({
  booking:     { type: Object, required: true },
  warranty:    { type: Object, default: null },
  invoice:     { type: Object, default: null },
  technicians: { type: Array,  default: () => [] },
})

const actionLoading       = ref(false)
const savingNotes         = ref(false)
const showRescheduleModal = ref(false)
const showCancelModal     = ref(false)
const adminNotes          = ref(props.booking.admin_notes ?? '')
const selectedTechnician  = ref(props.booking.technician_id ?? '')

const canAssign = computed(() =>
  !['completed', 'cancelled'].includes(props.booking.status)
)

// Explicit URL builder — no computed base that could lose the action segment
function actionUrl(action) {
  return `/admin/bookings/${props.booking.id}/${action}`
}

// Use Inertia router.patch — handles CSRF token automatically, no axios needed
function doAction(action, data = {}) {
  actionLoading.value = true
  router.patch(actionUrl(action), data, {
    preserveScroll: true,
    onSuccess: () => router.reload({ only: ['booking'] }),
    onError:   (errors) => alert(Object.values(errors)[0] ?? 'Action failed.'),
    onFinish:  () => { actionLoading.value = false },
  })
}

function assignTechnician() {
  if (!selectedTechnician.value) return
  doAction('assign', { technician_id: selectedTechnician.value })
}

function onCancelled(reason) {
  showCancelModal.value = false
  doAction('cancel', { reason })
}

function onRescheduled() {
  showRescheduleModal.value = false
  router.reload({ only: ['booking'] })
}

function saveNotes() {
  savingNotes.value = true
  router.patch(actionUrl('notes'), { admin_notes: adminNotes.value }, {
    preserveScroll: true,
    onFinish: () => { savingNotes.value = false },
  })
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' })
}

function formatDateTime(d) {
  return new Date(d).toLocaleString('en-IN', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}
</script>