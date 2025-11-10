<?php
session_start();
require_once __DIR__ . '/php/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: users.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $stmt = $conn->prepare('UPDATE users SET fullname=?, email=?, gender=? WHERE id=?');
    $stmt->bind_param('sssi', $fullname, $email, $gender, $id);
    $stmt->execute();
    header('Location: users.php');
    exit;
}

$stmt = $conn->prepare('SELECT id, fullname, email, gender FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
if (!$user) { header('Location: users.php'); exit; }
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="css/admin.css"></head>
<body><?php include 'partials/navbar.php'; ?>
<div class="container mt-4"><h4>Edit Pengguna</h4>
<form method="post">
  <div class="mb-3"><label>Nama lengkap</label><input name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>"></div>
  <div class="mb-3"><label>Email</label><input name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>"></div>
  <div class="mb-3"><label>Gender</label><select name="gender" class="form-select"><option value="L" <?php echo ($user['gender']=='L')?'selected':''; ?>>L</option><option value="P" <?php echo ($user['gender']=='P')?'selected':''; ?>>P</option></select></div>
  <button class="btn btn-primary">Simpan</button>
  <a class="btn btn-secondary" href="users.php">Batal</a>
</form></div></body></html>
