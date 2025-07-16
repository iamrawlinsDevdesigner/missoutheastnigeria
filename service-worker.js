const CACHE_NAME = 'msen-cache-v1';
const urlsToCache = [
    '/',
    '/index.php',
    '/assets/css/dashboard.css',
    '/assets/js/dashboard.js',
    '/admin/dashboard.php',
    '/assets/icons/icon-192.png',
    '/assets/icons/icon-512.png'
];

// Install SW and cache assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

// Fetch requests
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
    );
});

// Update SW
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => 
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
});
