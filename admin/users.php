<?php
session_start();
require_once __DIR__ . '/php/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: users.php');
    exit;
}

$res = $conn->query('SELECT id, nama, email, jenis_kelamin, umur FROM users ORDER BY id DESC');
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
<?php while($u = $res->fetch_assoc()): ?>
<tr>
  <td><?php echo htmlspecialchars($u['id']); ?></td>
  <td><?php echo htmlspecialchars($u['nama']); ?></td>
  <td><?php echo htmlspecialchars($u['email']); ?></td>
  <td><?php echo htmlspecialchars($u['jenis_kelamin']); ?></td>
  <td><?php echo htmlspecialchars($u['umur']); ?></td>
  <td>
    <a class="btn btn-sm btn-info" href="edit_user.php?id=<?php echo $u['id']; ?>">Edit</a>
    <a class="btn btn-sm btn-danger" href="users.php?delete=<?php echo $u['id']; ?>" onclick="return confirm('Hapus user ini?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
