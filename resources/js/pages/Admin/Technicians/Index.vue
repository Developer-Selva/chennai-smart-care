<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold text-gray-900">Technicians</h1>
          <p class="text-sm text-gray-500 mt-0.5">{{ technicians.length }} technicians registered</p>
        </div>
        <a href="/admin/technicians/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
          <PlusIcon class="w-4 h-4" /> Add Technician
        </a>
      </div>
    </template>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <div v-for="tech in technicians" :key="tech.id"
           class="bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-700 text-sm flex-shrink-0">
              {{ tech.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) }}
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ tech.name }}</p>
              <a :href="`tel:${tech.phone}`" class="text-blue-600 text-xs hover:underline">{{ tech.phone }}</a>
            </div>
          </div>
          <div class="flex flex-col items-end gap-1">
            <span :class="['text-xs font-semibold px-2.5 py-1 rounded-full', tech.is_available ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500']">
              {{ tech.is_available ? 'Available' : 'Busy' }}
            </span>
            <span v-if="!tech.is_active" class="text-xs bg-red-100 text-red-600 px-2.5 py-1 rounded-full">Inactive</span>
          </div>
        </div>

        <!-- Skills -->
        <div v-if="tech.skills?.length" class="flex flex-wrap gap-1.5 mb-4">
          <span v-for="skill in tech.skills" :key="skill"
                class="text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full">
            {{ skill }}
          </span>
        </div>

        <div class="flex items-center justify-between text-sm border-t border-gray-100 pt-4">
          <div class="flex items-center gap-1 text-yellow-500">
            <span class="font-bold text-gray-900">{{ tech.rating ?? '—' }}</span>
            <span>★</span>
            <span class="text-gray-400 text-xs ml-1">{{ tech.bookings_count ?? 0 }} jobs</span>
          </div>
          <a :href="`/admin/technicians/${tech.id}/edit`"
             class="text-blue-600 text-xs font-semibold hover:text-blue-800">Edit →</a>
        </div>
      </div>
    </div>

    <div v-if="!technicians.length" class="text-center py-16 text-gray-400">
      No technicians yet. <a href="/admin/technicians/create" class="text-blue-600 font-semibold">Add one</a>
    </div>
  </AdminLayout>
</template>

<script setup>
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

defineProps({
  technicians: { type: Array, default: () => [] },
})
</script>