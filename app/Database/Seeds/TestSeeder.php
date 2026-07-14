<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * TestSeeder - Seeder khusus untuk environment testing Playwright.
 *
 * - Admin: test.admin@bookshelf.test / AdminTest@123
 * - User:  test.user@bookshelf.test  / UserTest@123
 *
 * Buku testing: 3 buku dengan stok berbeda.
 *
 * PERINGATAN: Jangan jalankan di database produksi!
 */
use Myth\Auth\Models\UserModel;
use Myth\Auth\Entities\User;

class TestSeeder extends Seeder
{
    // =====================================================================
    // Konstanta akun & data testing — dipakai juga oleh Playwright fixtures
    // =====================================================================
    public const ADMIN_EMAIL    = 'test.admin@bookshelf.test';
    public const ADMIN_USERNAME = 'test_admin';
    public const ADMIN_PASSWORD = 'AdminTest@123';

    public const USER_EMAIL    = 'test.user@bookshelf.test';
    public const USER_USERNAME = 'test_user';
    public const USER_PASSWORD = 'UserTest@123';


    // Buku testing
    public const BUKU_DATA = [
        [
            'id'        => 9001,
            'judul'     => '[TEST] Buku Playwright Alpha',
            'pengarang' => 'Test Author',
            'penerbit'  => 'Test Publisher',
            'isbn'      => '000-000-001',
            'genre'     => 'Pengujian',
            'harga'     => 50000,
            'stok'      => 10,
            'deskripsi' => 'Buku testing untuk skenario Playwright.',
            'gambar'    => 'default.png',
        ],
        [
            'id'        => 9002,
            'judul'     => '[TEST] Buku Playwright Beta',
            'pengarang' => 'Test Author',
            'penerbit'  => 'Test Publisher',
            'isbn'      => '000-000-002',
            'genre'     => 'Pengujian',
            'harga'     => 75000,
            'stok'      => 5,
            'deskripsi' => 'Buku testing kedua untuk keranjang.',
            'gambar'    => 'default.png',
        ],
        [
            'id'        => 9003,
            'judul'     => '[TEST] Buku Stok Habis',
            'pengarang' => 'Test Author',
            'penerbit'  => 'Test Publisher',
            'isbn'      => '000-000-003',
            'genre'     => 'Pengujian',
            'harga'     => 30000,
            'stok'      => 0,
            'deskripsi' => 'Buku testing dengan stok habis.',
            'gambar'    => 'default.png',
        ],
    ];

    public function run(): void
    {
        $this->db->disableForeignKeyChecks();

        // -----------------------------------------------------------------
        // 1. Bersihkan data testing lama
        // -----------------------------------------------------------------
        $this->cleanTestData();

        // -----------------------------------------------------------------
        // 2. Pastikan auth_groups ada (id=3=admin, id=4=user)
        // -----------------------------------------------------------------
        $this->ensureGroups();

        // -----------------------------------------------------------------
        // 3. Seed akun admin testing
        // -----------------------------------------------------------------
        $userModel = new UserModel();
        
        $admin = new User([
            'email'    => self::ADMIN_EMAIL,
            'username' => self::ADMIN_USERNAME,
            'password' => self::ADMIN_PASSWORD,
        ]);
        $admin->activate(); // Penting: supaya bisa login
        $userModel->withGroup('admin')->save($admin);

        // -----------------------------------------------------------------
        // 4. Seed akun user testing
        // -----------------------------------------------------------------
        $user = new User([
            'email'    => self::USER_EMAIL,
            'username' => self::USER_USERNAME,
            'password' => self::USER_PASSWORD,
        ]);
        $user->activate();
        $userModel->withGroup('user')->save($user);

        // -----------------------------------------------------------------
        // 5. Seed buku testing
        // -----------------------------------------------------------------
        foreach (self::BUKU_DATA as $buku) {
            // Upsert array
            $data = [
                'id'         => $buku['id'],
                'judul'      => $buku['judul'],
                'pengarang'  => $buku['pengarang'],
                'penerbit'   => $buku['penerbit'],
                'isbn'       => $buku['isbn'],
                'genre'      => $buku['genre'],
                'harga'      => $buku['harga'],
                'stok'       => $buku['stok'],
                'deskripsi'  => $buku['deskripsi'],
                'gambar'     => $buku['gambar'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            // Check if exists
            $existing = $this->db->table('buku')->where('id', $buku['id'])->get()->getRow();
            if ($existing) {
                // Update
                unset($data['created_at']); // keep original created_at
                $this->db->table('buku')->where('id', $buku['id'])->update($data);
            } else {
                // Insert
                $this->db->table('buku')->insert($data);
            }
        }

        $this->db->enableForeignKeyChecks();

        echo "TestSeeder selesai:\n";
        echo "  Admin : " . self::ADMIN_EMAIL . " / " . self::ADMIN_PASSWORD . "\n";
        echo "  User  : " . self::USER_EMAIL  . " / " . self::USER_PASSWORD  . "\n";
        echo "  Buku  : " . count(self::BUKU_DATA) . " buku testing di-seed.\n";
    }

    // -------------------------------------------------------------------------
    // Hapus data testing lama (cart, order_items, orders, buku, users)
    // -------------------------------------------------------------------------
    private function cleanTestData(): void
    {
        $emails = [self::ADMIN_EMAIL, self::USER_EMAIL];

        // Dapatkan user ID
        $users = $this->db->table('users')->whereIn('email', $emails)->get()->getResultArray();
        $userIds = array_column($users, 'id');

        if (!empty($userIds)) {
            // Hapus cart_items
            $carts = $this->db->table('carts')->whereIn('user_id', $userIds)->get()->getResultArray();
            $cartIds = array_column($carts, 'id');
            if (!empty($cartIds)) {
                $this->db->table('cart_items')->whereIn('cart_id', $cartIds)->delete();
            }
            // Hapus carts
            $this->db->table('carts')->whereIn('user_id', $userIds)->delete();

            // Hapus order_items
            $orders = $this->db->table('orders')->whereIn('user_id', $userIds)->get()->getResultArray();
            $orderIds = array_column($orders, 'id');
            if (!empty($orderIds)) {
                $this->db->table('order_items')->whereIn('order_id', $orderIds)->delete();
            }
            // Hapus orders
            $this->db->table('orders')->whereIn('user_id', $userIds)->delete();

            // Hapus auth_groups_users
            $this->db->table('auth_groups_users')->whereIn('user_id', $userIds)->delete();

            // Hapus user
            $this->db->table('users')->whereIn('id', $userIds)->delete();
        }

        // Hapus buku testing (id >= 9000)
        $this->db->table('buku')->where('id >=', 9000)->delete();

        // Hapus akun registrasi test (domain @testing.test / @playwright.test)
        $this->db->table('users')->like('email', '@testing.test', 'before')->delete();
        $this->db->table('users')->like('email', '@playwright.test', 'before')->delete();
    }

    // -------------------------------------------------------------------------
    // Pastikan group admin (id=3) dan user (id=4) ada
    // -------------------------------------------------------------------------
    private function ensureGroups(): void
    {
        $this->db->table('auth_groups')->ignore(true)->insert([
            'id' => 3, 'name' => 'admin', 'description' => 'Administrator'
        ]);
        $this->db->table('auth_groups')->ignore(true)->insert([
            'id' => 4, 'name' => 'user', 'description' => 'User Biasa'
        ]);
    }
}
