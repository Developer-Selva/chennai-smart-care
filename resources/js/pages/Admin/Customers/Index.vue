<template>
  <AdminLayout>
    <template #header>
      <div>
        <h1 class="text-xl font-bold text-gray-900">Customers</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ customers.total }} registered customers</p>
      </div>
    </template>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100">
        <input v-model="search" type="text" placeholder="Search by name or phone..."
               class="w-full sm:w-72 px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
               @keyup.enter="doSearch" />
      </div>

      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Customer</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden sm:table-cell">Phone</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden md:table-cell">Bookings</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden lg:table-cell">Loyalty Points</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600 hidden lg:table-cell">Joined</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-if="!customers.data?.length">
            <td colspan="6" class="text-center py-12 text-gray-400">No customers found.</td>
          </tr>
          <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-4">
              <p class="font-medium text-gray-900">{{ customer.name }}</p>
              <p class="text-gray-400 text-xs mt-0.5">{{ customer.email }}</p>
            </td>
            <td class="px-5 py-4 hidden sm:table-cell text-gray-600">{{ customer.phone }}</td>
            <td class="px-5 py-4 hidden md:table-cell">
              <span class="font-semibold text-gray-900">{{ customer.bookings_count }}</span>
            </td>
            <td class="px-5 py-4 hidden lg:table-cell">
              <span class="text-blue-600 font-semibold">{{ customer.loyalty_points ?? 0 }} pts</span>
            </td>
            <td class="px-5 py-4 hidden lg:table-cell text-gray-500 text-xs">
              {{ formatDate(customer.created_at) }}
            </td>
            <td class="px-5 py-4 text-right">
              <a :href="`/admin/customers/${customer.id}`"
                 class="text-blue-600 text-xs font-semibold hover:text-blue-800">View →</a>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="customers.last_page > 1" class="border-t border-gray-100 px-5 py-4 flex items-center justify-between text-sm">
        <p class="text-gray-500">{{ customers.from }}–{{ customers.to }} of {{ customers.total }}</p>
        <div class="flex gap-2">
          <a v-if="customers.prev_page_url" :href="customers.prev_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">← Prev</a>
          <a v-if="customers.next_page_url" :href="customers.next_page_url"
             class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Next →</a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  customers: { type: Object, required: true },
})

const search = ref('')

function doSearch() {
  router.get('/admin/customers', { search: search.value || undefined }, { preserveState: true })
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>