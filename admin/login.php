<?php
session_start();
require_once __DIR__ . '/php/koneksi.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, username, password, nama FROM admin WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (md5($password) === $row['password']) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $err = 'Username atau password salah.';
        }
    } else {
        $err = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login - CekBMIKu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title mb-3">Admin Panel - CekBMIKu</h4>
          <?php if($err): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
          <?php endif; ?>
          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-primary">Login</button>
            </div>
          </form>
        </div>
      </div>
      <p class="text-center text-muted mt-3">Gunakan akun admin untuk mengakses.</p>
    </div>
  </div>
</div>
</body>
</html>
