<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-xl font-bold text-gray-900">Settings</h1>
        <p class="text-sm text-gray-500 mt-0.5">Application configuration</p>
      </div>
    </template>

    <form @submit.prevent="submit" class="max-w-2xl space-y-6">

      <!-- Contact -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Contact & Support</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="label">Support Phone</label>
            <input v-model="form.support_phone" type="text" class="input" placeholder="+91 98765 43210" />
          </div>
          <div>
            <label class="label">WhatsApp Number</label>
            <input v-model="form.whatsapp_number" type="text" class="input" placeholder="+91 98765 43210" />
          </div>
        </div>
      </div>

      <!-- Booking Config -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Booking Configuration</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
          <div>
            <label class="label">Service Radius (km)</label>
            <input v-model="form.booking_radius" type="number" min="1" max="50" class="input" />
          </div>
          <div>
            <label class="label">Max Bookings / Slot</label>
            <input v-model="form.max_per_slot" type="number" min="1" max="20" class="input" />
          </div>
          <div>
            <label class="label">Advance Days</label>
            <input v-model="form.advance_days" type="number" min="1" max="90" class="input" />
          </div>
        </div>
      </div>

      <!-- Analytics -->
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-900">Analytics</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="label">Google Tag Manager ID</label>
            <input v-model="form.gtm_id" type="text" class="input" placeholder="GTM-XXXXXXX" />
          </div>
          <div>
            <label class="label">GA4 Measurement ID</label>
            <input v-model="form.ga4_id" type="text" class="input" placeholder="G-XXXXXXXXXX" />
          </div>
        </div>
      </div>

      <div v-if="$page.props.flash?.success"
           class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
        {{ $page.props.flash.success }}
      </div>

      <div class="flex gap-4">
        <button type="submit" :disabled="form.processing"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors disabled:opacity-60">
          {{ form.processing ? 'Saving...' : 'Save Settings' }}
        </button>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  settings: { type: Object, default: () => ({}) },
})

const form = useForm({
  support_phone:   props.settings.support_phone   ?? '',
  whatsapp_number: props.settings.whatsapp_number ?? '',
  booking_radius:  props.settings.booking_radius  ?? 20,
  max_per_slot:    props.settings.max_per_slot    ?? 3,
  advance_days:    props.settings.advance_days    ?? 30,
  gtm_id:          props.settings.gtm_id          ?? '',
  ga4_id:          props.settings.ga4_id          ?? '',
})

function submit() {
  form.put('/admin/settings')
}
</script>

<style scoped>
.label { @apply block text-sm font-semibold text-gray-700 mb-1.5; }
.input { @apply w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow; }
</style>