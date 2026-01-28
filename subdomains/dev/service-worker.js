/* PWA Service Worker — Windels Uitvaartzorg */
const CACHE_STATIC='static-v2';
const CACHE_FALLBACK='fallback-v2';
const STATIC_ASSETS=[
  '/styles.css',
  '/app.js',
  '/assets/icons/icon-192.png',
  '/assets/icons/icon-512.png'
];
const OFFLINE_PAGE='/offline.html';
const MEDEWERKERS_HOST='medewerkers.windelsgreen-decoresin.com';

self.addEventListener('install',event=>{
  event.waitUntil((async()=>{
    const c=await caches.open(CACHE_STATIC);
    await c.addAll(STATIC_ASSETS);
    const f=await caches.open(CACHE_FALLBACK);
    await f.add(new Request(OFFLINE_PAGE,{cache:'reload'}));
  })());
  self.skipWaiting();
});

self.addEventListener('activate',event=>{
  event.waitUntil((async()=>{
    const keys=await caches.keys();
    await Promise.all(
      keys.filter(k=>![CACHE_STATIC,CACHE_FALLBACK].includes(k))
          .map(k=>caches.delete(k))
    );
    await self.clients.claim();
  })());
});

/** Network helpers **/
const isGet=req=>req.method==='GET';
const toURL=req=>new URL(req.url);
const sameOrigin=req=>toURL(req).origin===self.location.origin;

self.addEventListener('fetch',event=>{
  const req=event.request;
  if(!isGet(req)) return; // laat non-GET ongemoeid

  const url=toURL(req);

  // 1) Navigations: network-first met offline fallback
  if(req.mode==='navigate'){
    event.respondWith((async()=>{
      try{
        return await fetch(req,{cache:'no-store'});
      }catch(_){
        const fb=await caches.open(CACHE_FALLBACK);
        return (await fb.match(OFFLINE_PAGE)) || new Response('Offline',{status:503});
      }
    })());
    return;
  }

  // 2) Cross-origin: altijd pass-through (NIET cachen), vooral voor medewerkers-domein
  if(url.origin!==self.location.origin){
    // expliciet medewerkers-host, maar we laten alle cross-origin gewoon door
    event.respondWith(fetch(req));
    return;
  }

  // 3) Same-origin: strategie per type
  // 3a) Pre-cached static assets -> cache-first
  if(STATIC_ASSETS.includes(url.pathname)){
    event.respondWith((async()=>{
      const cache=await caches.open(CACHE_STATIC);
      const hit=await cache.match(req);
      if(hit) return hit;
      const res=await fetch(req);
      if(res && res.ok) cache.put(req,res.clone());
      return res;
    })());
    return;
  }

  // 3b) Afbeeldingen -> stale-while-revalidate
  if(req.destination==='image'){
    event.respondWith((async()=>{
      const cache=await caches.open(CACHE_STATIC);
      const cached=await cache.match(req);
      const fetchPromise=fetch(req).then(res=>{
        if(res && (res.ok || res.type==='opaque')) cache.put(req,res.clone());
        return res;
      }).catch(()=>cached);
      return cached || fetchPromise;
    })());
    return;
  }

  // 3c) Default same-origin -> network-first met cache fallback
  event.respondWith((async()=>{
    const cache=await caches.open(CACHE_STATIC);
    try{
      const res=await fetch(req);
      if(res && res.ok) cache.put(req,res.clone());
      return res;
    }catch(_){
      const cached=await cache.match(req);
      if(cached) return cached;
      // geen offline fallback voor niet-navigations
      return new Response('',{status:504,statusText:'Gateway Timeout'});
    }
  })());
});

// Optioneel: vanuit de app SW updaten zonder refresh
self.addEventListener('message',e=>{
  if(e.data && e.data.type==='SKIP_WAITING') self.skipWaiting();
});
