<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBukuTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'pengarang' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'penerbit' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'isbn' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'default'    => null,
            ],
            'genre' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'stok' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'default.png',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('buku');
    }

    public function down()
    {
        $this->forge->dropTable('buku');
    }
}
