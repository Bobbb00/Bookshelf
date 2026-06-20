<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        $this->db->disableForeignKeyChecks();

        // Helper: upsert untuk PostgreSQL (ON CONFLICT DO NOTHING)
        $upsert = function(string $table, array $rows) {
            foreach ($rows as $row) {
                $cols = implode('", "', array_keys($row));
                $vals = array_map(function($v) {
                    if ($v === null) return 'NULL';
                    // PostgreSQL escaping: double up single quotes
                    return "'" . str_replace("'", "''", (string)$v) . "'";
                }, array_values($row));
                $valStr = implode(', ', $vals);
                $this->db->query("INSERT INTO \"{$table}\" (\"{$cols}\") VALUES ({$valStr}) ON CONFLICT DO NOTHING");
            }
        };

        // 1. Seed auth_groups
        $groups = [
            [
                'id'          => 3,
                'name'        => 'admin',
                'description' => 'Administrator',
            ],
            [
                'id'          => 4,
                'name'        => 'user',
                'description' => 'User Biasa',
            ],
        ];
        $upsert('auth_groups', $groups);

        // 2. Seed auth_permissions
        $permissions = [
            [
                'id'          => 1,
                'name'        => 'manage-users',
                'description' => 'Manage All Users',
            ],
            [
                'id'          => 2,
                'name'        => 'manage-profile',
                'description' => 'Manage User\'s Profile',
            ],
        ];
        $upsert('auth_permissions', $permissions);

        // 3. Seed users
        $users = [
            [
                'id'            => 2,
                'email'         => 'aliiueo1@gmail.com',
                'username'      => 'aliiueo1',
                'password_hash' => '$2y$10$A6R3fGlQN6DUcyMTJ7/juODEy3zwyU6XZ3jJeEuQjawjh1/sABCxy',
                'active'        => 1,
                'created_at'    => '2026-04-13 15:34:31',
                'updated_at'    => '2026-04-26 05:23:04',
                'deleted_at'    => '2026-04-26 05:23:04',
            ],
            [
                'id'            => 3,
                'email'         => 'aliazmi@gmail.com',
                'username'      => 'Syafa Ali Azmi',
                'password_hash' => '$2y$10$Ch8x7bKz5se7wP6n6rB6s.A2ibZx3.0W7GfIQ.yGNXivssWZxw8Aq',
                'active'        => 1,
                'created_at'    => '2026-04-25 07:20:45',
                'updated_at'    => '2026-04-25 07:20:45',
                'deleted_at'    => null,
            ],
            [
                'id'            => 4,
                'email'         => 'user@gmail.com',
                'username'      => 'user',
                'password_hash' => '$2y$10$NbUkTE15bUHV3lsVsrfEX.DM1PunAEEPhriD/FpFl5BI1jtxyIuk.',
                'active'        => 1,
                'created_at'    => '2026-04-26 04:45:45',
                'updated_at'    => '2026-04-26 04:45:45',
                'deleted_at'    => null,
            ],
            [
                'id'            => 6,
                'email'         => 'admin@gmail.com',
                'username'      => 'admin',
                'password_hash' => '$2y$10$EvaKvQLMuh9guYMYg6ce9O01DpTX/p6DKOktWgFeiVRZbC/4LzcTe',
                'active'        => 1,
                'created_at'    => '2026-04-26 05:28:24',
                'updated_at'    => '2026-04-26 05:28:24',
                'deleted_at'    => null,
            ],
            [
                'id'            => 7,
                'email'         => 'erpan@gmail.com',
                'username'      => 'erpan',
                'password_hash' => '$2y$10$juw3qpnJA4ZijYD0Rmvv8eHHbA6KsTS/GwkkWQguhY.JeAZfdzSzu',
                'active'        => 1,
                'created_at'    => '2026-04-28 06:09:28',
                'updated_at'    => '2026-04-28 06:09:28',
                'deleted_at'    => null,
            ],
        ];
        $upsert('users', $users);

        // 4. Seed auth_groups_users
        $groupsUsers = [
            ['group_id' => 3, 'user_id' => 3],
            ['group_id' => 3, 'user_id' => 6],
            ['group_id' => 4, 'user_id' => 4],
            ['group_id' => 4, 'user_id' => 7],
        ];
        $upsert('auth_groups_users', $groupsUsers);

        // 5. Seed buku
        $buku = [
            [
                'id'         => 1,
                'judul'      => 'Seporsi Mie Ayam Sebelum Mati',
                'pengarang'  => 'Aliazmi',
                'penerbit'   => 'Gramedia',
                'isbn'       => '',
                'genre'      => 'Non-Fiksi',
                'harga'      => 200000.00,
                'stok'       => 2,
                'deskripsi'  => 'ini adalha cerita tentang Seporsi Mie Ayam Sebelum Mati',
                'gambar'     => '1777180624_77d626118d903e17e054.jpg',
                'created_at' => '2026-04-25 07:58:58',
                'updated_at' => '2026-04-28 06:11:04',
            ],
        ];
        $upsert('buku', $buku);

        // Re-enable foreign key checks
        $this->db->enableForeignKeyChecks();
    }
}
