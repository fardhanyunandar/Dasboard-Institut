# 🏷️ Dasboard Institut
### Platform manajemen data siswa berbasis web untuk administrator institusi pendidikan, dilengkapi fitur CRUD lengkap dan tampilan dashboard yang informatif.

> Banyak institusi pendidikan masih mengelola data siswa secara manual menggunakan spreadsheet atau dokumen fisik, yang rentan terhadap kesalahan dan sulit diakses secara cepat. Dasboard-Institut hadir sebagai solusi berbasis web yang memudahkan administrator sekolah atau kampus dalam mengelola data siswa secara terpusat — mulai dari penambahan, pengeditan, hingga penghapusan data — tanpa perlu keahlian teknis khusus. Dengan tampilan dashboard yang informatif, admin dapat memantau kondisi data siswa secara real-time dan efisien.

![Tech Stack](https://skillicons.dev/icons?i=php,html,css,js,mysql)

---

## 🌟 Tentang Project

Dasboard-Institut lahir dari kebutuhan nyata di lingkungan PeTIK Jombang, di mana pengelolaan data mahasantri masih dilakukan secara manual dan memakan waktu. Project ini dibangun sebagai solusi digital yang menyederhanakan proses tersebut melalui fitur CRUD yang terstruktur dan antarmuka dashboard yang mudah digunakan. Yang membedakannya adalah konteks penggunaannya — dirancang khusus untuk kebutuhan institusi berbasis pesantren, bukan sekadar dashboard generik.

---

## ✨ Fitur Utama

- ✅ Fitur Utama:

- ✅ Login admin dengan autentikasi untuk keamanan akses
- ✅ Tambah data mahasantri melalui form yang terstruktur
- ✅ Lihat daftar mahasantri dalam tampilan tabel yang informatif
- ✅ Hapus data mahasantri dengan konfirmasi untuk mencegah kesalahan
---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP / Laravel |
| Frontend | HTML5, CSS3, JavaScript, Bootstrap |
| Database | MySQL |
| Tools | VS Code, Git |

---

## 🚀 Cara Menjalankan

### Prasyarat
- PHP >= 8.x
- MySQL
- Composer *(kalau pakai Laravel)*

### 1. Clone Repository
```bash
git clone https://github.com/fardhanyunandar/Dashboard-Institut.git
cd Dashboard-Institut
```

### 2. Setup Database
```sql
CREATE DATABASE Dashboard-Institut;
```
Lalu import file `database.sql` yang ada di folder project.

### 3. Konfigurasi Koneksi
Edit file `config.php` (atau `.env` kalau Laravel):
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'Dashboard-Institut';
```

### 4. Jalankan di Browser
Letakkan folder project di `htdocs` (XAMPP) atau `www` (Laragon), lalu akses:
```
http://localhost/Dashboard-Institut
```

---

## 📁 Struktur Project

```
Dashboard-Institut/
├── index.php           ← Halaman utama
├── config.php          ← Konfigurasi database
├── assets/
│   ├── css/            ← Stylesheet
│   └── js/             ← JavaScript
├── includes/           ← Komponen reusable (header, footer)
└── pages/              ← Halaman-halaman aplikasi
```

## 👨‍💻 Developer

**Fardhan Maulana Yunandar**
Junior Full-Stack Developer

[![LinkedIn](https://img.shields.io/badge/LinkedIn-fardhanmaulana-blue?logo=linkedin)](https://linkedin.com/in/fardhanmaulana)
[![GitHub](https://img.shields.io/badge/GitHub-fardhanyunandar-black?logo=github)](https://github.com/fardhanyunandar)
[![Email](https://img.shields.io/badge/Email-fardhanyundr@gmail.com-red?logo=gmail)](mailto:fardhanyundr@gmail.com)
[![Website](https://img.shields.io/badge/Website-fardhan.web.id-green?logo=google-chrome)](https://fardhan.web.id)

---

*Built with ❤️ by Fardhan Maulana Yunandar*
