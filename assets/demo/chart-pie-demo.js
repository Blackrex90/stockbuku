// Chart.js v4 - Pie Chart Demo
(function(){
  Chart.defaults.font.family = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.color = '#292b2c';

  const canvas = document.getElementById('myPieChart');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ["Blue","Red","Yellow","Green"],
      datasets: [{
        data: [12.21,15.58,11.25,8.32],
        backgroundColor: ['#007bff','#dc3545','#ffc107','#28a745']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });
})();
