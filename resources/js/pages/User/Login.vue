<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

      <!-- Logo -->
      <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center gap-2.5">
          <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-extrabold text-white text-sm shadow">SC</div>
          <span class="font-bold text-gray-900 text-lg">Chennai Smart Care</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-6 mb-1">Welcome back</h1>
        <p class="text-gray-500 text-sm">Sign in to manage your bookings</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">

        <!-- Flash error -->
        <div v-if="$page.props.flash?.error"
             class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
          <ExclamationCircleIcon class="w-4 h-4 flex-shrink-0" />
          {{ $page.props.flash.error }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
            <div class="flex">
              <span class="inline-flex items-center px-4 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm font-medium">+91</span>
              <input v-model="form.phone" type="tel" maxlength="10" placeholder="98765 43210"
                     class="form-input rounded-l-none flex-1" autofocus />
            </div>
            <p v-if="form.errors.phone" class="text-red-500 text-xs mt-1">{{ form.errors.phone }}</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <div class="relative">
              <input v-model="form.password"
                     :type="showPassword ? 'text' : 'password'"
                     placeholder="Enter your password"
                     class="form-input pr-10" />
              <button type="button" @click="showPassword = !showPassword"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <EyeIcon v-if="!showPassword" class="w-4 h-4" />
                <EyeSlashIcon v-else class="w-4 h-4" />
              </button>
            </div>
            <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
          </div>

          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
              <input v-model="form.remember" type="checkbox"
                     class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
              <span class="text-gray-600">Remember me</span>
            </label>
            <a href="/forgot-password" class="text-blue-600 hover:underline font-medium">Forgot password?</a>
          </div>

          <button type="submit" :disabled="form.processing"
                  class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors disabled:opacity-60">
            {{ form.processing ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
          Don't have an account?
          <a href="/user/register" class="text-blue-600 font-semibold hover:underline">Create one</a>
        </p>
      </div>

      <p class="text-center text-xs text-gray-400 mt-6">
        <a href="/" class="hover:underline">← Back to Home</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { ExclamationCircleIcon, EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const showPassword = ref(false)

const form = useForm({
  phone:    '',
  password: '',
  remember: false,
})

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
         transition-colors text-sm;
}
</style>