(async()=>{
    const refreshBtn=document.getElementById('refreshBtn'); if(refreshBtn){refreshBtn.hidden=false; refreshBtn.onclick=()=>location.reload();}
    if(!('serviceWorker' in navigator)) return;
    const reg=await navigator.serviceWorker.register('/service-worker.js',{updateViaCache:'none'}).catch(()=>null); if(!reg) return;
    const check=()=>reg.update().catch(()=>{}); document.addEventListener('visibilitychange',()=>{if(document.visibilityState==='visible')check();}); check();
    function showUpdateToast(worker){const t=document.getElementById('updateToast'),b=document.getElementById('reloadNow'); if(!t||!b)return; t.hidden=false; b.onclick=()=>worker.postMessage({type:'SKIP_WAITING'});}
    if(reg.waiting) showUpdateToast(reg.waiting);
    reg.addEventListener('updatefound',()=>{const sw=reg.installing; if(!sw)return; sw.addEventListener('statechange',()=>{if(sw.state==='installed'&&navigator.serviceWorker.controller)showUpdateToast(sw);});});
    let reloaded=false; navigator.serviceWorker.addEventListener('controllerchange',()=>{if(reloaded)return; reloaded=true; location.reload();});
  })();
  