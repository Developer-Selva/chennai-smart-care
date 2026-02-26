<template>
  <AdminLayout>
    <div class="max-w-5xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
          <a :href="`/admin/bookings/${booking.id}`" class="text-gray-500 hover:text-gray-700">
            <ArrowLeftIcon class="w-5 h-5" />
          </a>
          <div>
            <h1 class="text-xl font-bold text-gray-900">Generate Invoice</h1>
            <p class="text-sm text-gray-500 font-mono">{{ booking.booking_number }} · {{ booking.customer_name }}</p>
          </div>
        </div>
        <!-- Existing invoice notice -->
        <div v-if="existingInvoice"
             class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-2 rounded-xl">
          <ExclamationTriangleIcon class="w-4 h-4 flex-shrink-0" />
          Invoice {{ existingInvoice.invoice_number }} already exists.
          <a :href="`/admin/bookings/${booking.id}/invoice/${existingInvoice.id}`"
             class="font-semibold underline ml-1">View →</a>
        </div>
      </div>

      <!-- Type selector -->
      <div class="grid grid-cols-2 gap-4">
        <button @click="switchType('system')"
                class="relative rounded-2xl border-2 p-5 text-left transition-all"
                :class="form.type === 'system'
                  ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200'
                  : 'border-gray-200 bg-white hover:border-gray-300'">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 :class="form.type === 'system' ? 'bg-blue-100' : 'bg-gray-100'">
              <BoltIcon class="w-5 h-5" :class="form.type === 'system' ? 'text-blue-600' : 'text-gray-400'" />
            </div>
            <div>
              <p class="font-bold text-gray-900">System Invoice</p>
              <p class="text-xs text-gray-500 mt-0.5">Auto-filled from booked services. Fast & accurate.</p>
            </div>
          </div>
          <div v-if="form.type === 'system'"
               class="absolute top-3 right-3 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
            <CheckIcon class="w-3 h-3 text-white" />
          </div>
        </button>

        <button @click="switchType('custom')"
                class="relative rounded-2xl border-2 p-5 text-left transition-all"
                :class="form.type === 'custom'
                  ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-200'
                  : 'border-gray-200 bg-white hover:border-gray-300'">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 :class="form.type === 'custom' ? 'bg-indigo-100' : 'bg-gray-100'">
              <PencilSquareIcon class="w-5 h-5" :class="form.type === 'custom' ? 'text-indigo-600' : 'text-gray-400'" />
            </div>
            <div>
              <p class="font-bold text-gray-900">Custom Invoice</p>
              <p class="text-xs text-gray-500 mt-0.5">Add extra charges, labour, parts, or build from scratch.</p>
            </div>
          </div>
          <div v-if="form.type === 'custom'"
               class="absolute top-3 right-3 w-5 h-5 bg-indigo-500 rounded-full flex items-center justify-center">
            <CheckIcon class="w-3 h-3 text-white" />
          </div>
        </button>
      </div>

      <div class="grid lg:grid-cols-3 gap-6">

        <!-- ── LEFT: LINE ITEMS ── -->
        <div class="lg:col-span-2 space-y-5">

          <!-- Line items -->
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
              <h2 class="font-bold text-gray-900">Line Items</h2>
              <button @click="addItem"
                      class="flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700">
                <PlusIcon class="w-4 h-4" /> Add Line
              </button>
            </div>

            <!-- Table header -->
            <div class="hidden sm:grid grid-cols-12 gap-2 px-5 py-2 bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wide">
              <div class="col-span-5">Description</div>
              <div class="col-span-2">Category</div>
              <div class="col-span-2 text-right">Unit Price</div>
              <div class="col-span-1 text-right">Qty</div>
              <div class="col-span-1 text-right">Total</div>
              <div class="col-span-1"></div>
            </div>

            <!-- Items -->
            <div v-for="(item, idx) in form.items" :key="idx"
                 class="grid grid-cols-12 gap-2 px-5 py-3 border-b border-gray-100 last:border-0 items-center">
              <!-- Description -->
              <div class="col-span-12 sm:col-span-5">
                <input v-model="item.description" type="text" placeholder="Service / item description"
                       class="field w-full" @input="recalc" />
              </div>
              <!-- Category -->
              <div class="col-span-5 sm:col-span-2">
                <select v-model="item.category" class="field w-full" @change="recalc">
                  <option value="service">Service</option>
                  <option value="labour">Labour</option>
                  <option value="parts">Parts</option>
                  <option value="extra">Extra</option>
                </select>
              </div>
              <!-- Unit price -->
              <div class="col-span-4 sm:col-span-2">
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₹</span>
                  <input v-model.number="item.unit_price" type="number" min="0" step="0.01"
                         class="field w-full pl-7" @input="recalc" />
                </div>
              </div>
              <!-- Qty -->
              <div class="col-span-2 sm:col-span-1">
                <input v-model.number="item.quantity" type="number" min="1" step="1"
                       class="field w-full text-center" @input="recalc" />
              </div>
              <!-- Total -->
              <div class="col-span-1 text-right font-semibold text-gray-900 text-sm">
                ₹{{ fmt(item.unit_price * item.quantity) }}
              </div>
              <!-- Delete -->
              <div class="col-span-1 flex justify-end">
                <button @click="removeItem(idx)"
                        class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                  <XMarkIcon class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Empty state -->
            <div v-if="!form.items.length" class="text-center py-10 text-gray-400">
              <DocumentPlusIcon class="w-10 h-10 mx-auto mb-2 opacity-30" />
              <p class="text-sm">No items yet. Click "Add Line" to start.</p>
            </div>

            <!-- Quick-add shortcuts (custom mode) -->
            <div v-if="form.type === 'custom'"
                 class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex flex-wrap gap-2">
              <span class="text-xs text-gray-400 font-medium self-center">Quick add:</span>
              <button v-for="q in quickItems" :key="q.label"
                      @click="addQuickItem(q)"
                      class="text-xs bg-white border border-gray-200 text-gray-700 px-3 py-1 rounded-full hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 transition-colors">
                + {{ q.label }}
              </button>
            </div>
          </div>

          <!-- Customer details (editable) -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="font-bold text-gray-900 mb-4">Customer Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="field-label">Name *</label>
                <input v-model="form.customer_name" type="text" class="field w-full" />
              </div>
              <div>
                <label class="field-label">Phone *</label>
                <input v-model="form.customer_phone" type="text" class="field w-full" />
              </div>
              <div>
                <label class="field-label">Email</label>
                <input v-model="form.customer_email" type="email" class="field w-full" />
              </div>
              <div class="sm:col-span-2">
                <label class="field-label">Address *</label>
                <input v-model="form.customer_address" type="text" class="field w-full" />
              </div>
            </div>
          </div>

          <!-- Notes & Terms -->
          <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="font-bold text-gray-900 mb-4">Notes & Terms</h2>
            <div class="space-y-3">
              <div>
                <label class="field-label">Invoice Notes <span class="text-gray-400 font-normal">(shown to customer)</span></label>
                <textarea v-model="form.notes" rows="2" placeholder="e.g. Thank you for your business!"
                          class="field w-full resize-none"></textarea>
              </div>
              <div>
                <label class="field-label">Terms & Conditions</label>
                <textarea v-model="form.terms" rows="4" class="field w-full resize-none text-xs"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- ── RIGHT: SUMMARY + PAYMENT ── -->
        <div class="space-y-5">

          <!-- Totals calculator -->
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden sticky top-4">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
              <h2 class="font-bold text-gray-900">Summary</h2>
            </div>
            <div class="p-5 space-y-4">

              <!-- Subtotal display -->
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-semibold">₹{{ fmt(subtotal) }}</span>
              </div>

              <!-- Discount -->
              <div>
                <label class="field-label">Discount</label>
                <div class="grid grid-cols-2 gap-2">
                  <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                    <input v-model.number="form.discount_percent" type="number" min="0" max="100" step="1"
                           placeholder="0" class="field w-full pl-7 text-sm"
                           @input="onDiscountPctChange" />
                  </div>
                  <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₹</span>
                    <input v-model.number="form.discount_amount" type="number" min="0" step="1"
                           placeholder="0" class="field w-full pl-7 text-sm"
                           @input="onDiscountAmtChange" />
                  </div>
                </div>
              </div>

              <!-- Tax -->
              <div>
                <label class="field-label">GST / Tax %</label>
                <div class="flex gap-2">
                  <input v-model.number="form.tax_percent" type="number" min="0" max="100" step="1"
                         placeholder="0" class="field flex-1 text-sm" @input="recalc" />
                  <div class="flex gap-1">
                    <button v-for="t in [0, 5, 12, 18]" :key="t"
                            @click="form.tax_percent = t; recalc()"
                            class="px-2 py-1 text-xs rounded-lg border transition-colors"
                            :class="form.tax_percent === t
                              ? 'bg-blue-600 text-white border-blue-600'
                              : 'bg-white border-gray-200 text-gray-600 hover:border-blue-300'">
                      {{ t }}%
                    </button>
                  </div>
                </div>
              </div>

              <hr class="border-gray-100" />

              <!-- Running breakdown -->
              <div class="space-y-1.5 text-sm">
                <div v-if="form.discount_amount > 0" class="flex justify-between text-green-600">
                  <span>Discount</span>
                  <span>−₹{{ fmt(form.discount_amount) }}</span>
                </div>
                <div v-if="taxAmount > 0" class="flex justify-between text-gray-500">
                  <span>GST ({{ form.tax_percent }}%)</span>
                  <span>+₹{{ fmt(taxAmount) }}</span>
                </div>
              </div>

              <!-- Grand total -->
              <div class="bg-blue-600 rounded-xl px-4 py-3 flex justify-between items-center text-white">
                <span class="font-semibold text-sm">Total</span>
                <span class="text-2xl font-extrabold">₹{{ fmt(grandTotal) }}</span>
              </div>
            </div>

            <!-- Payment method -->
            <div class="px-5 pb-5 space-y-3">
              <div>
                <label class="field-label">Payment Method</label>
                <select v-model="form.payment_method" class="field w-full">
                  <option value="pending">Pending</option>
                  <option value="cash">Cash</option>
                  <option value="upi">UPI</option>
                  <option value="card">Card</option>
                  <option value="bank_transfer">Bank Transfer</option>
                </select>
              </div>
              <div v-if="form.payment_method !== 'pending'">
                <label class="field-label">Reference / Txn ID</label>
                <input v-model="form.payment_reference" type="text"
                       placeholder="UPI ref, txn ID..." class="field w-full text-sm" />
              </div>
            </div>

            <!-- Submit -->
            <div class="px-5 pb-5">
              <button @click="submit" :disabled="saving || !form.items.length"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold text-sm transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
                <DocumentTextIcon v-if="!saving" class="w-4 h-4" />
                <span>{{ saving ? 'Generating...' : 'Generate Invoice' }}</span>
              </button>
              <p class="text-xs text-gray-400 text-center mt-2">
                Invoice will be saved as Draft. You can send it after review.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ArrowLeftIcon, CheckIcon, PlusIcon, XMarkIcon,
  BoltIcon, PencilSquareIcon, DocumentTextIcon,
  DocumentPlusIcon, ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

const props = defineProps({
  booking:          { type: Object, required: true },
  existing_invoice: { type: Object, default: null },
  system_preview:   { type: Object, required: true },
  custom_preview:   { type: Object, required: true },
})

const saving = ref(false)

// ── Form state ────────────────────────────────────────────────
const form = reactive({
  type:               'system',
  items:              [],
  discount_percent:   0,
  discount_amount:    0,
  tax_percent:        0,
  payment_method:     'cash',
  payment_reference:  '',
  notes:              '',
  terms:              '',
  customer_name:      '',
  customer_phone:     '',
  customer_email:     '',
  customer_address:   '',
})

// Init with system preview
function initForm(preview) {
  Object.assign(form, {
    ...preview,
    items: (preview.items ?? []).map(i => ({ ...i })),
  })
}
initForm(props.system_preview)

// ── Quick items (for custom mode) ─────────────────────────────
const quickItems = [
  { label: 'Visiting Charge', description: 'Technician Visiting Charge', category: 'service', unit_price: 200, quantity: 1 },
  { label: 'Labour Charge',   description: 'Labour Charges',             category: 'labour',  unit_price: 300, quantity: 1 },
  { label: 'Gas Refill',      description: 'AC Gas Refilling (R22)',     category: 'service', unit_price: 1800, quantity: 1 },
  { label: 'Spare Parts',     description: 'Spare Parts',                category: 'parts',   unit_price: 500, quantity: 1 },
  { label: 'Conveyance',      description: 'Conveyance Charges',         category: 'extra',   unit_price: 150, quantity: 1 },
]

// ── Type switching ────────────────────────────────────────────
function switchType(type) {
  initForm(type === 'system' ? props.system_preview : props.custom_preview)
}

// ── Line item management ──────────────────────────────────────
function addItem() {
  form.items.push({ description: '', category: 'service', unit_price: 0, quantity: 1 })
}

function addQuickItem(q) {
  form.items.push({ ...q })
  recalc()
}

function removeItem(idx) {
  form.items.splice(idx, 1)
  recalc()
}

// ── Calculations ──────────────────────────────────────────────
const subtotal = computed(() =>
  form.items.reduce((sum, i) => sum + (i.unit_price ?? 0) * (i.quantity ?? 1), 0)
)

const taxAmount = computed(() => {
  if (!form.tax_percent) return 0
  const afterDiscount = subtotal.value - (form.discount_amount ?? 0)
  return Math.round(afterDiscount * form.tax_percent / 100 * 100) / 100
})

const grandTotal = computed(() =>
  subtotal.value - (form.discount_amount ?? 0) + taxAmount.value
)

function recalc() {
  if (form.discount_percent > 0) {
    form.discount_amount = Math.round(subtotal.value * form.discount_percent / 100 * 100) / 100
  }
}

function onDiscountPctChange() {
  form.discount_amount = form.discount_percent > 0
    ? Math.round(subtotal.value * form.discount_percent / 100 * 100) / 100
    : form.discount_amount
}

function onDiscountAmtChange() {
  form.discount_percent = subtotal.value > 0
    ? Math.round(form.discount_amount / subtotal.value * 10000) / 100
    : 0
}

// ── Submit ────────────────────────────────────────────────────
function submit() {
  if (!form.items.length) return
  saving.value = true
  router.post(`/admin/bookings/${props.booking.id}/invoice`, {
    ...form,
    items: form.items,
  }, {
    onError:  (e) => alert(Object.values(e)[0] ?? 'Failed to generate invoice.'),
    onFinish: () => { saving.value = false },
  })
}

// ── Helpers ───────────────────────────────────────────────────
function fmt(n) {
  return Number(n ?? 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
</script>

<style scoped>
.field {
  @apply px-3 py-2 border border-gray-300 rounded-xl text-sm text-gray-900
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors placeholder-gray-400 bg-white;
}
.field-label {
  @apply block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5;
}
</style>