<?php
// ══════════════════════════════════════════════
//  KONFIGURASI DATABASE — Sistem Akademik PeTIK
// ══════════════════════════════════════════════

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'petik_akademik');
define('DB_PORT', 3306);

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die(json_encode([
                'error' => true,
                'message' => 'Koneksi database gagal: ' . $e->getMessage()
            ]));
        }
    }
    return $pdo;
}

// ── SQL untuk membuat tabel (jalankan sekali saat setup) ──
function setupDatabase(): void {
    $pdo = getDB();

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS mahasiswa (
            id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nim         VARCHAR(20)  NOT NULL UNIQUE,
            nama_lengkap VARCHAR(100) NOT NULL,
            jk          ENUM('Laki-laki','Perempuan') NOT NULL DEFAULT 'Laki-laki',
            jurusan     ENUM('PPL','DM') NOT NULL DEFAULT 'PPL',
            jurusan_nama VARCHAR(100) NOT NULL,
            angkatan    YEAR         NOT NULL DEFAULT 2025,
            status      ENUM('Aktif','Cuti','Lulus','DO') NOT NULL DEFAULT 'Aktif',
            tempat      VARCHAR(100),
            tanggal     DATE,
            telp        VARCHAR(20),
            email       VARCHAR(100),
            alamat      TEXT,
            masuk       DATE,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Seed data awal jika tabel kosong
    $count = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    if ((int)$count === 0) {
        $seed = [
            ['2501','Fardhan Maulana','Laki-laki','PPL','Pengembangan Perangkat Lunak','2025','Aktif','Jombang','2004-05-10','08111234001','fardhan@petik.ac.id','Jl. Mawar No. 1, Jombang','2025-07-14'],
            ['2502','Mukaim Laafu','Laki-laki','PPL','Pengembangan Perangkat Lunak','2025','Aktif','Mojokerto','2004-08-20','08111234002','mukaim@petik.ac.id','Jl. Melati No. 2, Mojokerto','2025-07-14'],
            ['2503','Abdillah Galang Prasojo','Laki-laki','PPL','Pengembangan Perangkat Lunak','2025','Aktif','Surabaya','2004-03-15','08111234003','abdillah@petik.ac.id','Jl. Kenanga No. 3, Surabaya','2025-07-14'],
            ['2504','Abdullah Syukur','Laki-laki','PPL','Pengembangan Perangkat Lunak','2025','Aktif','Jombang','2004-11-01','08111234004','abdullah@petik.ac.id','Jl. Anggrek No. 4, Jombang','2025-07-14'],
            ['2505','Alfan Syarif Hidayatullah','Laki-laki','PPL','Pengembangan Perangkat Lunak','2025','Aktif','Kediri','2004-07-22','08111234005','alfan@petik.ac.id','Jl. Dahlia No. 5, Kediri','2025-07-14'],
            ['2506','Ilham Hakim','Laki-laki','PPL','Pengembangan Perangkat Lunak','2025','Aktif','Gresik','2004-02-14','08111234006','ilham@petik.ac.id','Jl. Flamboyan No. 6, Gresik','2025-07-14'],
            ['2507','Miftahulhuda','Laki-laki','DM','Digital Marketing','2025','Aktif','Lamongan','2004-09-30','08111234007','miftah@petik.ac.id','Jl. Cempaka No. 7, Lamongan','2025-07-14'],
            ['2508','Ridho Nichol','Laki-laki','DM','Digital Marketing','2025','Aktif','Sidoarjo','2004-12-05','08111234008','ridho@petik.ac.id','Jl. Bougenville No. 8, Sidoarjo','2025-07-14'],
            ['2509','Abrar Ghifari','Laki-laki','DM','Digital Marketing','2025','Aktif','Malang','2004-06-18','08111234009','abrar@petik.ac.id','Jl. Teratai No. 9, Malang','2025-07-14'],
            ['2510','Yafi','Laki-laki','DM','Digital Marketing','2025','Aktif','Blitar','2004-04-25','08111234010','yafi@petik.ac.id','Jl. Seruni No. 10, Blitar','2025-07-14'],
        ];
        $stmt = $pdo->prepare("
            INSERT INTO mahasiswa (nim,nama_lengkap,jk,jurusan,jurusan_nama,angkatan,status,tempat,tanggal,telp,email,alamat,masuk)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        foreach ($seed as $row) {
            $stmt->execute($row);
        }
    }
}
