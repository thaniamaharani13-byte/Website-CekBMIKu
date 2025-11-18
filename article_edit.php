<?php
session_start();
require_once __DIR__ . '/koneksi.php'; // DIPERBAIKI: Path koneksi
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$id = intval($_GET['id'] ?? 0);

// Variabel disesuaikan dengan tabel
$judul = ''; $deskripsi = ''; $gambar = ''; $link = ''; $kategori_bmi = '';

if ($id) {
    // DIPERBAIKI: SELECT dari 'artikel' menggunakan 'id_artikel'
    $stmt = $conn->prepare('SELECT * FROM artikel WHERE id_artikel = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    
    // DIPERBAIKI: Mengisi variabel yang benar
    if ($row) { 
        $judul = $row['judul']; 
        $deskripsi = $row['deskripsi']; 
        $gambar = $row['gambar']; 
        $link = $row['link'];
        $kategori_bmi = $row['kategori_bmi'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $gambar = $_POST['gambar'] ?? '';
    $link = $_POST['link'] ?? '';
    $kategori_bmi = $_POST['kategori_bmi'] ?? '';

    if ($id) {
        // DIPERBAIKI: UPDATE tabel 'artikel' dengan kolom yang benar
        $stmt = $conn->prepare('UPDATE artikel SET judul=?, deskripsi=?, gambar=?, link=?, kategori_bmi=? WHERE id_artikel=?');
        $stmt->bind_param('sssssi', $judul, $deskripsi, $gambar, $link, $kategori_bmi, $id);
        $stmt->execute();
    } else {
        // DIPERBAIKI: INSERT ke tabel 'artikel' dengan kolom yang benar
        $stmt = $conn->prepare('INSERT INTO artikel (judul, deskripsi, gambar, link, kategori_bmi, tanggal) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->bind_param('sssss', $judul, $deskripsi, $gambar, $link, $kategori_bmi);
        $stmt->execute();
    }
    header('Location: articles.php');
    exit;
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="css/admin.css"></head>
<body><?php include 'partials/navbar.php'; ?>
<div class="container mt-4"><h4><?php echo $id? 'Edit' : 'Buat'; ?> Artikel</h4>

<form method="post">
  <div class="mb-3">
    <label>Judul</label>
    <input name="judul" class="form-control" value="<?php echo htmlspecialchars($judul); ?>">
  </div>

  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="10" class="form-control"><?php echo htmlspecialchars($deskripsi); ?></textarea>
  </div>

  <div class="mb-3">
    <label>Gambar (URL atau Nama File)</label>
    <input name="gambar" class="form-control" value="<?php echo htmlspecialchars($gambar); ?>">
  </div>

  <div class="mb-3">
    <label>Link</label>
    <input name="link" class="form-control" value="<?php echo htmlspecialchars($link); ?>">
  </div>

  <div class="mb-3">
    <label>Kategori BMI (cth: Normal, Kurus, Gemuk)</label>
    <input name="kategori_bmi" class="form-control" value="<?php echo htmlspecialchars($kategori_bmi); ?>">
  </div>

  <button class="btn btn-primary">Simpan</button> 
  <a class="btn btn-secondary" href="articles.php">Batal</a>
</form>

</div></body></html>