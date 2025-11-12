<?php
session_start();
require_once __DIR__ . '/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Email dan password wajib diisi.";
    } else {
        // Cek user di database
        $stmt = $conn->prepare("SELECT id_user, nama, email, password_hash FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                // Jika benar → simpan ke session
                $_SESSION['user_id'] = $row['id_user'];
                $_SESSION['user_name'] = $row['nama'];

                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Email tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CekBMiku - Login</title>
  <link rel="stylesheet" href="css/login.css">

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

      <div class="login-box">
        <h3>Lanjutkan perjalanan sehatmu, masuk disini!</h3>

        <form id="loginForm" method="POST" action="login.php">
            <input type="email" name="email" id="email" placeholder="Email" required>
            <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password" required>
        <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>
        <button type="submit" class="btn-login">Masuk</button>
        </form>


        <p class="register-text">Belum Punya Akun?</p>
        <button class="btn-register" onclick="window.location.href='register.php'">Register</button>
      </div>
    </div>

    <div class="right-section">
      <img src="asset/Login.png" alt="Illustrasi" />
    </div>
  </div>

  <!-- Hubungkan file JavaScript eksternal -->
  <script src="js/login.js"></script>
</body>
</html>
