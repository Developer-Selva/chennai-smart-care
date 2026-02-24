<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="isOpen"
           class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
           role="dialog" aria-modal="true" aria-labelledby="consultation-title">

        <!-- Backdrop -->
        <div @click="$emit('close')"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Modal Panel -->
        <div class="relative bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden">

          <!-- Header -->
          <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5 text-white">
            <div class="flex items-start justify-between">
              <div>
                <h3 id="consultation-title" class="text-xl font-bold">Free Consultation</h3>
                <p class="text-blue-100 text-sm mt-1">We'll call you back within 30 minutes</p>
              </div>
              <button @click="$emit('close')"
                      class="text-white/70 hover:text-white transition-colors ml-4 flex-shrink-0 mt-0.5">
                <XMarkIcon class="w-6 h-6" />
              </button>
            </div>
          </div>

          <!-- Form -->
          <div v-if="!submitted" class="p-6">
            <form @submit.prevent="submit" class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Your Name *</label>
                <input v-model="form.name" type="text" required
                       placeholder="e.g. Rajesh Kumar"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-shadow" />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number *</label>
                <div class="flex">
                  <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+91</span>
                  <input v-model="form.phone" type="tel" required maxlength="10"
                         placeholder="98765 43210"
                         class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-shadow" />
                </div>
                <p v-if="phoneError" class="text-red-500 text-xs mt-1">{{ phoneError }}</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">What needs repair?</label>
                <select v-model="form.service_interest"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-shadow bg-white">
                  <option value="">Select appliance...</option>
                  <option value="ac">Air Conditioner (AC)</option>
                  <option value="refrigerator">Refrigerator / Fridge</option>
                  <option value="washing_machine">Washing Machine</option>
                  <option value="multiple">Multiple Appliances</option>
                  <option value="other">Other / Not Sure</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Describe the issue <span class="font-normal text-gray-400">(optional)</span></label>
                <textarea v-model="form.message" rows="3"
                          placeholder="e.g. AC not cooling, fridge making noise..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-shadow resize-none"></textarea>
              </div>

              <p v-if="serverError" class="text-red-600 text-sm">{{ serverError }}</p>

              <button type="submit" :disabled="submitting"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-bold text-sm transition-colors disabled:opacity-60 disabled:cursor-not-allowed shadow-sm">
                {{ submitting ? 'Sending...' : 'Request Free Consultation' }}
              </button>

              <p class="text-xs text-gray-400 text-center">
                🔒 No spam. We respect your privacy.
              </p>
            </form>
          </div>

          <!-- Success state -->
          <div v-else class="p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <CheckIcon class="w-8 h-8 text-green-500" />
            </div>
            <h4 class="text-xl font-bold text-gray-900">Request Received!</h4>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed">
              Our expert will call you within <strong class="text-gray-700">30 minutes</strong>.
              We'll assess your appliance issue and give you a free quote.
            </p>
            <button @click="$emit('close')"
                    class="mt-6 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold text-sm transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import { XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  isOpen: { type: Boolean, required: true },
})

const emit = defineEmits(['close'])

const form = ref({ name: '', phone: '', message: '', service_interest: '' })
const submitting  = ref(false)
const submitted   = ref(false)
const phoneError  = ref('')
const serverError = ref('')

// Reset form when modal opens
watch(() => props.isOpen, (val) => {
  if (val) {
    form.value    = { name: '', phone: '', message: '', service_interest: '' }
    submitted.value  = false
    submitting.value = false
    phoneError.value = ''
    serverError.value = ''
  }
})

async function submit() {
  phoneError.value  = ''
  serverError.value = ''

  if (!/^[6-9]\d{9}$/.test(form.value.phone)) {
    phoneError.value = 'Please enter a valid 10-digit Indian mobile number.'
    return
  }

  submitting.value = true
  try {
    await axios.post('/free-consultation', form.value)
    submitted.value = true

    if (window.dataLayer) {
      window.dataLayer.push({
        event: 'consultation_request',
        event_category: 'Lead',
        service_interest: form.value.service_interest,
      })
    }
  } catch {
    serverError.value = 'Something went wrong. Please call us directly.'
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all .25s ease; }
.modal-enter-from, .modal-leave-to       { opacity: 0; }
.modal-enter-from .relative,
.modal-leave-to .relative                { transform: translateY(24px) scale(.97); }
</style>