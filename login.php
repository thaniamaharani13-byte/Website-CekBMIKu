<?php
session_start();
require_once __DIR__ . '/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input ini bisa berisi 'email' (untuk user) atau 'username' (untuk admin)
    $email_or_username = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? '';

    if ($email_or_username === '' || $password === '') {
        $error = "Email/Username dan password wajib diisi.";
    } else {
        $login_success = false;

        // --- 1. Coba Login sebagai USER BIASA (via Email) ---
        $stmt_user = $conn->prepare("SELECT id_user, nama, password_hash FROM users WHERE email = ? LIMIT 1");
        $stmt_user->bind_param("s", $email_or_username);
        $stmt_user->execute();
        $res_user = $stmt_user->get_result();

        if ($row_user = $res_user->fetch_assoc()) {
            if (password_verify($password, $row_user['password_hash'])) {
                // Login USER Berhasil
                $_SESSION['id_user'] = $row_user['id_user'];
                $_SESSION['user_name'] = $row_user['nama'];
                $login_success = true;
                header("Location: index.php"); // Arahkan ke halaman user
                exit;
            }
        }
        $stmt_user->close();

        // --- 2. Jika Gagal sebagai User, Coba Login sebagai ADMIN (via Username) ---
        if (!$login_success) {
            // Cek ke tabel 'admin' menggunakan 'username'
            $stmt_admin = $conn->prepare('SELECT id, username, password, nama FROM admin WHERE username = ? LIMIT 1');
            $stmt_admin->bind_param('s', $email_or_username);
            $stmt_admin->execute();
            $res_admin = $stmt_admin->get_result();

            if ($row_admin = $res_admin->fetch_assoc()) {
                // Verifikasi password admin (dari kolom 'password' yg berisi hash)
                if (password_verify($password, $row_admin['password'])) {
                    // Login ADMIN Berhasil
                    $_SESSION['admin_id'] = $row_admin['id'];
                    $_SESSION['admin_name'] = $row_admin['nama'];
                    $login_success = true;
                    header('Location: dashboard.php'); // Arahkan ke dashboard admin
                    exit;
                }
            }
            $stmt_admin->close();
        }

        // --- 3. Jika Keduanya Gagal ---
        if (!$login_success) {
            $error = "Email/Username atau password salah.";
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

        <?php if ($error): ?>
          <p style="color:red; font-weight:bold;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
          <input type="text" name="email" id="email" placeholder="Email atau Username Admin" required>
          
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
  <script src="js/login.js"></script>
</body>
</html>