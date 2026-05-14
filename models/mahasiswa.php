<?php
require_once __DIR__ . '/../config/database.php';

// ══════════════════════════════════════════════
//  MODEL MAHASISWA — Sistem Akademik PeTIK
// ══════════════════════════════════════════════

class Mahasiswa {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // ── Ambil semua data dengan filter & paginasi ──
    public function getAll(array $filter = [], int $limit = 10, int $offset = 0): array {
        $where  = [];
        $params = [];

        if (!empty($filter['search'])) {
            $where[]  = "(nim LIKE :search OR nama_lengkap LIKE :search)";
            $params[':search'] = '%' . $filter['search'] . '%';
        }
        if (!empty($filter['jurusan'])) {
            $where[]  = "jurusan = :jurusan";
            $params[':jurusan'] = $filter['jurusan'];
        }
        if (!empty($filter['status'])) {
            $where[]  = "status = :status";
            $params[':status'] = $filter['status'];
        }

        $sql = "SELECT * FROM mahasiswa";
        if ($where) $sql .= " WHERE " . implode(" AND ", $where);
        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // ── Hitung total data (untuk paginasi) ──
    public function countAll(array $filter = []): int {
        $where  = [];
        $params = [];

        if (!empty($filter['search'])) {
            $where[]  = "(nim LIKE :search OR nama_lengkap LIKE :search)";
            $params[':search'] = '%' . $filter['search'] . '%';
        }
        if (!empty($filter['jurusan'])) {
            $where[]  = "jurusan = :jurusan";
            $params[':jurusan'] = $filter['jurusan'];
        }
        if (!empty($filter['status'])) {
            $where[]  = "status = :status";
            $params[':status'] = $filter['status'];
        }

        $sql = "SELECT COUNT(*) FROM mahasiswa";
        if ($where) $sql .= " WHERE " . implode(" AND ", $where);

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    // ── Ambil satu data berdasarkan ID ──
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ── Ambil satu data berdasarkan NIM ──
    public function getByNim(string $nim, ?int $excludeId = null): array|false {
        $sql = "SELECT * FROM mahasiswa WHERE nim = :nim";
        $params = [':nim' => $nim];
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    // ── Tambah mahasiswa baru ──
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO mahasiswa
                (nim, nama_lengkap, jk, jurusan, jurusan_nama, angkatan, status,
                 tempat, tanggal, telp, email, alamat, masuk)
            VALUES
                (:nim, :nama_lengkap, :jk, :jurusan, :jurusan_nama, :angkatan, :status,
                 :tempat, :tanggal, :telp, :email, :alamat, :masuk)
        ");
        $stmt->execute($this->sanitize($data));
        return (int)$this->db->lastInsertId();
    }

    // ── Update data mahasiswa ──
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE mahasiswa SET
                nim          = :nim,
                nama_lengkap = :nama_lengkap,
                jk           = :jk,
                jurusan      = :jurusan,
                jurusan_nama = :jurusan_nama,
                angkatan     = :angkatan,
                status       = :status,
                tempat       = :tempat,
                tanggal      = :tanggal,
                telp         = :telp,
                email        = :email,
                alamat       = :alamat,
                masuk        = :masuk
            WHERE id = :id
        ");
        $params = $this->sanitize($data);
        $params[':id'] = $id;
        return $stmt->execute($params);
    }

    // ── Hapus data mahasiswa ──
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ── Statistik ringkasan ──
    public function getStats(): array {
        $row = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(status = 'Aktif') AS aktif,
                SUM(status = 'Cuti')  AS cuti,
                SUM(status = 'Lulus') AS lulus,
                SUM(jurusan = 'PPL')  AS ppl,
                SUM(jurusan = 'DM')   AS dm
            FROM mahasiswa
        ")->fetch();

        return [
            'total' => (int)$row['total'],
            'aktif' => (int)$row['aktif'],
            'cuti'  => (int)$row['cuti'],
            'lulus' => (int)$row['lulus'],
            'ppl'   => (int)$row['ppl'],
            'dm'    => (int)$row['dm'],
        ];
    }

    // ── Mahasiswa terbaru (untuk dashboard) ──
    public function getLatest(int $limit = 5): array {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Sanitasi input sebelum disimpan ke DB ──
    private function sanitize(array $data): array {
        $jurusanNama = ($data['jurusan'] ?? '') === 'PPL'
            ? 'Pengembangan Perangkat Lunak'
            : 'Digital Marketing';

        return [
            ':nim'          => trim($data['nim']          ?? ''),
            ':nama_lengkap' => trim($data['nama_lengkap'] ?? ''),
            ':jk'           => $data['jk']                ?? 'Laki-laki',
            ':jurusan'      => $data['jurusan']            ?? 'PPL',
            ':jurusan_nama' => $jurusanNama,
            ':angkatan'     => $data['angkatan']           ?? date('Y'),
            ':status'       => $data['status']             ?? 'Aktif',
            ':tempat'       => trim($data['tempat']        ?? ''),
            ':tanggal'      => $data['tanggal']            ?: null,
            ':telp'         => trim($data['telp']          ?? ''),
            ':email'        => trim($data['email']         ?? ''),
            ':alamat'       => trim($data['alamat']        ?? ''),
            ':masuk'        => $data['masuk']              ?: null,
        ];
    }
}
