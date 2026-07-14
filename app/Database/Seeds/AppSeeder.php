<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        $this->db->disableForeignKeyChecks();

        // Helper: upsert agnostik database (bisa PostgreSQL atau MySQL)
        $upsert = function(string $table, array $rows) {
            foreach ($rows as $row) {
                $this->db->table($table)->replace($row);
            }
        };

        // 1. Seed auth_groups
        $groups = [
            ['id' => 3, 'name' => 'admin', 'description' => 'Administrator'],
            ['id' => 4, 'name' => 'user', 'description' => 'User Biasa'],
        ];
        $upsert('auth_groups', $groups);

        // 2. Seed auth_permissions
        $permissions = [
            ['id' => 1, 'name' => 'manage-users', 'description' => 'Manage All Users'],
            ['id' => 2, 'name' => 'manage-profile', 'description' => 'Manage User\'s Profile'],
        ];
        $upsert('auth_permissions', $permissions);

        // 3. Seed users
        $users = [
            ['id' => 2, 'email' => 'aliiueo1@gmail.com', 'username' => 'aliiueo1', 'password_hash' => '$2y$10$A6R3fGlQN6DUcyMTJ7/juODEy3zwyU6XZ3jJeEuQjawjh1/sABCxy', 'active' => 1, 'created_at' => '2026-04-13 15:34:31', 'updated_at' => '2026-04-26 05:23:04', 'deleted_at' => '2026-04-26 05:23:04', 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 3, 'email' => 'aliazmi@gmail.com', 'username' => 'Syafa Ali Azmi', 'password_hash' => '$2y$10$Ch8x7bKz5se7wP6n6rB6s.A2ibZx3.0W7GfIQ.yGNXivssWZxw8Aq', 'active' => 1, 'created_at' => '2026-04-25 07:20:45', 'updated_at' => '2026-04-25 07:20:45', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 4, 'email' => 'user@gmail.com', 'username' => 'user', 'password_hash' => '$2y$10$NbUkTE15bUHV3lsVsrfEX.DM1PunAEEPhriD/FpFl5BI1jtxyIuk.', 'active' => 1, 'created_at' => '2026-04-26 04:45:45', 'updated_at' => '2026-04-26 04:45:45', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 6, 'email' => 'admin@gmail.com', 'username' => 'admin', 'password_hash' => '$2y$10$EvaKvQLMuh9guYMYg6ce9O01DpTX/p6DKOktWgFeiVRZbC/4LzcTe', 'active' => 1, 'created_at' => '2026-04-26 05:28:24', 'updated_at' => '2026-07-13 15:11:51', 'deleted_at' => null, 'alamat' => "Villa Mas Garden\r\nTOKO DEWI", 'no_hp' => '089501105701', 'fullname' => 'Syafa Ali Azmi', 'user_img' => 'default.svg'],
            ['id' => 24, 'email' => 'test.user@bookshelf.test', 'username' => 'testuser21', 'password_hash' => '$2y$10$b/8Edv/TSqDlVlaNzHDpu.Ima0cB07xbVcM3I.Bmd5jn14QLlIoeS', 'active' => 1, 'created_at' => '2026-07-13 14:57:19', 'updated_at' => '2026-07-13 14:57:19', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 25, 'email' => 'test.admin@bookshelf.test', 'username' => 'testadmin21', 'password_hash' => '$2y$10$f7wMaAjQh5D20q0IUHej2ez4bkzI5exui0cz0MkQKK7eT85GuDFgK', 'active' => 1, 'created_at' => '2026-07-13 14:59:19', 'updated_at' => '2026-07-13 14:59:19', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 26, 'email' => 'user_1783954796983@testing.test', 'username' => 'pengguna1783954796983', 'password_hash' => '$2y$10$Q56Ncw/r3GgNiDiF.apyrOC1bT11jDbABUInJw/zpCa.v3NEqy3z6', 'active' => 1, 'created_at' => '2026-07-13 14:59:59', 'updated_at' => '2026-07-13 14:59:59', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 27, 'email' => 'user_1783954929266@testing.test', 'username' => 'pengguna1783954929266', 'password_hash' => '$2y$10$b20nEt/4QbI.pWYpE2ROE.JGlXB/NQWvLNxAr7vrJyVpxuRhJzkI6', 'active' => 1, 'created_at' => '2026-07-13 15:02:11', 'updated_at' => '2026-07-13 15:02:11', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg'],
            ['id' => 28, 'email' => 'juli23@gmail.com', 'username' => 'JuliTest', 'password_hash' => '$2y$10$erfnWygZJew40Sm6qYJ4LOA22huYT7TYzmqwmwobQaUhdB7FyhtEy', 'active' => 1, 'created_at' => '2026-07-13 15:06:01', 'updated_at' => '2026-07-13 15:08:36', 'deleted_at' => null, 'alamat' => "Villa Mas Garden\r\nTOKO DEWI", 'no_hp' => '089501105701', 'fullname' => 'Juli Aprianto', 'user_img' => 'default.svg'],
            ['id' => 29, 'email' => 'user_1783955649323@testing.test', 'username' => 'pengguna1783955649323', 'password_hash' => '$2y$10$XuVO5Mt4jry5mRBB4Z1go.BclwHmhjT6qE9XWEo8LqfZZZNwwr3Qm', 'active' => 1, 'created_at' => '2026-07-13 15:14:11', 'updated_at' => '2026-07-13 15:14:11', 'deleted_at' => null, 'alamat' => null, 'no_hp' => null, 'fullname' => null, 'user_img' => 'default.svg']
        ];
        $upsert('users', $users);

        // 4. Seed auth_groups_users
        $groupsUsers = [
            ['group_id' => 3, 'user_id' => 3],
            ['group_id' => 3, 'user_id' => 6],
            ['group_id' => 3, 'user_id' => 25],
            ['group_id' => 4, 'user_id' => 4],
            ['group_id' => 4, 'user_id' => 10], // this doesn't exist in users, but keeping it to match pbf.sql
            ['group_id' => 4, 'user_id' => 17], // this doesn't exist in users
            ['group_id' => 4, 'user_id' => 24],
            ['group_id' => 4, 'user_id' => 26],
            ['group_id' => 4, 'user_id' => 27],
            ['group_id' => 4, 'user_id' => 28],
            ['group_id' => 4, 'user_id' => 29]
        ];
        // Filter out groupsUsers that don't have matching user_id to prevent foreign key errors
        $userIds = array_column($users, 'id');
        $groupsUsersFiltered = array_filter($groupsUsers, function($g) use ($userIds) {
            return in_array($g['user_id'], $userIds);
        });
        $upsert('auth_groups_users', $groupsUsersFiltered);

        // 5. Seed buku
        $buku = [
            ['id' => 1, 'judul' => 'Seporsi Mie Ayam Sebelum Mati', 'pengarang' => 'Aliazmi', 'penerbit' => 'Gramedia', 'isbn' => '', 'genre' => 'Non-Fiksi', 'harga' => 200000.00, 'stok' => 2, 'deskripsi' => 'ini adalha cerita tentang Seporsi Mie Ayam Sebelum Mati', 'gambar' => '1777180624_77d626118d903e17e054.jpg', 'created_at' => '2026-04-25 00:58:58', 'updated_at' => '2026-07-13 08:10:11'],
            ['id' => 9001, 'judul' => '[TEST] Buku Playwright Alpha', 'pengarang' => 'Test Author', 'penerbit' => 'Test Publisher', 'isbn' => '000-000-001', 'genre' => 'Pengujian', 'harga' => 50000.00, 'stok' => 0, 'deskripsi' => 'Buku testing untuk skenario Playwright.', 'gambar' => 'default.png', 'created_at' => '2026-07-12 07:40:31', 'updated_at' => '2026-07-13 08:15:07'],
            ['id' => 9002, 'judul' => '[TEST] Buku Playwright Beta', 'pengarang' => 'Test Author', 'penerbit' => 'Test Publisher', 'isbn' => '000-000-002', 'genre' => 'Pengujian', 'harga' => 75000.00, 'stok' => 8, 'deskripsi' => 'Buku testing kedua untuk keranjang.', 'gambar' => 'default.png', 'created_at' => '2026-07-12 07:40:31', 'updated_at' => '2026-07-13 08:15:07'],
            ['id' => 9003, 'judul' => '[TEST] Buku Stok Habis', 'pengarang' => 'Test Author', 'penerbit' => 'Test Publisher', 'isbn' => '000-000-003', 'genre' => 'Pengujian', 'harga' => 30000.00, 'stok' => 0, 'deskripsi' => 'Buku testing dengan stok habis.', 'gambar' => 'default.png', 'created_at' => '2026-07-12 07:40:31', 'updated_at' => '2026-07-13 08:01:57'],
            ['id' => 9010, 'judul' => 'Filosofi Teras', 'pengarang' => 'Hendri Marimping', 'penerbit' => 'Gramedia', 'isbn' => '000-000-007', 'genre' => 'Fiksi', 'harga' => 150000.00, 'stok' => 10, 'deskripsi' => '', 'gambar' => '1783955458_0e2ea27a28108c688d1a.png', 'created_at' => '2026-07-13 08:10:58', 'updated_at' => '2026-07-13 08:10:58']
        ];
        $upsert('buku', $buku);

        // Re-enable foreign key checks
        $this->db->enableForeignKeyChecks();
    }
}
