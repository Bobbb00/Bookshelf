<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id',
        'nomor_pesanan',
        'tanggal_pembelian',
        'nama_penerima',
        'alamat',
        'no_hp',
        'catatan',
        'total_pembayaran',
        'status_pesanan'
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all orders with buyer (user) information, useful for admin monitoring.
     */
    public function getOrdersForAdmin(?string $search = null, ?string $status = null, ?string $date = null): array
    {
        $builder = $this->select('orders.*, users.username, users.email')
                        ->join('users', 'users.id = orders.user_id');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('users.username', $search)
                    ->orLike('orders.nama_penerima', $search)
                    ->orLike('orders.nomor_pesanan', $search)
                    ->groupEnd();
        }

        if (!empty($status)) {
            $builder->where('orders.status_pesanan', $status);
        }

        if (!empty($date)) {
            // MySQL/MariaDB:
            $builder->where('DATE(orders.tanggal_pembelian)', $date);
        }

        return $builder->orderBy('orders.tanggal_pembelian', 'DESC')->findAll();
    }
}
