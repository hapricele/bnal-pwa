const CACHE_NAME = 'bnal-cache-v3';
const urlsToCache = [
  './',
  './index.html',
  './rates.html',
  './contacts.html',
  './reviews.html',
  './offline.html',
  './css/style.css',
  './js/script.js',
  './images/logo.png',
  './images/icon-72x72.png',
  './images/icon-192x192.png',
  './images/icon-512x512.png',
  './images/btc.png',
  './images/eth.png',
  './images/usdt.png',
  './images/review1.jpg'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
      .catch(err => console.log('Cache addAll error:', err))
  );
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;

  event.respondWith(
    caches.match(event.request)
      .then(cached => {
        return cached || fetch(event.request)
          .then(response => {
            if(!response || response.status !== 200) return response;
            
            const responseToCache = response.clone();
            caches.open(CACHE_NAME)
              .then(cache => cache.put(event.request, responseToCache));
            
            return response;
          })
          .catch(() => {
            if (event.request.headers.get('accept').includes('text/html')) {
              return caches.match('./offline.html');
            }
            return new Response('Offline', { status: 503 });
          });
      })
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => 
      Promise.all(
        keys.map(key => key !== CACHE_NAME ? caches.delete(key) : null)
      )
    )
  );
});