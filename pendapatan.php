<?php
require 'inc/db.php';
require 'inc/helpers.php';
require 'cek.php';

// Page shows monthly and yearly revenue charts
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pendapatan - Stock Buku</title>
  <link href="assets/css/theme.css" rel="stylesheet">
  <script>const STOCKB_K_CSRF = '<?= $csrf; ?>';</script>
</head>
<body>
  <?php require 'header.php' ?? null; ?>
  <main class="container" style="padding:24px;">
    <h1>Pendapatan</h1>
    <div class="card" style="padding:16px; margin-top:12px;">
      <h4>Pendapatan 12 Bulan Terakhir</h4>
      <div style="height:320px;"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="card" style="padding:16px; margin-top:12px;">
      <h4>Pendapatan Tahunan</h4>
      <div style="height:320px;"><canvas id="revenueYearChart"></canvas></div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="assets/js/chart_helper.js"></script>
  <script>
    (async function(){
      try{
        const ctx = document.getElementById('revenueChart').getContext('2d');
        await renderRevenueChart(ctx, 'api/pendapatan_api.php?range=12m');

        const ctx2 = document.getElementById('revenueYearChart').getContext('2d');
        await renderRevenueChart(ctx2, 'api/pendapatan_api.php?range=year');
      }catch(err){
        console.error(err);
        alert('Gagal memuat data pendapatan. Lihat console untuk detail.');
      }
    })();
  </script>
</body>
</html>
