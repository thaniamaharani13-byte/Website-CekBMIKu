<?php
// === Konfigurasi Database ===
$host = "localhost";      // Server database (biasanya localhost)
$user = "root";           // Username MySQL
$pass = "";               // Password MySQL (kosong secara default di XAMPP)
$db   = "cekbmiku_db";    // Ganti dengan nama database kamu

// === Membuat Koneksi ===
$conn = mysqli_connect($host, $user, $pass, $db);

// === Cek Koneksi ===
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    echo "Koneksi berhasil!";
}

?>