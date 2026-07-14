<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCartAndOrderTables extends Migration
{
    public function up()
    {
        // Add fields to users table (PostgreSQL: 'after' not supported, fields added at end)
        $this->forge->addColumn('users', [
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ]
        ]);

        // Carts Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('carts');

        // Cart Items Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'cart_id' => [
                'type' => 'INT',
            ],
            'buku_id' => [
                'type' => 'INT',
            ],
            'qty' => [
                'type'    => 'INT',
                'default' => 1,
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
        $this->forge->addForeignKey('cart_id', 'carts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buku_id', 'buku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cart_items');

        // Orders Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nomor_pesanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'tanggal_pembelian' => [
                'type' => 'TIMESTAMP',
            ],
            'nama_penerima' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'total_pembayaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status_pesanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Menunggu Konfirmasi',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');

        // Order Items Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
            ],
            'buku_id' => [
                'type' => 'INT',
            ],
            'qty' => [
                'type' => 'INT',
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
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
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buku_id', 'buku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
        $this->forge->dropTable('orders');
        $this->forge->dropTable('cart_items');
        $this->forge->dropTable('carts');

        // Remove fields from users table
        $this->forge->dropColumn('users', ['alamat', 'no_hp']);
    }
}
