<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <AppHeader :minimal="true" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

      <!-- Page Header -->
      <div class="text-center mb-8 animate-fade-in-down">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Book Your Service</h1>
        <p class="text-gray-500 mt-2 text-sm sm:text-base">Welcome back, {{ auth_user?.name?.split(' ')[0] || 'User' }}! Complete your booking in under 2 minutes</p>
      </div>

      <!-- Progress Bar with Steps -->
      <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <div v-for="(step, index) in stepLabels" :key="index" 
               class="flex flex-col items-center flex-1">
            <div class="flex items-center w-full">
              <div v-for="i in 2" :key="i" class="flex-1" :class="{ 'hidden': i === 1 && index === 0 }">
                <div class="h-1" :class="getProgressLineClass(index, i)"></div>
              </div>
            </div>
            <div class="flex items-center mt-2">
              <div :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300',
                currentStep > index + 1 ? 'bg-green-500 text-white' :
                currentStep === index + 1 ? 'bg-blue-600 text-white ring-4 ring-blue-100' :
                'bg-gray-200 text-gray-500'
              ]">
                <span v-if="currentStep > index + 1">✓</span>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <span :class="[
                'text-xs font-medium ml-2 hidden sm:block',
                currentStep >= index + 1 ? 'text-gray-900' : 'text-gray-400'
              ]">{{ step }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-fade-in-up">

        <!-- STEP 1: Contact -->
        <div v-if="currentStep === 1" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">👤</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Your Contact Details</h2>
              <p class="text-gray-500 text-sm">Verified from your account — edit if needed</p>
            </div>
          </div>

          <!-- Account banner -->
          <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <CheckCircleIcon class="w-5 h-5 text-blue-600" />
              </div>
              <div class="flex-1">
                <p class="text-sm text-blue-900 font-medium">Account Verified</p>
                <p class="text-xs text-blue-700 mt-0.5">Booking will be linked to your account for easy tracking & loyalty points</p>
              </div>
              <div class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                {{ loyaltyPoints }} Points
              </div>
            </div>
          </div>

          <div class="space-y-5">
            <FormField label="Full Name *" :error="errors.guest_name">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <UserIcon class="h-5 w-5 text-gray-400" />
                </div>
                <input v-model="form.guest_name" type="text" placeholder="Your full name"
                       class="form-input pl-10" @blur="validateField('guest_name')" 
                       :class="{ 'border-red-300 focus:ring-red-500': errors.guest_name }" />
              </div>
            </FormField>

            <FormField label="Phone Number *" :error="errors.guest_phone">
              <div class="flex">
                <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">
                  +91
                </span>
                <input v-model="form.guest_phone" type="tel" placeholder="94449 00470" maxlength="10"
                       class="form-input rounded-l-none flex-1" @blur="validateField('guest_phone')"
                       :class="{ 'border-red-300 focus:ring-red-500': errors.guest_phone }" />
              </div>
              <p class="text-xs text-gray-400 mt-1">We'll send booking updates via SMS & WhatsApp</p>
            </FormField>

            <FormField label="Email Address" :error="errors.guest_email">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                </div>
                <input v-model="form.guest_email" type="email" placeholder="you@example.com" 
                       class="form-input pl-10" />
              </div>
            </FormField>
          </div>

          <!-- Saved addresses (if any) -->
          <div v-if="savedAddresses.length > 0" class="mt-6">
            <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
              <HomeIcon class="w-4 h-4" />
              Saved Addresses
            </p>
            <div class="space-y-2">
              <button v-for="(addr, idx) in savedAddresses" :key="idx"
                      @click="useSavedAddress(addr)"
                      class="w-full text-left p-3 border border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <p class="text-sm font-medium text-gray-900">{{ addr.label || 'Address ' + (idx+1) }}</p>
                <p class="text-xs text-gray-500">{{ addr.address }}, {{ addr.area }}, {{ addr.pincode }}</p>
              </button>
            </div>
          </div>
        </div>

        <!-- STEP 2: Services -->
        <div v-if="currentStep === 2" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">🔧</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Select Services</h2>
              <p class="text-gray-500 text-sm">Choose the services you need</p>
            </div>
          </div>

          <div class="space-y-6">
            <div v-for="category in categories" :key="category.id" class="animate-slide-in">
              <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-6 h-6 flex items-center justify-center bg-blue-100 rounded-lg text-sm">
                  {{ category.icon || '🔧' }}
                </span>
                {{ category.name }}
              </h3>
              <div class="space-y-3">
                <div v-for="service in category.services" :key="service.id"
                     @click="toggleService(service)"
                     :class="[
                      'relative p-4 rounded-xl border-2 transition-all cursor-pointer hover:shadow-md group',
                      isSelected(service.id)
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-200 hover:border-blue-200 hover:bg-gray-50',
                    ]">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 flex-1">
                      <div class="pt-0.5">
                        <div :class="[
                          'w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all',
                          isSelected(service.id) 
                            ? 'border-blue-500 bg-blue-500' 
                            : 'border-gray-300 group-hover:border-blue-400'
                        ]">
                          <CheckIcon v-if="isSelected(service.id)" class="w-3 h-3 text-white" />
                        </div>
                      </div>
                      <div>
                        <p class="font-semibold text-gray-900">{{ service.name }}</p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                          <ClockIcon class="w-3 h-3" />
                          {{ service.duration_estimate || '30-45 mins' }}
                        </p>
                      </div>
                    </div>
                    <div class="text-right ml-4">
                      <p class="font-bold text-gray-900 text-lg">₹{{ service.discounted_price ?? service.base_price }}</p>
                      <p v-if="service.discounted_price" class="text-xs text-gray-400 line-through">₹{{ service.base_price }}</p>
                    </div>
                  </div>
                  
                  <!-- Loyalty points badge -->
                  <div v-if="service.loyalty_points" 
                       class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full shadow-lg flex items-center gap-1">
                    <StarIcon class="w-3 h-3" />
                    {{ service.loyalty_points }} pts
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Selected summary -->
          <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transform transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div v-if="form.services.length > 0"
                 class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <ShoppingBagIcon class="w-5 h-5 text-green-600" />
                  <div>
                    <p class="font-semibold text-green-800">
                      {{ form.services.length }} service{{ form.services.length > 1 ? 's' : '' }} selected
                    </p>
                    <p class="text-xs text-green-600 mt-0.5">{{ selectedNames.join(', ').slice(0, 50) }}...</p>
                  </div>
                </div>
                <p class="font-bold text-green-800 text-xl">₹{{ totalPrice }}</p>
              </div>
              <div class="mt-2 pt-2 border-t border-green-200 flex justify-between text-xs text-green-700">
                <span>You'll earn:</span>
                <span class="font-semibold flex items-center gap-1">
                  <StarIcon class="w-3 h-3" />
                  {{ loyaltyPointsToEarn }} loyalty points
                </span>
              </div>
            </div>
          </Transition>
        </div>

        <!-- STEP 3: Address -->
        <div v-if="currentStep === 3" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">📍</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Service Address</h2>
              <p class="text-gray-500 text-sm">Where should our technician come?</p>
            </div>
          </div>

          <button @click="detectLocation" :disabled="detectingLocation"
                  class="w-full group flex items-center justify-center gap-3 py-4 px-4 border-2 border-dashed border-blue-300 text-blue-600 rounded-xl hover:bg-blue-50 transition-all mb-6 font-medium relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <MapPinIcon class="w-5 h-5 group-hover:animate-bounce" />
            <span>{{ detectingLocation ? 'Detecting your location...' : 'Use My Current Location' }}</span>
            <span v-if="detectingLocation" class="ml-2">
              <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
          </button>

          <div class="flex items-center gap-3 mb-4">
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-sm text-gray-400 flex-shrink-0">or enter manually</span>
            <div class="flex-1 border-t border-gray-200"></div>
          </div>

          <div class="space-y-4">
            <FormField label="Full Address *" :error="errors.address">
              <div class="relative">
                <div class="absolute top-3 left-3 pointer-events-none">
                  <HomeIcon class="w-5 h-5 text-gray-400" />
                </div>
                <textarea v-model="form.address" rows="3"
                          placeholder="House/Flat No, Street, Locality..."
                          class="form-input pl-10 resize-none"></textarea>
              </div>
            </FormField>
            
            <div class="grid grid-cols-2 gap-4">
              <FormField label="Area / Locality">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <BuildingOfficeIcon class="h-5 w-5 text-gray-400" />
                  </div>
                  <input v-model="form.area" type="text" placeholder="Anna Nagar" class="form-input pl-10" />
                </div>
              </FormField>
              
              <FormField label="Pincode">
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <MapPinIcon class="h-5 w-5 text-gray-400" />
                  </div>
                  <input v-model="form.pincode" type="text" maxlength="6" placeholder="600001" 
                         class="form-input pl-10" @input="validatePincode" />
                </div>
              </FormField>
            </div>
          </div>

          <!-- Save address for future -->
          <div class="mt-4 flex items-center gap-2">
            <input v-model="form.save_address" type="checkbox" id="saveAddress" 
                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="saveAddress" class="text-sm text-gray-600">Save this address for future bookings</label>
          </div>
        </div>

        <!-- STEP 4: Date & Time -->
        <div v-if="currentStep === 4" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">📅</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Choose Date & Time</h2>
              <p class="text-gray-500 text-sm">Pick your preferred slot</p>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
              <CalendarIcon class="w-4 h-4" />
              Select Date
            </label>
            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
              <button v-for="date in availableDates" :key="date.value"
                      @click="selectDate(date.value)"
                      :class="[
                        'flex flex-col items-center py-3 px-1 rounded-xl border-2 transition-all text-center',
                        form.booking_date === date.value
                          ? 'border-blue-500 bg-blue-500 text-white shadow-lg shadow-blue-200'
                          : 'border-gray-200 hover:border-blue-200 hover:bg-blue-50 text-gray-700',
                        date.isPast && 'opacity-50 cursor-not-allowed hover:border-gray-200 hover:bg-transparent'
                      ]"
                      :disabled="date.isPast">
                <span class="text-xs font-medium">{{ date.day }}</span>
                <span class="text-lg font-bold leading-tight">{{ date.dateNum }}</span>
                <span class="text-xs">{{ date.month }}</span>
              </button>
            </div>
          </div>

          <div v-if="form.booking_date">
            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
              <ClockIcon class="w-4 h-4" />
              Select Time Slot
            </label>
            
            <div v-if="loadingSlots" class="text-center py-12">
              <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-200 border-t-blue-600"></div>
              <p class="text-gray-400 text-sm mt-2">Checking availability...</p>
            </div>
            
            <div v-else-if="timeSlots.length === 0" class="text-center py-8 bg-gray-50 rounded-xl">
              <CalendarIcon class="w-12 h-12 text-gray-300 mx-auto mb-2" />
              <p class="text-gray-500">No slots available for this date</p>
              <p class="text-xs text-gray-400 mt-1">Please select another date</p>
            </div>
            
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <button v-for="slot in timeSlots" :key="slot.slot"
                      @click="slot.available && (form.time_slot = slot.slot)"
                      :disabled="!slot.available"
                      :class="[
                        'relative py-3 px-2 rounded-xl border-2 text-sm font-medium transition-all',
                        !slot.available
                          ? 'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed'
                          : form.time_slot === slot.slot
                            ? 'border-blue-500 bg-blue-500 text-white shadow-lg shadow-blue-200'
                            : 'border-gray-200 hover:border-blue-200 hover:bg-blue-50 text-gray-700',
                      ]">
                <span>{{ slot.label }}</span>
                <span v-if="!slot.available" class="absolute -top-1 -right-1">
                  <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                  </span>
                </span>
              </button>
            </div>
          </div>

          <div class="mt-5">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center gap-1">
              <DocumentTextIcon class="w-4 h-4" />
              Special Instructions (Optional)
            </label>
            <textarea v-model="form.customer_notes" rows="3" 
                      class="form-input resize-none"
                      placeholder="AC model, floor number, gate code, symptoms..."></textarea>
          </div>
        </div>

        <!-- STEP 5: Confirm -->
        <div v-if="currentStep === 5" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl">✓</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Confirm Your Booking</h2>
              <p class="text-gray-500 text-sm">Review your details before submitting</p>
            </div>
          </div>

          <div class="space-y-4">
            <!-- Customer Details Card -->
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <UserIcon class="w-4 h-4" />
                Customer Details
              </h3>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-500">Name</span>
                  <span class="font-medium">{{ form.guest_name }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-500">Phone</span>
                  <span class="font-medium">+91 {{ form.guest_phone }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-500">Email</span>
                  <span class="font-medium">{{ form.guest_email || 'Not provided' }}</span>
                </div>
              </div>
            </div>

            <!-- Address Card -->
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <HomeIcon class="w-4 h-4" />
                Service Address
              </h3>
              <p class="text-sm text-gray-700">{{ fullAddress }}</p>
            </div>

            <!-- Services Card -->
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <WrenchIcon class="w-4 h-4" />
                Selected Services
              </h3>
              <div class="space-y-2">
                <div v-for="svc in selectedServices" :key="svc.id" 
                     class="flex justify-between text-sm py-1 border-b border-gray-100 last:border-0">
                  <span class="text-gray-600">{{ svc.name }}</span>
                  <span class="font-medium">₹{{ svc.price }}</span>
                </div>
                <div class="flex justify-between pt-2 font-bold text-lg">
                  <span>Total Estimate</span>
                  <span class="text-blue-600">₹{{ totalPrice }}</span>
                </div>
              </div>
            </div>

            <!-- Date & Time Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl">
                  📅
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-blue-900">{{ formattedDate }}</p>
                  <p class="text-blue-700 text-sm flex items-center gap-1 mt-1">
                    <ClockIcon class="w-4 h-4" />
                    {{ form.time_slot }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Loyalty points card -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-4 border border-yellow-200">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                  <StarIcon class="w-5 h-5 text-yellow-600" />
                </div>
                <div class="flex-1">
                  <p class="font-semibold text-yellow-800 text-sm">You'll earn loyalty points!</p>
                  <p class="text-yellow-700 text-xs mt-1">
                    <strong>{{ loyaltyPointsToEarn }} points</strong> will be added to your account when service is completed
                  </p>
                </div>
                <div class="bg-yellow-200 text-yellow-800 font-bold px-3 py-1 rounded-full text-sm">
                  {{ auth_user?.loyalty_points || 0 }} → {{ (auth_user?.loyalty_points || 0) + loyaltyPointsToEarn }}
                </div>
              </div>
            </div>

            <!-- Notes if any -->
            <div v-if="form.customer_notes" class="bg-gray-50 rounded-xl p-4 text-sm">
              <p class="text-gray-500 italic">"{{ form.customer_notes }}"</p>
            </div>

            <p class="text-xs text-gray-400 text-center pt-2">
              <InformationCircleIcon class="w-4 h-4 inline mr-1" />
              Final price may vary based on on-site diagnosis. We'll confirm via SMS.
            </p>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-between gap-4">
          <button v-if="currentStep > 1" @click="prevStep"
                  class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors rounded-lg hover:bg-gray-100">
            <ChevronLeftIcon class="w-4 h-4" />
            Back
          </button>
          <div v-else></div>

          <div class="flex gap-3">
            <button v-if="currentStep < totalSteps" @click="nextStep" :disabled="!canProceed"
                    :class="[
                      'flex items-center gap-2 px-6 py-2 rounded-xl font-semibold transition-all',
                      canProceed
                        ? 'bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg hover:-translate-y-0.5'
                        : 'bg-gray-200 text-gray-400 cursor-not-allowed',
                    ]">
              Continue
              <ChevronRightIcon class="w-4 h-4" />
            </button>

            <button v-else @click="submitBooking" :disabled="submitting"
                    class="flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transition-all hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-60">
              <span v-if="submitting">
                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Submitting...
              </span>
              <span v-else class="flex items-center gap-2">
                Confirm Booking
                <CheckIcon class="w-4 h-4" />
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Trust Badges -->
      <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs text-gray-400">
        <span class="flex items-center gap-1">
          <LockClosedIcon class="w-3 h-3" />
          Secure Booking
        </span>
        <span class="flex items-center gap-1">
          <ShieldCheckIcon class="w-3 h-3" />
          No Hidden Charges
        </span>
        <span class="flex items-center gap-1">
          <StarIcon class="w-3 h-3" />
          Earn Loyalty Points
        </span>
        <span class="flex items-center gap-1">
          <ChatBubbleLeftRightIcon class="w-3 h-3" />
          24/7 Support
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import {
  CheckIcon, CheckCircleIcon, MapPinIcon, CalendarIcon,
  StarIcon, ChevronLeftIcon, ChevronRightIcon,
  UserIcon, EnvelopeIcon, HomeIcon, BuildingOfficeIcon,
  ClockIcon, ShoppingBagIcon, WrenchIcon, LockClosedIcon,
  ShieldCheckIcon, ChatBubbleLeftRightIcon, DocumentTextIcon,
  InformationCircleIcon
} from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import FormField from '@/components/Shared/FormField.vue'

const props = defineProps({
  categories: { type: Array, default: () => [] },
  auth_user:  { type: Object, default: () => ({}) },
  savedAddresses: { type: Array, default: () => [] },
})

const currentStep  = ref(1)
const totalSteps   = 5
const stepLabels   = ['Contact', 'Services', 'Address', 'Date', 'Confirm']
const submitting   = ref(false)
const loadingSlots = ref(false)
const timeSlots    = ref([])
const detectingLocation = ref(false)
const errors       = ref({})

// Pre-fill from auth user
const form = ref({
  guest_name:     props.auth_user?.name  ?? '',
  guest_phone:    props.auth_user?.phone ?? '',
  guest_email:    props.auth_user?.email ?? '',
  services:       [],
  address:        '',
  area:           '',
  pincode:        '',
  latitude:       null,
  longitude:      null,
  booking_date:   '',
  time_slot:      '',
  customer_notes: '',
  save_address:   false,
})

// User loyalty points
const loyaltyPoints = computed(() => props.auth_user?.loyalty_points || 0)

// ---- Services ----
function toggleService(service) {
  const idx = form.value.services.findIndex(s => s.id === service.id)

  if (idx >= 0) {
    form.value.services.splice(idx, 1)
  } else {
    form.value.services.push({
      id: service.id,
      name: service.name,
      price: service.discounted_price ?? service.base_price ?? 0,
      quantity: 1,
    })
  }
}
function isSelected(id) { return form.value.services.some(s => s.id === id) }

const allServices   = computed(() => props.categories.flatMap(c => c.services ?? []))
const selectedServices = computed(() => form.value.services.map(s => ({
  ...s,
  name: allServices.value.find(a => a.id === s.id)?.name ?? s.name ?? '',
})))
const selectedNames = computed(() => selectedServices.value.map(s => s.name))
const totalPrice    = computed(() =>
  form.value.services.reduce((sum, s) => sum + parseFloat(s.price ?? 0) * (s.quantity ?? 1), 0)
)
const loyaltyPointsToEarn = computed(() => Math.floor(totalPrice.value / 100))

// ---- Address ----
const fullAddress = computed(() => {
  const parts = [form.value.address, form.value.area, form.value.pincode].filter(Boolean)
  return parts.join(', ')
})

function useSavedAddress(addr) {
  form.value.address = addr.address
  form.value.area = addr.area
  form.value.pincode = addr.pincode
  if (addr.latitude && addr.longitude) {
    form.value.latitude = addr.latitude
    form.value.longitude = addr.longitude
  }
}

// ---- Dates ----
const availableDates = computed(() => {
  const dates = []
  
  // Get current date in IST
  const now = new Date()
  
  // Create today at midnight IST
  const today = new Date(Date.UTC(
    now.getFullYear(),
    now.getMonth(),
    now.getDate(),
    0, 0, 0, 0
  ))
  
  // Adjust for IST offset (5 hours 30 minutes)
  today.setHours(today.getHours() + 5)
  today.setMinutes(today.getMinutes() + 30)
  
  for (let i = 0; i < 14; i++) {
    // Create date for each future day at noon IST
    const d = new Date(Date.UTC(
      now.getFullYear(),
      now.getMonth(),
      now.getDate() + i,
      12, 0, 0, 0 // Noon UTC
    ))
    
    // Adjust to IST
    d.setHours(d.getHours() + 5)
    d.setMinutes(d.getMinutes() + 30)
    
    // Format as YYYY-MM-DD in IST
    const year = d.getFullYear()
    const month = String(d.getMonth() + 1).padStart(2, '0')
    const day = String(d.getDate()).padStart(2, '0')
    const dateValue = `${year}-${month}-${day}`
    
    // Create date objects for comparison (at midnight IST)
    const compareDate = new Date(Date.UTC(year, d.getMonth(), d.getDate(), 0, 0, 0, 0))
    compareDate.setHours(compareDate.getHours() + 5)
    compareDate.setMinutes(compareDate.getMinutes() + 30)
    
    dates.push({
      value: dateValue,
      day: d.toLocaleDateString('en-IN', { weekday: 'short', timeZone: 'Asia/Kolkata' }),
      dateNum: d.getDate(),
      month: d.toLocaleDateString('en-IN', { month: 'short', timeZone: 'Asia/Kolkata' }),
      isPast: compareDate < today,
    })
  }
  return dates
})

const formattedDate = computed(() => {
  if (!form.value.booking_date) return ''
  return new Date(form.value.booking_date).toLocaleDateString('en-IN', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
  })
})

async function selectDate(date) {
  form.value.booking_date = date
  form.value.time_slot    = ''
  loadingSlots.value      = true
  try {
    const res = await axios.get(`/api/v1/bookings/slots/${date}`)
    timeSlots.value = (res.data.slots ?? []).map(slot => ({
      ...slot,
      label: slot.slot.replace('-', ' – ')
    }))
  } catch { timeSlots.value = [] }
  finally { loadingSlots.value = false }
}

// ---- Location ----
async function detectLocation() {
  if (!navigator.geolocation) return
  detectingLocation.value = true
  navigator.geolocation.getCurrentPosition(async (pos) => {
    form.value.latitude  = pos.coords.latitude
    form.value.longitude = pos.coords.longitude
    try {
      const res = await axios.get('https://nominatim.openstreetmap.org/reverse', {
        params: { lat: pos.coords.latitude, lon: pos.coords.longitude, format: 'json' }
      })
      const addr = res.data.address
      form.value.address = [addr.road, addr.suburb, addr.city].filter(Boolean).join(', ')
      form.value.area    = addr.suburb || addr.neighbourhood || addr.county || ''
      form.value.pincode = addr.postcode || ''
    } catch {}
    detectingLocation.value = false
  }, () => { detectingLocation.value = false })
}

function validatePincode(e) {
  form.value.pincode = e.target.value.replace(/\D/g, '').slice(0, 6)
}

// ---- Navigation ----
const canProceed = computed(() => {
  if (currentStep.value === 1) return form.value.guest_name && form.value.guest_phone?.length === 10
  if (currentStep.value === 2) return form.value.services.length > 0
  if (currentStep.value === 3) return !!form.value.address && form.value.pincode?.length === 6
  if (currentStep.value === 4) return !!(form.value.booking_date && form.value.time_slot)
  return true
})

function nextStep() { 
  if (canProceed.value) {
    currentStep.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

function prevStep() { 
  if (currentStep.value > 1) {
    currentStep.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Progress line styling
const getProgressLineClass = (index, lineIndex) => {
  const stepNum = index + 1
  if (currentStep > stepNum) return 'bg-green-500'
  if (currentStep === stepNum && lineIndex === 1) return 'bg-blue-500'
  return 'bg-gray-200'
}

// ---- Validation ----
function validateField(field) {
  if (field === 'guest_phone') {
    errors.value.guest_phone = form.value.guest_phone.length !== 10
      ? 'Please enter a valid 10-digit mobile number.' : undefined
  }
  if (field === 'guest_name') {
    errors.value.guest_name = !form.value.guest_name.trim()
      ? 'Name is required.' : undefined
  }
}

// ---- Submit via Inertia ----
function submitBooking() {
  submitting.value = true
  errors.value     = {}
  router.post('/user/bookings', form.value, {
    onError: (errs) => { 
      errors.value = errs
      currentStep.value = 1
    },
    onFinish: () => { submitting.value = false },
  })
}
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-all duration-200 text-sm bg-white;
}

.form-input:hover {
  @apply border-gray-400;
}

.form-input:focus {
  @apply shadow-lg shadow-blue-100;
}

/* Icon padding */
.pl-10 {
  padding-left: 2.5rem;
}

.pl-10.pr-12 {
  padding-left: 2.5rem;
  padding-right: 3rem;
}

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

/* Responsive */
@media (max-width: 640px) {
  .form-input {
    @apply py-2.5;
  }
}
</style>