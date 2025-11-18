<?php
session_start();
require_once __DIR__ . '/koneksi.php'; // Path ini sudah benar
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$id = intval($_GET['id'] ?? 0);
if (!$id) { header('Location: users.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // MENAMBAHKAN 'umur'
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $umur = intval($_POST['umur'] ?? 0); // Ambil umur
    
    // DIPERBAIKI: UPDATE 'nama', 'jenis_kelamin', dan 'umur'
    $stmt = $conn->prepare('UPDATE users SET nama=?, email=?, jenis_kelamin=?, umur=? WHERE id_user = ?');
    $stmt->bind_param('sssis', $nama, $email, $jenis_kelamin, $umur, $id); // sssis (string, string, string, integer, string)
    $stmt->execute();
    header('Location: users.php');
    exit;
}

// DIPERBAIKI: SELECT 'id_user', 'nama', 'jenis_kelamin', dan 'umur'
$stmt = $conn->prepare('SELECT id_user, nama, email, jenis_kelamin, umur FROM users WHERE id_user = ?');
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

  <div class="mb-3">
    <label>Nama lengkap</label>
    <input name="nama" class="form-control" value="<?php echo htmlspecialchars($user['nama']); ?>">
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
  </div>

  <div class="mb-3">
    <label>Umur</label>
    <input type="number" name="umur" class="form-control" value="<?php echo htmlspecialchars($user['umur']); ?>">
  </div>

  <div class="mb-3">
    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" class="form-select">
        <option value="L" <?php echo ($user['jenis_kelamin']=='L')?'selected':''; ?>>Laki-laki</option>
        <option value="P" <?php echo ($user['jenis_kelamin']=='P')?'selected':''; ?>>Perempuan</option>
    </select>
  </div>
  
  <button class="btn btn-primary">Simpan</button>
  <a class="btn btn-secondary" href="users.php">Batal</a>
</form></div></body></html>