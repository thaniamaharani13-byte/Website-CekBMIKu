<?php
$host = "localhost";       // Server database
$user = "root";            // Username MySQL
$pass = "";                // Password MySQL (kosongkan jika default)
$db   = "cekbmiku";        // Nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>