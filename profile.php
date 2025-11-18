<?php
// ==========================================
// profile.php - Halaman Profil User
// ==========================================
session_start();
require_once __DIR__ . '/koneksi.php';

// Cek login. Jika sesi hilang, arahkan ke login.
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 1. Ambil data user
$query = $conn->prepare("SELECT nama, email, umur, jenis_kelamin FROM users WHERE id_user = ?");
$query->bind_param("i", $id_user);
$query->execute();
$result_user = $query->get_result();
$user = $result_user->fetch_assoc();

// Jika data user tidak ditemukan, paksa ke login/logout
if (!$user) {
    // Kita arahkan ke logout.php agar sesi benar-benar bersih, lalu ke login.
    header("Location: logout.php"); 
    exit;
}

// 2. Ambil riwayat BMI user
$stmt = $conn->prepare("
    SELECT id, bmi, kategori, tanggal, weight_kg, height_cm
    FROM hasil_bmi
    WHERE user_id = ?
    ORDER BY tanggal DESC
");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result_bmi = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CekBMiku - Profil</title>
    <link rel="stylesheet" href="css/profile.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        <h4 class="riwayat-title">Riwayat Cek BMI</h4>

        <div class="riwayat-list" id="riwayatList">
            <?php if ($result_bmi->num_rows > 0): ?>
                <?php while ($row = $result_bmi->fetch_assoc()): 
                    // Tentukan warna latar belakang berdasarkan kategori (Opsional)
                    $kategori_class = strtolower(str_replace(' ', '-', $row['kategori']));
                ?>
                    <div class="riwayat-item <?= $kategori_class ?>">
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

        <div class="logout-container">
            <a href="logout.php" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>

    </div>
</div>

<script src="js/profile.js"></script>
</body>
</html>