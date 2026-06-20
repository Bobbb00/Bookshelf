<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomFieldsToUsers extends Migration
{
    public function up()
    {
        // Tambah kolom fullname dan user_img yang dibutuhkan aplikasi
        $this->forge->addColumn('users', [
            'fullname' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'user_img' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => 'default.svg',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['fullname', 'user_img']);
    }
}
