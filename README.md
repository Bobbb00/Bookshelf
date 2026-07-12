# 📚 Bookshelf — Sistem Manajemen Buku & Barang

> **Tugas PBF (Pemrograman Berbasis Framework)**
> Universitas Bhayangkara Jakarta Raya | Program Studi Informatika
> Framework: **CodeIgniter 4**

---

## 📋 Progres Pengerjaan Soal

| No Soal | Ketentuan                                                                             | Bobot | Status                 |
| ------- | ------------------------------------------------------------------------------------- | ----- | ---------------------- |
| 4       | Instalasi framework CodeIgniter 4 & jelaskan struktur folder                          | 5%    | ✅ Selesai             |
| 5       | Buat minimal 3 routing (home, barang, dashboard)                                      | 5%    | ✅ Selesai (12+ route) |
| 6       | Buat controller dasar untuk modul barang (index, create, store, edit, update, delete) | 10%   | ✅ Selesai             |
| 7       | Buat tampilan (view): halaman data barang, form tambah, form edit                     | 10%   | ✅ Selesai             |
| 8       | Implementasi CRUD Barang terhubung database (tambah, tampil, edit, hapus)             | 20%   | ✅ Selesai             |

---

## 🛠️ Tech Stack

| Komponen  | Detail                      |
| --------- | --------------------------- |
| Framework | CodeIgniter 4.6.x           |
| Database  | MySQL / MariaDB (via XAMPP) |
| Auth      | Myth/Auth                   |
| Frontend  | Bootstrap 5 + FontAwesome 6 |
| PHP       | >= 8.1                      |

---

## ⚙️ Cara Menjalankan Proyek

### Prasyarat

Pastikan sudah terinstall:

- [XAMPP](https://www.apachefriends.org/) (PHP 8.1+, MySQL/MariaDB)
- [Composer](https://getcomposer.org/)
- Git

---

### Langkah 1 — Clone Repositori

```bash
git clone https://github.com/Bobbb00/Bookshelf.git
cd Bookshelf
```

### Langkah 2 — Install Dependency Composer

```bash
composer install
```

### Langkah 3 — Konfigurasi Environment

Salin file template environment:

```bash
cp env .env
```

Lalu buka file `.env` dan sesuaikan konfigurasi berikut:

```ini
# Ubah mode ke development
CI_ENVIRONMENT = development

# Sesuaikan konfigurasi database
database.default.hostname = 127.0.0.1
database.default.database = pbf
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

> ⚠️ Jangan ubah nama file `.env` ke nama lain dan jangan commit file ini ke Git.

### Langkah 4 — Siapkan Database

Pastikan **XAMPP** sudah berjalan (Apache + MySQL aktif), kemudian buat database kosong terlebih dahulu:

1. Buka `http://localhost/phpmyadmin`
2. Klik **New** → beri nama `pbf` → klik **Create**

Setelah database `pbf` dibuat, pilih salah satu cara berikut:

---

#### 🅐 Via Migration & Seeder *(Cara Standar CI4)*

Cara ini menggunakan file migration yang ada di `app/Database/Migrations/` untuk membuat tabel secara otomatis, lalu mengisi data awal via Seeder.

```bash
# 1. Jalankan semua migration (membuat semua tabel)
php spark migrate

# 2. Isi data awal (auth_groups, users, buku, dll)
php spark db:seed AppSeeder
```

Urutan tabel yang dibuat oleh migration:

| Batch | Migration File | Yang Dibuat |
|-------|----------------|-------------|
| 1 | `Myth\Auth` (otomatis) | Semua tabel `auth_*` + `users` |
| 2 | `2026-06-09-030000_CreateBukuTable` | Tabel `buku` |
| 2 | `2026-06-09-035144_CreateCartAndOrderTables` | Tabel `carts`, `cart_items`, `orders`, `order_items` + kolom `alamat`, `no_hp` di `users` |
| 2 | `2026-06-18-100000_AddCustomFieldsToUsers` | Kolom `fullname`, `user_img` di `users` |

> ✅ Setelah selesai, semua tabel dan data awal (akun admin, user, data buku) sudah siap.

---

#### 🅑 Via Import SQL *(Cara Cepat)*

Cara ini menggunakan file `pbf.sql` yang sudah berisi semua tabel dan data sekaligus.

**Lewat phpMyAdmin:**
1. Pilih database `pbf` → klik tab **Import**
2. Klik **Choose File** → pilih file `pbf.sql` dari root folder proyek
3. Klik **Go / Import** → tunggu hingga sukses ✅

**Lewat Command Line:**
```bash
mysql -u root pbf < pbf.sql
```

> 📝 Gunakan cara ini jika ingin setup cepat tanpa menjalankan migration satu per satu.

---

### Langkah 5 — Buat Folder Upload *(Jika Belum Ada)*

```bash
# Windows
mkdir public\img\buku

# Linux / Mac
mkdir -p public/img/buku
```

### Langkah 6 — Jalankan Server

```bash
php spark serve
```

Akses aplikasi di browser:

```
http://localhost:8080
```

> 💡 Alternatif via XAMPP: akses `http://localhost/PBF/CI4/public`

---

## 🔐 Akun Login

### Admin (Akses Penuh)

| Field        | Value                                          |
| ------------ | ---------------------------------------------- |
| **Email**    | `admin@gmail.com`                              |
| **Password** | `Qwerty45`                                     |
| **Role**     | Administrator                                  |
| **Akses**    | Dashboard, CRUD Buku, CRUD Barang, Kelola User |

### User Biasa (Akses Terbatas)

| Field        | Value               |
| ------------ | ------------------- |
| **Email**    | `user@gmail.com`    |
| **Password** | `Qwerty45`          |
| **Role**     | User                |
| **Akses**    | Halaman publik saja |

---

## 🚦 Routing

### Autentikasi

| Method   | URL         | Fungsi                    |
| -------- | ----------- | ------------------------- |
| GET      | `/`         | Redirect ke halaman login |
| GET/POST | `/login`    | Halaman login (Myth/Auth) |
| GET/POST | `/register` | Halaman register          |
| GET      | `/logout`   | Logout                    |

### Admin — Dashboard

| Method | URL          | Controller        | Fungsi                |
| ------ | ------------ | ----------------- | --------------------- |
| GET    | `/dashboard` | `Home::dashboard` | Dashboard + statistik |

### Admin — Buku

| Method | URL                 | Controller     | Fungsi             |
| ------ | ------------------- | -------------- | ------------------ |
| GET    | `/buku`             | `Buku::index`  | Tampil daftar buku |
| GET    | `/buku/create`      | `Buku::create` | Form tambah buku   |
| POST   | `/buku/store`       | `Buku::store`  | Simpan buku baru   |
| GET    | `/buku/edit/{id}`   | `Buku::edit`   | Form edit buku     |
| POST   | `/buku/update/{id}` | `Buku::update` | Update data buku   |
| GET    | `/buku/delete/{id}` | `Buku::delete` | Hapus buku         |

### Admin — Barang

| Method | URL                   | Controller       | Fungsi               |
| ------ | --------------------- | ---------------- | -------------------- |
| GET    | `/barang`             | `Barang::index`  | Tampil daftar barang |
| GET    | `/barang/create`      | `Barang::create` | Form tambah barang   |
| POST   | `/barang/store`       | `Barang::store`  | Simpan barang baru   |
| GET    | `/barang/edit/{id}`   | `Barang::edit`   | Form edit barang     |
| POST   | `/barang/update/{id}` | `Barang::update` | Update data barang   |
| GET    | `/barang/delete/{id}` | `Barang::delete` | Hapus barang         |

### Admin — Kelola User

| Method | URL                 | Controller           | Fungsi             |
| ------ | ------------------- | -------------------- | ------------------ |
| GET    | `/user`             | `Admin\User::index`  | Tampil daftar user |
| GET    | `/user/create`      | `Admin\User::create` | Form tambah user   |
| POST   | `/user/store`       | `Admin\User::store`  | Simpan user baru   |
| GET    | `/user/edit/{id}`   | `Admin\User::edit`   | Form edit user     |
| POST   | `/user/update/{id}` | `Admin\User::update` | Update data user   |
| GET    | `/user/delete/{id}` | `Admin\User::delete` | Hapus user         |

---

## 🗃️ Skema Database

**Nama Database:** `pbf`
**Driver:** MySQLi | **Host:** 127.0.0.1 | **Port:** 3306

### Tabel `buku`

| Kolom        | Tipe               | Keterangan         |
| ------------ | ------------------ | ------------------ |
| `id`         | INT AUTO_INCREMENT | Primary key        |
| `judul`      | VARCHAR(150)       | Judul buku         |
| `pengarang`  | VARCHAR(100)       | Nama pengarang     |
| `penerbit`   | VARCHAR(100)       | Nama penerbit      |
| `isbn`       | VARCHAR(20)        | ISBN (opsional)    |
| `genre`      | VARCHAR(50)        | Genre buku         |
| `harga`      | DECIMAL(15,2)      | Harga buku         |
| `stok`       | INT                | Jumlah stok        |
| `deskripsi`  | TEXT               | Deskripsi/sinopsis |
| `gambar`     | VARCHAR(255)       | Nama file cover    |
| `created_at` | DATETIME           | Dibuat otomatis    |
| `updated_at` | DATETIME           | Diupdate otomatis  |

### Tabel `barang`

| Kolom         | Tipe               | Keterangan           |
| ------------- | ------------------ | -------------------- |
| `id`          | INT AUTO_INCREMENT | Primary key          |
| `nama_barang` | VARCHAR(100)       | Nama barang          |
| `kategori`    | VARCHAR(50)        | Kategori barang      |
| `harga`       | DECIMAL(15,2)      | Harga barang         |
| `stok`        | INT                | Jumlah stok          |
| `deskripsi`   | TEXT               | Deskripsi (opsional) |
| `created_at`  | DATETIME           | Dibuat otomatis      |
| `updated_at`  | DATETIME           | Diupdate otomatis    |

### Tabel `users` & Auth (Myth/Auth)

Menggunakan tabel bawaan **Myth/Auth**: `users`, `auth_groups`, `auth_groups_users`, `auth_permissions`, `auth_logins`, `auth_tokens`, dll.

---

## 🗂️ Struktur Folder Penting

```
CI4/
├── app/
│   ├── Config/
│   │   ├── Routes.php          → Semua routing aplikasi
│   │   ├── Filters.php         → Filter login & role (Myth/Auth)
│   │   └── Auth.php            → Konfigurasi autentikasi
│   ├── Controllers/
│   │   ├── Home.php            → Controller utama (dashboard, redirect)
│   │   ├── Barang.php          → CRUD Barang
│   │   └── Admin/
│   │       └── User.php        → CRUD User (admin only)
│   ├── Models/
│   │   ├── BukuModel.php       → Model tabel buku + validasi
│   │   └── BarangModel.php     → Model tabel barang + validasi
│   └── Views/
│       ├── auth/               → Login & Register
│       ├── admin/
│       │   ├── index.php       → Dashboard
│       │   ├── buku/           → CRUD Buku (index, create, edit)
│       │   └── user/           → Kelola User (index, create, edit)
│       ├── barang/             → CRUD Barang (index, create, edit)
│       ├── components/         → Komponen reusable (stat_card, book_card)
│       └── template/           → Layout utama (admin & user)
├── public/                     → Entry point & assets
│   └── img/buku/               → Upload cover buku
├── env                         → Template konfigurasi (salin ke .env)
├── composer.json               → Dependency PHP
└── spark                       → CLI CodeIgniter
```

---

## 🔐 Autentikasi & Hak Akses

Menggunakan library **Myth/Auth** untuk CodeIgniter 4.

| Role    | Akses                                                         |
| ------- | ------------------------------------------------------------- |
| `admin` | Seluruh fitur: Dashboard, CRUD Buku, CRUD Barang, Kelola User |
| `user`  | Halaman publik / terbatas                                     |

- Filter `login` aktif → semua halaman memerlukan autentikasi
- Role di-check via `inGroup('admin')` pada controller
- Setelah login → redirect ke `/dashboard`

---

## ❓ Troubleshooting

**Q: Halaman blank / error 500?**

> Pastikan file `.env` sudah dibuat (bukan masih bernama `env`) dan isi `CI_ENVIRONMENT = development` untuk melihat detail error di browser.

**Q: Database tidak bisa konek?**

> Cek konfigurasi `database.default.*` di `.env`. Pastikan XAMPP MySQL sudah **Running** dan nama database `pbf` sudah dibuat.

**Q: `composer install` gagal?**

> Pastikan versi PHP >= 8.1 dengan menjalankan `php -v`. Jika belum, update PHP di XAMPP.

**Q: `php spark migrate` error / tabel sudah ada?**

> Jika database sudah pernah diimport via SQL lalu dijalankan migrate, bisa konflik. Solusi:
> ```bash
> # Rollback semua migration lalu migrate ulang
> php spark migrate:rollback
> php spark migrate
> ```
> Atau drop database `pbf` dan buat ulang, lalu migrate dari awal.

**Q: `php spark db:seed AppSeeder` gagal / data duplikat?**

> Seeder menggunakan `ON CONFLICT DO NOTHING` (untuk PostgreSQL). Untuk MySQL, jika muncul error duplikat, jalankan:
> ```bash
> php spark db:seed AppSeeder --force
> ```
> Atau truncate tabel terkait terlebih dahulu via phpMyAdmin.

**Q: Gambar upload tidak muncul?**

> Pastikan folder `public/img/buku/` sudah ada dan memiliki permission write. Buat manual jika belum ada.

**Q: Error "Class not found" setelah clone?**

> Jalankan `composer dump-autoload` untuk menyegarkan autoloader.
