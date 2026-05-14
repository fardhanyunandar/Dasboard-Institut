<?php
// ══════════════════════════════════════════════
//  INDEX.PHP — Entry Point Sistem Akademik PeTIK
// ══════════════════════════════════════════════

session_start();

// Auto-setup database saat pertama kali dijalankan
require_once __DIR__ . '/config/database.php';
setupDatabase();

// Load model & controller
require_once __DIR__ . '/models/mahasiswa.php';
require_once __DIR__ . '/controllers/MahasiswaController.php';

// Routing sederhana berdasarkan GET action
$action = $_GET['action'] ?? 'dashboard';

// Halaman yang ditangani controller Mahasiswa
$mahasiswaActions = [
    'dashboard', 'list', 'detail', 'store', 'update', 'delete', 'export', 'api_stats'
];

// Halaman statis sederhana (jurusan, nilai, pengaturan)
$staticPages = ['jurusan', 'nilai', 'pengaturan'];

if (in_array($action, $mahasiswaActions)) {
    $ctrl = new MahasiswaController();
    $ctrl->handle();

} elseif ($action === 'jurusan') {
    // Halaman Jurusan (statis)
    $model     = new Mahasiswa();
    $stats     = $model->getStats();
    $pageTitle = 'Data Jurusan';
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . '/views/layout/sidebar.php';
    ?>
    <main class="main">
      <div class="page-top">
        <div>
          <div class="page-title">Data Jurusan</div>
          <div class="page-sub">Informasi jurusan yang tersedia</div>
        </div>
      </div>
      <div class="card">
        <div class="card-head"><span>Daftar Jurusan</span></div>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Kode</th><th>Nama Jurusan</th><th>Ketua Jurusan</th><th>Jumlah Mahasiswa</th><th>Status</th></tr></thead>
            <tbody>
              <tr>
                <td><span class="badge badge-blue">PPL</span></td>
                <td><strong>Pengembangan Perangkat Lunak</strong></td>
                <td>Alfajri S.Kom</td>
                <td><span class="nim-mono"><?= $stats['ppl'] ?></span></td>
                <td><span class="badge badge-green"><i class="fas fa-circle" style="font-size:.5rem"></i> Aktif</span></td>
              </tr>
              <tr>
                <td><span class="badge badge-purple">DM</span></td>
                <td><strong>Digital Marketing</strong></td>
                <td>Febby Cahya Triandra</td>
                <td><span class="nim-mono"><?= $stats['dm'] ?></span></td>
                <td><span class="badge badge-green"><i class="fas fa-circle" style="font-size:.5rem"></i> Aktif</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
    <?php
    require __DIR__ . '/views/layout/footer.php';

} elseif ($action === 'nilai') {
    // Halaman Nilai (sample data dari DB)
    $model     = new Mahasiswa();
    $pageTitle = 'Rekap Nilai';
    $semua     = $model->getAll([], 999, 0);
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . '/views/layout/sidebar.php';
    $grades = ['A', 'A-', 'B+', 'B', 'B-'];
    ?>
    <main class="main">
      <div class="page-top">
        <div>
          <div class="page-title">Rekap Nilai</div>
          <div class="page-sub">Data nilai mahasiswa per semester</div>
        </div>
      </div>
      <div class="card">
        <div class="card-head"><span>Nilai Mahasiswa — Semester Ganjil <?= date('Y') ?>/<?= date('Y') + 1 ?></span></div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>NIM</th><th>Nama</th><th>Pemrograman Web</th>
                <th>Basis Data</th><th>Jaringan</th><th>Bahasa Inggris</th><th>IPK</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($semua as $i => $m): ?>
              <?php
                $g   = fn(int $seed) => $grades[($seed + $i) % count($grades)];
                $ipk = number_format(3.0 + ($i * 0.07), 2);
                $gc  = fn(string $gr) => match(true) {
                    str_starts_with($gr, 'A') => 'badge-green',
                    str_starts_with($gr, 'B') => 'badge-blue',
                    default                   => 'badge-yellow'
                };
              ?>
              <tr>
                <td class="nim-mono"><?= htmlspecialchars($m['nim']) ?></td>
                <td><?= htmlspecialchars($m['nama_lengkap']) ?></td>
                <td><span class="badge <?= $gc($g(0)) ?>"><?= $g(0) ?></span></td>
                <td><span class="badge <?= $gc($g(1)) ?>"><?= $g(1) ?></span></td>
                <td><span class="badge <?= $gc($g(2)) ?>"><?= $g(2) ?></span></td>
                <td><span class="badge badge-yellow"><?= $g(3) ?></span></td>
                <td><strong class="nim-mono"><?= $ipk ?></strong></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
    <?php
    require __DIR__ . '/views/layout/footer.php';

} elseif ($action === 'pengaturan') {
    $pageTitle = 'Pengaturan';
    require __DIR__ . '/views/layout/header.php';
    require __DIR__ . '/views/layout/sidebar.php';
    ?>
    <main class="main">
      <div class="page-top">
        <div>
          <div class="page-title">Pengaturan</div>
          <div class="page-sub">Konfigurasi sistem</div>
        </div>
      </div>
      <div class="card">
        <div class="card-body" style="text-align:center;padding:3rem">
          <i class="fas fa-cog" style="font-size:3rem;color:var(--text2);opacity:.4;display:block;margin-bottom:1rem"></i>
          <div style="font-weight:700;margin-bottom:.5rem">Fitur Pengaturan</div>
          <div style="color:var(--text2)">Segera hadir dalam versi berikutnya.</div>
        </div>
      </div>
    </main>
    <?php
    require __DIR__ . '/views/layout/footer.php';

} else {
    // Fallback → redirect ke dashboard
    header('Location: index.php?action=dashboard');
    exit;
}
