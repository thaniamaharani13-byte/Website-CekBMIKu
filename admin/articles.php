<?php
session_start();
require_once __DIR__ . '/php/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

// Hapus artikel
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM artikel WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: articles.php');
    exit;
}

// Ambil semua artikel
$res = $conn->query('SELECT * FROM artikel ORDER BY tanggal DESC');
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Artikel - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center">
    <h4>Artikel</h4>
    <a class="btn btn-success" href="article_edit.php">Buat Baru</a>
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-bordered">
      <thead>
        <tr><th>#</th><th>Judul</th><th>Tanggal</th><th>Aksi</th></tr>
      </thead>
      <tbody>
<?php $i=1; while($a = $res->fetch_assoc()): ?>
<tr>
  <td><?php echo $i++; ?></td>
  <td><?php echo htmlspecialchars($a['judul']); ?></td>
  <td><?php echo htmlspecialchars($a['tanggal']); ?></td>
  <td>
    <a class="btn btn-sm btn-info" href="article_edit.php?id=<?php echo $a['id']; ?>">Edit</a>
    <a class="btn btn-sm btn-danger" href="articles.php?delete=<?php echo $a['id']; ?>" onclick="return confirm('Hapus artikel ini?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
