// assets/js/theme.js
// Lightweight theme toggler
(function(){
  const root = document.documentElement;
  const key = 'stockbuku_theme';
  const current = localStorage.getItem(key) || 'light';
  if (current === 'dark') root.setAttribute('data-theme','dark');
  else root.removeAttribute('data-theme');

  window.toggleTheme = function() {
    const now = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    if (now === 'dark') root.setAttribute('data-theme','dark'); else root.removeAttribute('data-theme');
    localStorage.setItem(key, now);
  };
})();
