<?php
session_start();
// Pastikan path koneksi.php sudah benar
require_once __DIR__ . '/koneksi.php'; 

// Cek jika bukan Admin, tendang ke login
if (!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
}

// Query untuk mengambil masukan beserta nama user
$query = "
    SELECT 
        m.id_masukan,   
        u.nama, 
        m.pesan, 
        m.tanggal
    FROM 
        masukan m
    LEFT JOIN 
        users u ON m.id_user = u.id_user
    ORDER BY 
        m.tanggal DESC
";

$res = $conn->query($query);
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin - Daftar Masukan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css"> 
</head>
<body>

<?php 
// Asumsi file navbar berada di partials/navbar.php 
include 'partials/navbar.php'; 
?> 

<div class="container mt-4">
    <h4>Daftar Masukan dari Pengguna</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama User</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res->num_rows > 0): ?>
                    <?php while($r = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $r['id_masukan']; ?></td> 
                        <td><?php echo htmlspecialchars($r['nama'] ?? 'User Dihapus'); ?></td>
                        <td><?php echo htmlspecialchars($r['pesan']); ?></td>
                        <td><?php echo date('d M Y, H:i', strtotime($r['tanggal'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Belum ada masukan yang diterima.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
