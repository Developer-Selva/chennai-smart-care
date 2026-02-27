<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">

    <Head><title>Quick Booking — Chennai Smart Care</title></Head>

    <AppHeader />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

      <!-- Page Header -->
      <div class="text-center mb-8 animate-fade-in-down">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Quick Service Booking</h1>
        <p class="text-gray-500 mt-2 text-sm sm:text-base">Book your appliance repair in under 2 minutes</p>
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

      <!-- Main Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-fade-in-up">

        <!-- ========== STEP 1: Contact Info ========== -->
        <div v-if="currentStep === 1" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">📞</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Your Contact Details</h2>
              <p class="text-gray-500 text-sm">No account needed — just your name and phone number.</p>
            </div>
          </div>

          <div class="space-y-5">
            <FormField label="Full Name *" :error="errors.guest_name">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <UserIcon class="h-5 w-5 text-gray-400" />
                </div>
                <input v-model="form.guest_name" type="text" placeholder="e.g., Rajesh Kumar"
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

            <FormField label="Email Address (Optional)" :error="errors.guest_email">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                </div>
                <input v-model="form.guest_email" type="email" placeholder="you@example.com" 
                       class="form-input pl-10" />
              </div>
            </FormField>
          </div>

          <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
            <div class="flex items-center gap-3">
              <UserCircleIcon class="w-8 h-8 text-blue-600" />
              <div class="flex-1">
                <p class="text-sm text-blue-900 font-medium">Already have an account?</p>
                <p class="text-xs text-blue-700 mt-0.5">Sign in for faster booking with saved details</p>
              </div>
              <a href="/user/login" 
                 class="px-4 py-2 bg-white text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-50 transition-colors shadow-sm">
                Sign In
              </a>
            </div>
          </div>
        </div>

        <!-- ========== STEP 2: Select Services ========== -->
        <div v-if="currentStep === 2" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">🔧</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Select Services</h2>
              <p class="text-gray-500 text-sm">Choose one or multiple services you need</p>
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
                      isServiceSelected(service.id)
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-200 hover:border-blue-200 hover:bg-gray-50',
                    ]">
                  <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 flex-1">
                      <div class="pt-0.5">
                        <div :class="[
                          'w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all',
                          isServiceSelected(service.id) 
                            ? 'border-blue-500 bg-blue-500' 
                            : 'border-gray-300 group-hover:border-blue-400'
                        ]">
                          <span v-if="isServiceSelected(service.id)" class="text-white text-xs">✓</span>
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
                      <p class="font-bold text-gray-900 text-lg">₹{{ service.effective_price ?? service.base_price }}</p>
                      <p v-if="service.discounted_price" class="text-xs text-gray-400 line-through">₹{{ service.base_price }}</p>
                    </div>
                  </div>
                  
                  <!-- Popular tag -->
                  <div v-if="service.is_popular" 
                       class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full shadow-lg">
                    Popular
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Selected summary with animation -->
          <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transform transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div v-if="selectedServices.length > 0"
                 class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <ShoppingBagIcon class="w-5 h-5 text-green-600" />
                  <div>
                    <p class="font-semibold text-green-800">
                      {{ selectedServices.length }} service{{ selectedServices.length > 1 ? 's' : '' }} selected
                    </p>
                    <p class="text-xs text-green-600 mt-0.5">You can add more services anytime</p>
                  </div>
                </div>
                <p class="font-bold text-green-800 text-xl">₹{{ totalPrice }}</p>
              </div>
            </div>
          </Transition>
          
          <p v-if="errors.services" class="mt-2 text-red-600 text-sm flex items-center gap-1">
            <ExclamationCircleIcon class="w-4 h-4" />
            {{ errors.services }}
          </p>
        </div>

        <!-- ========== STEP 3: Location (Enhanced with Accurate GPS) ========== -->
        <div v-if="currentStep === 3" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">📍</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Service Address</h2>
              <p class="text-gray-500 text-sm">We serve within 20km of Chennai city centre</p>
            </div>
          </div>

          <!-- Enhanced GPS Detection Button with Accuracy Indicator -->
          <div class="space-y-3 mb-6">
            <button @click="startAccurateLocationDetection" :disabled="detectingLocation"
                    class="w-full group relative overflow-hidden rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-4 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg">
              <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
              <div class="relative flex items-center justify-center gap-3">
                <GlobeAltIcon class="w-5 h-5 animate-pulse" />
                <span class="font-semibold">{{ detectingLocation ? 'Getting precise location...' : 'Use Precise GPS Location' }}</span>
                <span v-if="detectingLocation" class="ml-2">
                  <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
              </div>
            </button>

            <!-- GPS Accuracy Progress -->
            <div v-if="detectingLocation" class="bg-blue-50 rounded-xl p-4 border border-blue-200">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-blue-700">Acquiring GPS Signal</span>
                <span class="text-xs font-semibold text-blue-600">{{ gpsProgress }}%</span>
              </div>
              <div class="h-2 bg-blue-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full transition-all duration-300" 
                     :style="{ width: gpsProgress + '%' }"></div>
              </div>
              <p class="text-xs text-blue-600 mt-2 flex items-center gap-1">
                <SignalIcon class="w-3 h-3" />
                {{ gpsStatusMessage }}
              </p>
            </div>

            <!-- Accuracy Badge when location is detected -->
            <div v-if="form.latitude && gpsAccuracy" 
                 class="flex items-center gap-2 text-sm"
                 :class="accuracyClass">
              <div class="w-2 h-2 rounded-full" :class="accuracyDotClass"></div>
              <span>Accuracy: ±{{ gpsAccuracy }} meters</span>
              <span v-if="gpsAccuracy <= 10" class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">High Precision</span>
              <span v-else-if="gpsAccuracy <= 50" class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Medium Precision</span>
              <span v-else class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Low Precision</span>
            </div>
          </div>

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

          <!-- Location validation feedback with accuracy info -->
          <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
          >
            <div v-if="locationValidated" 
                 class="mt-4 p-3 bg-green-50 rounded-xl border border-green-200">
              <div class="flex items-center gap-2 text-green-700">
                <CheckCircleIcon class="w-5 h-5 flex-shrink-0" />
                <span class="text-sm">✅ Location is within our service area</span>
              </div>
              <div v-if="gpsAccuracy" class="mt-2 text-xs text-green-600 flex items-center gap-2">
                <MapPinIcon class="w-3 h-3" />
                <span>Coordinates: {{ form.latitude?.toFixed(6) }}, {{ form.longitude?.toFixed(6) }}</span>
              </div>
            </div>
          </Transition>
          
          <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
          >
            <div v-if="locationError" 
                 class="mt-4 p-3 bg-red-50 rounded-xl border border-red-200 flex items-center gap-2 text-red-600">
              <ExclamationCircleIcon class="w-5 h-5 flex-shrink-0" />
              <span class="text-sm">{{ locationError }}</span>
            </div>
          </Transition>

          <!-- Map Preview (Optional) -->
          <div v-if="form.latitude && form.longitude" class="mt-4">
            <button @click="showMap = !showMap" 
                    class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
              <MapIcon class="w-4 h-4" />
              {{ showMap ? 'Hide map' : 'Show on map' }}
            </button>
            <div v-if="showMap" class="mt-2 h-48 bg-gray-100 rounded-xl overflow-hidden">
              <!-- Embed map here - you can use Google Maps or OpenStreetMap -->
              <img :src="`https://maps.googleapis.com/maps/api/staticmap?center=${form.latitude},${form.longitude}&zoom=15&size=600x200&markers=color:red%7C${form.latitude},${form.longitude}&key=YOUR_API_KEY`" 
                   alt="Location map" class="w-full h-full object-cover">
            </div>
          </div>
        </div>

        <!-- ========== STEP 4: Date & Time ========== -->
        <div v-if="currentStep === 4" class="p-6 sm:p-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">📅</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Choose Date & Time</h2>
              <p class="text-gray-500 text-sm">Available 9AM – 9PM, 7 days a week</p>
            </div>
          </div>

          <!-- Date picker with calendar icon -->
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

          <!-- Time slots -->
          <div v-if="form.booking_date" class="animate-slide-in">
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

          <!-- Special instructions -->
          <FormField label="Any Special Instructions? (Optional)" class="mt-6">
            <div class="relative">
              <div class="absolute top-3 left-3 pointer-events-none">
                <DocumentTextIcon class="w-5 h-5 text-gray-400" />
              </div>
              <textarea v-model="form.customer_notes" rows="3"
                        placeholder="e.g., AC model, symptoms, floor number, gate code..."
                        class="form-input pl-10 resize-none"></textarea>
            </div>
          </FormField>
        </div>

        <!-- ========== STEP 5: Confirm ========== -->
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

            <!-- Address Card with GPS Info -->
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200">
              <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <HomeIcon class="w-4 h-4" />
                Service Address
              </h3>
              <p class="text-sm text-gray-700">{{ fullAddress }}</p>
              <div v-if="form.latitude && gpsAccuracy" class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                <GlobeAltIcon class="w-3 h-3" />
                <span>GPS: ±{{ gpsAccuracy }}m accuracy</span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span>Source: {{ form.location_source || 'GPS' }}</span>
              </div>
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

        <!-- ========== SUCCESS ========== -->
        <div v-if="bookingSuccess" class="p-6 sm:p-8 text-center animate-fade-in-up">
          <div class="relative mb-6">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto text-5xl animate-bounce-slow">
              ✅
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-32 h-32 bg-green-200 rounded-full animate-ping opacity-20"></div>
            </div>
          </div>
          
          <h2 class="text-3xl font-bold text-gray-900">Booking Confirmed!</h2>
          <p class="text-gray-500 mt-2">Your booking ID is:</p>
          
          <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl inline-block">
            <p class="text-3xl font-extrabold text-blue-600 font-mono tracking-wider">{{ bookingNumber }}</p>
          </div>
          
          <div class="mt-6 space-y-3 max-w-sm mx-auto">
            <div class="bg-green-50 rounded-xl p-3 text-sm text-green-700 flex items-center gap-2">
              <CheckCircleIcon class="w-5 h-5 flex-shrink-0" />
              <span>SMS confirmation sent to +91 {{ form.guest_phone }}</span>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mt-4">
              <a :href="`/track/${bookingNumber}`"
                 class="flex items-center justify-center gap-2 bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                <TruckIcon class="w-4 h-4" />
                Track Booking
              </a>
              <a href="/"
                 class="flex items-center justify-center gap-2 border border-gray-300 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                <HomeIcon class="w-4 h-4" />
                Home
              </a>
            </div>
            
            <button @click="shareBooking" 
                    class="w-full flex items-center justify-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
              <ShareIcon class="w-4 h-4" />
              Share booking details
            </button>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div v-if="!bookingSuccess"
             class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-between gap-4">
          <button v-if="currentStep > 1" @click="prevStep"
                  class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors rounded-lg hover:bg-gray-100">
            <ArrowLeftIcon class="w-4 h-4" />
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
              <ArrowRightIcon class="w-4 h-4" />
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
          <ClockIcon class="w-3 h-3" />
          30-Day Warranty
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
import { ref, computed, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import axios from 'axios'
import AppHeader from '@/components/Landing/AppHeader.vue'
import FormField  from '@/components/Shared/FormField.vue'
import {
  UserIcon,
  EnvelopeIcon,
  UserCircleIcon,
  ClockIcon,
  MapPinIcon,
  HomeIcon,
  BuildingOfficeIcon,
  CalendarIcon,
  DocumentTextIcon,
  WrenchIcon,
  ShoppingBagIcon,
  ExclamationCircleIcon,
  CheckCircleIcon,
  ArrowLeftIcon,
  ArrowRightIcon,
  CheckIcon,
  LockClosedIcon,
  ShieldCheckIcon,
  ChatBubbleLeftRightIcon,
  TruckIcon,
  ShareIcon,
  InformationCircleIcon,
  GlobeAltIcon,
  SignalIcon,
  MapIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  categories: { type: Array, default: () => [] },
})

const currentStep = ref(1)
const totalSteps  = 5
const stepLabels  = ['Contact', 'Services', 'Location', 'Date', 'Confirm']

const form = ref({
  guest_name: '', guest_phone: '', guest_email: '',
  address: '', area: '', pincode: '',
  latitude: null, longitude: null,
  location_accuracy: null, 
  location_source: null,
  services: [],
  booking_date: '', time_slot: '', customer_notes: '',
})

const errors          = ref({})
const detectingLocation = ref(false)
const locationValidated = ref(false)
const locationError     = ref('')
const gpsAccuracy       = ref(null)
const gpsProgress       = ref(0)
const gpsStatusMessage  = ref('Initializing GPS...')
const showMap           = ref(false)
let   watchId           = null
let   progressInterval  = null

const loadingSlots      = ref(false)
const timeSlots         = ref([])
const submitting        = ref(false)
const bookingSuccess    = ref(false)
const bookingNumber     = ref('')

// ---- Computed ----
const allServices = computed(() =>
  props.categories?.flatMap(c => c.services ?? []) ?? []
)

const selectedServices = computed(() =>
  form.value.services.map(sel => {
    const svc = allServices.value.find(s => s.id === sel.id)
    return svc ? { ...svc, price: sel.price } : sel
  })
)

const totalPrice = computed(() =>
  form.value.services.reduce((sum, s) => sum + parseFloat(s.price ?? 0), 0)
)

const isServiceSelected = (id) => form.value.services.some(s => s.id === id)

const formattedDate = computed(() => {
  if (!form.value.booking_date) return ''
  return new Date(form.value.booking_date).toLocaleDateString('en-IN', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
  })
})

const fullAddress = computed(() => {
  const parts = [form.value.address, form.value.area, form.value.pincode].filter(Boolean)
  return parts.join(', ')
})

const availableDates = computed(() => {
  const dates = []
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  
  for (let i = 0; i < 14; i++) {
    const d = new Date()
    d.setDate(d.getDate() + i)
    d.setHours(0, 0, 0, 0)
    
    dates.push({
      value:   d.toISOString().split('T')[0],
      day:     d.toLocaleDateString('en-IN', { weekday: 'short' }),
      dateNum: d.getDate(),
      month:   d.toLocaleDateString('en-IN', { month: 'short' }),
      isPast: d < today,
    })
  }
  return dates
})

const canProceed = computed(() => {
  if (currentStep.value === 1) return form.value.guest_name.trim() && form.value.guest_phone.length === 10
  if (currentStep.value === 2) return form.value.services.length > 0
  if (currentStep.value === 3) return form.value.address.trim() && form.value.latitude && form.value.longitude
  if (currentStep.value === 4) return form.value.booking_date && form.value.time_slot
  return true
})

// Accuracy classes for styling
const accuracyClass = computed(() => {
  if (!gpsAccuracy.value) return 'text-gray-500'
  if (gpsAccuracy.value <= 10) return 'text-green-600'
  if (gpsAccuracy.value <= 50) return 'text-yellow-600'
  return 'text-orange-600'
})

const accuracyDotClass = computed(() => {
  if (!gpsAccuracy.value) return 'bg-gray-400'
  if (gpsAccuracy.value <= 10) return 'bg-green-500'
  if (gpsAccuracy.value <= 50) return 'bg-yellow-500'
  return 'bg-orange-500'
})

// Progress line styling
const getProgressLineClass = (index, lineIndex) => {
  const stepNum = index + 1
  if (currentStep > stepNum) return 'bg-green-500'
  if (currentStep === stepNum && lineIndex === 1) return 'bg-blue-500'
  return 'bg-gray-200'
}

// ---- Enhanced Location Methods ----
function startAccurateLocationDetection() {
  if (!navigator.geolocation) {
    locationError.value = 'Geolocation is not supported by your browser.'
    return
  }

  detectingLocation.value = true
  locationError.value = ''
  gpsProgress.value = 0
  gpsStatusMessage.value = 'Requesting location permission...'

  // Simulate progress for better UX
  progressInterval = setInterval(() => {
    if (gpsProgress.value < 90 && detectingLocation.value) {
      gpsProgress.value += 5
      if (gpsProgress.value === 30) gpsStatusMessage.value = 'Acquiring satellite signals...'
      if (gpsProgress.value === 60) gpsStatusMessage.value = 'Calculating precise position...'
      if (gpsProgress.value === 80) gpsStatusMessage.value = 'Almost there...'
    }
  }, 300)

  // Start watching position for continuous updates
  watchId = navigator.geolocation.watchPosition(
    (pos) => {
      // Update with each new reading for better accuracy
      form.value.latitude = pos.coords.latitude
      form.value.longitude = pos.coords.longitude
      gpsAccuracy.value = Math.round(pos.coords.accuracy)
      form.value.location_accuracy = gpsAccuracy.value
      form.value.location_source = 'gps_high_accuracy'

      // Update progress based on accuracy
      if (pos.coords.accuracy <= 10) {
        gpsProgress.value = 100
        gpsStatusMessage.value = 'High precision location acquired!'
        stopLocationDetection() // Got good enough accuracy
      } else if (pos.coords.accuracy <= 30) {
        gpsProgress.value = 90
        gpsStatusMessage.value = 'Good accuracy, refining...'
      } else {
        gpsProgress.value = 70
        gpsStatusMessage.value = 'Low accuracy, waiting for better signal...'
      }
    },
    (error) => {
      handleLocationError(error)
    },
    {
      enableHighAccuracy: true,
      timeout: 30000,
      maximumAge: 0
    }
  )

  // Also get a single high-accuracy position as fallback
  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      form.value.latitude = pos.coords.latitude
      form.value.longitude = pos.coords.longitude
      gpsAccuracy.value = Math.round(pos.coords.accuracy)
      form.value.location_accuracy = gpsAccuracy.value
      form.value.location_source = 'gps_single'
      
      await reverseGeocode(pos.coords.latitude, pos.coords.longitude)
      locationValidated.value = true
      locationError.value = ''
      
      // Stop watching if we already have good accuracy
      if (pos.coords.accuracy <= 30) {
        stopLocationDetection()
      }
    },
    handleLocationError,
    {
      enableHighAccuracy: true,
      timeout: 30000,
      maximumAge: 0
    }
  )
}

function stopLocationDetection() {
  if (watchId) {
    navigator.geolocation.clearWatch(watchId)
    watchId = null
  }
  if (progressInterval) {
    clearInterval(progressInterval)
    progressInterval = null
  }
  detectingLocation.value = false
}

function handleLocationError(error) {
  stopLocationDetection()
  
  switch(error.code) {
    case error.PERMISSION_DENIED:
      locationError.value = 'Location permission denied. Please enter address manually.'
      break
    case error.POSITION_UNAVAILABLE:
      locationError.value = 'GPS signal unavailable. Please check your location settings.'
      break
    case error.TIMEOUT:
      locationError.value = 'GPS timed out. Please try again or enter manually.'
      break
    default:
      locationError.value = 'Could not detect location. Please enter address manually.'
  }
}

async function reverseGeocode(lat, lng) {
  try {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`
    const res = await fetch(url, {
      credentials: 'omit',
      headers: { 
        'Accept': 'application/json',
        'User-Agent': 'ChennaiSmartCare/1.0'
      },
    })
    const data = await res.json()
    const addr = data.address ?? {}
    
    // Build comprehensive address
    const road = addr.road || addr.pedestrian || addr.street || ''
    const suburb = addr.suburb || addr.neighbourhood || addr.suburb || ''
    const city = addr.city || addr.town || addr.village || addr.county || ''
    const postcode = addr.postcode || ''
    const state = addr.state || ''
    
    // Format address nicely
    const addressParts = [road, suburb, city, state].filter(Boolean)
    form.value.address = addressParts.join(', ')
    form.value.area = suburb || city || ''
    form.value.pincode = postcode
    
  } catch (error) {
    console.warn('Reverse geocoding failed:', error)
    // Still mark as validated even if reverse geocoding fails
    form.value.address = `Location at ${lat.toFixed(6)}, ${lng.toFixed(6)}`
  }
}

function validatePincode(e) {
  form.value.pincode = e.target.value.replace(/\D/g, '').slice(0, 6)
}

// ---- Service Methods ----
function toggleService(service) {
  const idx = form.value.services.findIndex(s => s.id === service.id)
  if (idx >= 0) {
    form.value.services.splice(idx, 1)
  } else {
    form.value.services.push({
      id:       service.id,
      name:     service.name,
      price:    service.effective_price ?? service.base_price,
      quantity: 1,
    })
  }
}

// ---- Date/Time Methods ----
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
  } catch {
    timeSlots.value = []
  } finally {
    loadingSlots.value = false
  }
}

// ---- Navigation ----
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

// ---- Submission ----
async function submitBooking() {
  submitting.value = true
  errors.value     = {}
  try {
    const res = await axios.post('/api/v1/bookings/quick', form.value)
    bookingNumber.value  = res.data.booking_number
    bookingSuccess.value = true
    window.scrollTo({ top: 0, behavior: 'smooth' })
    
    if (window.dataLayer) {
      window.dataLayer.push({
        event: 'booking_completed',
        booking_number: res.data.booking_number,
        total_value: totalPrice.value,
      })
    }
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value  = err.response.data.errors ?? {}
      currentStep.value = 1
    } else {
      alert('Something went wrong. Please try again.')
    }
  } finally {
    submitting.value = false
  }
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

// ---- Share ----
function shareBooking() {
  if (navigator.share) {
    navigator.share({
      title: 'My Booking with Chennai Smart Care',
      text: `Booking #${bookingNumber.value} confirmed. Track at: ${window.location.origin}/track/${bookingNumber.value}`,
    })
  } else {
    navigator.clipboard.writeText(`${window.location.origin}/track/${bookingNumber.value}`)
    alert('Booking link copied to clipboard!')
  }
}

// Clean up on component unmount
onUnmounted(() => {
  stopLocationDetection()
})
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

@keyframes bounceSlow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
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

.animate-bounce-slow {
  animation: bounceSlow 2s ease-in-out infinite;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
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

/* Responsive adjustments */
@media (max-width: 640px) {
  .form-input {
    @apply py-2.5;
  }
}
</style>