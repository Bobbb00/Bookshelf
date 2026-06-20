<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AuditCheck extends BaseCommand
{
    protected $group       = 'Audit';
    protected $name        = 'audit:check';
    protected $description = 'Pre-Production Audit Check for PBF Bookshelf Application';
    protected $usage       = 'audit:check';
    protected $arguments   = [];
    protected $options     = [];

    public function run(array $params)
    {
        CLI::write('=== PBF Pre-Production Audit Check ===', 'cyan');
        CLI::write('Server Time: ' . date('Y-m-d H:i:s') . "\n", 'yellow');

        $pass = 0;
        $warn = 0;
        $fail = 0;
        $info = 0;
        $findings = [];

        // ----------------------------------------------------
        // 1. Audit Konfigurasi Dasar CodeIgniter 4
        // ----------------------------------------------------
        
        // Environment check
        $env = ENVIRONMENT;
        CLI::write("[INFO] Aplikasi berjalan pada environment: {$env}", 'blue');
        $info++;

        // Base URL
        $appConfig = config('App');
        if (!empty($appConfig->baseURL) && $appConfig->baseURL !== 'http://localhost:8080/') {
            CLI::write('[PASS] Base URL terkonfigurasi dengan benar: ' . $appConfig->baseURL, 'green');
            $pass++;
        } else {
            CLI::write('[WARN] Base URL masih menggunakan default localhost: ' . ($appConfig->baseURL ?? 'kosong'), 'yellow');
            $warn++;
            $findings[] = [
                'Bagian' => 'Config/App',
                'Temuan' => 'Base URL menggunakan default localhost',
                'Risiko' => 'Aplikasi tidak dapat diakses di production',
                'Tingkat' => 'Sedang',
                'Rekomendasi' => 'Ubah app.baseURL di file .env sesuai domain production'
            ];
        }

        // Database connection
        $db = null;
        try {
            $db = \Config\Database::connect();
            $db->connect();
            CLI::write('[PASS] Database default berhasil terkoneksi', 'green');
            $pass++;
        } catch (\Exception $e) {
            CLI::write('[FAIL] Database default gagal terkoneksi: ' . $e->getMessage(), 'red');
            $fail++;
            $findings[] = [
                'Bagian' => 'Database',
                'Temuan' => 'Gagal koneksi ke database',
                'Risiko' => 'Aplikasi tidak berjalan',
                'Tingkat' => 'Tinggi',
                'Rekomendasi' => 'Periksa pengaturan database di .env'
            ];
        }

        // Writable folder
        if (is_writable(WRITEPATH)) {
            CLI::write('[PASS] Folder writable/ dapat ditulis (writable)', 'green');
            $pass++;
        } else {
            CLI::write('[FAIL] Folder writable/ tidak dapat ditulis (non-writable)', 'red');
            $fail++;
            $findings[] = [
                'Bagian' => 'Writable Folder',
                'Temuan' => 'Direktori writable/ tidak memiliki izin menulis',
                'Risiko' => 'Log, cache, dan session file gagal ditulis',
                'Tingkat' => 'Tinggi',
                'Rekomendasi' => 'Ubah permission folder writable/ (chmod 755/777)'
            ];
        }

        // CSRF Check
        $filtersConfig = config('Filters');
        $csrfActive = in_array('csrf', $filtersConfig->globals['before'] ?? []);
        if ($csrfActive) {
            CLI::write('[PASS] Filter CSRF aktif secara global', 'green');
            $pass++;
        } else {
            // Check in filters
            CLI::write('[WARN] Filter CSRF tidak aktif secara global di globals[\'before\']', 'yellow');
            $warn++;
            $findings[] = [
                'Bagian' => 'Config/Filters',
                'Temuan' => 'CSRF tidak aktif secara global',
                'Risiko' => 'Kerentanan terhadap serangan Cross-Site Request Forgery',
                'Tingkat' => 'Sedang',
                'Rekomendasi' => 'Aktifkan filter csrf di Config/Filters.php globals[before]'
            ];
        }

        // ----------------------------------------------------
        // 2. Audit Struktur Tabel Database
        // ----------------------------------------------------
        if ($db) {
            $requiredTables = [
                'users'       => 'Tabel user utama',
                'buku'        => 'Tabel buku (alias dari books)',
                'carts'       => 'Tabel keranjang belanja utama',
                'cart_items'  => 'Tabel item keranjang belanja',
                'orders'      => 'Tabel transaksi order',
                'order_items' => 'Tabel item transaksi order'
            ];

            foreach ($requiredTables as $table => $desc) {
                if ($db->tableExists($table)) {
                    CLI::write("[PASS] Tabel {$table} ditemukan ({$desc})", 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Tabel {$table} belum ditemukan ({$desc})", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Database Schema',
                        'Temuan' => "Tabel {$table} tidak ditemukan",
                        'Risiko' => "Fitur terkait {$table} akan error/tidak berfungsi",
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => "Jalankan database migration untuk membuat tabel {$table}"
                    ];
                }
            }

            // ----------------------------------------------------
            // 3. Audit Data User dan Role
            // ----------------------------------------------------
            if ($db->tableExists('users')) {
                // Email kosong
                $emptyEmails = $db->table('users')->where('email', '')->orWhere('email', null)->countAllResults();
                if ($emptyEmails === 0) {
                    CLI::write('[PASS] Tidak ada user dengan email kosong', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$emptyEmails} user dengan email kosong", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data User',
                        'Temuan' => "Ada {$emptyEmails} user dengan email kosong",
                        'Risiko' => 'Masalah pengiriman notifikasi dan keamanan akun',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Bersihkan data user atau tambahkan validasi email wajib diisi'
                    ];
                }

                // Email duplikat
                $dupQuery = $db->query("SELECT email, COUNT(*) AS total FROM users WHERE email IS NOT NULL AND email != '' GROUP BY email HAVING COUNT(*) > 1");
                $dupCount = $dupQuery->getNumRows();
                if ($dupCount === 0) {
                    CLI::write('[PASS] Tidak ada email duplikat pada tabel users', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$dupCount} email duplikat di tabel users", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data User',
                        'Temuan' => "Ada {$dupCount} email terdaftar pada beberapa akun",
                        'Risiko' => 'Masalah autentikasi akun dan duplikasi identitas',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Tambahkan unique constraint pada kolom email'
                    ];
                }

                // Password plain text (length < 20)
                $plainPasswords = $db->table('users')->where('LENGTH(password_hash) <', 20)->countAllResults();
                if ($plainPasswords === 0) {
                    CLI::write('[PASS] Tidak ada indikasi password disimpan secara plain text', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$plainPasswords} user dengan password yang dicurigai plain text", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data User',
                        'Temuan' => "Password {$plainPasswords} user dicurigai belum di-hash",
                        'Risiko' => 'Kebocoran kredensial dan kerentanan data user',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Gunakan password_hash() untuk mengenkripsi password'
                    ];
                }

                // Role check
                // Myth\Auth roles are in auth_groups and auth_groups_users
                if ($db->tableExists('auth_groups_users')) {
                    $unassignedUsers = $db->query("SELECT u.id FROM users u LEFT JOIN auth_groups_users agu ON u.id = agu.user_id WHERE agu.group_id IS NULL AND u.deleted_at IS NULL")->getNumRows();
                    if ($unassignedUsers === 0) {
                        CLI::write('[PASS] Semua user memiliki role yang sah', 'green');
                        $pass++;
                    } else {
                        CLI::write("[FAIL] Ditemukan {$unassignedUsers} user tanpa role/hak akses", 'red');
                        $fail++;
                        $findings[] = [
                            'Bagian' => 'Data User',
                            'Temuan' => "Ada {$unassignedUsers} user yang tidak masuk ke grup manapun",
                            'Risiko' => 'User tidak dapat mengakses fitur berdasar role',
                            'Tingkat' => 'Tinggi',
                            'Rekomendasi' => 'Pastikan setiap user yang mendaftar langsung diberikan grup default'
                        ];
                    }
                }
            }

            // ----------------------------------------------------
            // 4. Audit Data Buku
            // ----------------------------------------------------
            if ($db->tableExists('buku')) {
                // Invalid books check
                $invalidBooks = $db->table('buku')
                                  ->groupStart()
                                    ->where('judul', '')
                                    ->orWhere('judul', null)
                                    ->orWhere('pengarang', '')
                                    ->orWhere('pengarang', null)
                                    ->orWhere('harga <=', 0)
                                    ->orWhere('stok <', 0)
                                  ->groupEnd()
                                  ->countAllResults();

                if ($invalidBooks === 0) {
                    CLI::write('[PASS] Data buku tervalidasi dengan baik (harga > 0 dan stok >= 0)', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$invalidBooks} buku dengan data tidak valid (harga/stok tidak sesuai)", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data Buku',
                        'Temuan' => "Ada {$invalidBooks} buku tidak valid",
                        'Risiko' => 'Masalah kalkulasi belanja dan keakuratan stok barang',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Bersihkan data buku tidak valid atau perbaiki harga & stok'
                    ];
                }

                // Missing images
                $noImageBooks = $db->table('buku')->where('gambar', '')->orWhere('gambar', 'default.png')->orWhere('gambar', null)->countAllResults();
                if ($noImageBooks === 0) {
                    CLI::write('[PASS] Semua buku memiliki gambar cover unik', 'green');
                    $pass++;
                } else {
                    CLI::write("[WARN] Ditemukan {$noImageBooks} buku menggunakan gambar default/tanpa gambar", 'yellow');
                    $warn++;
                    $findings[] = [
                        'Bagian' => 'Data Buku',
                        'Temuan' => "Ada {$noImageBooks} buku tanpa cover unik",
                        'Risiko' => 'Tampilan katalog kurang menarik bagi customer',
                        'Tingkat' => 'Rendah',
                        'Rekomendasi' => 'Unggah cover gambar untuk semua buku'
                    ];
                }

                // Dummy books check
                $dummyBooks = $db->table('buku')
                                 ->like('judul', 'test')
                                 ->orLike('judul', 'dummy')
                                 ->orLike('deskripsi', 'lorem ipsum')
                                 ->countAllResults();
                if ($dummyBooks === 0) {
                    CLI::write('[PASS] Tidak ada data buku dummy/test', 'green');
                    $pass++;
                } else {
                    CLI::write("[WARN] Ditemukan {$dummyBooks} buku dummy/test", 'yellow');
                    $warn++;
                    $findings[] = [
                        'Bagian' => 'Data Buku',
                        'Temuan' => "Ada {$dummyBooks} data buku test/dummy",
                        'Risiko' => 'Data dummy tampil di production',
                        'Tingkat' => 'Sedang',
                        'Rekomendasi' => 'Hapus data buku test/dummy sebelum rilis production'
                    ];
                }
            }

            // ----------------------------------------------------
            // 5. Audit Data Cart
            // ----------------------------------------------------
            if ($db->tableExists('cart_items') && $db->tableExists('buku')) {
                // Cart item with quantity > stock
                $overstockCarts = $db->table('cart_items')
                                    ->join('buku', 'buku.id = cart_items.buku_id')
                                    ->where('cart_items.qty > buku.stok')
                                    ->countAllResults();

                if ($overstockCarts === 0) {
                    CLI::write('[PASS] Kuantitas cart tidak melebihi stok buku', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$overstockCarts} cart item dengan kuantitas melebihi stok buku", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data Cart',
                        'Temuan' => "Ada {$overstockCarts} item keranjang melebihi stok",
                        'Risiko' => 'Pembelian gagal/over-sell saat checkout',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Tambahkan validasi stok di backend saat checkout dan update keranjang'
                    ];
                }

                // Orphaned cart items
                $orphanedCarts = $db->table('cart_items')
                                    ->join('carts', 'carts.id = cart_items.cart_id', 'left')
                                    ->where('carts.id', null)
                                    ->countAllResults();

                if ($orphanedCarts === 0) {
                    CLI::write('[PASS] Relasi data cart valid', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$orphanedCarts} item keranjang yatim (tanpa parent cart)", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data Cart',
                        'Temuan' => "Ada {$orphanedCarts} item keranjang yatim",
                        'Risiko' => 'Kotoran data di database',
                        'Tingkat' => 'Rendah',
                        'Rekomendasi' => 'Bersihkan data yatim dan buat foreign key cascade'
                    ];
                }
            }

            // ----------------------------------------------------
            // 6. Audit Checkout dan Order
            // ----------------------------------------------------
            if ($db->tableExists('orders') && $db->tableExists('order_items')) {
                // Total price mismatch
                $mismatchOrders = $db->query("
                    SELECT o.id 
                    FROM orders o 
                    JOIN (
                        SELECT order_id, SUM(qty * harga) AS calculated_total 
                        FROM order_items 
                        GROUP BY order_id
                    ) oi ON o.id = oi.order_id 
                    WHERE ABS(o.total_pembayaran - oi.calculated_total) > 0.01
                ")->getNumRows();

                if ($mismatchOrders === 0) {
                    CLI::write('[PASS] Total order sesuai dengan total rincian item belanja', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$mismatchOrders} order dengan ketidaksesuaian total harga", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data Order',
                        'Temuan' => "Ada {$mismatchOrders} pesanan dengan total harga salah",
                        'Risiko' => 'Kesalahan transaksi keuangan dan pembukuan toko',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Pastikan total harga dihitung ulang dan disimpan aman dari backend'
                    ];
                }

                // Valid status check
                $invalidStatusOrders = $db->table('orders')
                                          ->whereNotIn('status_pesanan', [
                                              'Menunggu Konfirmasi',
                                              'Diproses',
                                              'Dikirim',
                                              'Selesai',
                                              'Dibatalkan'
                                          ])->countAllResults();

                if ($invalidStatusOrders === 0) {
                    CLI::write('[PASS] Semua status order bernilai valid', 'green');
                    $pass++;
                } else {
                    CLI::write("[FAIL] Ditemukan {$invalidStatusOrders} order dengan status tidak valid", 'red');
                    $fail++;
                    $findings[] = [
                        'Bagian' => 'Data Order',
                        'Temuan' => "Ada {$invalidStatusOrders} pesanan dengan status di luar daftar sah",
                        'Risiko' => 'Alur transaksi terhenti atau error',
                        'Tingkat' => 'Tinggi',
                        'Rekomendasi' => 'Gunakan enum atau konstanta untuk membatasi status pesanan'
                    ];
                }
            }
        }

        // ----------------------------------------------------
        // 7. Output Hasil Akhir & Kesimpulan
        // ----------------------------------------------------
        CLI::write("\n=== Ringkasan Hasil Audit ===", 'cyan');
        CLI::write("PASS : {$pass}", 'green');
        CLI::write("WARN : {$warn}", 'yellow');
        CLI::write("FAIL : {$fail}", 'red');
        CLI::write("INFO : {$info}", 'blue');

        CLI::write("\n=== Kesimpulan ===", 'cyan');
        if ($fail > 0) {
            CLI::write("BELUM SESUAI", 'red');
        } elseif ($warn > 0) {
            CLI::write("SESUAI DENGAN CATATAN", 'yellow');
        } else {
            CLI::write("SUDAH SESUAI", 'green');
        }

        if (!empty($findings)) {
            CLI::write("\n=== Temuan Audit ===", 'red');
            foreach ($findings as $i => $f) {
                $num = $i + 1;
                CLI::write("{$num}. Bagian: {$f['Bagian']}", 'yellow');
                CLI::write("   Temuan: {$f['Temuan']}");
                CLI::write("   Risiko: {$f['Risiko']}");
                CLI::write("   Tingkat Risiko: {$f['Tingkat']}");
                CLI::write("   Rekomendasi: {$f['Rekomendasi']}");
            }
        }
    }
}
