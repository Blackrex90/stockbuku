// assets/js/validation.js
// Client-side validation helpers and accessibility improvements
(function(){
  // Enhance focus outlines for keyboard users
  document.addEventListener('keydown', function(e){
    if (e.key === 'Tab') document.documentElement.classList.add('user-using-keyboard');
  });

  // Attach simple client-side validators for common forms
  function setupFormValidation(selector){
    document.querySelectorAll(selector).forEach(function(form){
      form.setAttribute('novalidate','');
      form.addEventListener('submit', function(e){
        if (!form.checkValidity()){
          e.preventDefault(); e.stopPropagation();
          // use SweetAlert if available
          if (window.Swal) Swal.fire({icon:'error', title:'Form belum lengkap', text:'Isi semua field yang diperlukan.'});
          form.classList.add('was-validated');
        }
      });
    });
  }

  // Common forms to validate
  setupFormValidation('form.needs-validation');
  setupFormValidation('form');

  // Accessibility: ensure buttons/links with role=button have keyboard support
  document.querySelectorAll('[role="button"]').forEach(function(el){
    if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex','0');
    el.addEventListener('keypress', function(e){ if (e.key === 'Enter' || e.key === ' ') el.click(); });
  });
})();
