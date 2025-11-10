<?php
// Contoh koneksi jika koneksi asli tidak ditemukan.
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'cekbmiku';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
