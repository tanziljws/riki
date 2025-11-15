const CACHE_VERSION = 'v1.0.0';
const APP_SHELL = `app-shell-${CACHE_VERSION}`;
const RUNTIME_IMG = `rt-img-${CACHE_VERSION}`;
const RUNTIME_API = `rt-api-${CACHE_VERSION}`;

const PRECACHE_URLS = [
  '/',
  '/galeri',
  '/offline.html',
  '/css/sidebar.css',
  '/images/logo-smkn.jpg',
  '/images/logo.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(APP_SHELL).then((cache) => cache.addAll(PRECACHE_URLS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter(k => ![APP_SHELL, RUNTIME_IMG, RUNTIME_API].includes(k)).map(k => caches.delete(k)))).then(() => self.clients.claim())
  );
});

async function limitCacheEntries(cacheName, maxEntries = 50) {
  const cache = await caches.open(cacheName);
  const keys = await cache.keys();
  if (keys.length <= maxEntries) return;
  const toDelete = keys.length - maxEntries;
  for (let i = 0; i < toDelete; i++) {
    await cache.delete(keys[i]);
  }
}

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;
  const url = new URL(req.url);

  // Navigation requests (HTML)
  if (req.mode === 'navigate') {
    event.respondWith(
      (async () => {
        try {
          const fresh = await fetch(req);
          const cache = await caches.open(APP_SHELL);
          cache.put(req, fresh.clone());
          return fresh;
        } catch (e) {
          const cached = await caches.match(req) || await caches.match('/offline.html');
          return cached || new Response('Offline', { status: 503, statusText: 'Offline' });
        }
      })()
    );
    return;
  }

  // Same-origin images -> stale-while-revalidate + capped entries
  if (url.origin === location.origin && req.destination === 'image') {
    event.respondWith(
      (async () => {
        const cache = await caches.open(RUNTIME_IMG);
        const cached = await cache.match(req);
        const network = fetch(req).then(async (res) => {
          try { await cache.put(req, res.clone()); await limitCacheEntries(RUNTIME_IMG, 50); } catch {}
          return res;
        }).catch(() => undefined);
        return cached || network || fetch(req);
      })()
    );
    return;
  }

  // Same-origin CSS/JS/API -> network-first with fallback to cache
  if (url.origin === location.origin) {
    event.respondWith(
      (async () => {
        try {
          const res = await fetch(req);
          const cache = await caches.open(RUNTIME_API);
          cache.put(req, res.clone());
          return res;
        } catch (e) {
          const cached = await caches.match(req);
          if (cached) return cached;
          throw e;
        }
      })()
    );
  }
});
