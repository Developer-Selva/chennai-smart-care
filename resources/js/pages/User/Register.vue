<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <!-- Logo with animated entrance -->
      <div class="text-center mb-8 animate-fade-in-down">
        <a href="/" class="inline-flex flex-col items-center gap-3 group">
          <div class="relative">
            <img
              src="/images/chennai_smart_care.png"
              alt="Chennai Smart Care — Expert Appliance Repair"
              class="h-20 w-auto object-contain transition-all duration-300 group-hover:scale-105"/>
            <div class="absolute -inset-1 bg-blue-500/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          <span class="font-bold text-gray-900 text-lg tracking-tight">Chennai Smart Care</span>
        </a>
        
        <div class="mt-8 space-y-2">
          <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create your account</h1>
          <p class="text-gray-500 text-sm">Join 10,000+ Chennai families who trust us</p>
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 animate-fade-in-up">
        <!-- Progress Steps -->
        <div class="mb-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
              <span class="text-sm font-medium text-gray-900">Account Details</span>
            </div>
            <div class="w-16 h-0.5 bg-gray-200"></div>
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-sm font-bold">2</div>
              <span class="text-sm font-medium text-gray-400">Verify</span>
            </div>
          </div>
        </div>

        <!-- Flash error/success messages -->
        <Transition
          enter-active-class="transform ease-out duration-300 transition"
          enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
          enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
          leave-active-class="transition ease-in duration-100"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div v-if="$page.props.flash?.error || $page.props.flash?.success"
               :class="['mb-6 px-4 py-3 rounded-xl text-sm flex items-center gap-3 shadow-sm', 
                        $page.props.flash?.error ? 'bg-gradient-to-r from-red-50 to-red-100/50 border border-red-200 text-red-700' : 
                        'bg-gradient-to-r from-green-50 to-green-100/50 border border-green-200 text-green-700']">
            <div :class="['w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0',
                         $page.props.flash?.error ? 'bg-red-100' : 'bg-green-100']">
              <ExclamationCircleIcon v-if="$page.props.flash?.error" class="w-4 h-4 text-red-600" />
              <CheckCircleIcon v-else class="w-4 h-4 text-green-600" />
            </div>
            <span class="flex-1">{{ $page.props.flash?.error || $page.props.flash?.success }}</span>
            <button @click="$page.props.flash = null" class="opacity-50 hover:opacity-100">
              <XMarkIcon class="w-4 h-4" />
            </button>
          </div>
        </Transition>

        <!-- Registration Form -->
        <form @submit.prevent="submit" class="space-y-5">
          <!-- Full Name -->
          <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700">
              Full Name
              <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <UserIcon class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                v-model="form.name" 
                type="text" 
                placeholder="e.g. Rajesh Kumar"
                class="form-input pl-10"
                :class="{ 'border-red-300 focus:ring-red-500': form.errors.name }"
                autofocus 
                @input="validateName"
              />
            </div>
            <Transition name="fade">
              <p v-if="form.errors.name" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                {{ form.errors.name }}
              </p>
            </Transition>
          </div>

          <!-- Phone Number -->
          <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700">
              Phone Number
              <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="flex">
              <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">
                +91
              </span>
              <input 
                v-model="form.phone" 
                type="tel" 
                maxlength="10" 
                placeholder="98765 43210"
                class="form-input rounded-l-none flex-1"
                :class="{ 'border-red-300 focus:ring-red-500': form.errors.phone }"
                @input="validatePhone"
              />
            </div>
            <Transition name="fade">
              <p v-if="form.errors.phone" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                {{ form.errors.phone }}
              </p>
            </Transition>
          </div>

          <!-- Email Address -->
          <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700">
              Email Address
              <span class="text-gray-400 text-xs font-normal ml-1">(optional)</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <EnvelopeIcon class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                v-model="form.email" 
                type="email" 
                placeholder="you@example.com"
                class="form-input pl-10"
                :class="{ 'border-red-300 focus:ring-red-500': form.errors.email }"
                @input="validateEmail"
              />
            </div>
            <Transition name="fade">
              <p v-if="form.errors.email" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                {{ form.errors.email }}
              </p>
            </Transition>
          </div>

          <!-- Password -->
          <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700">
              Password
              <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <LockClosedIcon class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Min. 8 characters"
                class="form-input pl-10 pr-12"
                :class="{ 'border-red-300 focus:ring-red-500': form.errors.password }"
                @input="validatePassword"
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button 
                  type="button" 
                  @click="showPassword = !showPassword"
                  class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-blue-600 transition-colors"
                  :aria-label="showPassword ? 'Hide password' : 'Show password'"
                >
                  <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                  <EyeSlashIcon v-else class="h-5 w-5" />
                </button>
              </div>
            </div>

            <!-- Password strength meter -->
            <div v-if="form.password" class="mt-2 space-y-1">
              <div class="flex gap-1">
                <div v-for="i in 4" :key="i"
                     :class="['h-1.5 flex-1 rounded-full transition-all duration-300', 
                              i <= passwordStrength ? strengthColorClasses[i-1] : 'bg-gray-200']">
                </div>
              </div>
              <div class="flex items-center justify-between">
                <p class="text-xs" :class="strengthTextColor">{{ strengthLabel }}</p>
              </div>
            </div>

            <Transition name="fade">
              <p v-if="form.errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                {{ form.errors.password }}
              </p>
            </Transition>
          </div>

          <!-- Confirm Password -->
          <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700">
              Confirm Password
              <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <LockClosedIcon class="h-5 w-5 text-gray-400" />
              </div>
              <input 
                v-model="form.password_confirmation"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Repeat password"
                class="form-input pl-10 pr-12"
                :class="{ 'border-red-300 focus:ring-red-500': passwordMismatch }"
                @input="checkPasswordMatch"
              />
              <!-- Password match indicator -->
              <div v-if="form.password_confirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <CheckCircleIcon v-if="passwordsMatch" class="h-5 w-5 text-green-500" />
                <XCircleIcon v-else class="h-5 w-5 text-red-500" />
              </div>
            </div>
            <Transition name="fade">
              <p v-if="passwordMismatch" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                Passwords do not match
              </p>
            </Transition>
          </div>

          <!-- Password requirements checklist -->
          <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
            <p class="text-xs font-medium text-gray-700 mb-2">Password must contain:</p>
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

          <!-- Terms agreement -->
          <div class="flex items-start gap-3">
            <input 
              v-model="form.agreeTerms" 
              type="checkbox"
              class="mt-1 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 transition-colors cursor-pointer"
            />
            <p class="text-xs text-gray-500">
              I agree to the 
              <a href="/privacy" class="text-blue-600 hover:underline">Privacy Policy</a> and 
              <a href="/terms" class="text-blue-600 hover:underline">Terms of Service</a>.
              You'll receive booking updates via SMS/WhatsApp.
            </p>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="form.processing || !isFormValid || !form.agreeTerms"
            class="relative w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3.5 rounded-xl font-semibold text-sm hover:from-blue-700 hover:to-blue-800 transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg shadow-blue-600/25 overflow-hidden group mt-6"
          >
            <span class="relative z-10 flex items-center justify-center gap-2">
              <UserPlusIcon v-if="!form.processing" class="w-4 h-4" />
              <svg v-else class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ form.processing ? 'Creating account...' : 'Create Account' }}
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-0 group-hover:opacity-20 transition-opacity"></div>
          </button>

          <!-- Benefits list -->
          <div class="grid grid-cols-3 gap-2 pt-4">
            <div v-for="benefit in benefits" :key="benefit.text" 
                 class="text-center">
              <div class="w-8 h-8 mx-auto bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mb-1">
                <component :is="benefit.icon" class="w-4 h-4" />
              </div>
              <p class="text-xs text-gray-600">{{ benefit.text }}</p>
            </div>
          </div>
        </form>

        <!-- Sign In Link -->
        <div class="relative my-8">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-400">Already have an account?</span>
          </div>
        </div>

        <div class="text-center">
          <a href="/user/login" 
             class="inline-flex items-center justify-center gap-2 w-full bg-gray-50 text-gray-700 py-3 px-4 rounded-xl font-medium text-sm hover:bg-gray-100 border border-gray-200 transition-all hover:border-gray-300 group">
            <ArrowRightOnRectangleIcon class="w-4 h-4 text-gray-400 group-hover:text-gray-600" />
            Sign in to existing account
            <ArrowRightIcon class="w-3 h-3 ml-auto text-gray-400 group-hover:text-gray-600 group-hover:translate-x-0.5 transition-transform" />
          </a>
        </div>

        <!-- Trust Badges -->
        <div class="mt-6 flex items-center justify-center gap-4 text-xs text-gray-400">
          <span class="flex items-center gap-1">
            <ShieldCheckIcon class="w-3 h-3" />
            SSL Secured
          </span>
          <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
          <span class="flex items-center gap-1">
            <LockClosedIcon class="w-3 h-3" />
            Encrypted
          </span>
          <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
          <span class="flex items-center gap-1">
            <DevicePhoneMobileIcon class="w-3 h-3" />
            OTP Verification
          </span>
        </div>
      </div>

      <!-- Back to Home -->
      <p class="text-center mt-8">
        <a href="/" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors group">
          <ArrowLeftIcon class="w-3 h-3 group-hover:-translate-x-0.5 transition-transform" />
          Back to Home
        </a>
      </p>

      <!-- Help Link -->
      <p class="text-center mt-4 text-xs text-gray-400">
        Need help? <a href="/contact" class="text-gray-500 hover:text-blue-600 underline underline-offset-2">Contact Support</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { 
  EyeIcon, 
  EyeSlashIcon, 
  UserIcon,
  EnvelopeIcon,
  LockClosedIcon,
  ExclamationCircleIcon,
  CheckCircleIcon,
  XCircleIcon,
  CheckIcon,
  XMarkIcon,
  UserPlusIcon,
  ArrowRightIcon,
  ArrowLeftIcon,
  ArrowRightOnRectangleIcon,
  ShieldCheckIcon,
  DevicePhoneMobileIcon,
  ClockIcon,
  CurrencyRupeeIcon,
  WrenchIcon
} from '@heroicons/vue/24/outline'

const showPassword = ref(false)
const page = usePage()

const form = useForm({
  name: '',
  phone: '',
  email: '',
  password: '',
  password_confirmation: '',
  agreeTerms: false
})

// Validation methods
const validateName = (e) => {
  form.name = e.target.value.replace(/[^a-zA-Z\s]/g, '')
}

const validatePhone = (e) => {
  form.phone = e.target.value.replace(/\D/g, '').slice(0, 10)
}

const validateEmail = (e) => {
  // Basic email validation
  form.email = e.target.value.toLowerCase().trim()
}

const validatePassword = (e) => {
  form.password = e.target.value
}

const checkPasswordMatch = (e) => {
  form.password_confirmation = e.target.value
}

// Computed properties
const passwordMismatch = computed(() => {
  return form.password_confirmation && form.password !== form.password_confirmation
})

const passwordsMatch = computed(() => {
  return form.password_confirmation && form.password === form.password_confirmation
})

const isFormValid = computed(() => {
  return form.name && 
         form.phone?.length === 10 && 
         form.password?.length >= 8 && 
         passwordsMatch.value &&
         (!form.email || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))
})

// Password strength
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

// Benefits of registration
const benefits = [
  { icon: ClockIcon, text: 'Track bookings' },
  { icon: CurrencyRupeeIcon, text: 'Loyalty points' },
  { icon: WrenchIcon, text: 'Service history' }
]

function submit() {
  form.post('/user/register', {
    onError: () => {},
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

/* Fix for icon spacing - inputs with left icons */
.pl-10 {
  padding-left: 2.5rem; /* 40px - enough space for icon */
}

/* Fix for inputs with both left and right icons */
.pl-10.pr-12 {
  padding-left: 2.5rem;
  padding-right: 3rem; /* 48px - enough space for right icon */
}

/* Icon container positioning */
.absolute.inset-y-0 {
  display: flex;
  align-items: center;
}

/* Ensure icons don't block input clicks */
.pointer-events-none {
  pointer-events: none;
}

/* Eye button should be clickable */
button.pointer-events-auto {
  pointer-events: auto;
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

/* Custom checkbox */
input[type="checkbox"] {
  @apply rounded border-gray-300 text-blue-600 shadow-sm
         focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50;
}
</style>