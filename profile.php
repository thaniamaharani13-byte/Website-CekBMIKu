<?php
// ==========================================
// profile.php - Halaman Profil User
// ==========================================
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
$result = $query->get_result();
$user = $result->fetch_assoc();

// Jika data user tidak ditemukan
if (!$user) {
    echo "<script>alert('Data pengguna tidak ditemukan.'); window.location='login.php';</script>";
    exit;
}

// Ambil riwayat BMI user
$riwayatQuery = $conn->prepare("
    SELECT nilai_bmi, tinggi, berat, kategori, tanggal_input 
    FROM hasil_bmi 
    WHERE id_user = ?
    ORDER BY tanggal_input DESC
");
$riwayatQuery->bind_param("i", $id_user);
$riwayatQuery->execute();
$riwayat = $riwayatQuery->get_result();
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

        <!-- HEADER PROFIL -->
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

        <!-- FORM PROFIL -->
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

        <!-- RIWAYAT BMI -->
        <h4 class="riwayat-title">Riwayat Cek BMI</h4>

        <div class="riwayat-list" id="riwayatList">
            <?php if ($riwayat->num_rows > 0): ?>
                <?php while ($row = $riwayat->fetch_assoc()): ?>
                    <div class="riwayat-item">
                        <span class="status"><?= htmlspecialchars($row['kategori']); ?></span>
                        <p>Hasil BMI: <?= htmlspecialchars($row['nilai_bmi']); ?></p>
                        <p>Tinggi (cm): <?= htmlspecialchars($row['tinggi']); ?></p>
                        <p>Berat (kg): <?= htmlspecialchars($row['berat']); ?></p>
                        <span class="tanggal"><?= htmlspecialchars($row['tanggal_input']); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-history">Belum ada riwayat cek BMI.</p>
            <?php endif; ?>
        </div>

        <!-- LOGOUT -->
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
