<?php
require 'inc/db.php';
require 'inc/helpers.php';
require 'cek.php';
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Grafik - Stock Buku</title>
  <link href="assets/css/theme.css" rel="stylesheet">
  <script>const STOCKB_K_CSRF = '<?= $csrf; ?>';</script>
</head>
<body>
  <?php require 'header.php' ?? null; ?>
  <main class="container" style="padding:24px;">
    <h1>Grafik Buku Populer</h1>
    <div class="card" style="padding:16px; margin-top:12px;">
      <div style="height:420px;"><canvas id="popularChart"></canvas></div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="assets/js/chart_helper.js"></script>
  <script>
    (async function(){
      try{
        const ctx = document.getElementById('popularChart').getContext('2d');
        await renderPopularBooksChart(ctx, 'api/grafik_api.php');
      }catch(err){
        console.error(err);
        alert('Gagal memuat data grafik.');
      }
    })();
  </script>
</body>
</html>
