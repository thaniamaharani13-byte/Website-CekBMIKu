<?php
session_start();
require_once __DIR__ . '/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$id_halaman = 1; // Kita hanya mengedit 1 baris data
$pesan_sukses = '';

// 1. LOGIKA SIMPAN (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $gambar_url = $_POST['gambar_url'] ?? '';
    $paragraf_pembuka = $_POST['paragraf_pembuka'] ?? '';
    $tujuan_isi = $_POST['tujuan_isi'] ?? '';
    $fitur_isi = $_POST['fitur_isi'] ?? '';
    $email_footer = $_POST['email_footer'] ?? '';

    $stmt = $conn->prepare(
        'UPDATE tentang_kami SET 
        judul = ?, 
        gambar_url = ?, 
        paragraf_pembuka = ?, 
        tujuan_isi = ?, 
        fitur_isi = ?, 
        email_footer = ? 
        WHERE id = ?'
    );
    $stmt->bind_param('ssssssi', $judul, $gambar_url, $paragraf_pembuka, $tujuan_isi, $fitur_isi, $email_footer, $id_halaman);
    
    if ($stmt->execute()) {
        $pesan_sukses = "Data 'Tentang Kami' berhasil diperbarui!";
    } else {
        $pesan_sukses = "Gagal memperbarui data: " . $conn->error;
    }
}

// 2. LOGIKA AMBIL DATA (GET)
$stmt = $conn->prepare('SELECT * FROM tentang_kami WHERE id = ?');
$stmt->bind_param('i', $id_halaman);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
if (!$data) { $data = []; } // Hindari error jika data kosong

?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Halaman 'Tentang Kami'</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; ?>

<div class="container mt-4">
    <h4>Edit Halaman 'Tentang Kami'</h4>

    <?php if ($pesan_sukses): ?>
    <div class="alert alert-success">
        <?php echo $pesan_sukses; ?>
    </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Judul Halaman</label>
            <input name="judul" class="form-control" value="<?php echo htmlspecialchars($data['judul'] ?? ''); ?>">
        </div>
        
        <div class="mb-3">
            <label>URL Gambar</label>
            <input name="gambar_url" class="form-control" value="<?php echo htmlspecialchars($data['gambar_url'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label>Paragraf Pembuka</label>
            <textarea name="paragraf_pembuka" rows="5" class="form-control"><?php echo htmlspecialchars($data['paragraf_pembuka'] ?? ''); ?></textarea>
        </div>

        <hr>

        <div class="mb-3">
            <label>Isi 'Tujuan Kami' (Judulnya statis)</label>
            <textarea name="tujuan_isi" rows="3" class="form-control"><?php echo htmlspecialchars($data['tujuan_isi'] ?? ''); ?></textarea>
        </div>
        
        <div class="mb-3">
            <label>Isi 'Fitur Utama' (Judulnya statis, gunakan - (strip) untuk list)</label>
            <textarea name="fitur_isi" rows="6" class="form-control"><?php echo htmlspecialchars($data['fitur_isi'] ?? ''); ?></textarea>
        </div>
        
        <hr>

        <div class="mb-3">
            <label>Email Footer (Contoh: 📧 cekbmiku@gmail.com)</label>
            <input name="email_footer" class="form-control" value="<?php echo htmlspecialchars($data['email_footer'] ?? ''); ?>">
        </div>
      
      <button class="btn btn-primary">Simpan Perubahan</button>
      
    </form>
</div>

</body>
</html>