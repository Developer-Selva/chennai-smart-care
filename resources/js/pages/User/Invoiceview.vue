<template>
  <div class="min-h-screen bg-gray-50">
    <Head><title>Invoice {{ invoice.invoice_number }} — Chennai Smart Care</title></Head>
    <AppHeader :minimal="true" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

      <!-- Header -->
      <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
        <div class="flex items-center gap-3">
          <a href="/user/dashboard" class="text-gray-500 hover:text-gray-700">
            <ArrowLeftIcon class="w-5 h-5" />
          </a>
          <div>
            <h1 class="text-xl font-bold text-gray-900 font-mono">{{ invoice.invoice_number }}</h1>
            <p class="text-sm text-gray-500">{{ formatDate(invoice.created_at) }}</p>
          </div>
        </div>

        <!-- Download button -->
        <a :href="`/user/invoices/${invoice.id}?download=1`" target="_blank"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
          <ArrowDownTrayIcon class="w-4 h-4" />
          Download / Print
        </a>
      </div>

      <!-- Invoice card -->
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        <!-- Gradient header -->
        <div class="bg-gradient-to-br from-blue-700 to-indigo-700 px-6 py-6 text-white">
          <div class="flex justify-between items-start gap-4 flex-wrap">
            <div>
              <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider">Chennai Smart Care</p>
              <p class="font-bold text-lg mt-0.5">Expert Appliance Repair</p>
              <p class="text-blue-200 text-sm mt-1">+91 94449 00470</p>
            </div>
            <div class="text-right">
              <p class="text-blue-300 text-xs uppercase tracking-wider">Invoice</p>
              <p class="text-xl font-mono font-bold">{{ invoice.invoice_number }}</p>
              <span class="inline-block mt-1.5 px-3 py-0.5 rounded-full text-xs font-bold"
                    :class="invoice.status === 'paid' ? 'bg-green-400 text-green-900' : 'bg-white/20 text-white'">
                {{ invoice.status === 'paid' ? '✓ PAID' : invoice.status_label?.toUpperCase() }}
              </span>
            </div>
          </div>
        </div>

        <!-- Booking ref strip -->
        <div class="bg-blue-50 border-b border-blue-100 px-6 py-3 flex flex-wrap gap-4 text-sm">
          <span class="text-blue-700">📋 <strong>{{ booking.booking_number }}</strong></span>
          <span class="text-blue-600">📅 {{ formatDate(booking.booking_date) }}</span>
          <span class="text-blue-600">⏰ {{ booking.time_slot }}</span>
          <span v-if="booking.technician" class="text-blue-600">
            👨‍🔧 {{ booking.technician.name }}
          </span>
        </div>

        <!-- Customer -->
        <div class="px-6 py-4 border-b border-gray-100">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Bill To</p>
          <p class="font-bold text-gray-900">{{ invoice.customer_name }}</p>
          <p class="text-sm text-gray-500">{{ invoice.customer_address }}</p>
        </div>

        <!-- Line items -->
        <div class="px-6 py-5">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Services & Charges</p>
          <div class="space-y-2">
            <div v-for="item in invoice.items" :key="item.id"
                 class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0 gap-4">
              <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 text-sm">{{ item.description }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                  ₹{{ fmt(item.unit_price) }} × {{ item.quantity }}
                  <span v-if="item.category && item.category !== 'service'"
                        class="ml-1.5 px-1.5 py-0.5 rounded text-xs font-medium"
                        :class="{
                          'bg-amber-100 text-amber-700': item.category === 'labour',
                          'bg-emerald-100 text-emerald-700': item.category === 'parts',
                          'bg-purple-100 text-purple-700': item.category === 'extra',
                        }">
                    {{ item.category }}
                  </span>
                </p>
              </div>
              <span class="font-bold text-gray-900 text-sm flex-shrink-0">₹{{ fmt(item.total) }}</span>
            </div>
          </div>

          <!-- Totals -->
          <div class="mt-4 pt-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-500">
              <span>Subtotal</span>
              <span>₹{{ fmt(invoice.subtotal) }}</span>
            </div>
            <div v-if="invoice.discount_amount > 0" class="flex justify-between text-sm text-green-600">
              <span>Discount</span>
              <span>−₹{{ fmt(invoice.discount_amount) }}</span>
            </div>
            <div v-if="invoice.tax_amount > 0" class="flex justify-between text-sm text-gray-500">
              <span>GST ({{ invoice.tax_percent }}%)</span>
              <span>+₹{{ fmt(invoice.tax_amount) }}</span>
            </div>
            <div class="border-t-2 border-blue-600 pt-3 flex justify-between items-center">
              <span class="font-bold text-gray-900">Total</span>
              <span class="text-2xl font-extrabold text-blue-600">₹{{ fmt(invoice.total) }}</span>
            </div>
          </div>
        </div>

        <!-- Payment status -->
        <div class="mx-6 mb-5 rounded-xl px-4 py-3 flex items-center gap-3"
             :class="invoice.status === 'paid' ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200'">
          <div class="w-2.5 h-2.5 rounded-full flex-shrink-0"
               :class="invoice.status === 'paid' ? 'bg-green-500' : 'bg-amber-400'"></div>
          <div>
            <p class="text-sm font-semibold" :class="invoice.status === 'paid' ? 'text-green-800' : 'text-amber-800'">
              {{ invoice.status === 'paid'
                  ? `Payment Received · ${invoice.payment_method?.toUpperCase()}`
                  : 'Payment Pending' }}
            </p>
            <p v-if="invoice.payment_reference" class="text-xs text-gray-500 mt-0.5">
              Ref: {{ invoice.payment_reference }}
            </p>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="invoice.notes" class="px-6 pb-5">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Notes</p>
          <p class="text-sm text-gray-600">{{ invoice.notes }}</p>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-100 px-6 py-4 flex items-center justify-between gap-3 flex-wrap">
          <span class="text-xs bg-blue-50 border border-blue-100 text-blue-700 font-semibold px-3 py-1.5 rounded-full">
            🛡️ 6-Month Service Warranty Included
          </span>
          <div class="text-xs text-gray-400 text-right">
            Queries? Call <a href="tel:+919444900470" class="text-blue-600 font-semibold">+91 94449 00470</a>
          </div>
        </div>
      </div>

    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { ArrowLeftIcon, ArrowDownTrayIcon } from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const props = defineProps({
  invoice: { type: Object, required: true },
  booking: { type: Object, required: true },
})

function fmt(n) {
  return Number(n ?? 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>