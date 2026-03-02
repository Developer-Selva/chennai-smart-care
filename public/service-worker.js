/**
 * Chennai Smart Care — Service Worker
 *
 * Strategy:
 *  - App shell (HTML, CSS, JS, fonts) → Cache-First (fast repeat loads)
 *  - API calls (/api/*)               → Network-First with cache fallback
 *  - Images                           → Cache-First with 30-day expiry
 *  - Everything else                  → Network-First
 *
 * Cache names are versioned — bump CACHE_VERSION to force full refresh
 * on the next deploy.
 */

const CACHE_VERSION   = 'v1.1.0';
const CACHE_STATIC    = `csc-static-${CACHE_VERSION}`;
const CACHE_DYNAMIC   = `csc-dynamic-${CACHE_VERSION}`;
const CACHE_IMAGES    = `csc-images-${CACHE_VERSION}`;
const CACHE_API       = `csc-api-${CACHE_VERSION}`;

// ── App Shell: files to pre-cache on install ────────────────────────────────
// These are the minimum files needed to render the app offline.
// Vite hashes change on each build, so we only cache static paths here.
const PRECACHE_URLS = [
  '/',
  '/offline',          // offline fallback page (see below)
  '/images/logo.png',
  '/images/favicon/favicon.ico',
  '/images/favicon/apple-touch-icon.png',
];

// ── Max cache entries per store ──────────────────────────────────────────────
const MAX_IMAGES_ENTRIES = 60;
const MAX_DYNAMIC_ENTRIES = 30;

// ── Install: pre-cache app shell ─────────────────────────────────────────────
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_STATIC).then((cache) => {
      // Use individual adds so one missing file doesn't abort everything
      return Promise.allSettled(
        PRECACHE_URLS.map((url) =>
          cache.add(url).catch((err) =>
            console.warn(`[SW] Precache failed for ${url}:`, err)
          )
        )
      );
    }).then(() => {
      // Activate immediately without waiting for old tabs to close
      self.skipWaiting();
    })
  );
});

// ── Activate: remove old caches ──────────────────────────────────────────────
self.addEventListener('activate', (event) => {
  const currentCaches = [CACHE_STATIC, CACHE_DYNAMIC, CACHE_IMAGES, CACHE_API];
  event.waitUntil(
    caches.keys().then((cacheNames) =>
      Promise.all(
        cacheNames
          .filter((name) => !currentCaches.includes(name))
          .map((name) => {
            console.log('[SW] Deleting old cache:', name);
            return caches.delete(name);
          })
      )
    ).then(() => self.clients.claim()) // Take control of all pages immediately
  );
});

// ── Fetch: routing by request type ───────────────────────────────────────────
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Only handle same-origin + safe methods
  if (request.method !== 'GET') return;
  if (url.origin !== self.location.origin && !isTrustedCDN(url)) return;

  // 1. API calls → Network-First (fresh data preferred, fallback to cache)
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(networkFirstWithCache(request, CACHE_API));
    return;
  }

  // 2. Static assets (JS, CSS bundles from Vite) → Cache-First
  if (isStaticAsset(url)) {
    event.respondWith(cacheFirst(request, CACHE_STATIC));
    return;
  }

  // 3. Images → Cache-First with size limit
  if (isImage(url)) {
    event.respondWith(cacheFirstWithLimit(request, CACHE_IMAGES, MAX_IMAGES_ENTRIES));
    return;
  }

  // 4. Google Fonts → Cache-First (changes rarely)
  if (url.hostname === 'fonts.googleapis.com' || url.hostname === 'fonts.gstatic.com') {
    event.respondWith(cacheFirst(request, CACHE_STATIC));
    return;
  }

  // 5. Navigation requests (HTML pages) → Network-First with offline fallback
  if (request.mode === 'navigate') {
    event.respondWith(networkFirstWithOfflineFallback(request));
    return;
  }

  // 6. Everything else → Network-First
  event.respondWith(networkFirstWithCache(request, CACHE_DYNAMIC));
});

// ── Strategy: Cache-First ─────────────────────────────────────────────────────
async function cacheFirst(request, cacheName) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(cacheName);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return new Response('Resource unavailable offline.', { status: 503 });
  }
}

// ── Strategy: Cache-First with LRU-style entry limit ────────────────────────
async function cacheFirstWithLimit(request, cacheName, maxEntries) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(cacheName);
      // Trim oldest entries if over limit
      const keys = await cache.keys();
      if (keys.length >= maxEntries) {
        await cache.delete(keys[0]);
      }
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return new Response('Image unavailable offline.', { status: 503 });
  }
}

// ── Strategy: Network-First with cache fallback ──────────────────────────────
async function networkFirstWithCache(request, cacheName) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(cacheName);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    const cached = await caches.match(request);
    if (cached) return cached;
    return new Response(JSON.stringify({ error: 'You appear to be offline.' }), {
      status: 503,
      headers: { 'Content-Type': 'application/json' },
    });
  }
}

// ── Strategy: Network-First for navigation with offline page fallback ─────────
async function networkFirstWithOfflineFallback(request) {
  try {
    const response = await fetch(request);
    // Cache successful HTML navigations for offline access
    if (response.ok) {
      const cache = await caches.open(CACHE_DYNAMIC);
      const keys = await cache.keys();
      if (keys.length >= MAX_DYNAMIC_ENTRIES) await cache.delete(keys[0]);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    // Try exact URL from cache first
    const cached = await caches.match(request);
    if (cached) return cached;

    // Fall back to cached homepage (Inertia SPA can handle routing client-side)
    const homeCached = await caches.match('/');
    if (homeCached) return homeCached;

    // Last resort: offline page
    const offlinePage = await caches.match('/offline');
    if (offlinePage) return offlinePage;

    // Bare minimum offline response
    return new Response(offlineHtml(), {
      status: 503,
      headers: { 'Content-Type': 'text/html; charset=utf-8' },
    });
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function isStaticAsset(url) {
  return (
    url.pathname.startsWith('/build/') ||    // Vite build output
    url.pathname.endsWith('.css') ||
    url.pathname.endsWith('.js') ||
    url.pathname.endsWith('.woff2') ||
    url.pathname.endsWith('.woff') ||
    url.pathname.endsWith('.ttf') ||
    url.pathname.endsWith('.webmanifest')
  );
}

function isImage(url) {
  return /\.(png|jpe?g|gif|webp|svg|ico|avif)(\?.*)?$/.test(url.pathname);
}

function isTrustedCDN(url) {
  const trusted = [
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    'cdnjs.cloudflare.com',
  ];
  return trusted.includes(url.hostname);
}

// Inline offline HTML — used only if /offline page isn't cached
function offlineHtml() {
  return `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Offline — Chennai Smart Care</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
      color: white;
      text-align: center;
      padding: 2rem;
    }
    .card {
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 1.5rem;
      padding: 2.5rem;
      max-width: 420px;
      width: 100%;
    }
    .icon { font-size: 4rem; margin-bottom: 1rem; }
    h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 0.75rem; }
    p { color: rgba(255,255,255,0.8); line-height: 1.6; margin-bottom: 1.5rem; }
    .phone {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: white;
      color: #1d4ed8;
      font-weight: 700;
      padding: 0.875rem 2rem;
      border-radius: 0.75rem;
      text-decoration: none;
      font-size: 1.1rem;
    }
    .retry {
      display: block;
      margin-top: 1rem;
      color: rgba(255,255,255,0.7);
      font-size: 0.875rem;
      cursor: pointer;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">📡</div>
    <h1>You're Offline</h1>
    <p>
      No internet connection detected. To book a service or speak with our team,
      call us directly — we're available 9AM–9PM, 7 days a week.
    </p>
    <a href="tel:+919444900470" class="phone">
      📞 +91 94449 00470
    </a>
    <a class="retry" onclick="window.location.reload()">
      Try again when online →
    </a>
  </div>
</body>
</html>`;
}

// ── Background Sync: retry failed booking submissions ────────────────────────
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-booking') {
    event.waitUntil(syncPendingBookings());
  }
});

async function syncPendingBookings() {
  try {
    const db = await openIndexedDB();
    const pending = await getAllPending(db);
    for (const booking of pending) {
      try {
        const res = await fetch('/api/v1/bookings/quick', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify(booking.data),
        });
        if (res.ok) {
          await deletePending(db, booking.id);
          // Notify the user their booking went through
          await self.registration.showNotification('Booking Confirmed!', {
            body: 'Your Chennai Smart Care booking has been submitted successfully.',
            icon: '/images/favicon/favicon-96x96.png',
            badge: '/images/favicon/favicon-96x96.png',
            tag: 'booking-confirmed',
          });
        }
      } catch {
        // Will retry on next sync
      }
    }
  } catch (err) {
    console.warn('[SW] Background sync failed:', err);
  }
}

// ── Push Notifications ────────────────────────────────────────────────────────
self.addEventListener('push', (event) => {
  if (!event.data) return;

  let payload;
  try {
    payload = event.data.json();
  } catch {
    payload = { title: 'Chennai Smart Care', body: event.data.text() };
  }

  event.waitUntil(
    self.registration.showNotification(payload.title || 'Chennai Smart Care', {
      body:    payload.body    || 'You have a new update.',
      icon:    payload.icon    || '/images/favicon/favicon-96x96.png',
      badge:   payload.badge   || '/images/favicon/favicon-96x96.png',
      image:   payload.image,
      tag:     payload.tag     || 'csc-notification',
      data:    payload.data    || {},
      actions: payload.actions || [],
      vibrate: [100, 50, 100],
      requireInteraction: payload.requireInteraction ?? false,
    })
  );
});

// Handle notification click → open/focus the app
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  const targetUrl = event.notification.data?.url || '/';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      // If app is already open, focus it
      for (const client of clientList) {
        if (client.url.includes(self.location.origin) && 'focus' in client) {
          client.navigate(targetUrl);
          return client.focus();
        }
      }
      // Otherwise open a new window
      if (clients.openWindow) return clients.openWindow(targetUrl);
    })
  );
});

// ── IndexedDB helpers (for offline booking queue) ─────────────────────────────
function openIndexedDB() {
  return new Promise((resolve, reject) => {
    const req = indexedDB.open('csc-offline-db', 1);
    req.onupgradeneeded = (e) => {
      e.target.result.createObjectStore('pending-bookings', {
        keyPath: 'id', autoIncrement: true,
      });
    };
    req.onsuccess = (e) => resolve(e.target.result);
    req.onerror   = (e) => reject(e.target.error);
  });
}

function getAllPending(db) {
  return new Promise((resolve, reject) => {
    const tx = db.transaction('pending-bookings', 'readonly');
    const req = tx.objectStore('pending-bookings').getAll();
    req.onsuccess = (e) => resolve(e.target.result);
    req.onerror   = (e) => reject(e.target.error);
  });
}

function deletePending(db, id) {
  return new Promise((resolve, reject) => {
    const tx = db.transaction('pending-bookings', 'readwrite');
    const req = tx.objectStore('pending-bookings').delete(id);
    req.onsuccess = () => resolve();
    req.onerror   = (e) => reject(e.target.error);
  });
}