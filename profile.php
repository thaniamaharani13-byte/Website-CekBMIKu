<?php
// ==========================================
// profile.php - Halaman Profil User
// ==========================================
session_start();
require_once __DIR__ . '/php/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = $conn->prepare("SELECT nama, email, umur, gender FROM users WHERE id_user = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Ambil riwayat cek BMI user
$riwayatQuery = $conn->prepare("SELECT hasil_bmi, tinggi, berat, status_bmi, tanggal_input 
                                FROM bmi_history 
                                WHERE id_user = ? 
                                ORDER BY tanggal_input DESC");
$riwayatQuery->bind_param("i", $user_id);
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
  <!-- Tombol Back -->
  <button class="back-btn" onclick="window.location.href='index.html'">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </button>

  <div class="profile-container">
    <div class="profile-card">
      <div class="profile-header">
        <div class="profile-info">
          <img src="asset/pp profile.png" alt="Foto Profil" class="profile-img" />
          <div>
            <h3 id="userName">Nama</h3>
            <p id="userEmail">email</p>
          </div>
        </div>
        <button class="edit-btn" id="editBtn">Edit</button>
      </div>

      <div class="profile-form">
        <div class="form-group">
          <label for="umur">Umur</label>
          <input type="text" id="umur" placeholder="Umur" readonly />
        </div>

        <div class="form-group">
          <label for="gender">Jenis Kelamin</label>
          <input type="text" id="gender" placeholder="Jenis Kelamin" readonly />
        </div>
      </div>

      <h4 class="riwayat-title">Riwayat Cek BMI</h4>

      <div class="riwayat-list" id="riwayatList">
        <div class="riwayat-item">
          <span class="status">Obesitas</span>
          <p>Hasil BMI: 000</p>
          <p>Tinggi (cm): 000</p>
          <p>Berat (kg): 000</p>
          <span class="tanggal">Tanggal</span>
        </div>

        <div class="riwayat-item">
          <span class="status">Obesitas</span>
          <p>Hasil BMI: 000</p>
          <p>Tinggi (cm): 000</p>
          <p>Berat (kg): 000</p>
          <span class="tanggal">Tanggal</span>
        </div>

        <div class="riwayat-item">
          <span class="status">Obesitas</span>
          <p>Hasil BMI: 000</p>
          <p>Tinggi (cm): 000</p>
          <p>Berat (kg): 000</p>
          <span class="tanggal">Tanggal</span>
        </div>
      </div>
      <div class="logout-container">
        <button class="logout-btn" id="logoutBtn">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </div>
    </div>
  </div>

  <script src="js/profile.js"></script>
</body>
</html>