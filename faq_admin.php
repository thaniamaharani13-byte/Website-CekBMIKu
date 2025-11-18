<?php
session_start();
require_once __DIR__ . '/koneksi.php'; // Path koneksi
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

// Logika Hapus
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // DIPERBAIKI: Hapus berdasarkan 'id_faq'
    $stmt = $conn->prepare('DELETE FROM faq WHERE id_faq = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: faq_admin.php');
    exit;
}

// DIPERBAIKI: Ambil 'id_faq'
$res = $conn->query('SELECT id_faq, pertanyaan FROM faq ORDER BY id_faq ASC');
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manajemen FAQ - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; // Sertakan navbar admin Anda ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center">
    <h4>Manajemen FAQ</h4>
    <a class="btn btn-success" href="faq_edit.php">Buat Baru</a>
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-bordered">
      <thead>
        <tr><th>#</th><th>Pertanyaan</th><th>Aksi</th></tr>
      </thead>
      <tbody>
<?php $i=1; while($row = $res->fetch_assoc()): ?>
<tr>
  <td><?php echo $i++; ?></td>
  <td><?php echo htmlspecialchars($row['pertanyaan']); ?></td>
  <td>
    <a class="btn btn-sm btn-info" href="faq_edit.php?id=<?php echo $row['id_faq']; ?>">Edit</a>
    <a class="btn btn-sm btn-danger" href="faq_admin.php?delete=<?php echo $row['id_faq']; ?>" onclick="return confirm('Hapus FAQ ini?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>