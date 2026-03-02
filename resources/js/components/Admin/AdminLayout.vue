<template>
  <div class="min-h-screen flex bg-gray-100">
    <aside :class="[
      'fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white flex flex-col',
      'transform transition-transform duration-200 ease-in-out',
      sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
    ]">
      <div class="px-5 py-4 border-b border-gray-700/60 flex-shrink-0">
        <a href="/admin/dashboard" class="flex items-center gap-2.5">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-extrabold text-sm shadow">SC</div>
          <div>
            <p class="font-bold text-sm leading-tight">Smart Care</p>
            <p class="text-gray-400 text-xs">Admin Panel</p>
          </div>
        </a>
      </div>
      <nav class="flex-1 py-4 px-2 space-y-0.5 overflow-y-auto">
        <template v-for="item in navItems" :key="item.label ?? item.divider">
          <p v-if="item.divider" class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
            {{ item.divider }}
          </p>
          <a v-else :href="item.href" :class="[
               'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
               isActive(item.href) ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white',
             ]">
            <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
            <span class="flex-1">{{ item.label }}</span>
            <span v-if="item.badge && item.badge > 0"
                  class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
              {{ item.badge > 99 ? '99+' : item.badge }}
            </span>
          </a>
        </template>
      </nav>
      <div class="px-4 py-4 border-t border-gray-700/60 flex-shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
            {{ adminInitials }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate">{{ $page.props.auth?.admin?.name }}</p>
            <p class="text-xs text-gray-400 truncate capitalize">{{ $page.props.auth?.admin?.role?.replace('_', ' ') }}</p>
          </div>
          <Link href="/admin/logout" method="post" as="button"
                class="text-gray-400 hover:text-white transition-colors flex-shrink-0" title="Logout">
            <ArrowRightOnRectangleIcon class="w-5 h-5" />
          </Link>
        </div>
      </div>
    </aside>

    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen overflow-x-hidden">
      <header class="bg-white border-b border-gray-200 sticky top-0 z-40 flex-shrink-0">
        <div class="px-4 sm:px-6 h-16 flex items-center gap-3">
          <button @click="sidebarOpen = !sidebarOpen"
                  class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
            <Bars3Icon class="w-5 h-5" />
          </button>
          <div class="flex-1 min-w-0"><slot name="header" /></div>
          <a href="/admin/bookings?status=pending"
             class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
            <BellIcon class="w-5 h-5" />
            <span v-if="pendingCount > 0"
                  class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
              {{ pendingCount > 9 ? '9+' : pendingCount }}
            </span>
          </a>
        </div>
      </header>
      <div v-if="$page.props.flash?.success"
           class="mx-4 sm:mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2.5 text-sm">
        <CheckCircleIcon class="w-5 h-5 text-green-500 flex-shrink-0" />
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error"
           class="mx-4 sm:mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2.5 text-sm">
        <ExclamationCircleIcon class="w-5 h-5 text-red-500 flex-shrink-0" />
        {{ $page.props.flash.error }}
      </div>
      <main class="flex-1 p-4 sm:p-6"><slot /></main>
    </div>

    <Transition name="fade">
      <div v-if="sidebarOpen" @click="sidebarOpen = false"
           class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import {
  HomeIcon, CalendarIcon, WrenchScrewdriverIcon,
  UsersIcon, UserGroupIcon, DocumentTextIcon,
  ChartBarIcon, CogIcon, Bars3Icon, BellIcon,
  ArrowRightOnRectangleIcon, CheckCircleIcon,
  ExclamationCircleIcon, ChatBubbleBottomCenterTextIcon,
} from '@heroicons/vue/24/outline'

const page        = usePage()
const sidebarOpen = ref(false)

const adminInitials = computed(() => {
  const name = page.props.auth?.admin?.name ?? 'A'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})
const pendingCount = computed(() => page.props.pending_count ?? 0)

const navItems = [
  { label: 'Dashboard',     href: '/admin/dashboard',      icon: HomeIcon },
  { divider: 'Bookings' },
  { label: 'All Bookings',  href: '/admin/bookings',        icon: CalendarIcon, badge: pendingCount },
  { label: 'New Booking',   href: '/admin/bookings/create', icon: CalendarIcon },
  { divider: 'Catalogue' },
  { label: 'Services',      href: '/admin/services',        icon: WrenchScrewdriverIcon },
  { label: 'Technicians',   href: '/admin/technicians',     icon: UserGroupIcon },
  { divider: 'Customers' },
  { label: 'Customers',     href: '/admin/customers',       icon: UsersIcon },
  { label: 'Consultations', href: '/admin/consultations',   icon: ChatBubbleBottomCenterTextIcon },
  { divider: 'Content' },
  { label: 'Blog Posts',    href: '/admin/blog',            icon: DocumentTextIcon },
  { divider: 'System' },
  { label: 'Reports',       href: '/admin/reports',         icon: ChartBarIcon },
  { label: 'Settings',      href: '/admin/settings',        icon: CogIcon },
  { divider: 'Meida' },
  { label: 'Library',      href: '/admin/media-library',        icon: CogIcon },
]

function isActive(href) { return page.url.startsWith(href) }
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }
</style>
