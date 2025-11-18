<?php
session_start(); // Wajib ada di baris pertama

// Jika session 'admin_id' TIDAK ada, berarti dia bukan admin
if (!isset($_SESSION['admin_id'])) {
    // Kembalikan dia ke halaman login
    header('Location: login.php');
    exit; // Hentikan eksekusi script
}

// ...
// Kode halaman admin Anda dimulai di sini
// ...
?>