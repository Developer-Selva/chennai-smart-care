<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <a href="/admin/bookings" class="text-gray-400 hover:text-gray-600 transition-colors">
          <ChevronLeftIcon class="w-5 h-5" />
        </a>
        <div>
          <h1 class="text-xl font-bold text-gray-900">New Booking</h1>
          <p class="text-sm text-gray-500 mt-0.5">Create a manual booking (phone / walk-in)</p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="max-w-3xl space-y-6">

      <!-- Customer Info -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
          <UserIcon class="w-5 h-5 text-blue-500" /> Customer Details
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="label">Full Name *</label>
            <input v-model="form.guest_name" type="text" class="input" placeholder="Rajesh Kumar" />
            <p v-if="errors.guest_name" class="err">{{ errors.guest_name }}</p>
          </div>
          <div>
            <label class="label">Phone Number *</label>
            <div class="flex">
              <span class="prefix">+91</span>
              <input v-model="form.guest_phone" type="tel" maxlength="10" class="input rounded-l-none" placeholder="9444900470" />
            </div>
            <p v-if="errors.guest_phone" class="err">{{ errors.guest_phone }}</p>
          </div>
          <div>
            <label class="label">Email (Optional)</label>
            <input v-model="form.guest_email" type="email" class="input" placeholder="customer@email.com" />
          </div>
          <div>
            <label class="label">Booking Source</label>
            <select v-model="form.source" class="input bg-white">
              <option value="phone">📞 Phone Call</option>
              <option value="whatsapp">💬 WhatsApp</option>
              <option value="walkin">🚶 Walk-in</option>
              <option value="website">🌐 Website</option>
              <option value="admin">👤 Admin</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Services -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
          <WrenchScrewdriverIcon class="w-5 h-5 text-blue-500" /> Services
        </h2>
        <div v-for="category in categories" :key="category.id" class="mb-4">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ category.name }}</p>
          <div class="space-y-2">
            <button v-for="service in category.services" :key="service.id" type="button"
                    @click="toggleService(service)"
                    :class="[
                      'w-full flex justify-between items-center px-4 py-3 rounded-xl border-2 text-sm transition-all text-left',
                      isSelected(service.id) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300',
                    ]">
              <span :class="isSelected(service.id) ? 'font-semibold text-blue-700' : 'text-gray-700'">
                {{ service.name }}
              </span>
              <span class="font-bold text-gray-900">₹{{ service.effective_price ?? service.base_price }}</span>
            </button>
          </div>
        </div>
        <p v-if="errors.services" class="err mt-2">{{ errors.services }}</p>
        <div v-if="form.services.length" class="mt-4 p-3 bg-blue-50 rounded-xl flex justify-between text-sm font-semibold">
          <span class="text-blue-700">{{ form.services.length }} service(s) selected</span>
          <span class="text-blue-900">₹{{ totalPrice }}</span>
        </div>
      </div>

      <!-- Address -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
          <MapPinIcon class="w-5 h-5 text-blue-500" /> Service Address
        </h2>
        <div class="space-y-4">
          <div>
            <label class="label">Full Address *</label>
            <textarea v-model="form.address" rows="2" class="input resize-none" placeholder="House/Flat No, Street, Locality..."></textarea>
            <p v-if="errors.address" class="err">{{ errors.address }}</p>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="label">Area</label>
              <input v-model="form.area" type="text" class="input" placeholder="Anna Nagar" />
            </div>
            <div>
              <label class="label">Pincode</label>
              <input v-model="form.pincode" type="text" maxlength="6" class="input" placeholder="600001" />
            </div>
          </div>
        </div>
      </div>

      <!-- Schedule -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
          <CalendarIcon class="w-5 h-5 text-blue-500" /> Schedule
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="label">Date *</label>
            <input v-model="form.booking_date" type="date" :min="today" class="input" @change="loadSlots" />
            <p v-if="errors.booking_date" class="err">{{ errors.booking_date }}</p>
          </div>
          <div>
            <label class="label">Time Slot *</label>
            <select v-model="form.time_slot" class="input bg-white" :disabled="!slots.length">
              <option value="">{{ form.booking_date ? 'Select slot' : 'Pick a date first' }}</option>
              <option v-for="slot in slots" :key="slot.slot"
                      :value="slot.slot" :disabled="!slot.available">
                {{ slot.label }}{{ !slot.available ? ' (Full)' : '' }}
              </option>
            </select>
            <p v-if="errors.time_slot" class="err">{{ errors.time_slot }}</p>
          </div>
          <div>
            <label class="label">Assign Technician (Optional)</label>
            <select v-model="form.technician_id" class="input bg-white">
              <option :value="null">— Auto-assign later —</option>
              <option v-for="t in technicians" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
          </div>
          <div>
            <label class="label">Admin Notes</label>
            <input v-model="form.admin_notes" type="text" class="input" placeholder="Internal notes..." />
          </div>
        </div>
      </div>

      <!-- Submit -->
      <div class="flex gap-4">
        <button type="submit" :disabled="submitting"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors disabled:opacity-60">
          {{ submitting ? 'Creating...' : 'Create Booking' }}
        </button>
        <a href="/admin/bookings"
           class="px-8 py-3 border border-gray-300 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
          Cancel
        </a>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import {
  ChevronLeftIcon, UserIcon, MapPinIcon,
  CalendarIcon, WrenchScrewdriverIcon,
} from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  categories:  { type: Array, default: () => [] },
  technicians: { type: Array, default: () => [] },
})

const submitting = ref(false)
const slots      = ref([])
const today      = new Date().toISOString().split('T')[0]

const form = useForm({
  guest_name:    '',
  guest_phone:   '',
  guest_email:   '',
  source:        'phone',
  services:      [],
  address:       '',
  area:          '',
  pincode:       '',
  latitude:      null,
  longitude:     null,
  booking_date:  '',
  time_slot:     '',
  technician_id: null,
  admin_notes:   '',
})

const errors = computed(() => form.errors)

const totalPrice = computed(() =>
  form.services.reduce((sum, s) => sum + parseFloat(s.price ?? 0), 0)
)

const allServices = computed(() =>
  props.categories.flatMap(c => c.services ?? [])
)

function isSelected(id) {
  return form.services.some(s => s.id === id)
}

function toggleService(service) {
  const idx = form.services.findIndex(s => s.id === service.id)
  if (idx >= 0) form.services.splice(idx, 1)
  else form.services.push({ id: service.id, name: service.name, price: service.effective_price ?? service.base_price, quantity: 1 })
}

async function loadSlots() {
  if (!form.booking_date) return
  form.time_slot = ''
  try {
    const res = await axios.get(`/admin/bookings/slots/${form.booking_date}`)
    slots.value = res.data.slots ?? []
  } catch { slots.value = [] }
}

function submit() {
  submitting.value = true
  form.post('/admin/bookings', {
    onFinish: () => { submitting.value = false },
  })
}
</script>

<style scoped>
.label  { @apply block text-sm font-semibold text-gray-700 mb-1.5; }
.input  { @apply w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow; }
.prefix { @apply inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm; }
.err    { @apply mt-1 text-red-500 text-xs; }
</style>