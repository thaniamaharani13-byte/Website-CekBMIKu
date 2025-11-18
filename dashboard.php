<?php
session_start();
require_once __DIR__ . '/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil statistik
$user_count = $conn->query('SELECT COUNT(*) as cnt FROM users')->fetch_assoc()['cnt'] ?? 0;
$bmi_count = $conn->query('SELECT COUNT(*) as cnt FROM hasil_bmi')->fetch_assoc()['cnt'] ?? 0;
$article_count = $conn->query('SELECT COUNT(*) as cnt FROM artikel')->fetch_assoc()['cnt'] ?? 0;
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Admin CekBMIKu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-4">
  <h3>Dashboard</h3>
  <div class="row">
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Pengguna</h5>
        <p class="display-6"><?php echo (int)$user_count; ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Perhitungan BMI</h5>
        <p class="display-6"><?php echo (int)$bmi_count; ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Artikel</h5>
        <p class="display-6"><?php echo (int)$article_count; ?></p>
      </div>
    </div>
  </div>
  <hr>
  <h5>Aktivitas Terbaru</h5>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>#</th><th>User ID</th><th>BMI</th><th>Kategori</th><th>Tanggal</th></tr></thead>
      <tbody>
<?php
$stmt = $conn->query('SELECT id, user_id, bmi, kategori, tanggal FROM hasil_bmi ORDER BY tanggal DESC LIMIT 10');
if ($stmt) {
    $i = 1;
    while($r = $stmt->fetch_assoc()) {
        echo '<tr>
                <td>'.($i++).'</td>
                <td>'.htmlspecialchars($r['user_id']).'</td>
                <td>'.htmlspecialchars($r['bmi']).'</td>
                <td>'.htmlspecialchars($r['kategori']).'</td>
                <td>'.htmlspecialchars($r['tanggal']).'</td>
              </tr>';
    }
}
?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
