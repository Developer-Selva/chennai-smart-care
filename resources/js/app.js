import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import axios from 'axios'

// ── Axios global setup ──────────────────────────────────────
// 1. Send CSRF token with every request (required by Laravel)
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''

// 3. Tell Laravel this is an AJAX request (so it returns JSON errors, not HTML)
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
// ────────────────────────────────────────────────────────────

createInertiaApp({
    title: (title) => `${title} — Chennai Smart Care`,
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
    progress: {
        color: '#3B82F6',
    },
})