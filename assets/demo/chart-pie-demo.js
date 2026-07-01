// Chart.js v4 compatible - Pie Chart Example
Chart.defaults.font.family = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.color = '#292b2c';

const ctxPie = document.getElementById('myPieChart');
if (ctxPie) {
  const pieChart = new Chart(ctxPie.getContext('2d'), {
    type: 'pie',
    data: {
      labels: ['Blue','Red','Yellow','Green'],
      datasets: [{
        data: [12.21,15.58,11.25,8.32],
        backgroundColor: ['#007bff','#dc3545','#ffc107','#28a745']
      }]
    },
    options: { responsive:true, maintainAspectRatio:false }
  });
}
