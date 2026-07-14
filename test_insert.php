<?php

// Script untuk membersihkan data lama dan menanam data baru yang benar dengan Query Builder
require 'public/index.php'; // Atau jalankan ini via spark

$db = \Config\Database::connect();
$builder = $db->table('users');

$adminEmail = 'test.admin@bookshelf.test';
$adminUser = 'test_admin';
$adminPass = 'AdminTest@123';
$adminHash = password_hash($adminPass, PASSWORD_BCRYPT);

$builder->where('email', $adminEmail)->delete();
$builder->insert([
    'email' => $adminEmail,
    'username' => $adminUser,
    'password_hash' => $adminHash,
    'active' => 1,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]);

echo "Admin inserted.\n";

// Cek apakah benar:
$row = $builder->where('email', $adminEmail)->get()->getRowArray();
print_r($row);
