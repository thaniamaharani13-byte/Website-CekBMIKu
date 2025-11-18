<?php
session_start();
require_once __DIR__ . '/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // DIPERBAIKI: Hapus berdasarkan 'id_user', bukan 'id'
    $stmt_del = $conn->prepare('DELETE FROM users WHERE id_user = ?'); 
    $stmt_del->bind_param('i', $id);
    $stmt_del->execute();
    header('Location: users.php');
    exit;
}

// Ini sudah benar, mengambil 'id_user'
$stmt = $conn->query('SELECT id_user, nama, email, umur, jenis_kelamin FROM users ORDER BY nama ASC');
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pengguna - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-4">
  <h4>Manajemen Pengguna</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Jenis Kelamin</th><th>Umur</th><th>Aksi</th></tr></thead>
      <tbody>
<?php 
// DIPERBAIKI: Ganti semua '$u' di bawah menjadi '$r'
// DIPERBAIKI: Ganti semua 'id' di bawah menjadi 'id_user'
?>
<?php while($r = $stmt->fetch_assoc()): ?>
<tr>
  <td><?php echo htmlspecialchars($r['id_user']); ?></td>
  <td><?php echo htmlspecialchars($r['nama']); ?></td>
  <td><?php echo htmlspecialchars($r['email']); ?></td>
  <td><?php echo htmlspecialchars($r['jenis_kelamin']); ?></td>
  <td><?php echo htmlspecialchars($r['umur']); ?></td>
  <td>
    <a class="btn btn-sm btn-info" href="edit_user.php?id=<?php echo $r['id_user']; ?>">Edit</a>
    <a class="btn btn-sm btn-danger" href="users.php?delete=<?php echo $r['id_user']; ?>" onclick="return confirm('Hapus user ini?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>