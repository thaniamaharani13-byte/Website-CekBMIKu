<?php
include 'koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// AMBIL SEMUA ARTIKEL DARI DATABASE
$artikelQuery = $conn->query("SELECT * FROM artikel ORDER BY tanggal DESC");
$artikelData = [];
while ($row = $artikelQuery->fetch_assoc()) {
    $artikelData[] = $row;
}

// PROSES HITUNG BMI JIKA SUBMIT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $height = floatval($_POST['height']);
    $weight = floatval($_POST['weight']);
    $gender = $_POST['gender'] ?? 'unknown';
    $id_user = $_SESSION['id_user'];

    // Hitung BMI
    $bmi = $weight / pow(($height / 100), 2);
    $bmi = round($bmi, 1);

    // Hitung Berat Ideal
    $base = $height - 100;
    if ($gender == "male") {
        $ideal = $base - ($base * 0.10);
    } elseif ($gender == "female") {
        $ideal = $base - ($base * 0.15);
    } else {
        $ideal = $base;
    }
    $ideal = round($ideal, 1);

    // Tentukan kategori
    if ($bmi < 18.5) {
        $kategori = "Kurus";
    } elseif ($bmi < 25) {
        $kategori = "Normal";
    } elseif ($bmi < 30) {
        $kategori = "Kelebihan Berat Badan";
    } else {
        $kategori = "Obesitas";
    }

    // Simpan ke database
    $stmt = $conn->prepare("
        INSERT INTO hasil_bmi (id_user, berat, tinggi, nilai_bmi, berat_ideal, kategori)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("idddds", 
        $id_user,
        $weight,
        $height,
        $bmi,
        $ideal,
        $kategori
    );

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        header("Location: hasil.php?id=$last_id");
        exit();
    } else {
        die("Gagal menyimpan data: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CekBMIku</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- ==================== NAVBAR ==================== -->
<nav>
  <div class="nav-content">
    <div class="nav-logo">
      <img src="asset/Logo.png" alt="Logo">
    </div>

    <div class="nav-right">
      <ul class="nav-menu">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="#tentangkami">Tentang Kami</a></li>
        <li><a href="#artikel">Artikel</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a href="#masukan">Masukan</a></li>
      </ul>
    </div>

    <div class="nav-profile">
      <a href="profile.php">
        <img src="asset/pp profile.png" alt="Profil">
      </a>
    </div>
  </div>
</nav>


<!-- ==================== FORM BMI ==================== -->
<section id="home" class="container">
    
    <div class="left-section">
      <h2>CekBMIku – Cara Mudah Tahu Berat Badan Idealmu!</h2>
      <p>
        Memiliki tubuh ideal tentu menjadi dambaan banyak orang.
        Bukan hanya soal penampilan, tetapi juga sebagai tanda bahwa tubuh berada dalam kondisi sehat dan seimbang.
        Yuk, cari tahu sekarang lewat BMI Kalkulator di CekBMIku!
      </p>
    </div>

    <div class="right-section">
      <div class="gender-container">
        <div class="gender" id="male">
          <img src="asset/animoji-cowok.png" alt="Pria">
          <p>Pria</p>
        </div>

        <div class="gender" id="female">
          <img src="asset/animoji-cewek.png" alt="Wanita">
          <p>Wanita</p>
        </div>
      </div>

      <div class="form">
        <form method="POST" action="" id="bmiForm">
          <input type="hidden" name="gender" id="genderInput" required>
          <input type="number" name="height" class="height" placeholder="Masukkan Tinggi Badan (cm)" required>
          <input type="number" name="weight" class="weight" placeholder="Masukkan Berat Badan (kg)" required>
          <button type="submit">Cek BMI</button>
        </form>
      </div>
    </div>

</section>



<!-- ==================== TENTANG KAMI ==================== -->
<section id="tentangkami" class="about-section">

  <div class="about-hero">
    <img src="asset/BannerTentangKami.png" alt="Banner Tentang Kami" class="about-banner">
  </div>

  <div class="about-content">
    <h2><strong>CekBMIku – Cek Sehatmu!</strong></h2>
    <p>
      Website kalkulator BMI yang membantu pengguna mengetahui apakah berat badan mereka sudah ideal berdasarkan tinggi badan.
      Selain menghitung BMI, CekBMIku juga memberikan penjelasan kategori berat badan serta tips menjaga pola hidup sehat.
    </p>

    <div class="info-container">
      <div class="info-box">
        <p>
          <strong>Tujuan kami sederhana:</strong><br>
          Membantu setiap orang lebih sadar akan kesehatannya melalui cara yang praktis dan menyenangkan.
        </p>
      </div>

      <div class="info-box">
        <p>
          <strong>Fitur Utama:</strong><br>
          - Kalkulator BMI interaktif<br>
          - Penjelasan hasil dan kategori<br>
          - Tips sehat sesuai hasil BMI<br>
          - FAQ dan artikel seputar kesehatan
        </p>
      </div>
    </div>

    <p class="footer-email">📧 cekbmiku@gmail.com</p>
  </div>

</section>



<!-- ==================== ARTIKEL (DINAMIS DATABASE) ==================== -->
<section id="artikel" class="artikel-section">
  <h2>Artikel & Tips Hidup Sehat</h2>

  <div class="artikel-container">

    <?php if (!empty($artikelData)): ?>
        <?php foreach ($artikelData as $a): ?>

            <a href="<?= htmlspecialchars($a['link']) ?>" class="artikel-card" target="_blank">

                <img 
                    src="uploads/artikel/<?= htmlspecialchars($a['gambar']) ?>" 
                    alt="<?= htmlspecialchars($a['judul']) ?>"
                >

                <div class="artikel-text">
                    <h3><?= htmlspecialchars($a['judul']) ?></h3>
                    <p><?= htmlspecialchars(substr($a['deskripsi'], 0, 120)) ?>...</p>
                </div>

            </a>

        <?php endforeach; ?>

    <?php else: ?>
        <p>Tidak ada artikel tersedia saat ini.</p>
    <?php endif; ?>

  </div>
</section>



<!-- ==================== FAQ ==================== -->
<section id="faq" class="faq-section">
  <h2>Yuk, Kenali Lebih Dalam Tentang BMI!</h2>

  <div class="faq-container">

    <div class="box">
      <div class="box_head">Apa itu BMI?</div>
      <div class="box_text">
        BMI (Body Mass Index) digunakan untuk mengetahui apakah berat badan seseorang sudah proporsional.
      </div>
    </div>

    <div class="box">
      <div class="box_head">Bagaimana cara menghitung BMI?</div>
      <div class="box_text">
        BMI = berat badan (kg) / (tinggi (m))²
      </div>
    </div>

    <div class="box">
      <div class="box_head">Apa arti dari hasil BMI saya?</div>
      <div class="box_text">
        < 18.5 → Kurus <br>
        18.5 – 24.9 → Normal <br>
        25 – 29.9 → Kelebihan berat badan <br>
        ≥ 30 → Obesitas
      </div>
    </div>

    <div class="box">
      <div class="box_head">Apakah BMI berlaku untuk semua orang?</div>
      <div class="box_text">
        Tidak akurat untuk atlet, ibu hamil, anak-anak.
      </div>
    </div>

    <div class="box">
      <div class="box_head">Apakah data saya disimpan?</div>
      <div class="box_text">
        Ya, hanya untuk membantu riwayat hasil BMI akun kamu.
      </div>
    </div>

    <div class="box">
      <div class="box_head">Bisa digunakan di HP?</div>
      <div class="box_text">
        Ya, web ini responsif untuk semua layar.
      </div>
    </div>

  </div>
</section>



<!-- ==================== MASUKAN ==================== -->
<section class="masukan" id="masukan">
  <h2>Punya Ide atau Saran? Ceritakan di Sini!</h2>
  <form class="masukan-form">
    <textarea placeholder="Ketik saran dan kritik di sini!" required></textarea>
    <button type="submit">Kirim</button>
  </form>
</section>



<!-- ==================== FOOTER ==================== -->
<footer class="footer">
  <div class="footer-container">

    <div class="footer-left">
      <img src="asset/Logo2.png" alt="CekBMIMku Logo" class="footer-logo" />
    </div>

    <div class="footer-right">
      <ul>
        <li><strong>Tentang CekBMIku</strong></li>
        <li>Email: cekbmiku@gmail.com</li>
        <li><a href="#faq">Butuh Bantuan? (FAQ)</a></li>
        <li><a href="#masukan">Saran & Masukan</a></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <p>© 2025 CekBMIMku.id – Cek berat badan idealmu dengan cepat dan akurat.</p>
  </div>
</footer>


<script src="js/main.js"></script>
</body>
</html>
