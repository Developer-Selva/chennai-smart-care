<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-xl font-bold text-gray-900">Consultations</h1>
        <p class="text-sm text-gray-500 mt-0.5">Free consultation requests from customers</p>
      </div>
    </template>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Customer</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden sm:table-cell">Service Interest</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden md:table-cell">Message</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden lg:table-cell">Received</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-if="!consultations.data?.length">
            <td colspan="6" class="text-center py-12 text-gray-400">No consultation requests yet.</td>
          </tr>
          <tr v-for="c in consultations.data" :key="c.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-4">
              <p class="font-medium text-gray-900">{{ c.name }}</p>
              <a :href="`tel:${c.phone}`" class="text-blue-600 text-xs">{{ c.phone }}</a>
            </td>
            <td class="px-5 py-4 hidden sm:table-cell text-gray-600 capitalize">
              {{ c.service_interest?.replace('_', ' ') ?? '—' }}
            </td>
            <td class="px-5 py-4 hidden md:table-cell">
              <p class="text-gray-600 line-clamp-1 max-w-[220px]">{{ c.message ?? '—' }}</p>
            </td>
            <td class="px-5 py-4">
              <select v-model="c.status" @change="updateStatus(c)"
                      :class="[
                        'text-xs font-semibold px-2.5 py-1 rounded-full border-0 focus:ring-2 focus:ring-blue-500 cursor-pointer',
                        statusColors[c.status] ?? 'bg-gray-100 text-gray-600',
                      ]">
                <option value="new">New</option>
                <option value="contacted">Contacted</option>
                <option value="converted">Converted</option>
                <option value="closed">Closed</option>
              </select>
            </td>
            <td class="px-5 py-4 hidden lg:table-cell text-gray-400 text-xs">
              {{ formatDate(c.created_at) }}
            </td>
            <td class="px-5 py-4 text-right">
              <a :href="`tel:${c.phone}`" class="text-green-600 text-xs font-semibold hover:text-green-800">📞 Call</a>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="consultations.last_page > 1" class="border-t border-gray-100 px-5 py-4 flex justify-between text-sm">
        <p class="text-gray-500">{{ consultations.from }}–{{ consultations.to }} of {{ consultations.total }}</p>
        <div class="flex gap-2">
          <a v-if="consultations.prev_page_url" :href="consultations.prev_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">← Prev</a>
          <a v-if="consultations.next_page_url" :href="consultations.next_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Next →</a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import axios from 'axios'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

defineProps({
  consultations: { type: Object, required: true },
})

const statusColors = {
  new:       'bg-blue-100 text-blue-700',
  contacted: 'bg-yellow-100 text-yellow-700',
  converted: 'bg-green-100 text-green-700',
  closed:    'bg-gray-100 text-gray-500',
}

async function updateStatus(consultation) {
  await axios.patch(`/admin/consultations/${consultation.id}`, { status: consultation.status })
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>