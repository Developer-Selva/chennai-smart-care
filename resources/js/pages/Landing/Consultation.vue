<template>
  <div class="min-h-screen bg-gray-50">
    <AppHeader />

    <!-- Hero -->
    <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white py-16 px-4">
      <div class="max-w-3xl mx-auto text-center">
        <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wider">
          Free Service
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mb-4">Free Consultation</h1>
        <p class="text-blue-100 text-lg">
          Not sure what's wrong? Describe the issue and our experts will get back to you within 2 hours — completely free.
        </p>
      </div>
    </section>

    <!-- Form -->
    <section class="max-w-2xl mx-auto px-4 py-12">

      <!-- Success state -->
      <div v-if="submitted" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
          <CheckCircleIcon class="w-8 h-8 text-green-500" />
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Request Received!</h2>
        <p class="text-gray-500 mb-6">
          Our team will call you back within 2 hours during business hours (9 AM – 9 PM).
        </p>
        <a href="/"
           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
          Back to Home
        </a>
      </div>

      <!-- Form state -->
      <div v-else class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8">
          <h2 class="text-xl font-bold text-gray-900 mb-1">Tell Us About Your Issue</h2>
          <p class="text-gray-500 text-sm mb-6">Fill in your details and we'll call you back with a free diagnosis.</p>

          <div class="space-y-5">
            <!-- Name -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Your Name *</label>
              <input v-model="form.name" type="text" placeholder="e.g. Rajesh Kumar"
                     class="form-input" :class="{ 'border-red-400': errors.name }" />
              <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
            </div>

            <!-- Phone -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number *</label>
              <div class="flex">
                <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+91</span>
                <input v-model="form.phone" type="tel" maxlength="10" placeholder="98765 43210"
                       class="form-input rounded-l-none flex-1" :class="{ 'border-red-400': errors.phone }" />
              </div>
              <p v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone }}</p>
            </div>

            <!-- Appliance type -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Appliance Type *</label>
              <select v-model="form.appliance_type" class="form-input bg-white"
                      :class="{ 'border-red-400': errors.appliance_type }">
                <option value="">Select appliance...</option>
                <option value="ac">Air Conditioner (AC)</option>
                <option value="washing_machine">Washing Machine</option>
                <option value="refrigerator">Refrigerator</option>
                <option value="microwave">Microwave / Oven</option>
                <option value="tv">Television / TV</option>
                <option value="water_heater">Water Heater / Geyser</option>
                <option value="dishwasher">Dishwasher</option>
                <option value="other">Other</option>
              </select>
              <p v-if="errors.appliance_type" class="text-red-500 text-xs mt-1">{{ errors.appliance_type }}</p>
            </div>

            <!-- Issue description -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Describe the Problem *</label>
              <textarea v-model="form.message" rows="4" placeholder="e.g. AC is running but not cooling, making a rattling noise..."
                        class="form-input resize-none" :class="{ 'border-red-400': errors.message }"></textarea>
              <p v-if="errors.message" class="text-red-500 text-xs mt-1">{{ errors.message }}</p>
            </div>

            <!-- Preferred time -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Callback Time</label>
              <div class="grid grid-cols-3 gap-2">
                <button v-for="slot in timeSlots" :key="slot.value"
                        @click="form.preferred_time = slot.value"
                        :class="[
                          'py-2.5 px-3 rounded-xl border-2 text-xs font-medium transition-all text-center',
                          form.preferred_time === slot.value
                            ? 'border-blue-500 bg-blue-50 text-blue-700'
                            : 'border-gray-200 text-gray-600 hover:border-blue-200',
                        ]">
                  {{ slot.label }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Error banner -->
        <div v-if="submitError" class="mx-6 mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
          {{ submitError }}
        </div>

        <!-- Submit -->
        <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100">
          <button @click="submit" :disabled="submitting"
                  class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold text-base hover:bg-blue-700 transition-colors disabled:opacity-60 flex items-center justify-center gap-2">
            <span v-if="submitting">Submitting...</span>
            <span v-else>Request Free Consultation</span>
          </button>
          <p class="text-center text-xs text-gray-400 mt-3">
            No spam. We'll only call to help diagnose your appliance issue.
          </p>
        </div>
      </div>

      <!-- Trust badges -->
      <div class="mt-8 grid grid-cols-3 gap-4 text-center">
        <div v-for="badge in badges" :key="badge.label" class="bg-white rounded-xl p-4 border border-gray-100">
          <div class="text-2xl mb-1">{{ badge.icon }}</div>
          <p class="text-xs font-semibold text-gray-700">{{ badge.label }}</p>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { CheckCircleIcon } from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const form = ref({
  name:           '',
  phone:          '',
  appliance_type: '',
  message:        '',
  preferred_time: 'morning',
})

const errors      = ref({})
const submitting  = ref(false)
const submitted   = ref(false)
const submitError = ref('')

const timeSlots = [
  { value: 'morning',   label: '9 AM – 12 PM' },
  { value: 'afternoon', label: '12 PM – 4 PM' },
  { value: 'evening',   label: '4 PM – 9 PM' },
]

const badges = [
  { icon: '⚡', label: '2-hr Callback' },
  { icon: '🆓', label: '100% Free' },
  { icon: '🔧', label: 'Expert Advice' },
]

function validate() {
  errors.value = {}
  if (!form.value.name.trim())           errors.value.name = 'Name is required.'
  if (!form.value.phone || form.value.phone.length !== 10)
                                          errors.value.phone = 'Enter a valid 10-digit number.'
  if (!form.value.appliance_type)        errors.value.appliance_type = 'Please select an appliance.'
  if (!form.value.message.trim())        errors.value.message = 'Please describe the problem.'
  return Object.keys(errors.value).length === 0
}

async function submit() {
  if (!validate()) return
  submitting.value  = true
  submitError.value = ''
  try {
    await axios.post('/free-consultation', form.value)
    submitted.value = true
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors ?? {}
    } else {
      submitError.value = 'Something went wrong. Please try again or call us directly.'
    }
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors text-sm;
}
</style>