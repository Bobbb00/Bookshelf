<?php
$hash = password_hash('AdminTest@123', PASSWORD_BCRYPT);
echo "Hash: " . $hash . PHP_EOL;
echo "Verify: " . (password_verify('AdminTest@123', $hash) ? 'OK' : 'FAIL') . PHP_EOL;

// Coba juga hash yang ada di TestSeeder (jika sudah di-seed)
// Cek langsung via query
$dsn = 'pgsql:host=' . getenv('database.default.hostname') . ';port=5432;dbname=' . getenv('database.default.database');
