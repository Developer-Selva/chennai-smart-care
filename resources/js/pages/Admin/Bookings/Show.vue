<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 py-6">

      <!-- Header with Breadcrumb -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in-down">
        <div class="flex items-center gap-3">
          <a href="/admin/bookings" 
             class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all shadow-sm border border-gray-200">
            <ArrowLeftIcon class="w-5 h-5" />
          </a>
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-2xl font-bold text-gray-900 font-mono">{{ booking.booking_number }}</h1>
              <StatusBadge :status="booking.status" size="lg" />
            </div>
            <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
              <CalendarIcon class="w-4 h-4" />
              Created {{ formatDateTime(booking.created_at) }}
              <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
              <span class="flex items-center gap-1">
                <UserIcon class="w-4 h-4" />
                {{ booking.source === 'website' ? 'Website' : 'Admin' }}
              </span>
            </p>
          </div>
        </div>
        
        <!-- Quick Actions Dropdown -->
        <div class="relative">
          <button @click="showQuickActions = !showQuickActions"
                  class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-all">
            <EllipsisHorizontalIcon class="w-5 h-5" />
            Quick Actions
            <ChevronDownIcon class="w-4 h-4" :class="{ 'rotate-180': showQuickActions }" />
          </button>
          
          <Transition
            enter-active-class="transform transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transform transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div v-if="showQuickActions" 
                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-1 z-50">
              <a href="#" @click.prevent="printBooking" 
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                <PrinterIcon class="w-4 h-4" />
                Print Booking Details
              </a>
              <a href="#" @click.prevent="emailCustomer" 
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                <EnvelopeIcon class="w-4 h-4" />
                Email Customer
              </a>
              <a href="#" @click.prevent="sendSMS" 
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                <ChatBubbleLeftRightIcon class="w-4 h-4" />
                Send SMS
              </a>
              <hr class="my-1 border-gray-100">
              <a href="#" @click.prevent="duplicateBooking" 
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                <DocumentDuplicateIcon class="w-4 h-4" />
                Duplicate Booking
              </a>
              <a href="#" @click.prevent="openInGoogleMaps" v-if="hasValidCoordinates"
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                <MapIcon class="w-4 h-4" />
                Open in Google Maps
              </a>
            </div>
          </Transition>
        </div>
      </div>

      <!-- Status Alert for Overdue/Priority -->
      <Transition
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
      >
        <div v-if="isOverdue" 
             class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
          <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <ExclamationTriangleIcon class="w-4 h-4 text-red-600" />
          </div>
          <div class="flex-1">
            <p class="text-sm font-semibold text-red-800">⚠️ This booking requires attention</p>
            <p class="text-xs text-red-600 mt-0.5">Created {{ daysSinceCreation }} days ago. Consider following up.</p>
          </div>
          <button @click="markAsUrgent" class="text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700">
            Mark Urgent
          </button>
        </div>
      </Transition>

      <!-- Main Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ======== LEFT COLUMN (Main Content) ======== -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Customer Information Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden animate-slide-in">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                  <UserIcon class="w-4 h-4 text-blue-600" />
                </div>
                <h3 class="font-semibold text-gray-900">Customer Details</h3>
              </div>
            </div>
            
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Info Grid -->
                <div class="space-y-4">
                  <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Full Name</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ booking.customer_name }}</p>
                  </div>
                  
                  <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Contact</p>
                    <div class="flex items-center gap-2 flex-wrap">
                      <a :href="`tel:${booking.customer_phone}`"
                         class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                        <PhoneIcon class="w-4 h-4" />
                        {{ booking.customer_phone }}
                      </a>
                      <a :href="`https://wa.me/91${booking.customer_phone}`" target="_blank"
                         class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors">
                        <ChatBubbleLeftRightIcon class="w-4 h-4" />
                        WhatsApp
                      </a>
                      <a :href="`mailto:${booking.customer_email}`" v-if="booking.customer_email"
                         class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-purple-100 transition-colors">
                        <EnvelopeIcon class="w-4 h-4" />
                        Email
                      </a>
                    </div>
                  </div>
                </div>

                <div class="space-y-4">
                  <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Service Address</p>
                    <p class="text-gray-700 bg-gray-50 rounded-xl p-3 text-sm">
                      {{ booking.address }}{{ booking.area ? `, ${booking.area}` : '' }}
                      <br v-if="booking.pincode">
                      <span v-if="booking.pincode" class="text-gray-500">Pincode: {{ booking.pincode }}</span>
                    </p>
                    
                    <!-- GPS Coordinates if available -->
                    <div v-if="hasValidCoordinates" class="mt-2 flex items-center gap-2 text-xs flex-wrap">
                      <GlobeAltIcon class="w-3 h-3 text-gray-500" />
                      <span class="text-gray-600">
                        {{ formatCoordinates(booking.latitude, booking.longitude) }}
                      </span>
                      <span v-if="booking.location_accuracy" class="text-gray-400">
                        (Accuracy: ±{{ booking.location_accuracy }}m)
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Customer Notes -->
                <div v-if="booking.customer_notes" class="md:col-span-2">
                  <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Customer Notes</p>
                  <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-sm text-amber-800 flex items-start gap-2">
                      <ChatBubbleLeftIcon class="w-4 h-4 flex-shrink-0 mt-0.5" />
                      "{{ booking.customer_notes }}"
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Google Maps Card -->
          <div v-if="hasValidCoordinates" class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                    <MapIcon class="w-4 h-4 text-red-600" />
                  </div>
                  <h3 class="font-semibold text-gray-900">Service Location</h3>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-xs text-gray-500">GPS Accuracy: ±{{ booking.location_accuracy || '?' }}m</span>
                  <a :href="googleMapsUrl" target="_blank"
                     class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-lg hover:bg-blue-100 transition-colors">
                    Open in Maps
                  </a>
                </div>
              </div>
            </div>
            
            <div class="p-6">
              <!-- Map Container -->
              <div class="relative w-full h-80 rounded-xl overflow-hidden border border-gray-200">
                <iframe
                  width="100%"
                  height="100%"
                  frameborder="0"
                  style="border:0"
                  referrerpolicy="no-referrer-when-downgrade"
                  :src="mapEmbedUrl"
                  allowfullscreen>
                </iframe>
                
                <!-- Loading Overlay -->
                <div v-if="mapLoading" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                  <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-300 border-t-blue-600"></div>
                    <p class="text-sm text-gray-500 mt-2">Loading map...</p>
                  </div>
                </div>
              </div>

              <!-- Location Actions -->
              <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                  <MapPinIcon class="w-4 h-4" />
                  <span>{{ fullAddress }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="copyCoordinates" 
                          class="text-xs bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-1">
                    <DocumentDuplicateIcon class="w-3 h-3" />
                    Copy Coordinates
                  </button>
                  <a :href="googleMapsDirectionsUrl" target="_blank"
                     class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1">
                    <MapIcon class="w-3 h-3" />
                    Get Directions
                  </a>
                </div>
              </div>

              <!-- Location Source Info -->
              <div class="mt-3 text-xs text-gray-400 flex items-center gap-2 flex-wrap">
                <GlobeAltIcon class="w-3 h-3" />
                <span>Location source: {{ booking.location_source || 'Address' }}</span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span>Last updated: {{ formatTime(booking.updated_at) }}</span>
              </div>
            </div>
          </div>

          <!-- Services Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                  <WrenchIcon class="w-4 h-4 text-purple-600" />
                </div>
                <h3 class="font-semibold text-gray-900">Services Booked</h3>
              </div>
            </div>

            <div class="p-6">
              <!-- Services Table -->
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b border-gray-100">
                      <th class="text-left py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                      <th class="text-center py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                      <th class="text-right py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Price</th>
                      <th class="text-right py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                    <tr v-for="item in booking.items" :key="item.id" class="hover:bg-gray-50 transition-colors">
                      <td class="py-3 text-sm font-medium text-gray-900">{{ item.service_name }}</td>
                      <td class="py-3 text-center text-sm text-gray-600">{{ item.quantity }}</td>
                      <td class="py-3 text-right text-sm text-gray-600">₹{{ item.unit_price }}</td>
                      <td class="py-3 text-right text-sm font-semibold text-gray-900">₹{{ item.total_price }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Totals -->
              <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-end">
                  <div class="w-full sm:w-64 space-y-2">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500">Subtotal</span>
                      <span class="font-medium">₹{{ subtotal }}</span>
                    </div>
                    <div v-if="discount" class="flex justify-between text-sm text-green-600">
                      <span>Discount</span>
                      <span>-₹{{ discount }}</span>
                    </div>
                    <div v-if="tax" class="flex justify-between text-sm">
                      <span>Tax ({{ taxRate }}%)</span>
                      <span>₹{{ tax }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                      <span>Total</span>
                      <span class="text-blue-600">₹{{ booking.final_amount }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Activity Timeline Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                  <ClockIcon class="w-4 h-4 text-amber-600" />
                </div>
                <h3 class="font-semibold text-gray-900">Activity Timeline</h3>
              </div>
            </div>

            <div class="p-6">
              <div v-if="booking.history?.length" class="space-y-4">
                <div v-for="(entry, i) in booking.history" :key="entry.id" 
                     class="relative pl-8 pb-4 border-l-2 border-gray-200 last:border-0 last:pb-0">
                  <!-- Timeline Dot -->
                  <div class="absolute -left-2 top-0">
                    <div class="w-4 h-4 rounded-full" :class="getTimelineDotColor(entry.action)"></div>
                  </div>
                  
                  <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-4">
                      <div>
                        <p class="text-sm font-semibold text-gray-900 capitalize flex items-center gap-2">
                          <component :is="getTimelineIcon(entry.action)" class="w-4 h-4" :class="getTimelineIconColor(entry.action)" />
                          {{ entry.action.replace(/_/g, ' ') }}
                        </p>
                        <p v-if="entry.from_status && entry.to_status" class="text-xs text-gray-500 mt-1">
                          Status changed from 
                          <StatusBadge :status="entry.from_status" size="sm" class="inline-flex mx-1" />
                          to 
                          <StatusBadge :status="entry.to_status" size="sm" class="inline-flex ml-1" />
                        </p>
                        <p v-if="entry.note" class="text-sm text-gray-600 mt-2 bg-white rounded-lg p-2 border border-gray-100">
                          "{{ entry.note }}"
                        </p>
                      </div>
                      <p class="text-xs text-gray-400 whitespace-nowrap">
                        {{ formatDateTime(entry.created_at) }}
                      </p>
                    </div>
                    
                    <!-- Performer Info -->
                    <div class="mt-2 flex items-center gap-2 text-xs text-gray-400">
                      <UserCircleIcon class="w-3 h-3" />
                      <span>{{ entry.causer?.name || 'System' }}</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-8">
                <ClockIcon class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                <p class="text-sm text-gray-500">No activity recorded yet</p>
              </div>
            </div>
          </div>

          <!-- Warranty Card (if exists) -->
          <div v-if="warranty" class="bg-white rounded-2xl shadow-xl border-2 overflow-hidden"
               :class="warranty.is_active ? 'border-green-200' : 'border-gray-200'">
            <div class="px-6 py-4 border-b" :class="warranty.is_active ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50'">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <ShieldCheckIcon class="w-5 h-5" :class="warranty.is_active ? 'text-green-600' : 'text-gray-400'" />
                  <h3 class="font-semibold" :class="warranty.is_active ? 'text-green-800' : 'text-gray-600'">
                    Service Warranty
                  </h3>
                </div>
                <span class="font-mono text-xs bg-white px-2 py-1 rounded-md border" 
                      :class="warranty.is_active ? 'border-green-200 text-green-700' : 'border-gray-200 text-gray-500'">
                  {{ warranty.warranty_number }}
                </span>
              </div>
            </div>

            <div class="p-6">
              <!-- Covered Services -->
              <div class="mb-4">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Covered Services</p>
                <div class="flex flex-wrap gap-2">
                  <span v-for="svc in warranty.services_list" :key="svc"
                        class="text-xs px-3 py-1.5 rounded-full font-medium"
                        :class="warranty.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'">
                    <CheckIcon class="w-3 h-3 inline mr-1" />
                    {{ svc }}
                  </span>
                </div>
              </div>

              <!-- Warranty Period Progress -->
              <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex justify-between text-xs text-gray-600 font-medium mb-3">
                  <span class="flex items-center gap-1">
                    <CalendarIcon class="w-3 h-3" />
                    {{ formatDate(warranty.starts_at) }}
                  </span>
                  <span class="flex items-center gap-1" :class="warranty.is_active ? 'text-green-600' : 'text-red-500'">
                    <CalendarIcon class="w-3 h-3" />
                    {{ formatDate(warranty.expires_at) }}
                  </span>
                </div>

                <div class="space-y-2">
                  <div class="flex justify-between text-xs">
                    <span class="text-gray-500">Warranty Period</span>
                    <span class="font-medium">{{ warranty.progress_percent }}% elapsed</span>
                  </div>
                  <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         :class="warranty.is_active ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gray-300'"
                         :style="`width:${warranty.progress_percent}%`"></div>
                  </div>
                  <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Started</span>
                    <span v-if="warranty.is_active" class="text-green-600 font-semibold">
                      {{ warranty.days_remaining }} days remaining
                    </span>
                    <span v-else class="text-red-400">Expired</span>
                  </div>
                </div>
              </div>

              <!-- Warranty Actions -->
              <div class="mt-4 flex gap-2">
                <button @click="viewWarrantyDetails" 
                        class="flex-1 text-sm bg-blue-50 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                  View Full Details
                </button>
                <button @click="claimWarranty" v-if="warranty.is_active"
                        class="flex-1 text-sm bg-green-50 text-green-700 px-3 py-2 rounded-lg hover:bg-green-100 transition-colors">
                  Claim Warranty
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ======== RIGHT COLUMN (Sidebar) ======== -->
        <div class="space-y-6">

          <!-- Appointment Card -->
          <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
            <div class="flex items-center gap-2 mb-4">
              <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <CalendarIcon class="w-4 h-4" />
              </div>
              <h3 class="font-semibold">Appointment</h3>
            </div>
            
            <div class="space-y-3">
              <div>
                <p class="text-blue-200 text-xs uppercase tracking-wider mb-1">Scheduled Date</p>
                <p class="text-2xl font-bold">{{ formatDate(booking.booking_date) }}</p>
              </div>
              
              <div class="flex items-center gap-2">
                <div class="flex-1 bg-white/10 rounded-xl p-3">
                  <p class="text-blue-200 text-xs mb-1">Time Slot</p>
                  <p class="font-semibold text-lg">{{ booking.time_slot }}</p>
                </div>
                <div class="flex-1 bg-white/10 rounded-xl p-3">
                  <p class="text-blue-200 text-xs mb-1">Duration</p>
                  <p class="font-semibold text-lg">{{ estimatedDuration }}</p>
                </div>
              </div>

              <!-- Countdown if future -->
              <div v-if="isFutureDate" class="bg-white/10 rounded-xl p-3 mt-2">
                <p class="text-blue-200 text-xs mb-1">Time until appointment</p>
                <p class="font-mono text-sm">{{ timeUntilAppointment }}</p>
              </div>
            </div>
          </div>

          <!-- Technician Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
              <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <UserIcon class="w-4 h-4 text-gray-500" />
                Technician Assignment
              </h3>
            </div>

            <div class="p-6">
              <!-- Current Technician -->
              <div v-if="booking.technician" class="mb-4">
                <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-xl">
                  <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold text-lg">
                      {{ booking.technician.name.charAt(0) }}
                    </span>
                  </div>
                  <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ booking.technician.name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                      <a :href="`tel:${booking.technician.phone}`"
                         class="text-xs bg-white text-indigo-600 px-2 py-1 rounded-lg flex items-center gap-1">
                        <PhoneIcon class="w-3 h-3" />
                        Call
                      </a>
                      <span class="text-xs text-gray-400">ID: {{ booking.technician.id }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <p v-else class="text-gray-400 text-sm mb-4 text-center py-4">
                No technician assigned yet
              </p>

              <!-- Assignment Form -->
              <div v-if="canAssign" class="space-y-3">
                <select v-model="selectedTechnician"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                  <option value="">Select Technician...</option>
                  <option v-for="tech in technicians" :key="tech.id" :value="tech.id">
                    {{ tech.name }} ({{ tech.skills?.join(', ') || 'General' }})
                  </option>
                </select>

                <button @click="assignTechnician"
                        :disabled="!selectedTechnician || actionLoading"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 disabled:opacity-50 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                  <UserPlusIcon v-if="!actionLoading" class="w-4 h-4" />
                  <span>{{ actionLoading ? 'Assigning...' : 'Assign Technician' }}</span>
                </button>
              </div>

              <!-- Assignment Notes -->
              <p v-if="booking.assignment_notes" class="mt-3 text-xs text-gray-500 bg-gray-50 p-2 rounded-lg">
                Note: {{ booking.assignment_notes }}
              </p>
            </div>
          </div>

          <!-- Action Buttons Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
              <h3 class="font-semibold text-gray-900">Booking Actions</h3>
            </div>

            <div class="p-6 space-y-3">
              <!-- Status-based Actions -->
              <template v-if="booking.status === 'pending'">
                <button @click="doAction('confirm')" :disabled="actionLoading"
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-xl text-sm font-semibold hover:from-green-700 hover:to-green-800 transition-all hover:-translate-y-0.5 shadow-lg shadow-green-200 flex items-center justify-center gap-2">
                  <CheckCircleIcon class="w-4 h-4" />
                  Confirm Booking
                </button>
              </template>

              <template v-if="booking.status === 'confirmed'">
                <button @click="doAction('assign')" :disabled="!selectedTechnician || actionLoading"
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 rounded-xl text-sm font-semibold hover:from-indigo-700 hover:to-indigo-800 transition-all hover:-translate-y-0.5 shadow-lg shadow-indigo-200">
                  Confirm & Assign Technician
                </button>
              </template>

              <template v-if="booking.status === 'assigned'">
                <button @click="doAction('in-progress')" :disabled="actionLoading"
                        class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-xl text-sm font-semibold hover:from-purple-700 hover:to-purple-800 transition-all hover:-translate-y-0.5 shadow-lg shadow-purple-200">
                  <PlayIcon class="w-4 h-4 inline mr-2" />
                  Mark In Progress
                </button>
              </template>

              <template v-if="['in_progress', 'assigned'].includes(booking.status)">
                <button @click="doAction('complete')" :disabled="actionLoading"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl text-sm font-semibold hover:from-blue-700 hover:to-blue-800 transition-all hover:-translate-y-0.5 shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                  <CheckCircleIcon class="w-4 h-4" />
                  Mark Completed
                </button>
              </template>

              <!-- Divider -->
              <div class="relative my-2">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                  <span class="px-2 bg-white text-gray-400">Other Actions</span>
                </div>
              </div>

              <!-- Invoice Button -->
              <a :href="`/admin/bookings/${booking.id}/invoice/create`"
                 class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-3 rounded-xl text-sm font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all hover:-translate-y-0.5 shadow-lg shadow-emerald-200 flex items-center justify-center gap-2">
                <DocumentTextIcon class="w-4 h-4" />
                {{ invoice ? 'View / Edit Invoice' : 'Generate Invoice' }}
              </a>

              <!-- Existing Invoice Link -->
              <a v-if="invoice"
                 :href="`/admin/bookings/${booking.id}/invoice/${invoice.id}`"
                 class="w-full border-2 border-emerald-200 text-emerald-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-50 transition-colors flex items-center justify-between px-4">
                <span class="flex items-center gap-2">
                  <DocumentTextIcon class="w-4 h-4" />
                  {{ invoice.invoice_number }}
                </span>
                <span class="flex items-center gap-1">
                  <span class="w-2 h-2 rounded-full" :class="invoiceStatusColor"></span>
                  <span class="text-xs capitalize">{{ invoice.status }}</span>
                </span>
              </a>

              <!-- Reschedule & Cancel -->
              <div class="grid grid-cols-2 gap-2">
                <button v-if="!['completed','cancelled'].includes(booking.status)"
                        @click="showRescheduleModal = true"
                        class="flex items-center justify-center gap-1 border-2 border-orange-400 text-orange-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-orange-50 transition-colors">
                  <CalendarIcon class="w-4 h-4" />
                  Reschedule
                </button>

                <button v-if="!['completed','cancelled'].includes(booking.status)"
                        @click="showCancelModal = true"
                        class="flex items-center justify-center gap-1 border-2 border-red-300 text-red-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-50 transition-colors">
                  <XMarkIcon class="w-4 h-4" />
                  Cancel
                </button>
              </div>
            </div>
          </div>

          <!-- Admin Notes Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
              <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <DocumentTextIcon class="w-4 h-4 text-gray-500" />
                Admin Notes
              </h3>
            </div>

            <div class="p-6">
              <textarea v-model="adminNotes" rows="4"
                        placeholder="Add internal notes about this booking..."
                        class="w-full text-sm border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none transition-shadow"
                        :class="{ 'border-blue-300 ring-1 ring-blue-100': adminNotes.length > 0 }"></textarea>
              
              <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-gray-400">
                  {{ adminNotes.length }} / 500 characters
                </span>
                <button @click="saveNotes" :disabled="savingNotes || !adminNotesChanged"
                        class="bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-900 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                  <CheckIcon v-if="!savingNotes" class="w-4 h-4" />
                  <span>{{ savingNotes ? 'Saving...' : 'Save Notes' }}</span>
                </button>
              </div>

              <!-- Last Saved Indicator -->
              <p v-if="lastSavedNotes" class="text-xs text-gray-400 mt-2 text-right">
                Last saved: {{ formatTime(lastSavedNotes) }}
              </p>
            </div>
          </div>

          <!-- Metadata Card -->
          <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
              <h3 class="font-semibold text-gray-900">Metadata</h3>
            </div>
            <div class="p-4 space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Booking ID</span>
                <span class="font-mono text-gray-900">{{ booking.booking_number }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Source</span>
                <span class="capitalize">{{ booking.source }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Created By</span>
                <span>{{ booking.created_by?.name || 'System' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Last Updated</span>
                <span>{{ formatTime(booking.updated_at) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <RescheduleModal
      :is-open="showRescheduleModal"
      :booking-id="booking.id"
      :current-date="booking.booking_date"
      :current-slot="booking.time_slot"
      @close="showRescheduleModal = false"
      @rescheduled="onRescheduled"
    />
    
    <CancelModal
      :is-open="showCancelModal"
      :booking-id="booking.id"
      @close="showCancelModal = false"
      @cancelled="onCancelled"
    />
  </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  ArrowLeftIcon, CalendarIcon, ClockIcon, ShieldCheckIcon, DocumentTextIcon,
  CheckIcon, CheckCircleIcon, UserIcon, PhoneIcon, EnvelopeIcon,
  ChatBubbleLeftRightIcon, EllipsisHorizontalIcon, ChevronDownIcon,
  PrinterIcon, DocumentDuplicateIcon, ExclamationTriangleIcon,
  UserPlusIcon, PlayIcon, XMarkIcon, WrenchIcon, ChatBubbleLeftIcon,
  UserCircleIcon, MapIcon, GlobeAltIcon, MapPinIcon
} from '@heroicons/vue/24/outline'
import AdminLayout     from '@/components/Admin/AdminLayout.vue'
import StatusBadge     from '@/components/Shared/StatusBadge.vue'
import RescheduleModal from '@/components/Admin/Modals/RescheduleModal.vue'
import CancelModal     from '@/components/Admin/Modals/CancelModal.vue'

const props = defineProps({
  booking:     { type: Object, required: true },
  warranty:    { type: Object, default: null },
  invoice:     { type: Object, default: null },
  technicians: { type: Array,  default: () => [] },
})

// State
const actionLoading       = ref(false)
const savingNotes         = ref(false)
const showRescheduleModal = ref(false)
const showCancelModal     = ref(false)
const showQuickActions    = ref(false)
const adminNotes          = ref(props.booking.admin_notes ?? '')
const lastSavedNotes      = ref(props.booking.updated_at)
const selectedTechnician  = ref(props.booking.technician_id ?? '')
const mapLoading          = ref(false)
const copySuccess         = ref(false)

// Safe coordinate parsing for URLs
const safeLatitude = computed(() => {
  if (!props.booking.latitude) return null
  const lat = parseFloat(props.booking.latitude)
  return isNaN(lat) ? null : lat
})

const safeLongitude = computed(() => {
  if (!props.booking.longitude) return null
  const lng = parseFloat(props.booking.longitude)
  return isNaN(lng) ? null : lng
})

// Computed
const fullAddress = computed(() => {
  const parts = [props.booking.address, props.booking.area, props.booking.pincode].filter(Boolean)
  return parts.join(', ')
})

const hasValidCoordinates = computed(() => {
  return safeLatitude.value !== null && safeLongitude.value !== null
})

const googleMapsUrl = computed(() => {
  if (!hasValidCoordinates.value) return '#'
  return `https://www.google.com/maps/search/?api=1&query=${safeLatitude.value},${safeLongitude.value}`
})

const googleMapsDirectionsUrl = computed(() => {
  if (!hasValidCoordinates.value) return '#'
  return `https://www.google.com/maps/dir/?api=1&destination=${safeLatitude.value},${safeLongitude.value}`
})

const mapEmbedUrl = computed(() => {
  if (!hasValidCoordinates.value) return ''
  // Using OpenStreetMap (free, no API key required)
  return `https://www.openstreetmap.org/export/embed.html?bbox=${safeLongitude.value - 0.01},${safeLatitude.value - 0.01},${safeLongitude.value + 0.01},${safeLatitude.value + 0.01}&layer=mapnik&marker=${safeLatitude.value},${safeLongitude.value}`
})

const canAssign = computed(() =>
  !['completed', 'cancelled'].includes(props.booking.status)
)

const isOverdue = computed(() => {
  const created = new Date(props.booking.created_at)
  const now = new Date()
  const diffDays = Math.floor((now - created) / (1000 * 60 * 60 * 24))
  return diffDays > 2 && props.booking.status === 'pending'
})

const daysSinceCreation = computed(() => {
  const created = new Date(props.booking.created_at)
  const now = new Date()
  return Math.floor((now - created) / (1000 * 60 * 60 * 24))
})

const isFutureDate = computed(() => {
  return new Date(props.booking.booking_date) > new Date()
})

const timeUntilAppointment = computed(() => {
  const appointment = new Date(props.booking.booking_date)
  const now = new Date()
  const diffMs = appointment - now
  const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))
  const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
  
  if (diffDays > 0) return `${diffDays}d ${diffHours}h`
  if (diffHours > 0) return `${diffHours}h`
  return 'Today'
})

const estimatedDuration = computed(() => {
  return props.booking.items?.length > 1 ? '2-3 hours' : '1-2 hours'
})

const subtotal = computed(() => {
  return props.booking.items?.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0) || 0
})

const discount = computed(() => {
  return subtotal.value - (props.booking.final_amount - (tax.value || 0))
})

const tax = computed(() => {
  return 0 // Implement based on your logic
})

const taxRate = computed(() => {
  return 18 // Implement based on your logic
})

const adminNotesChanged = computed(() => {
  return adminNotes.value !== (props.booking.admin_notes ?? '')
})

const invoiceStatusColor = computed(() => {
  const colors = {
    paid: 'bg-green-500',
    sent: 'bg-amber-500',
    draft: 'bg-gray-400',
    overdue: 'bg-red-500'
  }
  return colors[props.invoice?.status] || 'bg-gray-400'
})

// Timeline helpers
const getTimelineDotColor = (action) => {
  const colors = {
    created: 'bg-blue-500',
    confirmed: 'bg-green-500',
    assigned: 'bg-purple-500',
    in_progress: 'bg-orange-500',
    completed: 'bg-emerald-500',
    cancelled: 'bg-red-500',
    rescheduled: 'bg-yellow-500'
  }
  return colors[action] || 'bg-gray-500'
}

const getTimelineIcon = (action) => {
  const icons = {
    created: CheckCircleIcon,
    confirmed: CheckIcon,
    assigned: UserPlusIcon,
    in_progress: PlayIcon,
    completed: CheckCircleIcon,
    cancelled: XMarkIcon,
    rescheduled: CalendarIcon
  }
  return icons[action] || ClockIcon
}

const getTimelineIconColor = (action) => {
  const colors = {
    created: 'text-blue-500',
    confirmed: 'text-green-500',
    assigned: 'text-purple-500',
    in_progress: 'text-orange-500',
    completed: 'text-emerald-500',
    cancelled: 'text-red-500',
    rescheduled: 'text-yellow-500'
  }
  return colors[action] || 'text-gray-500'
}

// Actions
function actionUrl(action) {
  return `/admin/bookings/${props.booking.id}/${action}`
}

function doAction(action, data = {}) {
  actionLoading.value = true
  router.patch(actionUrl(action), data, {
    preserveScroll: true,
    onSuccess: () => router.reload({ only: ['booking'] }),
    onError:   (errors) => {
      const message = Object.values(errors)[0] ?? 'Action failed.'
      alert(message)
    },
    onFinish:  () => { actionLoading.value = false },
  })
}

function assignTechnician() {
  if (!selectedTechnician.value) return
  doAction('assign', { technician_id: selectedTechnician.value })
}

function onCancelled(reason) {
  showCancelModal.value = false
  doAction('cancel', { reason })
}

function onRescheduled() {
  showRescheduleModal.value = false
  router.reload({ only: ['booking'] })
}

function saveNotes() {
  if (!adminNotesChanged.value) return
  
  savingNotes.value = true
  router.patch(actionUrl('notes'), { admin_notes: adminNotes.value }, {
    preserveScroll: true,
    onSuccess: () => {
      lastSavedNotes.value = new Date().toISOString()
    },
    onFinish: () => { savingNotes.value = false },
  })
}

// Quick Actions
function printBooking() {
  window.print()
  showQuickActions.value = false
}

function emailCustomer() {
  window.location.href = `mailto:${props.booking.customer_email}`
  showQuickActions.value = false
}

function sendSMS() {
  // Implement SMS sending logic
  showQuickActions.value = false
}

function duplicateBooking() {
  router.visit(`/admin/bookings/create?duplicate=${props.booking.id}`)
  showQuickActions.value = false
}

function markAsUrgent() {
  doAction('mark-urgent')
}

function viewWarrantyDetails() {
  router.visit(`/admin/warranties/${props.warranty?.id}`)
}

function claimWarranty() {
  router.visit(`/admin/warranties/${props.warranty?.id}/claim`)
}

function openInGoogleMaps() {
  if (hasValidCoordinates.value) {
    window.open(googleMapsUrl.value, '_blank')
  }
  showQuickActions.value = false
}

function copyCoordinates() {
  if (hasValidCoordinates.value) {
    const coords = `${safeLatitude.value}, ${safeLongitude.value}`
    navigator.clipboard.writeText(coords)
    copySuccess.value = true
    setTimeout(() => {
      copySuccess.value = false
    }, 2000)
  }
}

// Formatting
function formatDate(d) {
  return new Date(d).toLocaleDateString('en-IN', { 
    weekday: 'short', 
    day: 'numeric', 
    month: 'long', 
    year: 'numeric' 
  })
}

function formatDateTime(d) {
  return new Date(d).toLocaleString('en-IN', { 
    day: 'numeric', 
    month: 'short', 
    year: 'numeric', 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}

function formatTime(d) {
  return new Date(d).toLocaleString('en-IN', { 
    hour: '2-digit', 
    minute: '2-digit',
    day: 'numeric',
    month: 'short'
  })
}

// Close quick actions on click outside
watch(showQuickActions, (val) => {
  if (val) {
    const close = () => {
      showQuickActions.value = false
      document.removeEventListener('click', close)
    }
    setTimeout(() => {
      document.addEventListener('click', close)
    }, 0)
  }
})

// Handle map loading
watch(() => props.booking, () => {
  if (hasValidCoordinates.value) {
    mapLoading.value = true
    // Simulate map load (remove this in production)
    setTimeout(() => {
      mapLoading.value = false
    }, 1000)
  }
}, { immediate: true })

// Safe coordinate formatting
const formatCoordinates = (lat, lng) => {
  if (!lat || !lng) return 'No coordinates available'
  
  const latNum = parseFloat(lat)
  const lngNum = parseFloat(lng)
  
  if (isNaN(latNum) || isNaN(lngNum)) return 'Invalid coordinates'
  
  return `${latNum.toFixed(6)}, ${lngNum.toFixed(6)}`
}
</script>

<style scoped>
/* Animations */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in-down {
  animation: fadeInDown 0.6s ease-out;
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

.animate-slide-in {
  animation: slideIn 0.4s ease-out;
}

/* Hover Effects */
.hover\:shadow-lg:hover {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
}

.hover\:-translate-y-0\.5:hover {
  transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>