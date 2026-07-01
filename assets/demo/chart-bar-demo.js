// Chart.js v4 - Bar Chart Demo
(function(){
  Chart.defaults.font.family = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.color = '#292b2c';

  const canvas = document.getElementById('myBarChart');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["January","February","March","April","May","June"],
      datasets: [{
        label: 'Revenue',
        backgroundColor: 'rgba(2,117,216,1)',
        borderColor: 'rgba(2,117,216,1)',
        data: [4215,5312,6251,7841,9821,14984]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: {
          grid: { display: false },
          ticks: { maxTicksLimit: 6 }
        },
        y: {
          min: 0,
          max: 15000,
          ticks: { stepSize: 3000 },
          grid: { display: true }
        }
      }
    }
  });
})();
