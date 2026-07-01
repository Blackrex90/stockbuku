// Chart.js v4 - Area Chart Demo
(function(){
  // Set defaults
  Chart.defaults.font.family = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.color = '#292b2c';

  const canvas = document.getElementById('myAreaChart');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        '2026-03-01','2026-03-02','2026-03-03','2026-03-04','2026-03-05','2026-03-06','2026-03-07','2026-03-08','2026-03-09','2026-03-10','2026-03-11','2026-03-12','2026-03-13'
      ],
      datasets: [{
        label: 'Sessions',
        tension: 0.3,
        backgroundColor: 'rgba(2,117,216,0.2)',
        borderColor: 'rgba(2,117,216,1)',
        pointRadius: 5,
        pointBackgroundColor: 'rgba(2,117,216,1)',
        pointBorderColor: 'rgba(255,255,255,0.8)',
        pointHoverRadius: 5,
        pointHoverBackgroundColor: 'rgba(2,117,216,1)',
        pointHitRadius: 50,
        pointBorderWidth: 2,
        data: [10000,30162,26263,18394,18287,28682,31274,33259,25849,24159,32651,31984,38451]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        x: {
          type: 'time',
          time: { unit: 'day', tooltipFormat: 'PPP' },
          grid: { display: false },
          ticks: { maxTicksLimit: 7 }
        },
        y: {
          min: 0,
          max: 40000,
          ticks: { stepSize: 10000 },
          grid: { color: 'rgba(0,0,0,0.125)' }
        }
      }
    }
  });
})();
