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
          <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Welcome back</h1>
          <p class="text-gray-500 text-sm">Sign in to manage your bookings and account</p>
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 animate-fade-in-up">
        <!-- Flash error with icon -->
        <Transition
          enter-active-class="transform ease-out duration-300 transition"
          enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
          enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
          leave-active-class="transition ease-in duration-100"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div v-if="$page.props.flash?.error"
               class="mb-6 bg-gradient-to-r from-red-50 to-red-100/50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-3 shadow-sm">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
              <ExclamationCircleIcon class="w-4 h-4 text-red-600" />
            </div>
            <span class="flex-1">{{ $page.props.flash.error }}</span>
            <button @click="$page.props.flash.error = null" class="text-red-400 hover:text-red-600">
              <XMarkIcon class="w-4 h-4" />
            </button>
          </div>
        </Transition>

        <!-- Login Form -->
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Phone Number Input -->
          <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700">
              Phone Number
              <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="flex group">
              <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium group-focus-within:border-blue-500 group-focus-within:ring-1 group-focus-within:ring-blue-500 transition-colors">
                +91
              </span>
              <input 
                v-model="form.phone" 
                type="tel" 
                maxlength="10" 
                placeholder="98765 43210"
                class="form-input rounded-l-none flex-1"
                :class="{ 'border-red-300 focus:ring-red-500': form.errors.phone }"
                autofocus 
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

          <!-- Password Input -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label class="block text-sm font-semibold text-gray-700">
                Password
                <span class="text-red-500 ml-1">*</span>
              </label>
              <a href="/forgot-password" class="text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline transition-colors">
                Forgot password?
              </a>
            </div>
            <div class="relative group">
              <input 
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Enter your password"
                class="form-input pr-12"
                :class="{ 'border-red-300 focus:ring-red-500': form.errors.password }"
                @keyup.enter="submit"
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-blue-600 transition-colors"
                :aria-label="showPassword ? 'Hide password' : 'Show password'"
              >
                <EyeIcon v-if="!showPassword" class="w-4 h-4" />
                <EyeSlashIcon v-else class="w-4 h-4" />
              </button>
              
              <!-- Password strength indicator (optional) -->
              <div v-if="form.password && form.password.length > 0" class="absolute -bottom-4 left-0 right-0 flex gap-1">
                <div class="h-1 flex-1 rounded-full" :class="getPasswordStrengthColor(0)"></div>
                <div class="h-1 flex-1 rounded-full" :class="getPasswordStrengthColor(1)"></div>
                <div class="h-1 flex-1 rounded-full" :class="getPasswordStrengthColor(2)"></div>
              </div>
            </div>
            <Transition name="fade">
              <p v-if="form.errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                <ExclamationCircleIcon class="w-3 h-3" />
                {{ form.errors.password }}
              </p>
            </Transition>
          </div>

          <!-- Remember Me & Security Note -->
          <div class="flex items-center justify-between pt-2">
            <label class="flex items-center gap-2 cursor-pointer group">
              <input 
                v-model="form.remember" 
                type="checkbox"
                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 transition-colors cursor-pointer"
              />
              <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Remember me</span>
            </label>
            
            <div class="flex items-center gap-1 text-xs text-gray-400">
              <LockClosedIcon class="w-3 h-3" />
              <span>Secure login</span>
            </div>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="form.processing || !isFormValid"
            class="relative w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3.5 rounded-xl font-semibold text-sm hover:from-blue-700 hover:to-blue-800 transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg shadow-blue-600/25 overflow-hidden group"
          >
            <span class="relative z-10 flex items-center justify-center gap-2">
              <ArrowRightOnRectangleIcon v-if="!form.processing" class="w-4 h-4" />
              <svg v-else class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ form.processing ? 'Signing in...' : 'Sign In to Your Account' }}
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-0 group-hover:opacity-20 transition-opacity"></div>
          </button>

          <!-- Demo Credentials (for testing) -->
          <div v-if="appEnv === 'local'" class="mt-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
            <p class="text-xs font-medium text-gray-500 mb-2 flex items-center gap-1">
              <BeakerIcon class="w-3 h-3" />
              Demo Credentials (Local Only)
            </p>
            <div class="grid grid-cols-2 gap-2 text-xs">
              <button type="button" @click="fillDemoCredentials('admin')" class="text-left px-2 py-1 bg-white rounded border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <span class="font-medium text-gray-700">Admin</span>
                <span class="block text-gray-500">9876543210 / password</span>
              </button>
              <button type="button" @click="fillDemoCredentials('user')" class="text-left px-2 py-1 bg-white rounded border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <span class="font-medium text-gray-700">User</span>
                <span class="block text-gray-500">9876543211 / password</span>
              </button>
            </div>
          </div>
        </form>

        <!-- Divider -->
        <div class="relative my-8">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-400">New here?</span>
          </div>
        </div>

        <!-- Register CTA -->
        <div class="text-center">
          <a href="/user/register" 
             class="inline-flex items-center justify-center gap-2 w-full bg-gray-50 text-gray-700 py-3 px-4 rounded-xl font-medium text-sm hover:bg-gray-100 border border-gray-200 transition-all hover:border-gray-300 group">
            <UserPlusIcon class="w-4 h-4 text-gray-400 group-hover:text-gray-600" />
            Create a new account
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
  ExclamationCircleIcon, 
  EyeIcon, 
  EyeSlashIcon, 
  XMarkIcon,
  LockClosedIcon,
  ArrowRightOnRectangleIcon,
  UserPlusIcon,
  ArrowRightIcon,
  ArrowLeftIcon,
  ShieldCheckIcon,
  BeakerIcon
} from '@heroicons/vue/24/outline'

const showPassword = ref(false)
const page = usePage()

// Get app environment
const appEnv = computed(() => page.props.app_env ?? import.meta.env.VITE_APP_ENV ?? 'production')

const form = useForm({
  phone:    '',
  password: '',
  remember: false,
})

// Form validation
const isFormValid = computed(() => {
  return form.phone.length === 10 && form.password.length >= 6
})

// Phone validation
const validatePhone = (e) => {
  form.phone = e.target.value.replace(/\D/g, '').slice(0, 10)
}

// Password strength indicator (simple version)
const getPasswordStrengthColor = (index) => {
  if (!form.password) return 'bg-gray-200'
  
  const strength = form.password.length
  if (strength < 6) return 'bg-red-200'
  if (strength < 8) return index === 0 ? 'bg-yellow-400' : 'bg-gray-200'
  if (strength < 10) return index < 2 ? 'bg-green-400' : 'bg-gray-200'
  return 'bg-green-500'
}

// Demo credentials (local only)
const fillDemoCredentials = (type) => {
  if (type === 'admin') {
    form.phone = '9876543210'
    form.password = 'password'
  } else {
    form.phone = '9876543211'
    form.password = 'password'
  }
}

function submit() {
  form.post('/user/login', {
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

/* Input group focus effect */
.group:focus-within span {
  @apply border-blue-500 ring-1 ring-blue-500;
}

/* Custom checkbox */
input[type="checkbox"] {
  @apply rounded border-gray-300 text-blue-600 shadow-sm
         focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50;
}
</style>