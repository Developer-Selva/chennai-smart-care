<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <Head><title>My Profile — Chennai Smart Care</title></Head>
    <AppHeader :minimal="true" />

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-6 sm:py-8">

      <!-- Header with Back Navigation -->
      <div class="flex items-center gap-4 mb-6 animate-fade-in-down">
        <button @click="goBack" 
                class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all shadow-sm border border-gray-200">
          <ArrowLeftIcon class="w-5 h-5" />
        </button>
        <div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">My Profile</h1>
          <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
            <UserIcon class="w-4 h-4" />
            Manage your account details and preferences
          </p>
        </div>
      </div>

      <!-- Success Flash with Animation -->
      <Transition
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transform transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div v-if="$page.props.flash?.success"
             class="flex items-center gap-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-xl px-5 py-4 mb-6 text-sm shadow-sm">
          <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <CheckCircleIcon class="w-5 h-5 text-green-600" />
          </div>
          <span class="flex-1 font-medium">{{ $page.props.flash.success }}</span>
          <button @click="$page.props.flash.success = null" class="text-green-500 hover:text-green-700">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </Transition>

      <!-- Profile Overview Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6 animate-fade-in-up">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-white relative overflow-hidden">
          <!-- Background Pattern -->
          <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 1px); background-size: 30px 30px;"></div>
          </div>
          
          <div class="relative flex items-center gap-5">
            <!-- Avatar with Edit Overlay -->
            <div class="relative group">
              <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-3xl font-bold border-4 border-white/30 shadow-xl">
                {{ initials }}
              </div>
              <button @click="triggerAvatarUpload" 
                      class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-lg hover:bg-blue-50 transition-colors border border-gray-200 opacity-0 group-hover:opacity-100">
                <CameraIcon class="w-4 h-4" />
              </button>
            </div>
            
            <div class="flex-1">
              <h2 class="text-2xl font-bold">{{ form.name }}</h2>
              <div class="flex items-center gap-2 mt-1 text-blue-100">
                <PhoneIcon class="w-4 h-4" />
                <span>{{ form.phone }}</span>
              </div>
              <div class="flex items-center gap-2 mt-1 text-blue-100">
                <EnvelopeIcon class="w-4 h-4" />
                <span>{{ form.email || 'No email provided' }}</span>
              </div>
            </div>
            
            <!-- Member Since Badge -->
            <div class="hidden sm:block bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 text-center">
              <p class="text-xs text-blue-200">Member since</p>
              <p class="font-semibold text-white">{{ joinedDate }}</p>
            </div>
          </div>
        </div>

        <!-- Account Stats -->
        <div class="grid grid-cols-3 divide-x divide-gray-100 bg-gray-50/50">
          <div class="text-center py-4">
            <p class="text-2xl font-bold text-gray-900">{{ userStats.totalBookings }}</p>
            <p class="text-xs text-gray-500">Bookings</p>
          </div>
          <div class="text-center py-4">
            <p class="text-2xl font-bold text-green-600">{{ userStats.completed }}</p>
            <p class="text-xs text-gray-500">Completed</p>
          </div>
          <div class="text-center py-4">
            <p class="text-2xl font-bold text-purple-600">{{ userStats.loyaltyPoints }}</p>
            <p class="text-xs text-gray-500">Points</p>
          </div>
        </div>
      </div>

      <!-- Personal Information Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6 animate-fade-in-up" style="animation-delay: 100ms">
        <div class="px-6 py-5 border-b border-gray-100">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
              <UserIcon class="w-4 h-4 text-blue-600" />
            </div>
            <h2 class="font-bold text-gray-900 text-lg">Personal Information</h2>
          </div>
        </div>

        <div class="p-6 space-y-5">
          <!-- Full Name -->
          <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700 flex items-center gap-1">
              <UserIcon class="w-4 h-4 text-gray-400" />
              Full Name <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input v-model="form.name" type="text" 
                     class="form-input pl-10"
                     :class="{ 'border-red-300 focus:ring-red-500': errors.name }"
                     placeholder="Enter your full name" />
              <UserIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            </div>
            <Transition name="fade">
              <p v-if="errors.name" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                {{ errors.name }}
              </p>
            </Transition>
          </div>

          <!-- Phone & Email Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Phone -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700 flex items-center gap-1">
                <PhoneIcon class="w-4 h-4 text-gray-400" />
                Phone <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <input v-model="form.phone" type="tel" maxlength="10"
                       class="form-input pl-10"
                       :class="{ 'border-red-300 focus:ring-red-500': errors.phone }"
                       placeholder="9876543210" />
                <PhoneIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              </div>
              <Transition name="fade">
                <p v-if="errors.phone" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                  <ExclamationCircleIcon class="w-3 h-3" />
                  {{ errors.phone }}
                </p>
              </Transition>
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700 flex items-center gap-1">
                <EnvelopeIcon class="w-4 h-4 text-gray-400" />
                Email
              </label>
              <div class="relative">
                <input v-model="form.email" type="email" 
                       class="form-input pl-10"
                       :class="{ 'border-red-300 focus:ring-red-500': errors.email }"
                       placeholder="you@example.com" />
                <EnvelopeIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              </div>
              <Transition name="fade">
                <p v-if="errors.email" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                  <ExclamationCircleIcon class="w-3 h-3" />
                  {{ errors.email }}
                </p>
              </Transition>
            </div>
          </div>

          <!-- Address -->
          <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700 flex items-center gap-1">
              <HomeIcon class="w-4 h-4 text-gray-400" />
              Address
            </label>
            <div class="relative">
              <input v-model="form.address" type="text" 
                     class="form-input pl-10"
                     placeholder="House/Flat No, Street, Locality" />
              <HomeIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            </div>
          </div>

          <!-- City & Pincode Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- City -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700 flex items-center gap-1">
                <BuildingOfficeIcon class="w-4 h-4 text-gray-400" />
                City
              </label>
              <div class="relative">
                <input v-model="form.city" type="text" 
                       class="form-input pl-10"
                       placeholder="Chennai" />
                <BuildingOfficeIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              </div>
            </div>

            <!-- Pincode -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700 flex items-center gap-1">
                <MapPinIcon class="w-4 h-4 text-gray-400" />
                Pincode
              </label>
              <div class="relative">
                <input v-model="form.pincode" type="number" maxlength="6" 
                       class="form-input pl-10"
                       placeholder="600001"
                       @input="validatePincode" />
                <MapPinIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Change Password Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6 animate-fade-in-up" style="animation-delay: 200ms">
        <div class="px-6 py-5 border-b border-gray-100">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                <LockClosedIcon class="w-4 h-4 text-purple-600" />
              </div>
              <h2 class="font-bold text-gray-900 text-lg">Change Password</h2>
            </div>
            <button @click="showPasswordFields = !showPasswordFields"
                    class="text-sm font-semibold px-4 py-2 rounded-lg transition-all"
                    :class="showPasswordFields 
                      ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' 
                      : 'bg-blue-50 text-blue-600 hover:bg-blue-100'">
              {{ showPasswordFields ? 'Cancel' : 'Change Password' }}
            </button>
          </div>
        </div>

        <Transition
          enter-active-class="transform transition duration-300 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transform transition duration-200 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div v-if="showPasswordFields" class="p-6 space-y-4">
            <!-- Current Password -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700">Current Password *</label>
              <div class="relative">
                <input v-model="form.current_password" type="password" 
                       class="form-input pl-10 pr-12"
                       :class="{ 'border-red-300 focus:ring-red-500': errors.current_password }"
                       placeholder="Enter current password" />
                <LockClosedIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <button type="button" @click="togglePasswordVisibility('current')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  <EyeIcon v-if="!showCurrentPassword" class="w-4 h-4" />
                  <EyeSlashIcon v-else class="w-4 h-4" />
                </button>
              </div>
              <Transition name="fade">
                <p v-if="errors.current_password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                  <ExclamationCircleIcon class="w-3 h-3" />
                  {{ errors.current_password }}
                </p>
              </Transition>
            </div>

            <!-- New Password -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700">New Password *</label>
              <div class="relative">
                <input v-model="form.password" type="password" 
                       class="form-input pl-10 pr-12"
                       :class="{ 'border-red-300 focus:ring-red-500': errors.password }"
                       placeholder="Min. 8 characters" />
                <LockClosedIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <button type="button" @click="togglePasswordVisibility('new')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  <EyeIcon v-if="!showNewPassword" class="w-4 h-4" />
                  <EyeSlashIcon v-else class="w-4 h-4" />
                </button>
              </div>

              <!-- Password Strength Meter -->
              <div v-if="form.password" class="mt-2">
                <div class="flex gap-1">
                  <div v-for="i in 4" :key="i"
                       :class="['h-1 flex-1 rounded-full transition-colors', 
                                i <= passwordStrength ? strengthColorClasses[i-1] : 'bg-gray-200']">
                  </div>
                </div>
                <p class="text-xs mt-1" :class="strengthTextColor">{{ strengthLabel }}</p>
              </div>

              <Transition name="fade">
                <p v-if="errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                  <ExclamationCircleIcon class="w-3 h-3" />
                  {{ errors.password }}
                </p>
              </Transition>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700">Confirm New Password *</label>
              <div class="relative">
                <input v-model="form.password_confirmation" type="password" 
                       class="form-input pl-10 pr-12"
                       :class="{ 'border-red-300 focus:ring-red-500': passwordMismatch }"
                       placeholder="Repeat new password" />
                <LockClosedIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <div v-if="form.password_confirmation" class="absolute right-3 top-1/2 -translate-y-1/2">
                  <CheckCircleIcon v-if="passwordsMatch" class="w-4 h-4 text-green-500" />
                  <XCircleIcon v-else class="w-4 h-4 text-red-500" />
                </div>
              </div>
              <Transition name="fade">
                <p v-if="passwordMismatch" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                  <ExclamationCircleIcon class="w-3 h-3" />
                  Passwords do not match
                </p>
              </Transition>
            </div>

            <!-- Password Requirements -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
              <p class="text-xs font-medium text-gray-700 mb-2 flex items-center gap-1">
                <InformationCircleIcon class="w-3 h-3" />
                Password must contain:
              </p>
              <div class="grid grid-cols-2 gap-2 text-xs">
                <div v-for="req in passwordRequirements" :key="req.text"
                     class="flex items-center gap-1.5"
                     :class="req.met ? 'text-green-600' : 'text-gray-400'">
                  <CheckIcon v-if="req.met" class="w-3 h-3" />
                  <XMarkIcon v-else class="w-3 h-3" />
                  <span>{{ req.text }}</span>
                </div>
              </div>
            </div>
          </div>
        </Transition>

        <!-- Masked Password Display -->
        <div v-if="!showPasswordFields" class="px-6 py-4 text-gray-400">
          <div class="flex items-center gap-2">
            <LockClosedIcon class="w-4 h-4" />
            <span class="text-sm">••••••••</span>
            <span class="text-xs text-gray-300 ml-2">(hidden)</span>
          </div>
        </div>
      </div>

      <!-- Save Button with Animation -->
      <div class="sticky bottom-4 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 animate-fade-in-up" style="animation-delay: 300ms">
        <button @click="submit" :disabled="saving || !hasChanges"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-4 rounded-xl font-bold text-sm transition-all disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg shadow-blue-200 hover:-translate-y-0.5">
          <span v-if="saving">
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving Changes...
          </span>
          <span v-else class="flex items-center gap-2">
            <CheckIcon class="w-4 h-4" />
            {{ hasChanges ? 'Save Changes' : 'No Changes to Save' }}
          </span>
        </button>
      </div>

    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import {
  ArrowLeftIcon, CheckCircleIcon, CheckIcon, XMarkIcon,
  UserIcon, PhoneIcon, EnvelopeIcon, HomeIcon,
  BuildingOfficeIcon, MapPinIcon, LockClosedIcon,
  CameraIcon, EyeIcon, EyeSlashIcon, ExclamationCircleIcon,
  XCircleIcon, InformationCircleIcon
} from '@heroicons/vue/24/outline'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'

const props  = defineProps({ 
  user: { type: Object, required: true },
  userStats: { type: Object, default: () => ({ totalBookings: 0, completed: 0, loyaltyPoints: 0 }) }
})

const page   = usePage()
const saving = ref(false)
const showPasswordFields = ref(false)

// Password visibility toggles
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)

// Track original values for change detection
const originalForm = reactive({
  name: props.user.name ?? '',
  phone: props.user.phone ?? '',
  email: props.user.email ?? '',
  address: props.user.address ?? '',
  city: props.user.city ?? '',
  pincode: props.user.pincode ?? '',
})

const form = reactive({
  name:                 props.user.name         ?? '',
  phone:                props.user.phone        ?? '',
  email:                props.user.email        ?? '',
  address:              props.user.address      ?? '',
  city:                 props.user.city         ?? '',
  pincode:              props.user.pincode      ?? '',
  current_password:     '',
  password:             '',
  password_confirmation:'',
})

// Check if form has changes
const hasChanges = computed(() => {
  return Object.keys(originalForm).some(key => originalForm[key] !== form[key])
})

const errors   = computed(() => page.props.errors ?? {})

const initials = computed(() => {
  if (!form.name) return 'U'
  return form.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
})

const joinedDate = computed(() =>
  new Date(props.user.created_at).toLocaleDateString('en-IN', { month: 'long', year: 'numeric' })
)

// Password validation
const passwordMismatch = computed(() => {
  return form.password_confirmation && form.password !== form.password_confirmation
})

const passwordsMatch = computed(() => {
  return form.password_confirmation && form.password === form.password_confirmation
})

const passwordStrength = computed(() => {
  const p = form.password
  if (!p) return 0
  let score = 0
  if (p.length >= 8) score++
  if (/[A-Z]/.test(p)) score++
  if (/[0-9]/.test(p)) score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  return score
})

const strengthColorClasses = [
  'bg-red-500',
  'bg-orange-500',
  'bg-yellow-500',
  'bg-green-500'
]

const strengthTextColor = computed(() => {
  const colors = ['text-red-500', 'text-orange-500', 'text-yellow-600', 'text-green-600']
  return colors[passwordStrength.value - 1] ?? 'text-gray-400'
})

const strengthLabel = computed(() => {
  const labels = ['Weak', 'Fair', 'Good', 'Strong']
  return labels[passwordStrength.value - 1] ?? ''
})

const passwordRequirements = computed(() => {
  return [
    { text: '8+ characters', met: form.password?.length >= 8 },
    { text: 'Uppercase letter', met: /[A-Z]/.test(form.password) },
    { text: 'Number', met: /[0-9]/.test(form.password) },
    { text: 'Special character', met: /[^A-Za-z0-9]/.test(form.password) }
  ]
})

// Methods
function goBack() {
  router.visit('/user/dashboard')
}

function triggerAvatarUpload() {
  // Implement avatar upload functionality
  console.log('Avatar upload triggered')
}

function validatePincode(e) {
  form.pincode = e.target.value.replace(/\D/g, '').slice(0, 6)
}

function togglePasswordVisibility(field) {
  if (field === 'current') {
    showCurrentPassword.value = !showCurrentPassword.value
  } else if (field === 'new') {
    showNewPassword.value = !showNewPassword.value
  }
}

function submit() {
  if (!hasChanges.value && !showPasswordFields.value) return
  
  saving.value = true
  router.put('/user/profile', form, {
    preserveScroll: true,
    onFinish: () => { 
      saving.value = false
      // Update original values after save
      if (!errors.value) {
        Object.assign(originalForm, {
          name: form.name,
          phone: form.phone,
          email: form.email,
          address: form.address,
          city: form.city,
          pincode: form.pincode
        })
      }
    },
  })
}

// Reset password fields when toggling off
watch(showPasswordFields, (newVal) => {
  if (!newVal) {
    form.current_password = ''
    form.password = ''
    form.password_confirmation = ''
  }
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

.animate-fade-in-down {
  animation: fadeInDown 0.6s ease-out;
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out;
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

/* Sticky button on mobile */
@media (max-width: 640px) {
  .sticky {
    position: sticky;
    bottom: 1rem;
    z-index: 10;
  }
}
</style>