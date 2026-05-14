<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../layout/sidebar.php'; ?>

<?php
// Helper fungsi
function fmtDate(?string $str): string {
    if (!$str) return '-';
    $d = DateTime::createFromFormat('Y-m-d', $str);
    return $d ? $d->format('d M Y') : $str;
}
function calcAge(?string $str): string {
    if (!$str) return '-';
    $b   = new DateTime($str);
    $now = new DateTime();
    return $b->diff($now)->y . ' tahun';
}
function statusBadge(string $s): string {
    $class = match($s) {
        'Aktif' => 'badge-green',  'Cuti'  => 'badge-yellow',
        'Lulus' => 'badge-blue',   default => 'badge-red'
    };
    $icon = match($s) {
        'Aktif' => 'fa-check-circle', 'Cuti' => 'fa-pause-circle',
        'Lulus' => 'fa-graduation-cap', default => 'fa-times-circle'
    };
    return "<span class='badge {$class}'><i class='fas {$icon}'></i> " . htmlspecialchars($s) . "</span>";
}
function jrsLabel(string $kode): string {
    return $kode === 'PPL'
        ? "<span class='badge badge-blue'>PPL</span>"
        : "<span class='badge badge-purple'>DM</span>";
}

$m       = $mahasiswa;
$inisial = strtoupper(substr($m['nama_lengkap'], 0, 1));
?>

<!-- ══ MAIN — DETAIL MAHASISWA ══ -->
<main class="main">

  <div class="page-top">
    <div>
      <div class="breadcrumb">
        <i class="fas fa-home"></i> /
        <a href="index.php?action=list">Mahasiswa</a> /
        <span><?= htmlspecialchars($m['nama_lengkap']) ?></span>
      </div>
      <div class="page-title">Detail Mahasiswa</div>
      <div class="page-sub">Informasi lengkap data mahasiswa</div>
    </div>
    <div style="display:flex;gap:.75rem;flex-wrap:wrap">
      <button class="btn btn-outline"
              onclick="openEditModal(<?= $m['id'] ?>, <?= htmlspecialchars(json_encode($m), ENT_QUOTES) ?>)">
        <i class="fas fa-edit"></i> Edit
      </button>
      <button class="btn btn-danger btn-sm"
              onclick="confirmHapus('index.php?action=delete&id=<?= $m['id'] ?>','<?= htmlspecialchars(addslashes($m['nama_lengkap'])) ?>')">
        <i class="fas fa-trash"></i> Hapus
      </button>
      <a href="index.php?action=list" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  <div class="detail-grid">
    <!-- Kolom kiri: profil -->
    <div>
      <div class="profile-card">
        <div class="profile-ava"><?= $inisial ?></div>
        <div class="profile-name"><?= htmlspecialchars($m['nama_lengkap']) ?></div>
        <div class="profile-nim"><?= htmlspecialchars($m['nim']) ?></div>
        <?= statusBadge($m['status']) ?>

        <hr class="divider">

        <div class="info-row">
          <div class="info-label">Jenis Kelamin</div>
          <div class="info-value">
            <i class="fas <?= $m['jk'] === 'Laki-laki' ? 'fa-mars' : 'fa-venus' ?>" style="color:var(--accent)"></i>
            <?= htmlspecialchars($m['jk']) ?>
          </div>
        </div>
        <div class="info-row">
          <div class="info-label">Tempat, Tanggal Lahir</div>
          <div class="info-value">
            <?= htmlspecialchars($m['tempat'] ?: '-') ?>,
            <?= fmtDate($m['tanggal']) ?>
          </div>
          <?php if ($m['tanggal']): ?>
          <div style="color:var(--text2);font-size:.8rem"><?= calcAge($m['tanggal']) ?></div>
          <?php endif; ?>
        </div>
        <div class="info-row">
          <div class="info-label">Alamat</div>
          <div class="info-value" style="font-size:.85rem"><?= nl2br(htmlspecialchars($m['alamat'] ?: '-')) ?></div>
        </div>
      </div>
    </div>

    <!-- Kolom kanan: rincian -->
    <div style="display:flex;flex-direction:column;gap:1.5rem">

      <div class="card">
        <div class="card-head"><span><i class="fas fa-address-card" style="color:var(--accent)"></i> Informasi Kontak</span></div>
        <div class="card-body">
          <div class="info-grid-2">
            <div class="info-row">
              <div class="info-label">No. Telepon</div>
              <div class="info-value"><?= htmlspecialchars($m['telp'] ?: '-') ?></div>
              <?php if ($m['telp']): ?>
              <a href="tel:<?= htmlspecialchars($m['telp']) ?>" class="btn btn-sm btn-outline" style="margin-top:.4rem">
                <i class="fas fa-phone"></i> Hubungi
              </a>
              <?php endif; ?>
            </div>
            <div class="info-row">
              <div class="info-label">Email</div>
              <div class="info-value" style="word-break:break-all"><?= htmlspecialchars($m['email'] ?: '-') ?></div>
              <?php if ($m['email']): ?>
              <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn btn-sm btn-outline" style="margin-top:.4rem">
                <i class="fas fa-envelope"></i> Kirim Email
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-head"><span><i class="fas fa-university" style="color:var(--purple)"></i> Informasi Akademik</span></div>
        <div class="card-body">
          <div class="info-grid-2">
            <div class="info-row">
              <div class="info-label">Kode Jurusan</div>
              <div class="info-value"><?= jrsLabel($m['jurusan']) ?></div>
            </div>
            <div class="info-row">
              <div class="info-label">Nama Jurusan</div>
              <div class="info-value"><?= htmlspecialchars($m['jurusan_nama']) ?></div>
            </div>
            <div class="info-row">
              <div class="info-label">Angkatan</div>
              <div class="info-value nim-mono"><?= htmlspecialchars($m['angkatan']) ?></div>
            </div>
            <div class="info-row">
              <div class="info-label">Tanggal Masuk</div>
              <div class="info-value"><?= fmtDate($m['masuk']) ?></div>
            </div>
            <div class="info-row">
              <div class="info-label">Status</div>
              <div class="info-value"><?= statusBadge($m['status']) ?></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</main>

<!-- ══ MODAL EDIT (inline di halaman detail) ══ -->
<div class="modal-overlay" id="modalForm">
  <div class="modal">
    <div class="modal-title" id="modalFormTitle">Edit Mahasiswa</div>
    <form method="POST" action="index.php?action=update" id="mahasiswaForm">
      <input type="hidden" name="id" id="f-id" value="<?= $m['id'] ?>">

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">NIM *</label>
          <input class="form-field" type="text" name="nim" id="f-nim" value="<?= htmlspecialchars($m['nim']) ?>" required maxlength="10">
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-field" name="status" id="f-status">
            <?php foreach (['Aktif','Cuti','Lulus','DO'] as $s): ?>
            <option value="<?= $s ?>" <?= $m['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap *</label>
        <input class="form-field" type="text" name="nama_lengkap" id="f-nama" value="<?= htmlspecialchars($m['nama_lengkap']) ?>" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Jenis Kelamin</label>
          <select class="form-field" name="jk" id="f-jk">
            <option value="Laki-laki" <?= $m['jk'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $m['jk'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Jurusan</label>
          <select class="form-field" name="jurusan" id="f-jurusan">
            <option value="PPL" <?= $m['jurusan'] === 'PPL' ? 'selected' : '' ?>>PPL — Pengembangan Perangkat Lunak</option>
            <option value="DM"  <?= $m['jurusan'] === 'DM'  ? 'selected' : '' ?>>DM — Digital Marketing</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tempat Lahir</label>
          <input class="form-field" type="text" name="tempat" id="f-tempat" value="<?= htmlspecialchars($m['tempat'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Lahir</label>
          <input class="form-field" type="date" name="tanggal" id="f-tanggal" value="<?= htmlspecialchars($m['tanggal'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">No. Telepon</label>
        <input class="form-field" type="text" name="telp" value="<?= htmlspecialchars($m['telp'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-field" type="email" name="email" value="<?= htmlspecialchars($m['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Alamat</label>
        <textarea class="form-field" name="alamat" rows="2"><?= htmlspecialchars($m['alamat'] ?? '') ?></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Angkatan</label>
          <input class="form-field" type="text" name="angkatan" value="<?= htmlspecialchars($m['angkatan']) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Masuk</label>
          <input class="form-field" type="date" name="masuk" value="<?= htmlspecialchars($m['masuk'] ?? '') ?>">
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
function openEditModal(id, m) {
  openModal('modalForm');
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
