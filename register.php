<?php
require_once __DIR__ . '/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $nama = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $umur = isset($_POST['age']) ? (int) $_POST['age'] : null;
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;

    // Validasi dasar
    if (empty($nama) || empty($email) || empty($password) || empty($confirm) || empty($umur) || empty($gender)) {
        echo "<script>alert('Semua kolom wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Validasi password cocok
    if ($password !== $confirm) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // Enkripsi password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $cek = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
    if (!$cek) {
        die("Query cek gagal: " . $conn->error);
    }
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar, silakan login.'); window.location='login.php';</script>";
        exit;
    }

    // Simpan data ke tabel users
    $stmt = $conn->prepare("
        INSERT INTO users (nama, email, password_hash, umur, jenis_kelamin) 
        VALUES (?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        die("Query insert gagal: " . $conn->error);
    }

    $stmt->bind_param("sssis", $nama, $email, $password_hash, $umur, $gender);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
        exit;
    } else {
        // Tampilkan pesan error MySQL kalau gagal
        die("Gagal menyimpan data ke database: " . $stmt->error);
    }

    // Tutup koneksi
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

        <!-- Form dengan method POST -->
        <form id="registerForm" method="POST" action="register.php">
          <input type="text" name="name" id="name" placeholder="Nama Lengkap" required>
          <input type="email" name="email" id="email" placeholder="Email" required>

          <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class="fa-solid fa-eye toggle-pass" data-target="password"></i>
          </div>

          <div class="password-wrapper">
            <input type="password" name="confirm" id="confirm" placeholder="Konfirmasi Password" required>
            <i class="fa-solid fa-eye toggle-pass" data-target="confirm"></i>
          </div>

          <div class="input-row">
            <input type="number" name="age" id="age" placeholder="Umur" required>
            <select name="gender" id="gender" required>
              <option value="" disabled selected>Jenis Kelamin</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
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

  <!-- Pastikan JS tidak mencegah submit -->
  <script src="js/register.js"></script>
</body>
</html>
