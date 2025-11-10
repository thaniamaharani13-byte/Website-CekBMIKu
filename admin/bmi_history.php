<?php
session_start();
require_once __DIR__ . '/php/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$res = $conn->query("
    SELECT b.id, b.user_id, u.nama, b.tinggi, b.berat, b.bmi, b.kategori, b.tanggal
    FROM hasil_bmi b
    LEFT JOIN users u ON b.user_id = u.id
    ORDER BY b.tanggal DESC
");
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Riwayat BMI</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-4">
  <h4>Riwayat Perhitungan BMI</h4>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr><th>#</th><th>User</th><th>Tinggi (cm)</th><th>Berat (kg)</th><th>BMI</th><th>Kategori</th><th>Tanggal</th></tr>
      </thead>
      <tbody>
<?php $i=1; while($r = $res->fetch_assoc()): ?>
<tr>
  <td><?php echo $i++; ?></td>
  <td><?php echo htmlspecialchars($r['nama'] ?? $r['user_id']); ?></td>
  <td><?php echo htmlspecialchars($r['tinggi']); ?></td>
  <td><?php echo htmlspecialchars($r['berat']); ?></td>
  <td><?php echo htmlspecialchars($r['bmi']); ?></td>
  <td><?php echo htmlspecialchars($r['kategori']); ?></td>
  <td><?php echo htmlspecialchars($r['tanggal']); ?></td>
</tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
