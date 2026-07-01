// Chart.js v4 compatible - Bar Chart Example
// Set default font + color
Chart.defaults.font.family = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.color = '#292b2c';

const ctxBar = document.getElementById("myBarChart");
if (ctxBar) {
  const barChart = new Chart(ctxBar.getContext('2d'), {
    type: 'bar',
    data: {
      labels: ["January", "February", "March", "April", "May", "June"],
      datasets: [{
        label: "Revenue",
        data: [4215, 5312, 6251, 7841, 9821, 14984],
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          grid: { display: false },
          ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 6 }
        },
        y: {
          min: 0,
          max: 15000,
          ticks: { stepSize: 3000 },
          grid: { display: true }
        }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
}
