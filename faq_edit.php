<?php
session_start();
require_once __DIR__ . '/koneksi.php'; // Path koneksi
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$id = intval($_GET['id'] ?? 0);
$pertanyaan = '';
$jawaban = '';

// Logika Ambil Data (jika mode Edit)
if ($id) {
    // DIPERBAIKI: Select berdasarkan 'id_faq'
    $stmt = $conn->prepare('SELECT pertanyaan, jawaban FROM faq WHERE id_faq = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row) {
        $pertanyaan = $row['pertanyaan'];
        $jawaban = $row['jawaban'];
    }
}

// Logika Simpan Data (saat form disubmit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pertanyaan = $_POST['pertanyaan'] ?? '';
    $jawaban = $_POST['jawaban'] ?? '';

    if ($id) {
        // Mode UPDATE (Edit)
        // DIPERBAIKI: Update berdasarkan 'id_faq'
        $stmt = $conn->prepare('UPDATE faq SET pertanyaan = ?, jawaban = ? WHERE id_faq = ?');
        $stmt->bind_param('ssi', $pertanyaan, $jawaban, $id);
    } else {
        // Mode INSERT (Buat Baru) - Ini sudah benar
        $stmt = $conn->prepare('INSERT INTO faq (pertanyaan, jawaban) VALUES (?, ?)');
        $stmt->bind_param('ss', $pertanyaan, $jawaban);
    }
    $stmt->execute();
    header('Location: faq_admin.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $id ? 'Edit' : 'Buat'; ?> FAQ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php include 'partials/navbar.php'; // Sertakan navbar admin Anda ?>

<div class="container mt-4">
<h4><?php echo $id ? 'Edit' : 'Buat'; ?> FAQ</h4>

<form method="post">
  <div class="mb-3">
    <label>Pertanyaan</label>
    <textarea name="pertanyaan" rows="3" class="form-control"><?php echo htmlspecialchars($pertanyaan); ?></textarea>
  </div>
  
  <div class="mb-3">
    <label>Jawaban</label>
    <textarea name="jawaban" rows="10" class="form-control"><?php echo htmlspecialchars($jawaban); ?></textarea>
  </div>
  
  <button class="btn btn-primary">Simpan</button>
  <a class="btn btn-secondary" href="faq_admin.php">Batal</a>
</form>

</div>
</body>
</html>