<template>
  <AdminLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold text-gray-900">Services</h1>
          <p class="text-sm text-gray-500 mt-0.5">Manage service catalogue & pricing</p>
        </div>
        <a href="/admin/services/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
          <PlusIcon class="w-4 h-4" /> Add Service
        </a>
      </div>
    </template>

    <!-- Group by category -->
    <div v-for="category in groupedServices" :key="category.id" class="mb-8">
      <div class="flex items-center gap-3 mb-3">
        <span class="text-2xl">{{ category.icon ?? '🔧' }}</span>
        <h2 class="text-lg font-bold text-gray-900">{{ category.name }}</h2>
        <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
          {{ category.services.length }} services
        </span>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="text-left px-5 py-3 font-semibold text-gray-600">Service Name</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden sm:table-cell">Base Price</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden md:table-cell">Discounted</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden lg:table-cell">Duration</th>
              <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="service in category.services" :key="service.id"
                class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <span v-if="service.is_featured"
                        class="text-xs bg-yellow-100 text-yellow-700 font-semibold px-2 py-0.5 rounded-full">
                    ★ Featured
                  </span>
                  <span class="font-medium text-gray-900">{{ service.name }}</span>
                </div>
              </td>
              <td class="px-5 py-4 hidden sm:table-cell font-semibold text-gray-900">
                ₹{{ service.base_price }}
              </td>
              <td class="px-5 py-4 hidden md:table-cell">
                <span v-if="service.discounted_price" class="text-green-600 font-semibold">
                  ₹{{ service.discounted_price }}
                </span>
                <span v-else class="text-gray-300">—</span>
              </td>
              <td class="px-5 py-4 hidden lg:table-cell text-gray-500">
                {{ service.duration_estimate ?? '—' }}
              </td>
              <td class="px-5 py-4">
                <span :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                  service.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500',
                ]">
                  {{ service.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-5 py-4 text-right">
                <a :href="`/admin/services/${service.id}/edit`"
                   class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                  Edit →
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="!services?.length" class="text-center py-16 text-gray-400">
      No services yet. <a href="/admin/services/create" class="text-blue-600 font-semibold">Add one</a>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { PlusIcon } from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  services:   { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
})

const groupedServices = computed(() => {
  return props.categories.map(cat => ({
    ...cat,
    services: props.services.filter(s => s.category_id === cat.id),
  })).filter(cat => cat.services.length > 0)
})
</script>