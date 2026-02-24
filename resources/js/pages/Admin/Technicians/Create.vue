<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center gap-3">
        <a href="/admin/technicians" class="text-gray-400 hover:text-gray-600">
          <ChevronLeftIcon class="w-5 h-5" />
        </a>
        <h1 class="text-xl font-bold text-gray-900">{{ isEdit ? 'Edit Technician' : 'Add Technician' }}</h1>
      </div>
    </template>

    <form @submit.prevent="submit" class="max-w-xl space-y-6">
      <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="label">Full Name *</label>
            <input v-model="form.name" type="text" class="input" placeholder="e.g. Suresh Kumar" />
            <p v-if="form.errors.name" class="err">{{ form.errors.name }}</p>
          </div>
          <div>
            <label class="label">Phone *</label>
            <input v-model="form.phone" type="tel" class="input" placeholder="9876543210" />
            <p v-if="form.errors.phone" class="err">{{ form.errors.phone }}</p>
          </div>
          <div class="sm:col-span-2">
            <label class="label">Email</label>
            <input v-model="form.email" type="email" class="input" placeholder="tech@example.com" />
          </div>
        </div>

        <!-- Skills -->
        <div>
          <label class="label">Skills</label>
          <div class="flex flex-wrap gap-2 mb-2">
            <button v-for="skill in availableSkills" :key="skill" type="button"
                    @click="toggleSkill(skill)"
                    :class="[
                      'px-3 py-1.5 rounded-full text-xs font-semibold border-2 transition-all',
                      form.skills.includes(skill)
                        ? 'border-blue-500 bg-blue-50 text-blue-700'
                        : 'border-gray-200 text-gray-600 hover:border-blue-200',
                    ]">
              {{ skill }}
            </button>
          </div>
        </div>

        <!-- Notes -->
        <div>
          <label class="label">Notes</label>
          <textarea v-model="form.notes" rows="2" class="input resize-none"
                    placeholder="Internal notes about this technician..."></textarea>
        </div>

        <!-- Toggles -->
        <div class="space-y-3 pt-2 border-t border-gray-100">
          <label class="flex items-center gap-3 cursor-pointer">
            <input v-model="form.is_active" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
            <div>
              <p class="text-sm font-medium text-gray-900">Active</p>
              <p class="text-xs text-gray-400">Can be assigned to bookings</p>
            </div>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input v-model="form.is_available" type="checkbox"
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
            <div>
              <p class="text-sm font-medium text-gray-900">Available Now</p>
              <p class="text-xs text-gray-400">Currently free to take new bookings</p>
            </div>
          </label>
        </div>
      </div>

      <div class="flex gap-4">
        <button type="submit" :disabled="form.processing"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors disabled:opacity-60">
          {{ form.processing ? 'Saving...' : (isEdit ? 'Update' : 'Add Technician') }}
        </button>
        <a href="/admin/technicians"
           class="px-8 py-3 border border-gray-300 text-gray-600 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
          Cancel
        </a>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ChevronLeftIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  technician: { type: Object, default: null },
})

const isEdit = !!props.technician

const availableSkills = ['AC Repair', 'AC Installation', 'Refrigerator Repair', 'Washing Machine Repair', 'PCB Repair', 'Gas Refilling']

const form = useForm({
  name:         props.technician?.name ?? '',
  phone:        props.technician?.phone ?? '',
  email:        props.technician?.email ?? '',
  skills:       props.technician?.skills ?? [],
  notes:        props.technician?.notes ?? '',
  is_active:    props.technician?.is_active ?? true,
  is_available: props.technician?.is_available ?? true,
})

function toggleSkill(skill) {
  const idx = form.skills.indexOf(skill)
  if (idx >= 0) form.skills.splice(idx, 1)
  else form.skills.push(skill)
}

function submit() {
  if (isEdit) form.put(`/admin/technicians/${props.technician.id}`)
  else        form.post('/admin/technicians')
}
</script>

<style scoped>
.label { @apply block text-sm font-semibold text-gray-700 mb-1.5; }
.input { @apply w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow; }
.err   { @apply mt-1 text-red-500 text-xs; }
</style>