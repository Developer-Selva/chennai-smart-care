<template>
  <header :class="[
    'sticky top-0 z-40 py-2 w-full transition-all duration-200',
    scrolled || minimal ? 'bg-white shadow-md' : 'bg-gray-900',
  ]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">

        <!-- Logo -->
        <a href="/" class="flex items-center flex-shrink-0">
          <img
            src="/images/chennai_smart_care.png"
            alt="Chennai Smart Care — Expert Appliance Repair"
            class="h-20 w-auto object-contain transition-all duration-200"/>
        </a>

        <!-- Desktop Nav -->
        <nav v-if="!minimal" class="hidden lg:flex items-center gap-1">
          <a v-for="item in navLinks" :key="item.label" :href="item.href"
             :class="['px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                      scrolled ? 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' : 'text-blue-100 hover:text-white hover:bg-white/10']">
            {{ item.label }}
          </a>
        </nav>

        <!-- Desktop Right -->
        <div class="hidden lg:flex items-center gap-3">
          <a :href="`tel:${phone}`"
             :class="['flex items-center gap-1.5 text-sm font-semibold transition-colors',
                      scrolled || minimal ? 'text-gray-700 hover:text-blue-600' : 'text-white hover:text-blue-200']">
            📞 {{ phone }}
          </a>

          <!-- Logged-in user dropdown -->
          <div v-if="authUser" class="relative" ref="userMenuRef">
            <button @click="userMenuOpen = !userMenuOpen"
                    :class="['flex items-center gap-2 px-3 py-2 rounded-xl border transition-colors',
                             scrolled || minimal ? 'border-gray-200 text-gray-700 hover:bg-gray-50' : 'border-white/20 text-white hover:bg-white/10']">
              <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ userInitials }}</div>
              <span class="text-sm font-semibold max-w-[100px] truncate">{{ firstName }}</span>
              <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <Transition name="dropdown">
              <div v-if="userMenuOpen"
                   class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                <div class="px-4 py-2.5 border-b border-gray-100">
                  <p class="font-semibold text-gray-900 text-sm truncate">{{ authUser.name }}</p>
                  <p class="text-xs text-gray-500 truncate">{{ authUser.phone }}</p>
                  <p v-if="authUser.loyalty_points > 0" class="text-xs text-yellow-600 font-medium mt-0.5">⭐ {{ authUser.loyalty_points }} loyalty points</p>
                </div>
                <a href="/user/dashboard" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                  🏠 My Dashboard
                </a>
                <a href="/user/bookings/create" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                  📅 Book a Service
                </a>
                <div class="border-t border-gray-100 mt-1 pt-1">
                  <Link href="/user/logout" method="post" as="button"
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    🚪 Sign Out
                  </Link>
                </div>
              </div>
            </Transition>
          </div>

          <!-- Guest: Login + Register -->
          <template v-else>
            <a href="/user/login"
               :class="['text-sm font-semibold px-3 py-2 rounded-lg transition-colors',
                        scrolled || minimal ? 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' : 'text-white hover:text-blue-200']">
              Login
            </a>
            <a href="/user/register"
               :class="['text-sm font-semibold px-4 py-2.5 rounded-xl border-2 transition-all',
                        scrolled || minimal ? 'border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white' : 'border-white text-white hover:bg-white hover:text-blue-600']">
              Register
            </a>
          </template>

          <a href="/quick-booking" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all">
            Book Now
          </a>
        </div>

        <!-- Mobile right -->
        <div class="flex items-center gap-2 lg:hidden">
          <a v-if="authUser" href="/user/dashboard"
             class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
            {{ userInitials }}
          </a>
          <a :href="`tel:${phone}`" :class="['p-2 rounded-lg', scrolled || minimal ? 'text-gray-700' : 'text-white']">📞</a>
          <button @click="mobileOpen = !mobileOpen"
                  :class="['p-2 rounded-lg transition-colors', scrolled || minimal ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10']">
            <span class="text-xl leading-none">{{ mobileOpen ? '✕' : '☰' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition name="slide-down">
      <div v-if="mobileOpen && !minimal" class="lg:hidden bg-white border-t border-gray-100 shadow-lg">
        <nav class="px-4 py-3 space-y-1">
          <a v-for="item in navLinks" :key="item.label" :href="item.href" @click="mobileOpen = false"
             class="flex items-center px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium text-sm transition-colors">
            {{ item.label }}
          </a>

          <!-- Mobile logged-in -->
          <template v-if="authUser">
            <div class="border-t border-gray-100 pt-3 mt-2">
              <div class="flex items-center gap-3 px-4 py-2 mb-1">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">{{ userInitials }}</div>
                <div>
                  <p class="font-semibold text-gray-900 text-sm">{{ authUser.name }}</p>
                  <p v-if="authUser.loyalty_points > 0" class="text-xs text-yellow-600">⭐ {{ authUser.loyalty_points }} pts</p>
                </div>
              </div>
              <a href="/user/dashboard" @click="mobileOpen = false"
                 class="flex items-center gap-2 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium text-sm">🏠 My Dashboard</a>
              <a href="/user/bookings/create" @click="mobileOpen = false"
                 class="flex items-center gap-2 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium text-sm">📅 Book a Service</a>
              <Link href="/user/logout" method="post" as="button" @click="mobileOpen = false"
                    class="w-full flex items-center gap-2 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-medium text-sm">🚪 Sign Out</Link>
            </div>
          </template>

          <!-- Mobile guest -->
          <template v-else>
            <div class="border-t border-gray-100 pt-3 mt-2 grid grid-cols-2 gap-2">
              <a href="/user/login" @click="mobileOpen = false"
                 class="text-center py-2.5 border border-gray-300 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50">Login</a>
              <a href="/user/register" @click="mobileOpen = false"
                 class="text-center py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700">Register</a>
            </div>
          </template>
        </nav>

        <div class="px-4 pb-4 flex gap-3 mt-1">
          <a href="/quick-booking" class="flex-1 text-center bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700">Book Service Now</a>
          <a :href="`tel:${phone}`" class="flex-1 text-center border border-gray-300 text-gray-700 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">Call Us</a>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

defineProps({ minimal: { type: Boolean, default: false } })

const page         = usePage()
const authUser     = computed(() => page.props.auth?.user ?? null)
const scrolled     = ref(false)
const mobileOpen   = ref(false)
const userMenuOpen = ref(false)
const userMenuRef  = ref(null)
const phone        = '+91 94449 00470'

const firstName    = computed(() => authUser.value?.name?.split(' ')[0] ?? '')
const userInitials = computed(() => {
  return (authUser.value?.name ?? '').split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || 'U'
})

const navLinks = [
  { label: 'Services',     href: '/#services' },
  { label: 'How It Works', href: '/#how-it-works' },
  { label: 'Testimonials', href: '/#testimonials' },
  { label: 'About Us',     href: '/about' },
  { label: 'Blog',         href: '/blog' },
  { label: 'Contact',      href: '/contact' },
]

function onScroll() { scrolled.value = window.scrollY > 20 }
function onClickOutside(e) {
  if (userMenuRef.value && !userMenuRef.value.contains(e.target)) userMenuOpen.value = false
}

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
  document.addEventListener('click', onClickOutside)
})
onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  document.removeEventListener('click', onClickOutside)
})
</script>

<style scoped>
.slide-down-enter-active, .slide-down-leave-active { transition: all .2s ease; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; transform: translateY(-8px); }
.dropdown-enter-active, .dropdown-leave-active { transition: all .15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(.98); }
</style>