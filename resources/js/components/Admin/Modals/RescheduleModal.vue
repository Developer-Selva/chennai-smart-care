<template>
  <!-- RescheduleModal.vue -->
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="$emit('close')" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
          <div class="bg-orange-500 px-5 py-4 text-white flex items-center justify-between">
            <h3 class="font-bold text-lg">Reschedule Booking</h3>
            <button @click="$emit('close')"><XMarkIcon class="w-5 h-5" /></button>
          </div>

          <div class="p-5 space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Date *</label>
              <input v-model="form.date" type="date"
                     :min="today"
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Time Slot *</label>
              <div class="grid grid-cols-2 gap-2">
                <button v-for="slot in slots" :key="slot.value"
                        @click="form.time_slot = slot.value"
                        :class="[
                          'py-2.5 px-3 rounded-xl border-2 text-xs font-medium transition-all',
                          form.time_slot === slot.value
                            ? 'border-orange-500 bg-orange-50 text-orange-700'
                            : 'border-gray-200 text-gray-600 hover:border-orange-200'
                        ]">
                  {{ slot.label }}
                </button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Reason (Optional)</label>
              <input v-model="form.note" type="text" placeholder="e.g. Customer requested change"
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none" />
            </div>

            <p v-if="error" class="text-red-600 text-sm">{{ error }}</p>
          </div>

          <div class="px-5 pb-5 flex gap-3">
            <button @click="$emit('close')"
                    class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button @click="submit" :disabled="submitting || !canSubmit"
                    class="flex-1 bg-orange-500 text-white py-2.5 rounded-xl text-sm font-bold hover:bg-orange-600 transition-colors disabled:opacity-60">
              {{ submitting ? 'Saving...' : 'Reschedule' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  isOpen:    { type: Boolean, required: true },
  bookingId: { type: Number,  required: true },
})

const emit = defineEmits(['close', 'rescheduled'])

const form       = ref({ date: '', time_slot: '', note: '' })
const submitting  = ref(false)
const error       = ref('')
const today       = new Date().toISOString().split('T')[0]

const slots = [
  { value: '09:00-11:00', label: '9:00 – 11:00 AM' },
  { value: '11:00-13:00', label: '11:00 AM – 1:00 PM' },
  { value: '13:00-15:00', label: '1:00 – 3:00 PM' },
  { value: '15:00-17:00', label: '3:00 – 5:00 PM' },
  { value: '17:00-19:00', label: '5:00 – 7:00 PM' },
  { value: '19:00-21:00', label: '7:00 – 9:00 PM' },
]

const canSubmit = computed(() => form.value.date && form.value.time_slot)

function submit() {
  error.value      = ''
  submitting.value  = true
  router.patch(
    `/admin/bookings/${props.bookingId}/reschedule`,
    form.value,
    {
      preserveScroll: true,
      onSuccess: () => {
        emit('rescheduled')
        form.value = { date: '', time_slot: '', note: '' }
      },
      onError: (errors) => {
        error.value = Object.values(errors)[0] ?? 'Something went wrong.'
      },
      onFinish: () => { submitting.value = false },
    }
  )
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all .2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(.97); }
</style>