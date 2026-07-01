// assets/js/loader.js
// Simple loader control
(function(){
  function showLoader(){
    let ov = document.getElementById('globalLoaderOverlay');
    if(!ov){
      ov = document.createElement('div'); ov.id = 'globalLoaderOverlay'; ov.className = 'loader-overlay';
      ov.innerHTML = '<div class="loader-uiverse"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>';
      document.body.appendChild(ov);
    }
    ov.classList.add('show');
  }
  function hideLoader(){
    const ov = document.getElementById('globalLoaderOverlay'); if(ov) ov.classList.remove('show');
  }
  window.showLoader = showLoader; window.hideLoader = hideLoader;
})();
