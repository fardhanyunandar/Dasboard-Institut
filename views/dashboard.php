<?php require __DIR__ . '/layout/header.php'; ?>
<?php require __DIR__ . '/layout/sidebar.php'; ?>

<!-- ══ MAIN — DASHBOARD ══ -->
<main class="main">

<?php
// Flash message
if (!empty($_SESSION['flash'])):
  $f = $_SESSION['flash'];
  unset($_SESSION['flash']);
?>
<div class="alert alert-<?= htmlspecialchars($f['type']) ?>">
  <i class="fas fa-<?= $f['type'] === 'success' ? 'check-circle' : ($f['type'] === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
  <?= htmlspecialchars($f['msg']) ?>
</div>
<?php endif; ?>

  <!-- Page top -->
  <div class="page-top">
    <div>
      <div class="page-title">Dashboard</div>
      <div class="page-sub">Selamat datang kembali, Admin PeTIK!</div>
    </div>
    <a href="index.php?action=list" class="btn btn-primary">
      <i class="fas fa-users"></i> Kelola Mahasiswa
    </a>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="stat-head">
        <div class="stat-label">Total Mahasiswa</div>
        <div class="stat-ico blue"><i class="fas fa-users"></i></div>
      </div>
      <div class="stat-num"><?= $stats['total'] ?></div>
      <div class="stat-info"><i class="fas fa-arrow-up" style="color:var(--green)"></i> Data terdaftar</div>
    </div>
    <div class="stat-card green">
      <div class="stat-head">
        <div class="stat-label">Mahasiswa Aktif</div>
        <div class="stat-ico green"><i class="fas fa-user-check"></i></div>
      </div>
      <div class="stat-num"><?= $stats['aktif'] ?></div>
      <div class="stat-info">Status aktif kuliah</div>
    </div>
    <div class="stat-card yellow">
      <div class="stat-head">
        <div class="stat-label">Jurusan PPL</div>
        <div class="stat-ico yellow"><i class="fas fa-laptop-code"></i></div>
      </div>
      <div class="stat-num"><?= $stats['ppl'] ?></div>
      <div class="stat-info">Pengembangan Perangkat Lunak</div>
    </div>
    <div class="stat-card purple">
      <div class="stat-head">
        <div class="stat-label">Jurusan DM</div>
        <div class="stat-ico purple"><i class="fas fa-bullhorn"></i></div>
      </div>
      <div class="stat-num"><?= $stats['dm'] ?></div>
      <div class="stat-info">Digital Marketing</div>
    </div>
  </div>

  <!-- Content grid -->
  <div class="content-grid">
    <!-- Tabel mahasiswa terbaru -->
    <div class="card">
      <div class="card-head">
        <span><i class="fas fa-clock" style="color:var(--accent)"></i> Mahasiswa Terbaru</span>
        <a href="index.php?action=list" class="btn btn-sm btn-outline">Lihat Semua</a>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>NIM</th>
              <th>Nama</th>
              <th>Jurusan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($latest)): ?>
            <tr><td colspan="4" class="empty"><i class="fas fa-inbox"></i><div class="empty-title">Belum ada data</div></td></tr>
            <?php else: ?>
            <?php foreach ($latest as $m): ?>
            <?php
              $statusClass = match($m['status']) {
                'Aktif' => 'badge-green', 'Cuti' => 'badge-yellow',
                'Lulus' => 'badge-blue',  default => 'badge-red'
              };
              $jrsClass = $m['jurusan'] === 'PPL' ? 'badge-blue' : 'badge-purple';
            ?>
            <tr>
              <td class="nim-mono"><?= htmlspecialchars($m['nim']) ?></td>
              <td>
                <a href="index.php?action=detail&id=<?= $m['id'] ?>" style="color:var(--text);font-weight:600;text-decoration:none">
                  <?= htmlspecialchars($m['nama_lengkap']) ?>
                </a>
              </td>
              <td><span class="badge <?= $jrsClass ?>"><?= htmlspecialchars($m['jurusan']) ?></span></td>
              <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($m['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Distribusi jurusan -->
    <div class="card">
      <div class="card-head">
        <span><i class="fas fa-chart-bar" style="color:var(--purple)"></i> Distribusi Jurusan</span>
      </div>
      <div class="card-body">
        <?php
          $total = $stats['total'] ?: 1;
          $pplPct = round($stats['ppl'] / $total * 100);
          $dmPct  = round($stats['dm']  / $total * 100);
        ?>
        <div style="margin-bottom:1.25rem">
          <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
            <span style="font-size:.85rem;font-weight:600">PPL — Pengembangan Perangkat Lunak</span>
            <span class="nim-mono" style="color:var(--accent)"><?= $stats['ppl'] ?> (<?= $pplPct ?>%)</span>
          </div>
          <div style="background:var(--surface2);border-radius:999px;height:10px;overflow:hidden">
            <div style="width:<?= $pplPct ?>%;background:linear-gradient(90deg,var(--accent),var(--purple));height:100%;border-radius:999px;transition:.6s"></div>
          </div>
        </div>
        <div>
          <div style="display:flex;justify-content:space-between;margin-bottom:.4rem">
            <span style="font-size:.85rem;font-weight:600">DM — Digital Marketing</span>
            <span class="nim-mono" style="color:var(--purple)"><?= $stats['dm'] ?> (<?= $dmPct ?>%)</span>
          </div>
          <div style="background:var(--surface2);border-radius:999px;height:10px;overflow:hidden">
            <div style="width:<?= $dmPct ?>%;background:linear-gradient(90deg,var(--purple),#a78bfa);height:100%;border-radius:999px;transition:.6s"></div>
          </div>
        </div>

        <hr class="divider">

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem;text-align:center">
          <div>
            <div class="stat-num" style="font-size:1.5rem;color:var(--green)"><?= $stats['aktif'] ?></div>
            <div style="font-size:.75rem;color:var(--text2)">Aktif</div>
          </div>
          <div>
            <div class="stat-num" style="font-size:1.5rem;color:var(--yellow)"><?= $stats['cuti'] ?></div>
            <div style="font-size:.75rem;color:var(--text2)">Cuti</div>
          </div>
          <div>
            <div class="stat-num" style="font-size:1.5rem;color:var(--accent)"><?= $stats['lulus'] ?></div>
            <div style="font-size:.75rem;color:var(--text2)">Lulus</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<?php require __DIR__ . '/layout/footer.php'; ?>
