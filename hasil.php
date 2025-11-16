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
$query = $conn->prepare("SELECT * FROM hasil_bmi WHERE id_hasil = ? AND id_user = ?");
$query->bind_param("ii", $id, $id_user);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan atau bukan milik Anda.");
}

// Ambil data
$height = $data['tinggi'];
$weight = $data['berat'];
$bmi    = $data['nilai_bmi'];
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

// ==== SARAN MAKANAN ====
$saran_makanan = [
    "Kurus" => "Tambahkan makanan tinggi kalori sehat seperti alpukat, kacang-kacangan, daging tanpa lemak, keju, dan susu.",
    "Normal" => "Pertahankan pola makan seimbang: karbohidrat, protein, serat, dan vitamin dalam porsi cukup.",
    "Kelebihan Berat Badan" => "Kurangi makanan tinggi gula & lemak. Pilih dada ayam, ikan, sayuran, buah rendah gula.",
    "Obesitas" => "Hindari gorengan, minuman manis. Fokus pada sayur, oats, ikan, dan air putih."
];

// ==== SARAN VITAMIN ====
$saran_vitamin = [
    "Kurus" => "Vitamin B1, B6, B12 untuk menambah nafsu makan.",
    "Normal" => "Vitamin C, D, dan Omega-3 untuk menjaga imunitas.",
    "Kelebihan Berat Badan" => "Vitamin D, Magnesium, dan Green Tea Extract.",
    "Obesitas" => "Omega-3, Vitamin D, dan serat tinggi (psyllium husk)."
];

$card_food = $saran_makanan[$cat] ?? "-";
$card_vit  = $saran_vitamin[$cat] ?? "-";

// ==== AMBIL ARTIKEL ====
$artikel = $conn->query("SELECT * FROM artikel ORDER BY id_artikel DESC LIMIT 3");
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

<!-- NAVBAR -->
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

  <!-- LEFT SECTION -->
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

  <!-- RIGHT SECTION -->
  <div class="right-section">

    <!-- CARD MAKANAN -->
    <div class="card makanan-card">
      <div>
        <h3>Saran Makanan untuk <?= $cat ?>:</h3>
        <p><?= $card_food ?></p>
      </div>
      <div class="makanan-image">
        <img src="asset/makanan.png" alt="makanan">
      </div>
    </div>

    <!-- CARD VITAMIN -->
    <div class="card vitamin-card">
      <div>
        <h3>Saran Vitamin untuk <?= $cat ?>:</h3>
        <p><?= $card_vit ?></p>
      </div>
      <div class="vitamin-image">
        <img src="asset/vitamin.png" alt="vitamin">
      </div>
    </div>

    <!-- ARTIKEL SECTION -->
    <div class="card artikel-section">
      <h3>Artikel Terkait</h3>
      <p class="sub-judul">Rekomendasi bacaan untuk kamu</p>

      <?php if ($artikel->num_rows > 0): ?>
        <?php while ($a = $artikel->fetch_assoc()): ?>
          <div class="artikel-card">
            <img src="asset/artikel.png" alt="img">
            <div>
              <h4><?= $a['judul'] ?></h4>
              <p><?= substr($a['isi'], 0, 90) . '...' ?></p>
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
    <p>© 2025 CekBMIku.id</p>
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
