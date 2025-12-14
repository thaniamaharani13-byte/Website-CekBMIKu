<?php
include 'koneksi.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil ID hasil dari URL
if (!isset($_GET['id'])) {
    die("Data tidak ditemukan.");
}

$id = intval($_GET['id']);

// Query untuk mengambil data hasil
$query = $conn->prepare("SELECT * FROM hasil_bmi WHERE id = ? AND user_id = ?");
$query->bind_param("ii", $id, $id_user);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan atau bukan milik Anda.");
}

// Ambil data (Ini sudah benar)
$height = $data['height_cm']; 
$weight = $data['weight_kg'];
$bmi    = $data['bmi'];
$ideal  = $data['berat_ideal']; 
$cat    = $data['kategori'];
$tgl    = $data['tanggal'];

// Tentukan subtitle berdasarkan kategori
switch ($cat) {
    case "Kurus":
        $subtitle = "Berat kurang dari normal";
        break;
    case "Normal":
        $subtitle = "Berat badan dalam kisaran sehat";
        break;
    case "Kelebihan Berat Badan":
        $subtitle = "Sedikit di atas normal";
        break;
    case "Obesitas":
        $subtitle = "Berat terlalu berlebih";
        break;
    default:
        $subtitle = "-";
}

// PENGAMBILAN SARAN DARI DATABASE (BARU)
$kategori_db = strtolower($cat); // Digunakan juga untuk query artikel

$stmt_saran = $conn->prepare("SELECT saran_makanan, saran_vitamin FROM saran_bmi WHERE kategori_bmi = ?");
$stmt_saran->bind_param("s", $kategori_db);
$stmt_saran->execute();
$saran_result = $stmt_saran->get_result();
$saran_data = $saran_result->fetch_assoc();

if ($saran_data) {
    $card_food = $saran_data['saran_makanan'];
    $card_vit  = $saran_data['saran_vitamin'];
} else {
    $card_food = "Saran makanan tidak ditemukan. Pastikan data 'saran_bmi' sudah terisi di database.";
    $card_vit  = "Saran vitamin tidak ditemukan. Pastikan data 'saran_bmi' sudah terisi di database.";
}
// AKHIR PENGAMBILAN SARAN

// KODE ARTIKEL TERBARU ANDA
$stmt_artikel = $conn->prepare("SELECT * FROM artikel WHERE LOWER(kategori_bmi) = ?");
$stmt_artikel->bind_param("s", $kategori_db);
$stmt_artikel->execute();
$artikel = $stmt_artikel->get_result();

if ($artikel->num_rows == 0) {
    // Fallback ke kategori 'normal' jika artikel tidak ditemukan
    $stmt_fallback = $conn->prepare("SELECT * FROM artikel WHERE LOWER(kategori_bmi) = 'normal'");
    $stmt_fallback->execute();
    $artikel = $stmt_fallback->get_result();
}
// AKHIR KODE ARTIKEL BARU
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil BMI</title>
  <link rel="stylesheet" href="css/hasil.css">
</head>
<body>

<nav>
  <div class="nav-content">
    <div class="nav-logo">
      <img src="asset/Logo.png" alt="Logo">
    </div>

    <div class="nav-right">
      <ul class="nav-menu">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="index.php#tentangkami">Tentang Kami</a></li>
        <li><a href="index.php#artikel">Artikel</a></li>
        <li><a href="index.php#faq">FAQ</a></li>
        <li><a href="index.php#masukan">Masukan</a></li>
      </ul>

      <div class="nav-profile">
      <a href="profile.php">
        <img src="asset/pp profile.png" alt="Profil">
      </a>
    </div>
    </div>
  </div>
</nav>


<div class="container">

  <div class="left-section">
    <h2>Hasil CekBMIku</h2>
    <h1 class="obesitas"><?php echo $cat; ?></h1>
    <p class="sub"><?php echo $subtitle; ?></p>

    <div class="bmi-info">
      <p><strong>Tinggi:</strong> <?= $height ?> cm</p>
      <p><strong>Berat:</strong> <?= $weight ?> kg</p>
      <p><strong>BMI kamu:</strong> <span class="bmi"><?= $bmi ?></span></p>
      <p><strong>Berat Ideal:</strong> <?= $ideal ?> kg</p>
    </div>

    <p class="note">Kategori kamu: <?= $cat ?></p>
    <a href="index.php"><button class="cek-ulang">Cek Ulang</button></a>
  </div>

  <div class="right-section">

    <div class="card makanan-card">
      <div>
        <h3>Saran Makanan untuk <?= $cat ?>:</h3>
        <p><?= $card_food ?></p>
      </div>
      <div class="makanan-image">
        <img src="asset/makanan.jpg" alt="makanan">
      </div>
    </div>

    <div class="card vitamin-card">
      <div>
        <h3>Saran Vitamin untuk <?= $cat ?>:</h3>
        <p><?= $card_vit ?></p>
      </div>
      <div class="vitamin-image">
        <img src="asset/vitamin.jpg" alt="vitamin">
      </div>
    </div>

    <div class="card artikel-section">
      <h3>Artikel Terkait</h3>
      <p class="sub-judul">Rekomendasi bacaan untuk kamu</p>

      <?php if ($artikel->num_rows > 0): ?>
        <?php while ($a = $artikel->fetch_assoc()): ?>
          <div class="artikel-card" 
          onclick="window.open('<?= $a['link']; ?>', '_blank')">
          <img src="asset/artikel/<?= $a['gambar']; ?>" alt="<?= $a['judul']; ?>">
          <div>
        <h4><?= $a['judul'] ?></h4>
        <p><?= substr($a['deskripsi'], 0, 90) . '...' ?></p>
        </div>
        </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="color:#888">Belum ada artikel tersedia.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<footer class="footer">
  <div class="footer-bottom">
    <p>© 2025 CekBMIKu.id</p>
  </div>
</footer>

<script>
sessionStorage.setItem('bmiResult', JSON.stringify({
  height: <?= $height ?>,
  weight: <?= $weight ?>,
  bmi: <?= $bmi ?>,
  idealWeight: <?= $ideal ?>,
  category: "<?= $cat ?>",
  gender: "<?= isset($_SESSION['gender']) ? $_SESSION['gender'] : 'unknown' ?>"
}));
</script>

<script src="js/result.js"></script>

</body>
</html>
