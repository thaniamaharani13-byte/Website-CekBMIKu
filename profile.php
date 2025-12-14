<?php
// profile.php - Halaman Profil User (DENGAN GRAFIK BMI)
session_start();
require_once __DIR__ . '/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil data user
$query = $conn->prepare("SELECT nama, email, umur, jenis_kelamin FROM users WHERE id_user = ?");
$query->bind_param("i", $id_user);
$query->execute();
$result_user = $query->get_result();
$user = $result_user->fetch_assoc();

if (!$user) {
    header("Location: logout.php"); 
    exit;
}

// Ambil Riwayat untuk Daftar
$stmt_list = $conn->prepare("
    SELECT bmi, kategori, tanggal, weight_kg, height_cm, id
    FROM hasil_bmi
    WHERE user_id = ?
    ORDER BY tanggal DESC
");
$stmt_list->bind_param("i", $id_user);
$stmt_list->execute();
$result_list = $stmt_list->get_result(); 

// Ambil Riwayat untuk Grafik 
$stmt_chart = $conn->prepare("
    SELECT bmi, tanggal
    FROM hasil_bmi
    WHERE user_id = ?
    ORDER BY tanggal DESC
    LIMIT 5
");
$stmt_chart->bind_param("i", $id_user);
$stmt_chart->execute();
$result_chart = $stmt_chart->get_result();

$chartData = [];
while ($row = $result_chart->fetch_assoc()) {
    $chartData[] = $row;
}
$chartData = array_reverse($chartData);

$labels = array_map(function($item) {
    return date('d M', strtotime($item['tanggal']));
}, $chartData);

$data_bmi = array_column($chartData, 'bmi');

$labels_json = json_encode($labels);
$data_bmi_json = json_encode($data_bmi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CekBMiku - Profil</title>
    <link rel="stylesheet" href="css/profile.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
</head>

<body>

<button class="back-btn" onclick="window.history.back()">
    <i class="fa-solid fa-arrow-left"></i> Kembali
</button>

<div class="profile-container">
    <div class="profile-card">

        <div class="profile-header">
            <div class="profile-info">
                <img src="asset/pp profile.png" alt="Foto Profil" class="profile-img" />
                <div>
                    <h3 id="userName"><?= htmlspecialchars($user['nama']); ?></h3>
                    <p id="userEmail"><?= htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <button class="edit-btn" id="editBtn">Edit</button>
        </div>

        <div class="profile-form">
            <div class="form-group">
                <label for="umur">Umur</label>
                <input type="text" id="umur" value="<?= htmlspecialchars($user['umur']); ?>" readonly />
            </div>

            <div class="form-group">
                <label for="gender">Jenis Kelamin</label>
                <input type="text" id="gender" 
                    value="<?= $user['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>" 
                    readonly />
            </div>
        </div>

        <?php if (count($chartData) > 1): ?>
        <h4 class="riwayat-title" style="margin-top: 40px;">Tren Perubahan BMI (5 Terakhir)</h4> <div class="chart-container" style="max-height: 300px; margin-bottom: 40px;"> <canvas id="bmiChart"></canvas>
        </div>
        <?php elseif (count($chartData) == 1): ?>
        <p class="no-history" style="margin-top: 40px; margin-bottom: 40px;">BMI Anda tercatat 1 kali (Diperlukan minimal 2 data untuk melihat tren).</p>
        <?php endif; ?>
        <h4 class="riwayat-title">Riwayat Cek BMI</h4>

        <div class="riwayat-list mt-3" id="riwayatList"> 
            <?php if ($result_list->num_rows > 0): ?>
                <?php while ($row = $result_list->fetch_assoc()): ?>
                    <div class="riwayat-item">
                        <span class="status"><?= htmlspecialchars($row['kategori']); ?></span>
                        
                        <p>Hasil BMI: <?= htmlspecialchars($row['bmi']); ?></p>
                        <p>Tinggi (cm): <?= htmlspecialchars($row['height_cm']); ?></p>
                        <p>Berat (kg): <?= htmlspecialchars($row['weight_kg']); ?></p>
                        
                        <a href="hasil.php?id=<?= $row['id'] ?>" class="detail-link">Lihat Detail</a>
                        
                        <span class="tanggal"><?= htmlspecialchars(date('d F Y', strtotime($row['tanggal']))); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-history">Belum ada riwayat cek BMI.</p>
            <?php endif; ?>
        </div>

        <div class="logout-container" style="margin-top: 40px;"> <a href="logout.php" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = <?php echo $labels_json; ?>;
    const data_bmi = <?php echo $data_bmi_json; ?>;
    
    if (data_bmi && data_bmi.length > 1) {
        const ctx = document.getElementById('bmiChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai BMI',
                    data: data_bmi,
                    borderColor: '#D8518C', 
                    backgroundColor: 'rgba(74, 142, 245, 0.2)', 
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#D8518C',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Tren BMI 5 Pengecekan Terakhir',
                        font: {
                            size: 14
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Nilai BMI'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal Pengecekan'
                        }
                    }
                }
            }
        });
    }
});
</script>

<script src="js/profile.js"></script>
</body>
</html>
