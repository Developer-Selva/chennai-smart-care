<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center p-4">

    <Head><title>Admin Login — Chennai Smart Care</title></Head>

    <div class="w-full max-w-md">

      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center font-extrabold text-white text-xl mx-auto mb-4 shadow-lg shadow-blue-900/40">
          SC
        </div>
        <h1 class="text-2xl font-bold text-white">Chennai Smart Care</h1>
        <p class="text-gray-400 text-sm mt-1">Admin Panel</p>
      </div>

      <!-- Card -->
      <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-semibold text-white mb-6">Sign in to continue</h2>

        <form @submit.prevent="submit" class="space-y-5">

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              autocomplete="email"
              placeholder="admin@example.com"
              :class="[
                'w-full px-4 py-3 bg-gray-800 border rounded-xl text-white placeholder-gray-500 text-sm',
                'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow',
                errors.email ? 'border-red-500' : 'border-gray-700',
              ]"
            />
            <p v-if="errors.email" class="mt-1.5 text-red-400 text-xs">{{ errors.email }}</p>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                placeholder="••••••••"
                :class="[
                  'w-full px-4 py-3 bg-gray-800 border rounded-xl text-white placeholder-gray-500 text-sm pr-11',
                  'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow',
                  errors.password ? 'border-red-500' : 'border-gray-700',
                ]"
              />
              <button type="button"
                      @click="showPassword = !showPassword"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition-colors">
                <EyeSlashIcon v-if="showPassword" class="w-5 h-5" />
                <EyeIcon v-else class="w-5 h-5" />
              </button>
            </div>
            <p v-if="errors.password" class="mt-1.5 text-red-400 text-xs">{{ errors.password }}</p>
          </div>

          <!-- Remember me -->
          <div class="flex items-center gap-2.5">
            <input v-model="form.remember" id="remember" type="checkbox"
                   class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-900" />
            <label for="remember" class="text-sm text-gray-300 select-none cursor-pointer">
              Keep me signed in
            </label>
          </div>

          <!-- Submit -->
          <button type="submit"
                  :disabled="submitting"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold text-sm transition-colors disabled:opacity-60 disabled:cursor-not-allowed shadow-lg shadow-blue-900/30 mt-2">
            {{ submitting ? 'Signing in...' : 'Sign In' }}
          </button>

        </form>
      </div>

      <p class="text-center text-xs text-gray-600 mt-6">
        Chennai Smart Care · Admin Panel · Restricted Access
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

defineProps({
  errors: { type: Object, default: () => ({}) },
})

const showPassword = ref(false)
const submitting   = ref(false)

const form = useForm({
  email:    '',
  password: '',
  remember: false,
})

function submit() {
  submitting.value = true
  form.post('/admin/login', {
    onFinish: () => { submitting.value = false },
  })
}
</script>