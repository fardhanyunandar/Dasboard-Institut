<!-- ══ FOOTER SCRIPTS ══ -->
<script>
// ── SIDEBAR TOGGLE ──
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('open');
}

// ── THEME TOGGLE ──
function toggleTheme() {
  const html = document.documentElement;
  const dark = html.getAttribute('data-theme') === 'dark';
  const next = dark ? 'light' : 'dark';
  html.setAttribute('data-theme', next);
  document.getElementById('themeBtn').innerHTML = next === 'dark'
    ? '<i class="fas fa-sun"></i>'
    : '<i class="fas fa-moon"></i>';
  document.cookie = 'theme=' + next + '; path=/; max-age=31536000';
}

// Init theme icon sesuai cookie
(function () {
  const theme = document.documentElement.getAttribute('data-theme') || 'light';
  const btn = document.getElementById('themeBtn');
  if (btn) btn.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
})();

// ── MODAL UTILS ──
function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.add('open');
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}
// Tutup modal saat klik overlay
document.querySelectorAll('.modal-overlay').forEach(o => {
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

// ── AUTO-HIDE ALERT ──
document.querySelectorAll('.alert').forEach(el => {
  setTimeout(() => {
    el.style.transition = 'opacity .4s';
    el.style.opacity = '0';
    setTimeout(() => el.remove(), 400);
  }, 4000);
});

// ── CONFIRM HAPUS (inline) ──
function confirmHapus(url, nama) {
  document.getElementById('confirm-nama').textContent =
    'Yakin hapus data ' + nama + '? Tindakan ini tidak bisa dibatalkan.';
  document.getElementById('confirm-link').href = url;
  openModal('modalConfirm');
}
</script>

<!-- Confirm Delete Modal (global, ada di setiap halaman) -->
<div class="modal-overlay" id="modalConfirm">
  <div class="modal" style="max-width:400px;text-align:center">
    <div style="font-size:3.5rem;margin-bottom:1rem">🗑️</div>
    <div style="font-size:1.2rem;font-weight:800;margin-bottom:.5rem">Hapus Mahasiswa?</div>
    <div id="confirm-nama" style="color:var(--text2);font-size:0.9rem;margin-bottom:1.5rem">
      Data ini akan dihapus secara permanen.
    </div>
    <div style="display:flex;gap:.75rem;justify-content:center">
      <button class="btn btn-outline" onclick="closeModal('modalConfirm')">Batal</button>
      <a id="confirm-link" href="#" class="btn btn-danger">
        <i class="fas fa-trash"></i> Hapus
      </a>
    </div>
  </div>
</div>

</body>
</html>
