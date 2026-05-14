<?php
require_once __DIR__ . '/../models/mahasiswa.php';

// ══════════════════════════════════════════════
//  CONTROLLER MAHASISWA — Sistem Akademik PeTIK
// ══════════════════════════════════════════════

class MahasiswaController {

    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // ── Router utama berdasarkan action ──
    public function handle(): void {
        $action = $_GET['action'] ?? 'dashboard';

        match ($action) {
            'dashboard' => $this->dashboard(),
            'list'      => $this->list(),
            'detail'    => $this->detail(),
            'create'    => $this->create(),
            'store'     => $this->store(),
            'edit'      => $this->edit(),
            'update'    => $this->updateData(),
            'delete'    => $this->delete(),
            'export'    => $this->exportCSV(),
            'api_stats' => $this->apiStats(),
            default     => $this->notFound(),
        };
    }

    // ── DASHBOARD ──
    public function dashboard(): void {
        $stats   = $this->model->getStats();
        $latest  = $this->model->getLatest(5);
        $pageTitle = 'Dashboard';

        require __DIR__ . '/../views/dashboard.php';
    }

    // ── DAFTAR MAHASISWA ──
    public function list(): void {
        $perPage = max(1, (int)($_GET['per_page'] ?? 10));
        $page    = max(1, (int)($_GET['page']     ?? 1));
        $offset  = ($page - 1) * $perPage;

        $filter = [
            'search'  => trim($_GET['search']  ?? ''),
            'jurusan' => trim($_GET['jurusan'] ?? ''),
            'status'  => trim($_GET['status']  ?? ''),
        ];

        $total      = $this->model->countAll($filter);
        $mahasiswas = $this->model->getAll($filter, $perPage, $offset);
        $totalPages = (int)ceil($total / $perPage);
        $stats      = $this->model->getStats();
        $pageTitle  = 'Data Mahasiswa';

        require __DIR__ . '/../views/mahasiswa/list.php';
    }

    // ── DETAIL MAHASISWA ──
    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        $mahasiswa = $this->model->getById($id);

        if (!$mahasiswa) {
            $this->redirectWithMsg('list', 'error', 'Mahasiswa tidak ditemukan.');
            return;
        }

        $pageTitle = 'Detail — ' . htmlspecialchars($mahasiswa['nama_lengkap']);
        require __DIR__ . '/../views/mahasiswa/detail.php';
    }

    // ── FORM TAMBAH ──
    public function create(): void {
        $pageTitle = 'Tambah Mahasiswa';
        $mahasiswa = [];
        $errors    = [];
        require __DIR__ . '/../views/mahasiswa/list.php'; // modal terbuka via URL parameter
    }

    // ── SIMPAN DATA BARU (POST) ──
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('list');
            return;
        }

        $data   = $this->getPostData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->redirectWithMsg('list', 'error', implode(' | ', $errors));
            return;
        }

        // Cek duplikat NIM
        if ($this->model->getByNim($data['nim'])) {
            $this->redirectWithMsg('list', 'error', 'NIM ' . htmlspecialchars($data['nim']) . ' sudah terdaftar!');
            return;
        }

        $id = $this->model->create($data);
        $this->redirectWithMsg('detail&id=' . $id, 'success', 'Mahasiswa baru berhasil ditambahkan!');
    }

    // ── FORM EDIT ──
    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $mahasiswa = $this->model->getById($id);

        if (!$mahasiswa) {
            $this->redirectWithMsg('list', 'error', 'Mahasiswa tidak ditemukan.');
            return;
        }

        $pageTitle = 'Edit — ' . htmlspecialchars($mahasiswa['nama_lengkap']);
        $errors    = [];
        require __DIR__ . '/../views/mahasiswa/list.php';
    }

    // ── UPDATE DATA (POST) ──
    public function updateData(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('list');
            return;
        }

        $id   = (int)($_POST['id'] ?? 0);
        $data = $this->getPostData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->redirectWithMsg('edit&id=' . $id, 'error', implode(' | ', $errors));
            return;
        }

        // Cek duplikat NIM (kecuali diri sendiri)
        if ($this->model->getByNim($data['nim'], $id)) {
            $this->redirectWithMsg('edit&id=' . $id, 'error', 'NIM ' . htmlspecialchars($data['nim']) . ' sudah dipakai mahasiswa lain!');
            return;
        }

        $this->model->update($id, $data);
        $this->redirectWithMsg('detail&id=' . $id, 'success', 'Data mahasiswa berhasil diperbarui!');
    }

    // ── HAPUS DATA ──
    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        $mahasiswa = $this->model->getById($id);

        if (!$mahasiswa) {
            $this->redirectWithMsg('list', 'error', 'Mahasiswa tidak ditemukan.');
            return;
        }

        $this->model->delete($id);
        $this->redirectWithMsg('list', 'info', 'Data ' . htmlspecialchars($mahasiswa['nama_lengkap']) . ' berhasil dihapus.');
    }

    // ── EXPORT CSV ──
    public function exportCSV(): void {
        $filter = [
            'search'  => trim($_GET['search']  ?? ''),
            'jurusan' => trim($_GET['jurusan'] ?? ''),
            'status'  => trim($_GET['status']  ?? ''),
        ];

        $data = $this->model->getAll($filter, 9999, 0);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="mahasiswa_petik_' . date('Ymd_His') . '.csv"');

        $out = fopen('php://output', 'w');
        // BOM UTF-8 agar Excel terbaca benar
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, ['NIM','Nama Lengkap','Jenis Kelamin','Jurusan','Angkatan','Status','Email','No Telepon','Tempat Lahir','Tanggal Lahir','Alamat']);
        foreach ($data as $row) {
            fputcsv($out, [
                $row['nim'], $row['nama_lengkap'], $row['jk'], $row['jurusan_nama'],
                $row['angkatan'], $row['status'], $row['email'], $row['telp'],
                $row['tempat'], $row['tanggal'], $row['alamat'],
            ]);
        }
        fclose($out);
        exit;
    }

    // ── API STATS (JSON) untuk AJAX / dashboard ──
    public function apiStats(): void {
        header('Content-Type: application/json');
        echo json_encode($this->model->getStats());
        exit;
    }

    // ── 404 ──
    private function notFound(): void {
        http_response_code(404);
        echo '<h1>404 — Halaman tidak ditemukan</h1>';
    }

    // ══════════════════════════════════════════════
    //  HELPERS PRIVATE
    // ══════════════════════════════════════════════

    private function getPostData(): array {
        return [
            'nim'          => trim($_POST['nim']          ?? ''),
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'jk'           => $_POST['jk']                ?? 'Laki-laki',
            'jurusan'      => $_POST['jurusan']            ?? 'PPL',
            'angkatan'     => $_POST['angkatan']           ?? date('Y'),
            'status'       => $_POST['status']             ?? 'Aktif',
            'tempat'       => trim($_POST['tempat']        ?? ''),
            'tanggal'      => $_POST['tanggal']            ?? '',
            'telp'         => trim($_POST['telp']          ?? ''),
            'email'        => trim($_POST['email']         ?? ''),
            'alamat'       => trim($_POST['alamat']        ?? ''),
            'masuk'        => $_POST['masuk']              ?? '',
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['nim']))          $errors[] = 'NIM wajib diisi.';
        if (empty($data['nama_lengkap'])) $errors[] = 'Nama Lengkap wajib diisi.';
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }
        return $errors;
    }

    private function redirect(string $action): void {
        header('Location: index.php?action=' . $action);
        exit;
    }

    private function redirectWithMsg(string $action, string $type, string $msg): void {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
        $this->redirect($action);
    }
}
