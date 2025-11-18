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

// =================== PROSES MASUKAN ===================
if (isset($_POST['kirim_masukan'])) {

    $id_user = $_SESSION['id_user'];
    $pesan = $conn->real_escape_string($_POST['pesan']);

    $query = "INSERT INTO masukan (id_user, pesan) VALUES ('$id_user', '$pesan')";
    
    if ($conn->query($query)) {
        $_SESSION['notif_masukan'] = "Masukan berhasil dikirim!";
    } else {
        $_SESSION['notif_masukan'] = "Gagal mengirim masukan.";
    }

    header("Location: index.php#masukan");
    exit();
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
    INSERT INTO hasil_bmi (user_id, height_cm, weight_kg, bmi, berat_ideal, kategori, tanggal)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

// String bind_param yang BENAR adalah "idddds" (6 huruf)
$stmt->bind_param("idddds", 
    $id_user,       // i (integer)
    $height,      // d (double/angka desimal)
    $weight,      // d (double/angka desimal)
    $bmi,         // d (double/angka desimal)
    $ideal,       // d (double/angka desimal)
    $kategori     // s (string/teks)
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

<?php
// 1. AMBIL KONEKSI DATABASE (pastikan ini ada di atas file)
// require_once __DIR__ . '/koneksi.php';

// 2. AMBIL DATA DARI TABEL (KITA AMBIL BARIS DENGAN ID=1)
$res_about = $conn->query("SELECT * FROM tentang_kami WHERE id = 1");
$data_about = $res_about->fetch_assoc();

// Jika data tidak ditemukan, beri nilai default
if (!$data_about) {
    $data_about = [
        'judul' => 'Tentang Kami (Data tidak ditemukan)',
        'gambar_url' => 'asset/BannerTentangKami.png',
        'paragraf_pembuka' => 'Silakan isi konten melalui halaman admin.',
        'tujuan_isi' => 'Konten belum diisi.',
        'fitur_isi' => '- Konten belum diisi.',
        'email_footer' => 'email@contoh.com'
    ];
}
?>

<!-- ==================== Tentang Kami ==================== -->
<section id="tentangkami" class="about-section">

  <div class="about-hero">
    <img src="<?php echo htmlspecialchars($data_about['gambar_url']); ?>" alt="Banner Tentang Kami" class="about-banner">
  </div>

  <div class="about-content">
    <h2><strong><?php echo htmlspecialchars($data_about['judul']); ?></strong></h2>
    
    <p>
      <?php echo nl2br(htmlspecialchars($data_about['paragraf_pembuka'])); ?>
    </p>

    <div class="info-container">
      <div class="info-box">
        <p>
          <strong>Tujuan kami sederhana:</strong><br>
          <?php echo nl2br(htmlspecialchars($data_about['tujuan_isi'])); ?>
        </p>
      </div>

      <div class="info-box">
        <p>
          <strong>Fitur Utama:</strong><br>
          <?php echo nl2br(htmlspecialchars($data_about['fitur_isi'])); ?>
        </p>
      </div>
    </div>

    <p class="footer-email"><?php echo htmlspecialchars($data_about['email_footer']); ?></p>

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
                  src="asset/artikel/<?= htmlspecialchars($a['gambar']) ?>" 
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

    <?php
      include 'koneksi.php';

      $faq = $conn->query("SELECT * FROM faq WHERE status='aktif' ORDER BY urutan ASC, id_faq ASC");

      if ($faq->num_rows > 0):
        while ($row = $faq->fetch_assoc()):
    ?>
        <div class="box">
          <div class="box_head"><?= htmlspecialchars($row['pertanyaan']) ?></div>
          <div class="box_text"><?= nl2br(htmlspecialchars($row['jawaban'])) ?></div>
        </div>

    <?php
        endwhile;
      else:
        echo "<p style='color:#666;'>Belum ada FAQ tersedia.</p>";
      endif;
    ?>

  </div>
</section>

<!-- ==================== MASUKAN ==================== -->
<section class="masukan" id="masukan">
  <h2>Punya Ide atau Saran? Ceritakan di Sini!</h2>

  <!-- NOTIFIKASI -->
  <?php if (isset($_SESSION['notif_masukan'])): ?>
      <p class="notif" style="color: green; font-weight: bold;">
          <?= $_SESSION['notif_masukan']; unset($_SESSION['notif_masukan']); ?>
      </p>
  <?php endif; ?>

  <form class="masukan-form" method="POST">
    <textarea name="pesan" placeholder="Ketik saran dan kritik di sini!" required></textarea>
    <button type="submit" name="kirim_masukan">Kirim</button>
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
