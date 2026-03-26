importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.4.1/workbox-sw.js');

if (workbox) {
    console.log(`[PWA] Workbox is loaded 🎉`);

    // 1. Pre-caching core assets (Offline Fallback)
    workbox.precaching.precacheAndRoute([
        { url: '/offline.php', revision: '3.5.0' },
        { url: '/manifest.json', revision: '3.5.0' },
        { url: '/assets/pwa/icon-192x192.png', revision: '3.5.0' }
    ]);

    // 2. Cache CSS and JavaScript (Stale-While-Revalidate)
    workbox.routing.registerRoute(
        ({ request }) => request.destination === 'style' || request.destination === 'script',
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'cms-static-resources',
        })
    );

    // 3. Cache Images (Cache-First)
    workbox.routing.registerRoute(
        ({ request }) => request.destination === 'image',
        new workbox.strategies.CacheFirst({
            cacheName: 'cms-images',
            plugins: [
                new workbox.expiration.ExpirationPlugin({
                    maxEntries: 50,
                    maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
                }),
            ],
        })
    );

    // 4. Navigation (Network-First with Offline Fallback)
    const networkFirstHandler = new workbox.strategies.NetworkFirst({
        cacheName: 'cms-pages',
    });

    workbox.routing.registerRoute(
        ({ request }) => request.mode === 'navigate',
        async (params) => {
            try {
                return await networkFirstHandler.handle(params);
            } catch (error) {
                return caches.match('/offline.php');
            }
        }
    );

    // Skip waiting and claim clients
    self.addEventListener('install', () => self.skipWaiting());
    self.addEventListener('activate', () => self.clients.claim());

} else {
    console.log(`[PWA] Workbox failed to load ❌`);
}
