<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <Head><title>My Dashboard — Chennai Smart Care</title></Head>
    <AppHeader :minimal="true" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">

      <!-- Welcome Section with Quick Actions -->
      <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in-down">
        <div class="flex items-center gap-4">
          <div class="relative">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-blue-200">
              {{ userInitials }}
            </div>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
          </div>
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
              Welcome back, <span class="text-blue-600">{{ firstName }}</span>!
            </h1>
            <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
              <CalendarIcon class="w-4 h-4" />
              {{ currentDate }}
            </p>
          </div>
        </div>
        
        <div class="flex flex-wrap gap-3 w-full sm:w-auto">
          <a href="bookings/create"
             class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-3 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-blue-200 hover:shadow-xl hover:-translate-y-0.5">
            <PlusIcon class="w-4 h-4" />
            Book a Service
          </a>
          <button @click="showQuickActions = !showQuickActions"
                  class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-all">
            <EllipsisHorizontalIcon class="w-4 h-4" />
            Quick Actions
          </button>
        </div>
      </div>

      <!-- Quick Actions Dropdown -->
      <Transition
        enter-active-class="transform transition duration-200 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transform transition duration-150 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div v-if="showQuickActions" class="mb-6 bg-white rounded-xl border border-gray-200 p-4 shadow-lg">
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="/user/profile" class="flex flex-col items-center p-3 hover:bg-gray-50 rounded-xl transition-colors">
              <UserIcon class="w-5 h-5 text-blue-600 mb-1" />
              <span class="text-xs font-medium">Profile</span>
            </a>
            <a :href="isDisabled ? null : '/user/warranties'"
              @click.prevent="isDisabled && $event.preventDefault()"
              :class="[
                'flex flex-col items-center p-3 rounded-xl transition-colors',
                isDisabled
                  ? 'text-gray-400 cursor-not-allowed pointer-events-none'
                  : 'hover:bg-gray-50'
              ]"
            >
              <ShieldCheckIcon class="w-5 h-5 mb-1" />
              <span class="text-xs font-medium">Warranties</span>
            </a>
            <a :href="isDisabled ? null : '/user/invoices'"
              @click.prevent="isDisabled && $event.preventDefault()"
              :class="[
                'flex flex-col items-center p-3 rounded-xl transition-colors',
                isDisabled
                  ? 'text-gray-400 cursor-not-allowed pointer-events-none'
                  : 'hover:bg-gray-50'
              ]"
            >
              <DocumentTextIcon class="w-5 h-5 text-purple-600 mb-1" />
              <span class="text-xs font-medium">Invoices</span>
            </a>
            <a :href="isDisabled ? null : '/user/support'"
              @click.prevent="isDisabled && $event.preventDefault()"
              :class="[
                'flex flex-col items-center p-3 rounded-xl transition-colors',
                isDisabled
                  ? 'text-gray-400 cursor-not-allowed pointer-events-none'
                  : 'hover:bg-gray-50'
              ]"
            >
              <ChatBubbleLeftRightIcon class="w-5 h-5 text-orange-600 mb-1" />
              <span class="text-xs font-medium">Support</span>
            </a>
          </div>
        </div>
      </Transition>

      <!-- Stats Cards with Icons -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div v-for="stat in stats" :key="stat.label"
             class="bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-lg transition-all hover:-translate-y-0.5 animate-slide-in">
          <div class="flex items-center justify-between mb-3">
            <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', stat.bgColor]">
              <component :is="stat.icon" class="w-5 h-5" :class="stat.iconColor" />
            </div>
            <span v-if="stat.trend" class="text-xs font-medium" :class="stat.trendColor">
              {{ stat.trend }}
            </span>
          </div>
          <p class="text-2xl font-extrabold text-gray-900">{{ stat.value }}</p>
          <p class="text-xs text-gray-500 mt-1">{{ stat.label }}</p>
        </div>
      </div>

      <!-- AMC Section (Active or Progress) -->
      <div class="mb-8 animate-fade-in-up">
        <!-- Active AMC Banner -->
        <div v-if="amc?.is_active"
             class="relative overflow-hidden bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-xl">
          <!-- Animated background -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 1px); background-size: 40px 40px;"></div>
          </div>
          
          <div class="relative">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                  <TrophyIcon class="w-8 h-8 text-white" />
                </div>
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold bg-white/30 px-2 py-1 rounded-full uppercase tracking-wider">
                      {{ amc.type === 'free' ? '🎁 FREE AMC' : '⭐ PREMIUM AMC' }}
                    </span>
                    <span class="font-mono text-xs text-emerald-200">{{ amc.amc_number }}</span>
                  </div>
                  <h3 class="text-xl font-bold">Annual Maintenance Contract</h3>
                  <p class="text-emerald-200 text-sm mt-1 flex items-center gap-2">
                    <CalendarIcon class="w-4 h-4" />
                    Valid till {{ formatDate(amc.expires_at) }} · <strong>{{ amc.days_remaining }} days left</strong>
                  </p>
                </div>
              </div>

              <!-- AMC Benefits Chips -->
              <div class="flex flex-wrap gap-2">
                <span class="bg-white/15 backdrop-blur-sm text-white text-xs font-semibold px-3 py-2 rounded-full flex items-center gap-1">
                  <WrenchIcon class="w-3 h-3" />
                  {{ amc.free_services_remaining }}/{{ amc.free_services_total }} Free Calls
                </span>
                <span class="bg-white/15 backdrop-blur-sm text-white text-xs font-semibold px-3 py-2 rounded-full flex items-center gap-1">
                  <CurrencyRupeeIcon class="w-3 h-3" />
                  {{ amc.discount_percent }}% Off Repairs
                </span>
                <span class="bg-white/15 backdrop-blur-sm text-white text-xs font-semibold px-3 py-2 rounded-full flex items-center gap-1">
                  <BoltIcon class="w-3 h-3" />
                  Priority Booking
                </span>
              </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
              <div class="flex justify-between text-xs text-emerald-200 mb-2">
                <span>AMC Period Progress</span>
                <span>{{ amc.progress_percent }}% elapsed</span>
              </div>
              <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white/70 rounded-full transition-all duration-500 relative"
                     :style="`width:${amc.progress_percent}%`">
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/30"></div>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-4 flex gap-3">
              <button @click="viewAmcDetails" class="text-sm text-white/80 hover:text-white transition-colors flex items-center gap-1">
                View Details <ArrowRightIcon class="w-3 h-3" />
              </button>
              <span class="text-white/30">|</span>
              <button @click="bookWithAmc" class="text-sm text-white/80 hover:text-white transition-colors flex items-center gap-1">
                Book with AMC <ArrowRightIcon class="w-3 h-3" />
              </button>
            </div>
          </div>
        </div>

        <!-- AMC Unlock Progress -->
        <div v-else class="bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg transition-all">
          <div class="flex flex-col lg:flex-row items-start gap-6">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center flex-shrink-0">
              <TrophyIcon class="w-8 h-8 text-amber-500" />
            </div>
            
            <div class="flex-1">
              <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                <h3 class="text-lg font-bold text-gray-900">Unlock Free Annual Maintenance Contract</h3>
                <div class="bg-amber-50 text-amber-700 text-sm font-bold px-4 py-2 rounded-full">
                  ₹{{ formatCurrency(currentYearSpend) }} / ₹5,000
                </div>
              </div>
              
              <p class="text-gray-600 text-sm mb-4">
                <span v-if="spendToUnlockAmc > 0">
                  Spend <strong class="text-gray-900">₹{{ formatCurrency(spendToUnlockAmc) }} more</strong> this year to unlock a 
                  <strong class="text-emerald-600">FREE 1-year AMC worth ₹1,499!</strong>
                </span>
                <span v-else class="text-emerald-600 font-semibold flex items-center gap-2">
                  <CheckCircleIcon class="w-5 h-5" />
                  🎉 Congratulations! You've qualified for a free AMC. Contact us to activate.
                </span>
              </p>

              <!-- Progress Bar -->
              <div class="mb-4">
                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-700 relative"
                       :style="`width:${Math.min(spendProgressPct, 100)}%`">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/30"></div>
                  </div>
                </div>
              </div>

              <!-- Benefits Preview -->
              <div class="flex flex-wrap gap-2">
                <span v-for="b in amcBenefits" :key="b"
                      class="text-xs bg-gray-50 text-gray-600 border border-gray-200 px-3 py-1.5 rounded-full flex items-center gap-1">
                  <CheckIcon class="w-3 h-3 text-green-500" />
                  {{ b }}
                </span>
              </div>
            </div>
          </div>

          <!-- Paid AMC Option -->
          <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
              <InformationCircleIcon class="w-4 h-4 inline mr-1 text-gray-400" />
              Don't want to wait? Get AMC instantly for <strong class="text-gray-900">₹1,499/year</strong>
            </p>
            <a href="https://wa.me/919444900470?text=I'd+like+to+subscribe+to+AMC"
               target="_blank"
               class="inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-800 transition-all hover:-translate-y-0.5 shadow-lg">
              <ShieldCheckIcon class="w-4 h-4" />
              Subscribe to AMC
              <ArrowRightIcon class="w-3 h-3" />
            </a>
          </div>
        </div>
      </div>

      <!-- Main Dashboard Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Active Bookings -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Active Bookings Card -->
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                  <CalendarIcon class="w-4 h-4 text-blue-600" />
                </div>
                <h2 class="font-bold text-gray-900">Active Bookings</h2>
                <span v-if="activeBookings.length" 
                      class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">
                  {{ activeBookings.length }}
                </span>
              </div>
              <a href="bookings/create" 
                 class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                New Booking
                <PlusIcon class="w-3 h-3" />
              </a>
            </div>

            <div v-if="activeBookings.length" class="divide-y divide-gray-100">
              <div v-for="booking in activeBookings" :key="booking.id"
                   class="relative group">
                <a :href="`/user/bookings/${booking.booking_number}`"
                   class="block px-6 py-4 hover:bg-gray-50 transition-all">
                  <div class="flex items-start gap-4">
                    <!-- Status Timeline Indicator -->
                    <div class="relative mt-1">
                      <div class="w-2 h-2 rounded-full" :class="statusColor(booking.status)"></div>
                      <div class="absolute top-3 left-1 w-0.5 h-12 bg-gray-200 -translate-x-1/2"></div>
                    </div>

                    <div class="flex-1 min-w-0">
                      <div class="flex items-start justify-between gap-4">
                        <div>
                          <p class="font-semibold text-gray-900 text-base">
                            {{ booking.items?.map(i => i.service_name).join(', ') }}
                          </p>
                          <p class="text-gray-400 text-xs mt-1 font-mono">{{ booking.booking_number }}</p>
                        </div>
                        <StatusBadge :status="booking.status" size="md" />
                      </div>

                      <div class="flex flex-wrap items-center gap-4 mt-3 text-sm">
                        <div class="flex items-center gap-1 text-gray-500">
                          <CalendarIcon class="w-4 h-4" />
                          {{ formatDate(booking.booking_date) }}
                        </div>
                        <div class="flex items-center gap-1 text-gray-500">
                          <ClockIcon class="w-4 h-4" />
                          {{ booking.time_slot }}
                        </div>
                        <div v-if="booking.technician" class="flex items-center gap-1 text-gray-500">
                          <UserIcon class="w-4 h-4" />
                          {{ booking.technician.name }}
                        </div>
                      </div>

                      <!-- Progress Bar for In-Progress Bookings -->
                      <div v-if="booking.status === 'in_progress'" class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                          <span>Service in progress</span>
                          <span>Estimated: {{ booking.estimated_completion }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                          <div class="h-full bg-blue-500 rounded-full" style="width: 60%"></div>
                        </div>
                      </div>
                    </div>

                    <ChevronRightIcon class="w-5 h-5 text-gray-300 group-hover:text-gray-400 transition-colors flex-shrink-0" />
                  </div>
                </a>
              </div>
            </div>

            <div v-else class="text-center py-12 px-6">
              <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <CalendarIcon class="w-8 h-8 text-gray-300" />
              </div>
              <p class="text-gray-500 font-medium">No active bookings</p>
              <p class="text-xs text-gray-400 mt-1 mb-4">Ready to book your next service?</p>
              <a href="bookings/create"
                 class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                <PlusIcon class="w-4 h-4" />
                Book a Service
              </a>
            </div>
          </div>

          <!-- Service History -->
          <div v-if="pastBookings.length" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                  <ClockIcon class="w-4 h-4 text-gray-600" />
                </div>
                <h2 class="font-bold text-gray-900">Service History</h2>
              </div>
            </div>

            <div class="divide-y divide-gray-100">
              <div v-for="booking in pastBookings.slice(0, 5)" :key="booking.id"
                   class="group hover:bg-gray-50 transition-colors">
                <a :href="`/user/bookings/${booking.booking_number}`"
                   class="flex items-center gap-4 px-6 py-4">
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3">
                      <p class="font-medium text-gray-900 text-sm truncate">
                        {{ booking.items?.map(i => i.service_name).join(', ') }}
                      </p>
                      <span v-if="booking.status === 'completed'"
                            class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <ShieldCheckIcon class="w-3 h-3" />
                        Warranty Active
                      </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ formatDate(booking.booking_date) }}</p>
                  </div>
                  <StatusBadge :status="booking.status" size="sm" />
                  <ChevronRightIcon class="w-4 h-4 text-gray-300 group-hover:text-gray-400" />
                </a>
              </div>
            </div>

            <div v-if="pastBookings.length > 5" class="px-6 py-3 border-t border-gray-100 text-center">
              <a href="/user/history" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                View All History ({{ pastBookings.length }})
              </a>
            </div>
          </div>
        </div>

        <!-- Right Column - Warranties & Loyalty -->
        <div class="space-y-6">
          <!-- Loyalty Points Card -->
          <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-start justify-between mb-4">
              <div>
                <p class="text-purple-200 text-sm font-medium">Your Balance</p>
                <p class="text-3xl font-bold mt-1">{{ props.user?.loyalty_points ?? 0 }}</p>
                <p class="text-purple-200 text-xs mt-1">Loyalty Points</p>
              </div>
              <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <StarIcon class="w-6 h-6 text-yellow-300" />
              </div>
            </div>

            <div class="space-y-3">
              <div class="bg-white/10 rounded-xl p-3">
                <p class="text-xs text-purple-200 mb-1">Next Reward at 500 points</p>
                <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" :style="`width:${loyaltyProgress}%`"></div>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-2">
                <div class="bg-white/10 rounded-xl p-2 text-center">
                  <p class="text-lg font-bold">₹{{ pointsValue }}</p>
                  <p class="text-xs text-purple-200">Points Value</p>
                </div>
                <div class="bg-white/10 rounded-xl p-2 text-center">
                  <p class="text-lg font-bold">{{ pointsToNextReward }}</p>
                  <p class="text-xs text-purple-200">To Next Reward</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Warranties Card -->
          <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <ShieldCheckIcon class="w-5 h-5 text-blue-600" />
                  <h2 class="font-bold text-gray-900">My Warranties</h2>
                </div>
                <span v-if="activeWarrantyCount"
                      class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">
                  {{ activeWarrantyCount }} Active
                </span>
              </div>
            </div>

            <div v-if="warranties?.length" class="divide-y divide-gray-100">
              <div v-for="w in warranties.slice(0, 3)" :key="w.id" class="p-5">
                <div class="flex items-start gap-3 mb-3">
                  <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <ShieldCheckIcon class="w-5 h-5 text-green-600" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                      <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                        {{ w.warranty_number }}
                      </span>
                      <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                            :class="w.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
                        {{ w.is_active ? 'Active' : 'Expired' }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 truncate">{{ w.appliances_covered }}</p>
                  </div>
                  <p v-if="w.is_active" class="text-xl font-bold text-green-600">{{ w.days_remaining }}d</p>
                </div>

                <!-- Warranty Progress -->
                <div v-if="w.is_active" class="mt-2">
                  <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>{{ formatDate(w.starts_at) }}</span>
                    <span>{{ formatDate(w.expires_at) }}</span>
                  </div>
                  <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" :style="`width:${w.progress_percent}%`"></div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="text-center py-10 px-6">
              <ShieldCheckIcon class="w-10 h-10 text-gray-300 mx-auto mb-3" />
              <p class="text-sm text-gray-500">No warranties yet</p>
              <p class="text-xs text-gray-400 mt-1">Warranties appear after completed services</p>
            </div>

            <div v-if="warranties?.length > 3" class="px-6 py-3 border-t border-gray-100 text-center">
              <a href="/warranties" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                View All Warranties
              </a>
            </div>
          </div>

          <!-- Quick Tips Card -->
          <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
            <div class="flex items-center gap-2 mb-3">
              <LightBulbIcon class="w-5 h-5 text-blue-600" />
              <h3 class="font-semibold text-gray-900">Pro Tips</h3>
            </div>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2 text-gray-600">
                <CheckCircleIcon class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" />
                <span>Book before 2 PM for same-day service</span>
              </li>
              <li class="flex items-start gap-2 text-gray-600">
                <CheckCircleIcon class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" />
                <span>Earn 1 point for every ₹100 spent</span>
              </li>
              <li class="flex items-start gap-2 text-gray-600">
                <CheckCircleIcon class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" />
                <span>Share your referral code for bonus points</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import {
  PlusIcon, ShieldCheckIcon, ChevronRightIcon, TrophyIcon, 
  DocumentTextIcon, CalendarIcon, ClockIcon, UserIcon,
  EllipsisHorizontalIcon, StarIcon, CheckCircleIcon,
  ArrowRightIcon, CurrencyRupeeIcon, BoltIcon, WrenchIcon,
  ChatBubbleLeftRightIcon, LightBulbIcon, InformationCircleIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'
import AppHeader   from '@/components/Landing/AppHeader.vue'
import AppFooter   from '@/components/Landing/AppFooter.vue'
import StatusBadge from '@/components/Shared/StatusBadge.vue'

const props = defineProps({
  user:               { type: Object, required: true },
  bookings:           { type: Array,  default: () => [] },
  warranties:         { type: Array,  default: () => [] },
  amc:                { type: Object, default: null },
  currentYearSpend:   { type: Number, default: 0 },
  spendToUnlockAmc:   { type: Number, default: 5000 },
  spendProgressPct:   { type: Number, default: 0 },
  amcThreshold:       { type: Number, default: 5000 },
  amcPaidPrice:       { type: Number, default: 1499 },
})

const showQuickActions = ref(false)

const activeStatuses  = ['pending', 'confirmed', 'assigned', 'in_progress', 'rescheduled']
const activeBookings  = computed(() => props.bookings.filter(b => activeStatuses.includes(b.status)))
const pastBookings    = computed(() => props.bookings.filter(b => !activeStatuses.includes(b.status)))
const firstName       = computed(() => props.user?.name?.split(' ')[0] ?? 'there')
const activeWarrantyCount = computed(() => props.warranties?.filter(w => w.is_active).length ?? 0)

const userInitials = computed(() => {
  const name = props.user?.name ?? 'User'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const currentDate = computed(() => {
  return new Date().toLocaleDateString('en-IN', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
})

// Stats with icons and colors
const stats = computed(() => [
  { 
    value: props.bookings.length, 
    label: 'Total Services', 
    icon: DocumentTextIcon,
    bgColor: 'bg-blue-50',
    iconColor: 'text-blue-600'
  },
  { 
    value: activeBookings.value.length, 
    label: 'Active', 
    icon: ClockIcon,
    bgColor: 'bg-orange-50',
    iconColor: 'text-orange-600',
    trend: '+2 this week',
    trendColor: 'text-green-600'
  },
  { 
    value: props.bookings.filter(b => b.status === 'completed').length, 
    label: 'Completed', 
    icon: CheckCircleIcon,
    bgColor: 'bg-green-50',
    iconColor: 'text-green-600'
  },
  { 
    value: props.user?.loyalty_points ?? 0, 
    label: 'Loyalty Points', 
    icon: StarIcon,
    bgColor: 'bg-purple-50',
    iconColor: 'text-purple-600'
  },
])

const amcBenefits = [
  '2 Free Service Calls/year',
  '15% Off All Repairs',
  'Priority Booking',
  '6-Month Warranties',
]

// Loyalty calculations
const loyaltyProgress = computed(() => {
  const points = props.user?.loyalty_points ?? 0
  return Math.min((points / 500) * 100, 100)
})

const pointsValue = computed(() => {
  return Math.floor((props.user?.loyalty_points ?? 0) * 0.5) // 1 point = ₹0.5 value
})

const pointsToNextReward = computed(() => {
  return Math.max(0, 500 - (props.user?.loyalty_points ?? 0))
})

// Status color mapping
const statusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-500',
    confirmed: 'bg-blue-500',
    assigned: 'bg-purple-500',
    in_progress: 'bg-orange-500',
    rescheduled: 'bg-indigo-500',
    completed: 'bg-green-500',
    cancelled: 'bg-red-500'
  }
  return colors[status] || 'bg-gray-500'
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' })
}

function formatCurrency(n) {
  return Number(n).toLocaleString('en-IN')
}

function viewAmcDetails() {
  // Navigate to AMC details page
}

function bookWithAmc() {
  // Navigate to booking with AMC benefits
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