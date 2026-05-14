<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../layout/sidebar.php'; ?>

<!-- ══ MAIN — DAFTAR MAHASISWA ══ -->
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
      <div class="breadcrumb"><i class="fas fa-home"></i> / <span>Mahasiswa</span></div>
      <div class="page-title">Data Mahasiswa</div>
      <div class="page-sub">Kelola data mahasiswa PeTIK Jombang</div>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalForm')">
      <i class="fas fa-plus"></i> Tambah Mahasiswa
    </button>
  </div>

  <!-- Mini stats -->
  <div class="stats-grid" style="margin-bottom:1.5rem">
    <div class="stat-card blue"><div class="stat-head"><div class="stat-label">Total</div><div class="stat-ico blue"><i class="fas fa-users"></i></div></div><div class="stat-num"><?= $stats['total'] ?></div></div>
    <div class="stat-card green"><div class="stat-head"><div class="stat-label">Aktif</div><div class="stat-ico green"><i class="fas fa-user-check"></i></div></div><div class="stat-num"><?= $stats['aktif'] ?></div></div>
    <div class="stat-card yellow"><div class="stat-head"><div class="stat-label">PPL</div><div class="stat-ico yellow"><i class="fas fa-laptop-code"></i></div></div><div class="stat-num"><?= $stats['ppl'] ?></div></div>
    <div class="stat-card purple"><div class="stat-head"><div class="stat-label">DM</div><div class="stat-ico purple"><i class="fas fa-bullhorn"></i></div></div><div class="stat-num"><?= $stats['dm'] ?></div></div>
  </div>

  <!-- Table card -->
  <div class="card">
    <div class="card-head">
      <span>Daftar Mahasiswa <span style="color:var(--text2);font-weight:400;font-size:0.82rem">(<?= $total ?> data)</span></span>
      <a href="index.php?action=export&search=<?= urlencode($filter['search']) ?>&jurusan=<?= urlencode($filter['jurusan']) ?>&status=<?= urlencode($filter['status']) ?>"
         class="btn btn-sm btn-outline">
        <i class="fas fa-file-csv"></i> Export CSV
      </a>
    </div>

    <div class="card-body">
      <!-- Filter bar -->
      <form method="GET" action="index.php" id="filterForm">
        <input type="hidden" name="action" value="list">
        <input type="hidden" name="page"   value="1">
        <div class="filter-bar">
          <div class="search-wrap">
            <i class="fas fa-search search-icon"></i>
            <input class="form-input" type="text" name="search" placeholder="Cari NIM atau Nama…"
                   value="<?= htmlspecialchars($filter['search']) ?>" oninput="this.form.submit()">
          </div>
          <select class="form-select" name="jurusan" onchange="this.form.submit()" style="width:180px;padding-left:1rem">
            <option value="">Semua Jurusan</option>
            <option value="PPL" <?= $filter['jurusan'] === 'PPL' ? 'selected' : '' ?>>PPL</option>
            <option value="DM"  <?= $filter['jurusan'] === 'DM'  ? 'selected' : '' ?>>DM</option>
          </select>
          <select class="form-select" name="status" onchange="this.form.submit()" style="width:150px;padding-left:1rem">
            <option value="">Semua Status</option>
            <option value="Aktif" <?= $filter['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="Cuti"  <?= $filter['status'] === 'Cuti'  ? 'selected' : '' ?>>Cuti</option>
            <option value="Lulus" <?= $filter['status'] === 'Lulus' ? 'selected' : '' ?>>Lulus</option>
          </select>
          <select class="form-select" name="per_page" onchange="this.form.submit()" style="width:120px;padding-left:1rem">
            <?php foreach ([5, 10, 25] as $pp): ?>
            <option value="<?= $pp ?>" <?= $perPage == $pp ? 'selected' : '' ?>><?= $pp ?> / hal</option>
            <?php endforeach; ?>
          </select>
        </div>
      </form>

      <!-- Table -->
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th style="width:40px">No</th>
              <th>NIM</th>
              <th>Nama Lengkap</th>
              <th>JK</th>
              <th>Jurusan</th>
              <th>Angkatan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($mahasiswas)): ?>
            <tr>
              <td colspan="8">
                <div class="empty">
                  <i class="fas fa-search"></i>
                  <div class="empty-title">Tidak ada data ditemukan</div>
                  <div>Coba ubah filter pencarian Anda</div>
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($mahasiswas as $i => $m): ?>
            <?php
              $no = ($page - 1) * $perPage + $i + 1;
              $statusClass = match($m['status']) {
                'Aktif' => 'badge-green', 'Cuti' => 'badge-yellow',
                'Lulus' => 'badge-blue',  default => 'badge-red'
              };
              $jrsClass = $m['jurusan'] === 'PPL' ? 'badge-blue' : 'badge-purple';
              $inisial  = strtoupper(substr($m['nama_lengkap'], 0, 1));
            ?>
            <tr>
              <td style="color:var(--text2)"><?= $no ?></td>
              <td class="nim-mono"><?= htmlspecialchars($m['nim']) ?></td>
              <td>
                <div style="display:flex;align-items:center;gap:.6rem">
                  <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;color:white;font-size:.75rem;font-weight:700;flex-shrink:0">
                    <?= $inisial ?>
                  </div>
                  <a href="index.php?action=detail&id=<?= $m['id'] ?>" style="font-weight:600;color:var(--text);text-decoration:none">
                    <?= htmlspecialchars($m['nama_lengkap']) ?>
                  </a>
                </div>
              </td>
              <td style="color:var(--text2)"><?= htmlspecialchars($m['jk']) === 'Laki-laki' ? 'L' : 'P' ?></td>
              <td><span class="badge <?= $jrsClass ?>"><?= htmlspecialchars($m['jurusan']) ?></span></td>
              <td class="nim-mono"><?= htmlspecialchars($m['angkatan']) ?></td>
              <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($m['status']) ?></span></td>
              <td>
                <div class="action-btns">
                  <a href="index.php?action=detail&id=<?= $m['id'] ?>" class="btn-icon" title="Detail" style="color:var(--accent)">
                    <i class="fas fa-eye"></i>
                  </a>
                  <button class="btn-icon" title="Edit"
                          onclick="openEditModal(<?= $m['id'] ?>,<?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)"
                          style="color:var(--yellow)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn-icon" title="Hapus"
                          onclick="confirmHapus('index.php?action=delete&id=<?= $m['id'] ?>','<?= htmlspecialchars(addslashes($m['nama_lengkap'])) ?>')"
                          style="color:var(--red)">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);flex-wrap:wrap;gap:.75rem">
        <div style="color:var(--text2);font-size:.83rem">
          Menampilkan <?= min(($page - 1) * $perPage + 1, $total) ?>–<?= min($page * $perPage, $total) ?> dari <?= $total ?> data
        </div>
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
          <?php if ($page > 1): ?>
          <a href="?action=list&page=<?= $page - 1 ?>&per_page=<?= $perPage ?>&search=<?= urlencode($filter['search']) ?>&jurusan=<?= urlencode($filter['jurusan']) ?>&status=<?= urlencode($filter['status']) ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
          <?php endif; ?>

          <?php for ($p = max(1, $page - 2); $p <= min($totalPages, $page + 2); $p++): ?>
          <a href="?action=list&page=<?= $p ?>&per_page=<?= $perPage ?>&search=<?= urlencode($filter['search']) ?>&jurusan=<?= urlencode($filter['jurusan']) ?>&status=<?= urlencode($filter['status']) ?>"
             class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
          <?php endfor; ?>

          <?php if ($page < $totalPages): ?>
          <a href="?action=list&page=<?= $page + 1 ?>&per_page=<?= $perPage ?>&search=<?= urlencode($filter['search']) ?>&jurusan=<?= urlencode($filter['jurusan']) ?>&status=<?= urlencode($filter['status']) ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</main>

<!-- ══ MODAL TAMBAH / EDIT MAHASISWA ══ -->
<div class="modal-overlay" id="modalForm">
  <div class="modal">
    <div class="modal-title" id="modalFormTitle">Tambah Mahasiswa</div>
    <form method="POST" action="" id="mahasiswaForm">
      <input type="hidden" name="id" id="f-id">

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">NIM *</label>
          <input class="form-field" type="text" name="nim" id="f-nim" placeholder="cth. 2511" maxlength="10" required>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-field" name="status" id="f-status">
            <option value="Aktif">Aktif</option>
            <option value="Cuti">Cuti</option>
            <option value="Lulus">Lulus</option>
            <option value="DO">DO</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap *</label>
        <input class="form-field" type="text" name="nama_lengkap" id="f-nama" placeholder="Nama lengkap mahasiswa" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Jenis Kelamin</label>
          <select class="form-field" name="jk" id="f-jk">
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Jurusan</label>
          <select class="form-field" name="jurusan" id="f-jurusan">
            <option value="PPL">PPL — Pengembangan Perangkat Lunak</option>
            <option value="DM">DM — Digital Marketing</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tempat Lahir</label>
          <input class="form-field" type="text" name="tempat" id="f-tempat" placeholder="Kota kelahiran">
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Lahir</label>
          <input class="form-field" type="date" name="tanggal" id="f-tanggal">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">No. Telepon</label>
        <input class="form-field" type="text" name="telp" id="f-telp" placeholder="0812xxxxxxxx">
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-field" type="email" name="email" id="f-email" placeholder="email@example.com">
      </div>
      <div class="form-group">
        <label class="form-label">Alamat</label>
        <textarea class="form-field" name="alamat" id="f-alamat" rows="2" placeholder="Alamat lengkap…"></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Angkatan</label>
          <input class="form-field" type="text" name="angkatan" id="f-angkatan" placeholder="cth. 2025" value="2025">
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Masuk</label>
          <input class="form-field" type="date" name="masuk" id="f-masuk">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalForm')">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
// Set form action saat tambah / edit
function openEditModal(id, m) {
  document.getElementById('modalFormTitle').textContent = 'Edit Mahasiswa — ' + m.nama_lengkap;
  document.getElementById('mahasiswaForm').action = 'index.php?action=update';
  document.getElementById('f-id').value      = id;
  document.getElementById('f-nim').value     = m.nim;
  document.getElementById('f-nama').value    = m.nama_lengkap;
  document.getElementById('f-jk').value      = m.jk;
  document.getElementById('f-jurusan').value = m.jurusan;
  document.getElementById('f-tempat').value  = m.tempat  || '';
  document.getElementById('f-tanggal').value = m.tanggal || '';
  document.getElementById('f-telp').value    = m.telp    || '';
  document.getElementById('f-email').value   = m.email   || '';
  document.getElementById('f-alamat').value  = m.alamat  || '';
  document.getElementById('f-angkatan').value= m.angkatan;
  document.getElementById('f-masuk').value   = m.masuk   || '';
  document.getElementById('f-status').value  = m.status;
  openModal('modalForm');
}

// Default action untuk tambah
document.querySelector('[onclick*="openModal(\'modalForm\')"]')?.addEventListener('click', () => {
  document.getElementById('modalFormTitle').textContent = 'Tambah Mahasiswa';
  document.getElementById('mahasiswaForm').action = 'index.php?action=store';
  document.getElementById('mahasiswaForm').reset();
  document.getElementById('f-angkatan').value = '<?= date('Y') ?>';
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
