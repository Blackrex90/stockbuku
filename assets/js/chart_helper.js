// assets/js/chart_helper.js
// Helper to fetch API and render Chart.js charts (v4+). Minimal utilities for pendapatan and grafik pages.
async function fetchJSON(url){
  const res = await fetch(url, {credentials:'same-origin'});
  if(!res.ok) throw new Error('Network error');
  return res.json();
}

// Render monthly revenue chart
async function renderRevenueChart(ctx, apiUrl){
  const data = await fetchJSON(apiUrl);
  const labels = data.data.map(r => r.ym);
  const totals = data.data.map(r => parseFloat(r.total));
  return new Chart(ctx, {
    type: 'line',
    data: { labels, datasets: [{ label: 'Pendapatan', data: totals, borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary') || '#2563eb', backgroundColor: 'transparent' }] },
    options: { responsive:true, maintainAspectRatio:false }
  });
}

async function renderPopularBooksChart(ctx, apiUrl){
  const data = await fetchJSON(apiUrl);
  const labels = data.data.map(r => r.judulbuku);
  const totals = data.data.map(r => parseInt(r.total_qty));
  return new Chart(ctx, { type:'bar', data:{ labels, datasets:[{ label:'Jumlah Terjual', data:totals, backgroundColor: '#4f46e5' }] }, options:{ responsive:true } });
}
