// Update inline Pendapatan chart to Chart.js v4
document.addEventListener('DOMContentLoaded', function() {
    var ctxEl = document.getElementById('pendapatanChart');
    if (ctxEl) {
        var ctx = ctxEl.getContext('2d');
        var revenueData = {
            labels: <?php echo json_encode(array_column($pendapatan, 'bulan')); ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?php echo json_encode(array_map(function($data) { return $data['qty'] * $data['harga']; }, $pendapatan)); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var revenueChart = new Chart(ctx, {
            type: 'bar',
            data: revenueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    const logoutButton = document.querySelector('.logout-button');
    const loader = document.querySelector('.loader');

    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            if (loader) loader.style.display = 'flex';
        });
    }
});
