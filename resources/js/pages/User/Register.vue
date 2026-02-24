<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

      <!-- Logo -->
      <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center gap-2.5">
          <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-extrabold text-white text-sm shadow">SC</div>
          <span class="font-bold text-gray-900 text-lg">Chennai Smart Care</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-6 mb-1">Create your account</h1>
        <p class="text-gray-500 text-sm">Track bookings, save addresses, get faster service</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
            <input v-model="form.name" type="text" placeholder="e.g. Rajesh Kumar"
                   class="form-input" autofocus />
            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number *</label>
            <div class="flex">
              <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+91</span>
              <input v-model="form.phone" type="tel" maxlength="10" placeholder="98765 43210"
                     class="form-input rounded-l-none flex-1" />
            </div>
            <p v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address (Optional)</label>
            <input v-model="form.email" type="email" placeholder="you@example.com"
                   class="form-input" />
            <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password *</label>
            <div class="relative">
              <input v-model="form.password"
                     :type="showPassword ? 'text' : 'password'"
                     placeholder="Min. 8 characters"
                     class="form-input pr-10" />
              <button type="button" @click="showPassword = !showPassword"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <EyeIcon v-if="!showPassword" class="w-4 h-4" />
                <EyeSlashIcon v-else class="w-4 h-4" />
              </button>
            </div>
            <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password *</label>
            <input v-model="form.password_confirmation"
                   :type="showPassword ? 'text' : 'password'"
                   placeholder="Repeat password"
                   class="form-input" />
          </div>

          <!-- Password strength -->
          <div v-if="form.password" class="space-y-1">
            <div class="flex gap-1">
              <div v-for="i in 4" :key="i"
                   :class="['h-1 flex-1 rounded-full transition-colors', i <= passwordStrength ? strengthColor : 'bg-gray-200']">
              </div>
            </div>
            <p class="text-xs" :class="strengthTextColor">{{ strengthLabel }}</p>
          </div>

          <button type="submit" :disabled="form.processing"
                  class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors disabled:opacity-60">
            {{ form.processing ? 'Creating account...' : 'Create Account' }}
          </button>

          <p class="text-xs text-gray-400 text-center">
            By creating an account, you agree to our
            <a href="/privacy" class="underline">Privacy Policy</a> and
            <a href="/terms" class="underline">Terms of Service</a>.
          </p>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
          Already have an account?
          <a href="/user/login" class="text-blue-600 font-semibold hover:underline">Sign in</a>
        </p>
      </div>

      <p class="text-center text-xs text-gray-400 mt-6">
        <a href="/" class="hover:underline">← Back to Home</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const showPassword = ref(false)

const form = useForm({
  name:                  '',
  phone:                 '',
  email:                 '',
  password:              '',
  password_confirmation: '',
})

function submit() {
  form.post('/user/register')
}

// Password strength
const passwordStrength = computed(() => {
  const p = form.password
  if (!p) return 0
  let score = 0
  if (p.length >= 8)              score++
  if (/[A-Z]/.test(p))            score++
  if (/[0-9]/.test(p))            score++
  if (/[^A-Za-z0-9]/.test(p))    score++
  return score
})

const strengthColor = computed(() => {
  return ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'][passwordStrength.value - 1] ?? 'bg-gray-200'
})
const strengthTextColor = computed(() => {
  return ['text-red-500', 'text-orange-500', 'text-yellow-600', 'text-green-600'][passwordStrength.value - 1] ?? 'text-gray-400'
})
const strengthLabel = computed(() => {
  return ['Weak', 'Fair', 'Good', 'Strong'][passwordStrength.value - 1] ?? ''
})
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400
         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
         transition-colors text-sm;
}
</style>