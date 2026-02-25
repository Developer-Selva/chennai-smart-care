import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { createPinia } from 'pinia'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import '../css/app.css'
import { ZiggyVue } from 'ziggy-js'
import axios from 'axios'

// ── Axios global setup ──────────────────────────────────────

axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''

axios.defaults.withCredentials = true

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const appName = import.meta.env.VITE_APP_NAME || 'Chennai Smart Care'

createInertiaApp({
  title: (title) => `${title} — ${appName}`,

  resolve: (name) =>
    resolvePageComponent(
      `./pages/${name}.vue`,
      import.meta.glob('./pages/**/*.vue')
    ),

  setup({ el, App, props, plugin }) {
    const pinia = createPinia()

    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia)
      .use(ZiggyVue)
      .component('Head', Head)
      .component('Link', Link)
      .mount(el)
  },

  progress: {
    color: '#3B82F6',
  },
})