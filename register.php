<?php
// ======================================
// register.php - Halaman Register PHP
// ======================================

// Sambungkan ke database
require_once __DIR__ . '/php/koneksi.php';

// Jika form dikirim (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $umur = (int) $_POST['age'];
    $gender = $_POST['gender'];

    // Validasi password
    if ($password !== $confirm) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // Enkripsi password sebelum disimpan
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah email sudah digunakan
    $cek = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar, silakan login!'); window.location.href='login.php';</script>";
        exit;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (nama, email, password, umur, gender) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $nama, $email, $password_hash, $umur, $gender);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menyimpan data.');</script>";
    }

    $stmt->close();
    $cek->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CekBMiku - Register</title>
  <link rel="stylesheet" href="css/register.css">

  <!-- Font Awesome untuk ikon mata -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <div class="container">
    <div class="left-section">
      <div class="logo">
        <img src="asset/Logo.png" alt="Logo CekBMiku">
      </div>

      <div class="register-box">
        <h3>Buat akunmu dan mulai perjalanan sehatmu!</h3>

        <form id="registerForm">
          <input type="text" id="name" placeholder="Nama Lengkap" required>
          <input type="email" id="email" placeholder="Email" required>

          <div class="password-wrapper">
            <input type="password" id="password" placeholder="Password" required>
            <i class="fa-solid fa-eye toggle-pass" data-target="password"></i>
          </div>

          <div class="password-wrapper">
            <input type="password" id="confirm" placeholder="Konfirmasi Password" required>
            <i class="fa-solid fa-eye toggle-pass" data-target="confirm"></i>
          </div>

          <div class="input-row">
            <input type="number" id="age" placeholder="Umur" required>
            <select id="gender" required>
              <option value="" disabled selected>Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <button type="submit" class="btn-register">Daftar</button>
        </form>
      </div>
    </div>

    <div class="right-section">
      <img src="asset/register.png" alt="Ilustrasi Register" />
    </div>
  </div>

  <!-- Hubungkan file JavaScript eksternal -->
  <script src="js/register.js"></script>
</body>
</html>
