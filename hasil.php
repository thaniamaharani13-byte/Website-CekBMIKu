<?php
include 'koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
  die("Data tidak ditemukan.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM bmi_results WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
  die("Data tidak ditemukan di database.");
}

// Ambil data dari database
$height = $data['height_cm'];
$weight = $data['weight_kg'];
$bmi = $data['bmi'];
$note = $data['note'];

// Tentukan kategori BMI
if ($bmi < 18.5) {
  $category = "Kurus";
  $subtitle = "Berat kurang dari normal";
} elseif ($bmi < 25) {
  $category = "Normal";
  $subtitle = "Berat badan sehat";
} elseif ($bmi < 30) {
  $category = "Kelebihan Berat Badan";
  $subtitle = "Sedikit di atas normal";
} else {
  $category = "Obesitas";
  $subtitle = "Berat Terlalu Berlebih";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil CekBMIku</title>
  <link rel="stylesheet" href="../css/hasil.css">
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
        <li><a href="#tentang">Tentang Kami</a></li>
        <li><a href="#artikel">Artikel</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a href="#masukan">Masukan</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

  <div class="left-section">
    <h2>Hasil CekBMIku</h2>
    <h1><?php echo $category; ?></h1>
    <p class="sub"><?php echo $subtitle; ?></p>

    <div class="bmi-info">
      <p><strong>Tinggi:</strong> <?php echo $height; ?> cm</p>
      <p><strong>Berat:</strong> <?php echo $weight; ?> kg</p>
      <p><strong>BMI kamu:</strong> <span class="bmi"><?php echo $bmi; ?></span></p>
    </div>

    <p class="note"><?php echo $note; ?></p>

    <a href="index.php"><button class="cek-ulang">Cek Ulang</button></a>
  </div>

  <div class="right-section">

    <div class="card makanan-card">
      <h3>Saran Makanan untuk <?php echo $category; ?>:</h3>
      <p><?php echo $card_food; ?></p>
    </div>

    <div class="card vitamin-card">
      <h3>Saran Vitamin untuk <?php echo $category; ?>:</h3>
      <p><?php echo $card_vit; ?></p>
    </div>

  </div>
</div>

<footer class="footer">
  <div class="footer-bottom">
    <p>© 2025 CekBMIku.id</p>
  </div>
</footer>

</body>
</html>
