# Bookshelf — Aplikasi Manajemen Buku (CI4)

> **Tugas PBF — Universitas Bhayangkara Jakarta Raya**
> Program Studi Informatika | CodeIgniter 4

---

## 📋 Progres Pengerjaan Soal

| No Soal | Ketentuan | Bobot | Status |
|---------|-----------|-------|--------|
| 4 | Instalasi framework CodeIgniter 4 & jelaskan struktur folder | 5% | ✅ Selesai |
| 5 | Buat minimal 3 routing (home, barang, dashboard) | 5% | ✅ Selesai (9 route) |
| 6 | Buat controller dasar untuk modul barang (index, create, store, edit, update, delete) | 10% | ✅ Selesai |
| 7 | Buat tampilan (view): halaman data barang, form tambah, form edit | 10% | ✅ Selesai |
| 8 | Implementasi CRUD Barang terhubung database (tambah, tampil, edit, hapus) | 20% | ✅ Selesai |

---

## 🗂️ Struktur Folder Project

```
CI4/
├── app/
│   ├── Config/
│   │   ├── Routes.php       → Routing aplikasi (9 route)
│   │   ├── Filters.php      → Filter login, role, permission (Myth/Auth)
│   │   └── Auth.php         → Konfigurasi autentikasi
│   ├── Controllers/
│   │   ├── Home.php         → Controller utama (index, register, dashboard)
│   │   └── Buku.php         → Controller CRUD buku (6 method)
│   ├── Models/
│   │   └── BukuModel.php    → Model tabel buku + validasi
│   ├── Views/
│   │   ├── auth/
│   │   │   ├── login.php    → Halaman login
│   │   │   └── register.php → Halaman register
│   │   ├── admin/
│   │   │   ├── index.php    → Dashboard admin (statistik)
│   │   │   └── buku/
│   │   │       ├── index.php  → Halaman data buku (tampil)
│   │   │       ├── create.php → Form tambah buku
│   │   │       └── edit.php   → Form edit buku
│   │   └── template/
│   │       ├── index.php    → Layout utama
│   │       ├── sidebar.php  → Navigasi sidebar
│   │       ├── topbar.php   → Navigasi topbar
│   │       └── footer.php   → Footer
│   └── Database/
│       └── Migrations/      → (kosong, tabel dibuat manual)
├── public/                  → Entry point aplikasi
├── vendor/                  → Dependencies Composer
├── .env                     → Konfigurasi environment
└── spark                    → CLI CodeIgniter
```

---

## 🗃️ Database

**Nama Database:** `PBF`
**Driver:** MySQLi | **Host:** 127.0.0.1 | **Port:** 3306

### Tabel `buku`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT AUTO_INCREMENT | Primary key |
| `judul` | VARCHAR(150) | Judul buku |
| `pengarang` | VARCHAR(100) | Nama pengarang |
| `penerbit` | VARCHAR(100) | Nama penerbit |
| `isbn` | VARCHAR(20) | ISBN (opsional) |
| `genre` | VARCHAR(50) | Genre buku |
| `harga` | DECIMAL(15,2) | Harga buku |
| `stok` | INT | Jumlah stok |
| `deskripsi` | TEXT | Deskripsi/sinopsis |
| `created_at` | DATETIME | Dibuat otomatis |
| `updated_at` | DATETIME | Diupdate otomatis |

---

## 🚦 Routing

| Method | URL | Controller | Fungsi |
|--------|-----|------------|--------|
| GET | `/` | `Home::index` | Halaman login |
| GET | `/register` | `Home::register` | Halaman register |
| GET | `/dashboard` | `Home::dashboard` | Dashboard admin |
| GET | `/buku` | `Buku::index` | Tampil daftar buku |
| GET | `/buku/create` | `Buku::create` | Form tambah buku |
| POST | `/buku/store` | `Buku::store` | Simpan buku baru |
| GET | `/buku/edit/{id}` | `Buku::edit` | Form edit buku |
| POST | `/buku/update/{id}` | `Buku::update` | Update data buku |
| GET | `/buku/delete/{id}` | `Buku::delete` | Hapus buku |

---

## 🔐 Autentikasi

Menggunakan library **Myth/Auth** untuk CI4.

- Filter `login` aktif secara global → semua halaman membutuhkan login
- Filter `role` dan `permission` tersedia tapi belum diterapkan per-route
- Setelah login berhasil → redirect ke `/dashboard`

---

## ▶️ Cara Menjalankan

```bash
# Jalankan development server
php spark serve

# Akses di browser
http://localhost:8080
```

---

## 🛠️ Tech Stack

| Komponen | Detail |
|----------|--------|
| Framework | CodeIgniter 4 |
| Database | MySQL (via XAMPP) |
| Auth | Myth/Auth |
| Frontend | Bootstrap 5 + FontAwesome 6 |
| PHP | >= 8.1 |
