var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/images/icons/icon-192.png',
    '/images/icons/icon-512.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});

// ─── Push Notifications ───────────────────────────────────────────────────────

self.addEventListener('push', event => {
    if (!event.data) return;

    let data = {};
    try {
        data = event.data.json();
    } catch (e) {
        data = { title: 'Doonates', body: event.data.text(), url: '/' };
    }

    const title   = data.title  || 'Doonates';
    const options = {
        body:  data.body  || '',
        icon:  data.icon  || '/images/icons/icon-192.png',
        badge: data.badge || '/images/icons/icon-96x96.png',
        data:  { url: data.url || '/' },
        vibrate: [200, 100, 200],
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(clientList => {
                // If the app is already open, focus it and navigate
                for (const client of clientList) {
                    if ('focus' in client) {
                        client.focus();
                        client.navigate(targetUrl);
                        return;
                    }
                }
                // Otherwise open a new window
                if (clients.openWindow) {
                    return clients.openWindow(targetUrl);
                }
            })
    );
});