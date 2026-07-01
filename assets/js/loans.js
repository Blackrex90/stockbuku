// assets/js/loans.js
// Handles extend and return actions for loans page
document.addEventListener('DOMContentLoaded', function(){
  async function postJSON(url, data){
    const opts = { method:'POST', credentials:'same-origin', headers: {'Accept':'application/json'}, body: data };
    const res = await fetch(url, opts);
    if(!res.ok) throw new Error('Network response was not ok');
    return res.json();
  }

  function getCSRF(){ return window.STOCKB_K_CSRF || document.querySelector('meta[name="csrf-token"]')?.content || ''; }

  document.querySelectorAll('.btn-extend').forEach(btn => {
    btn.addEventListener('click', async function(){
      const loanId = this.dataset.loan;
      if (!confirm('Ajukan perpanjangan untuk pinjaman #' + loanId + '?')) return;
      showLoader();
      try{
        const fd = new FormData(); fd.append('_csrf', getCSRF()); fd.append('loan_id', loanId);
        const json = await postJSON('api/extend_loan.php', fd);
        hideLoader();
        if (json.success) {
          alert('Perpanjangan disetujui. Tanggal baru: ' + json.new_due);
          location.reload();
        } else {
          alert('Gagal: ' + (json.error || 'Unknown'));
        }
      }catch(err){ hideLoader(); alert('Terjadi kesalahan: ' + err.message); }
    });
  });

  document.querySelectorAll('.btn-return').forEach(btn => {
    btn.addEventListener('click', async function(){
      const loanId = this.dataset.loan;
      if (!confirm('Proses pengembalian untuk pinjaman #' + loanId + '?')) return;
      showLoader();
      try{
        const fd = new FormData(); fd.append('_csrf', getCSRF()); fd.append('loan_id', loanId);
        const json = await postJSON('api/return_book.php', fd);
        hideLoader();
        if (json.success) {
          alert('Pengembalian sukses. Denda: Rp ' + (json.fine || 0));
          location.reload();
        } else {
          alert('Gagal: ' + (json.error || 'Unknown'));
        }
      }catch(err){ hideLoader(); alert('Terjadi kesalahan: ' + err.message); }
    });
  });
});
