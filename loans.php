<?php
require 'inc/db.php';
require 'inc/helpers.php';
require 'cek.php';

$csrf = csrf_token();

// Fetch list of loans with book and user info
$stmt = $pdo->prepare('SELECT l.id, l.idbuku, l.iduser, l.borrowed_at, l.due_date, l.returned_at, l.fine_amount, l.extended_times, b.judulbuku, b.image, u.username FROM loans l LEFT JOIN buku b ON l.idbuku = b.idbuku LEFT JOIN login u ON l.iduser = u.iduser ORDER BY l.borrowed_at DESC');
$stmt->execute();
$loans = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Peminjaman - Stock Buku</title>
  <link href="assets/css/theme.css" rel="stylesheet">
  <link href="assets/css/loader.css" rel="stylesheet">
  <script>const STOCKB_K_CSRF = '<?= $csrf; ?>';</script>
</head>
<body>
  <?php require 'header.php' ?? null; ?>
  <main class="container" style="padding:24px;">
    <h1>Daftar Peminjaman</h1>
    <div class="card" style="padding:16px; margin-top:12px;">
      <div class="table-responsive">
        <table class="table" id="loansTable">
          <thead>
            <tr><th>ID</th><th>Buku</th><th>Peminjam</th><th>Dipinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th></tr>
          </thead>
          <tbody>
          <?php foreach($loans as $loan):
            $due = $loan['due_date'];
            $returned = $loan['returned_at'];
            $fine = (int)$loan['fine_amount'];
            $isLate = (!$returned && (new DateTime() > new DateTime($due)));
            ?>
            <tr data-loan-id="<?= (int)$loan['id']; ?>">
              <td><?= (int)$loan['id']; ?></td>
              <td><?= e($loan['judulbuku'] ?? ''); ?></td>
              <td><?= e($loan['username'] ?? ''); ?></td>
              <td><?= formatDateTime($loan['borrowed_at']); ?></td>
              <td><?= formatDateTime($loan['due_date']); ?></td>
              <td>
                <?php if ($loan['returned_at']): ?>
                  <span class="badge badge-success">Dikembalikan: <?= formatDateTime($loan['returned_at']); ?></span>
                <?php elseif ($isLate): ?>
                  <span class="badge badge-danger">Terlambat mengembalikan buku pada <?= formatDateTime($loan['due_date']); ?></span>
                <?php else: ?>
                  <span class="badge badge-success">Tepat waktu mengembalikan buku pada <?= formatDateTime($loan['due_date']); ?></span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!$loan['returned_at']): ?>
                  <button class="button-17 btn-extend" data-loan="<?= (int)$loan['id']; ?>">Perpanjang</button>
                  <button class="button-17 btn-return" data-loan="<?= (int)$loan['id']; ?>">Kembalikan</button>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script src="assets/js/loader.js"></script>
  <script src="assets/js/loans.js"></script>
</body>
</html>
