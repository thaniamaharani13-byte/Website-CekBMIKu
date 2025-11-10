<?php
session_start();
require_once __DIR__ . '/php/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }

$id = intval($_GET['id'] ?? 0);
$title = ''; $content = ''; $author = $_SESSION['admin_name'] ?? 'Admin';

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row) { $title = $row['title']; $content = $row['content']; $author = $row['author']; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? $author;
    if ($id) {
        $stmt = $conn->prepare('UPDATE articles SET title=?, content=?, author=?, updated_at=NOW() WHERE id=?');
        $stmt->bind_param('sssi', $title, $content, $author, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare('INSERT INTO articles (title, content, author) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $title, $content, $author);
        $stmt->execute();
    }
    header('Location: articles.php');
    exit;
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Artikel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="css/admin.css"></head>
<body><?php include 'partials/navbar.php'; ?>
<div class="container mt-4"><h4><?php echo $id? 'Edit' : 'Buat'; ?> Artikel</h4>
<form method="post"><div class="mb-3"><label>Judul</label><input name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>"></div>
<div class="mb-3"><label>Author</label><input name="author" class="form-control" value="<?php echo htmlspecialchars($author); ?>"></div>
<div class="mb-3"><label>Konten</label><textarea name="content" rows="10" class="form-control"><?php echo htmlspecialchars($content); ?></textarea></div>
<button class="btn btn-primary">Simpan</button> <a class="btn btn-secondary" href="articles.php">Batal</a></form></div></body></html>
