<?php
session_start();
require_once __DIR__ . '/koneksi.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil statistik ringkasan
$user_count = $conn->query('SELECT COUNT(*) as cnt FROM users')->fetch_assoc()['cnt'] ?? 0;
$bmi_count = $conn->query('SELECT COUNT(*) as cnt FROM hasil_bmi')->fetch_assoc()['cnt'] ?? 0;
$article_count = $conn->query('SELECT COUNT(*) as cnt FROM artikel')->fetch_assoc()['cnt'] ?? 0;

// ===============================================
// LOGIKA DATA GRAFIK DISTRIBUSI KATEGORI BMI
// ===============================================
// Ambil jumlah riwayat per kategori BMI
$stmt = $conn->query("SELECT kategori, COUNT(*) as total FROM hasil_bmi GROUP BY kategori ORDER BY total DESC");

$chartLabels = []; 
$chartData = [];   

if ($stmt) {
    while ($row = $stmt->fetch_assoc()) {
        $chartLabels[] = $row['kategori'];
        $chartData[] = (int)$row['total']; 
    }
}

// Persiapkan data dalam format JSON
$labels_json = json_encode($chartLabels);
$data_json = json_encode($chartData);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Admin CekBMIKu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-4">
    <h3>Dashboard</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 bg-primary text-white">
                <h5>Total Pengguna</h5>
                <p class="display-6"><?php echo (int)$user_count; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-success text-white">
                <h5>Total Perhitungan BMI</h5>
                <p class="display-6"><?php echo (int)$bmi_count; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-info text-white">
                <h5>Total Artikel</h5>
                <p class="display-6"><?php echo (int)$article_count; ?></p>
            </div>
        </div>
    </div>
  
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Distribusi Kategori BMI (Semua Riwayat Pengecekan)
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="bmiDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            </div>
    </div>

    <hr class="mt-4">
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

<div id="bmi-chart-labels" data-content='<?php echo htmlspecialchars($labels_json, ENT_QUOTES, 'UTF-8'); ?>' style="display: none;"></div>
<div id="bmi-chart-data" data-content='<?php echo htmlspecialchars($data_json, ENT_QUOTES, 'UTF-8'); ?>' style="display: none;"></div>

<script src="js/admin_chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>