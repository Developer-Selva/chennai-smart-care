<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="$emit('close')" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
          <!-- Header -->
          <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <ExclamationTriangleIcon class="w-5 h-5 text-red-600" />
            </div>
            <div>
              <h3 class="font-bold text-gray-900">Cancel Booking</h3>
              <p class="text-xs text-gray-400">This action cannot be undone.</p>
            </div>
            <button @click="$emit('close')" class="ml-auto text-gray-400 hover:text-gray-600">
              <XMarkIcon class="w-5 h-5" />
            </button>
          </div>

          <!-- Body -->
          <div class="p-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
              Reason for Cancellation *
            </label>
            <textarea v-model="reason" rows="3"
                      placeholder="e.g. Customer requested cancellation, No technician available..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none resize-none"></textarea>

            <!-- Quick reasons -->
            <div class="flex flex-wrap gap-2 mt-2">
              <button v-for="r in quickReasons" :key="r"
                      @click="reason = r"
                      :class="[
                        'text-xs px-3 py-1 rounded-full border transition-colors',
                        reason === r
                          ? 'border-red-400 bg-red-50 text-red-700'
                          : 'border-gray-200 text-gray-500 hover:border-red-200'
                      ]">
                {{ r }}
              </button>
            </div>

            <p v-if="error" class="text-red-600 text-xs mt-2">{{ error }}</p>
          </div>

          <!-- Footer -->
          <div class="px-5 pb-5 flex gap-3">
            <button @click="$emit('close')"
                    class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
              Keep Booking
            </button>
            <button @click="submit" :disabled="!reason.trim()"
                    class="flex-1 bg-red-600 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-red-700 transition-colors disabled:opacity-50">
              Yes, Cancel
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import { XMarkIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

defineProps({ isOpen: { type: Boolean, required: true } })
const emit = defineEmits(['close', 'cancelled'])

const reason = ref('')
const error  = ref('')

const quickReasons = [
  'Customer requested',
  'No technician available',
  'Wrong location',
  'Duplicate booking',
]

function submit() {
  if (!reason.value.trim()) {
    error.value = 'Please provide a reason.'
    return
  }
  emit('cancelled', reason.value)
  reason.value = ''
  error.value  = ''
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all .2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(.97); }
</style>