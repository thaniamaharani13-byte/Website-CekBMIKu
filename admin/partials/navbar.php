<?php
// navbar sederhana
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin CekBMIKu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="users.php">Pengguna</a></li>
        <li class="nav-item"><a class="nav-link" href="bmi_history.php">Riwayat BMI</a></li>
        <li class="nav-item"><a class="nav-link" href="articles.php">Artikel</a></li>
      </ul>
      <div class="d-flex">
        <span class="navbar-text me-2"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>
