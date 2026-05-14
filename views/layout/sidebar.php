<?php
// Tentukan halaman aktif dari URL
$currentAction = $_GET['action'] ?? 'dashboard';
$isActive = fn(string $act) => $currentAction === $act ? 'active' : '';

// Hitung total mahasiswa untuk badge
try {
    $badgeMhs = (new Mahasiswa())->countAll();
} catch (Throwable) {
    $badgeMhs = 0;
}
?>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar" id="sidebar">
  <div class="nav-section">
    <div class="nav-label">Menu Utama</div>

    <a href="index.php?action=dashboard" class="nav-item <?= $isActive('dashboard') ?>">
      <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
      Dashboard
    </a>

    <a href="index.php?action=list" class="nav-item <?= $isActive('list') === '' && in_array($currentAction, ['list','detail','edit']) ? 'active' : $isActive('list') ?>">
      <span class="nav-icon"><i class="fas fa-users"></i></span>
      Mahasiswa
      <?php if ($badgeMhs > 0): ?>
        <span class="nav-badge"><?= $badgeMhs ?></span>
      <?php endif; ?>
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Akademik</div>

    <a href="index.php?action=jurusan" class="nav-item <?= $isActive('jurusan') ?>">
      <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
      Jurusan
    </a>

    <a href="index.php?action=nilai" class="nav-item <?= $isActive('nilai') ?>">
      <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
      Nilai
    </a>
  </div>

  <div class="nav-section">
    <div class="nav-label">Sistem</div>

    <a href="index.php?action=pengaturan" class="nav-item <?= $isActive('pengaturan') ?>">
      <span class="nav-icon"><i class="fas fa-cog"></i></span>
      Pengaturan
    </a>
  </div>
</aside>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
