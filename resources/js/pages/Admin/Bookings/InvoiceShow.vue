<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
          <a :href="`/admin/bookings/${booking.id}`" class="text-gray-500 hover:text-gray-700">
            <ArrowLeftIcon class="w-5 h-5" />
          </a>
          <div>
            <h1 class="text-xl font-bold text-gray-900 font-mono">{{ invoice.invoice_number }}</h1>
            <p class="text-sm text-gray-500">{{ booking.customer_name }} · {{ formatDate(invoice.created_at) }}</p>
          </div>
        </div>

        <!-- Status + actions -->
        <div class="flex items-center gap-2 flex-wrap">
          <!-- Status badge -->
          <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide"
                :class="{
                  'bg-green-100 text-green-700': invoice.status === 'paid',
                  'bg-amber-100 text-amber-700': invoice.status === 'sent',
                  'bg-gray-100 text-gray-600':   invoice.status === 'draft',
                  'bg-red-100 text-red-600':     invoice.status === 'void',
                }">
            {{ invoice.status_label }}
          </span>

          <!-- Send via WhatsApp -->
          <button v-if="invoice.status === 'draft' || invoice.status === 'sent'"
                  @click="sendWhatsApp" :disabled="sending"
                  class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors disabled:opacity-60">
            <ChatBubbleLeftRightIcon class="w-4 h-4" />
            {{ sending ? 'Sending...' : (invoice.status === 'sent' ? 'Resend WhatsApp' : 'Send via WhatsApp') }}
          </button>

          <!-- Mark Paid -->
          <button v-if="invoice.status !== 'paid'"
                  @click="showPayModal = true"
                  class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
            <CheckCircleIcon class="w-4 h-4" /> Mark Paid
          </button>

          <!-- Download -->
          <a :href="`/admin/bookings/${booking.id}/invoice/${invoice.id}/download`"
             target="_blank"
             class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
            <ArrowDownTrayIcon class="w-4 h-4" /> Download
          </a>

          <!-- Edit (draft only) -->
          <a v-if="invoice.status === 'draft'"
             :href="`/admin/bookings/${booking.id}/invoice/create`"
             class="flex items-center gap-2 border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-semibold transition-colors">
            <PencilSquareIcon class="w-4 h-4" /> Edit
          </a>
        </div>
      </div>

      <!-- Sent notice -->
      <div v-if="invoice.sent_at"
           class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm">
        <CheckCircleIcon class="w-5 h-5 text-green-600 flex-shrink-0" />
        <span class="text-green-800">
          Invoice sent to <strong>{{ invoice.customer_phone }}</strong> via WhatsApp on
          {{ formatDateTime(invoice.sent_at) }}
        </span>
      </div>

      <!-- Invoice preview card -->
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        <!-- Invoice header bar -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-8 py-6 text-white">
          <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
              <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider mb-1">Chennai Smart Care</p>
              <p class="text-lg font-bold">Expert Appliance Repair · Chennai</p>
              <p class="text-blue-200 text-sm mt-1">+91 94449 00470 · support@chennaismartcare.com</p>
            </div>
            <div class="text-right">
              <p class="text-blue-300 text-xs uppercase tracking-wider">Invoice</p>
              <p class="text-2xl font-mono font-bold">{{ invoice.invoice_number }}</p>
              <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold"
                    :class="{
                      'bg-green-400 text-green-900': invoice.status === 'paid',
                      'bg-amber-400 text-amber-900': invoice.status === 'sent',
                      'bg-white/20 text-white':      invoice.status === 'draft',
                    }">
                {{ invoice.status === 'paid' ? '✓ PAID' : invoice.status.toUpperCase() }}
              </span>
            </div>
          </div>
        </div>

        <!-- Parties -->
        <div class="grid grid-cols-2 gap-0 divide-x divide-gray-100 px-0">
          <div class="px-6 py-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bill To</p>
            <p class="font-bold text-gray-900">{{ invoice.customer_name }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ invoice.customer_phone }}</p>
            <p v-if="invoice.customer_email" class="text-sm text-gray-600">{{ invoice.customer_email }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ invoice.customer_address }}</p>
          </div>
          <div class="px-6 py-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Booking Ref</p>
            <p class="font-bold text-gray-900 font-mono">{{ booking.booking_number }}</p>
            <p class="text-sm text-gray-600 mt-1">📅 {{ formatDate(booking.booking_date) }}</p>
            <p class="text-sm text-gray-600">⏰ {{ booking.time_slot }}</p>
            <p class="text-xs font-semibold mt-1 px-2 py-0.5 rounded-md inline-block"
               :class="invoice.type === 'system' ? 'bg-blue-50 text-blue-700' : 'bg-indigo-50 text-indigo-700'">
              {{ invoice.type === 'system' ? '⚡ System' : '✏️ Custom' }} Invoice
            </p>
          </div>
        </div>

        <!-- Meta strip -->
        <div class="grid grid-cols-3 divide-x divide-gray-100 bg-gray-50 border-y border-gray-100">
          <div class="px-6 py-3">
            <p class="text-xs text-gray-400 font-semibold uppercase">Invoice Date</p>
            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ formatDate(invoice.created_at) }}</p>
          </div>
          <div class="px-6 py-3">
            <p class="text-xs text-gray-400 font-semibold uppercase">Payment Method</p>
            <p class="text-sm font-semibold text-gray-900 mt-0.5 capitalize">{{ invoice.payment_method }}</p>
          </div>
          <div class="px-6 py-3">
            <p class="text-xs text-gray-400 font-semibold uppercase">Due Date</p>
            <p class="text-sm font-semibold text-gray-900 mt-0.5">
              {{ invoice.due_date ? formatDate(invoice.due_date) : 'Upon Receipt' }}
            </p>
          </div>
        </div>

        <!-- Line items table -->
        <div class="px-6 py-5">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="text-left py-2 font-semibold text-gray-500 text-xs uppercase tracking-wide">Description</th>
                <th class="text-right py-2 font-semibold text-gray-500 text-xs uppercase tracking-wide w-28">Unit Price</th>
                <th class="text-right py-2 font-semibold text-gray-500 text-xs uppercase tracking-wide w-16">Qty</th>
                <th class="text-right py-2 font-semibold text-gray-500 text-xs uppercase tracking-wide w-28">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in invoice.items" :key="item.id" class="border-b border-gray-50">
                <td class="py-3 text-gray-900">
                  {{ item.description }}
                  <span v-if="item.category && item.category !== 'service'"
                        class="ml-2 text-xs px-2 py-0.5 rounded font-medium"
                        :class="{
                          'bg-amber-100 text-amber-700': item.category === 'labour',
                          'bg-emerald-100 text-emerald-700': item.category === 'parts',
                          'bg-purple-100 text-purple-700': item.category === 'extra',
                        }">
                    {{ item.category }}
                  </span>
                </td>
                <td class="py-3 text-right text-gray-700">₹{{ fmt(item.unit_price) }}</td>
                <td class="py-3 text-right text-gray-700">{{ item.quantity }}</td>
                <td class="py-3 text-right font-semibold text-gray-900">₹{{ fmt(item.total) }}</td>
              </tr>
            </tbody>
          </table>

          <!-- Totals -->
          <div class="flex justify-end mt-4">
            <div class="w-64 space-y-2">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal</span>
                <span>₹{{ fmt(invoice.subtotal) }}</span>
              </div>
              <div v-if="invoice.discount_amount > 0" class="flex justify-between text-sm text-green-600">
                <span>Discount{{ invoice.discount_percent > 0 ? ` (${invoice.discount_percent}%)` : '' }}</span>
                <span>−₹{{ fmt(invoice.discount_amount) }}</span>
              </div>
              <div v-if="invoice.tax_amount > 0" class="flex justify-between text-sm text-gray-500">
                <span>GST ({{ invoice.tax_percent }}%)</span>
                <span>+₹{{ fmt(invoice.tax_amount) }}</span>
              </div>
              <div class="border-t-2 border-blue-600 pt-3 flex justify-between">
                <span class="font-bold text-gray-900 text-base">Total</span>
                <span class="font-extrabold text-blue-600 text-xl">₹{{ fmt(invoice.total) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment status bar -->
        <div class="mx-6 mb-5 rounded-xl px-4 py-3 flex items-center gap-3"
             :class="invoice.status === 'paid' ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200'">
          <div class="w-2.5 h-2.5 rounded-full flex-shrink-0"
               :class="invoice.status === 'paid' ? 'bg-green-500' : 'bg-amber-500'"></div>
          <div>
            <p class="text-sm font-semibold" :class="invoice.status === 'paid' ? 'text-green-800' : 'text-amber-800'">
              {{ invoice.status === 'paid'
                  ? `Payment Received · ${invoice.payment_method?.toUpperCase()}`
                  : 'Payment Pending · Accepted: Cash / UPI / Card' }}
            </p>
            <p v-if="invoice.payment_reference" class="text-xs text-gray-500 mt-0.5">Ref: {{ invoice.payment_reference }}</p>
          </div>
        </div>

        <!-- Notes + Terms -->
        <div v-if="invoice.notes || invoice.terms" class="grid grid-cols-2 gap-6 px-6 pb-6">
          <div v-if="invoice.notes">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Notes</p>
            <p class="text-sm text-gray-600 leading-relaxed">{{ invoice.notes }}</p>
          </div>
          <div v-if="invoice.terms">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Terms & Conditions</p>
            <p class="text-xs text-gray-500 leading-relaxed whitespace-pre-line">{{ invoice.terms }}</p>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-100 px-6 py-4 flex items-center justify-between">
          <span class="text-xs bg-blue-50 border border-blue-100 text-blue-700 font-semibold px-3 py-1.5 rounded-full">
            🛡️ 6-Month Service Warranty Included
          </span>
          <span class="text-xs text-gray-400">Generated by Chennai Smart Care</span>
        </div>
      </div>
    </div>

    <!-- ── MARK PAID MODAL ── -->
    <div v-if="showPayModal"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4 text-lg">Mark as Paid</h3>
        <div class="space-y-3">
          <div>
            <label class="field-label">Payment Method *</label>
            <select v-model="payForm.method" class="field w-full">
              <option value="cash">Cash</option>
              <option value="upi">UPI</option>
              <option value="card">Card</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
          <div>
            <label class="field-label">Reference / Txn ID</label>
            <input v-model="payForm.reference" type="text" placeholder="Optional"
                   class="field w-full" />
          </div>
        </div>
        <div class="flex gap-3 mt-5">
          <button @click="showPayModal = false"
                  class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl font-semibold text-sm">
            Cancel
          </button>
          <button @click="confirmPaid" :disabled="markingPaid"
                  class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl font-semibold text-sm disabled:opacity-60">
            {{ markingPaid ? 'Saving...' : 'Confirm Paid' }}
          </button>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ArrowLeftIcon, CheckCircleIcon, ArrowDownTrayIcon,
  PencilSquareIcon, ChatBubbleLeftRightIcon,
} from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  booking: { type: Object, required: true },
  invoice: { type: Object, required: true },
})

const sending     = ref(false)
const markingPaid = ref(false)
const showPayModal = ref(false)

const payForm = reactive({ method: 'cash', reference: '' })

async function sendWhatsApp() {
  sending.value = true
  try {
    const res = await fetch(`/admin/bookings/${props.booking.id}/invoice/${props.invoice.id}/send`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    })
    const data = await res.json()
    if (data.success) {
      router.reload({ only: ['invoice'] })
      alert('✅ ' + data.message)
    } else {
      alert('Failed to send invoice.')
    }
  } catch {
    alert('Network error while sending.')
  } finally {
    sending.value = false
  }
}

async function confirmPaid() {
  markingPaid.value = true
  try {
    const res = await fetch(`/admin/bookings/${props.booking.id}/invoice/${props.invoice.id}/mark-paid`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ payment_method: payForm.method, payment_reference: payForm.reference }),
    })
    const data = await res.json()
    if (data.success) {
      showPayModal.value = false
      router.reload({ only: ['invoice'] })
    }
  } catch {
    alert('Failed to update payment status.')
  } finally {
    markingPaid.value = false
  }
}

function fmt(n) {
  return Number(n ?? 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}
function formatDateTime(d) {
  return new Date(d).toLocaleString('en-IN', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}
</script>

<style scoped>
.field {
  @apply px-3 py-2 border border-gray-300 rounded-xl text-sm text-gray-900
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors bg-white;
}
.field-label {
  @apply block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5;
}
</style>