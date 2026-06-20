<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['order_id', 'buku_id', 'qty', 'harga'];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all items for a specific order along with book details.
     */
    public function getItemsWithBuku(int $orderId): array
    {
        return $this->select('order_items.*, buku.judul, buku.gambar, buku.pengarang, buku.genre')
                    ->join('buku', 'buku.id = order_items.buku_id')
                    ->where('order_items.order_id', $orderId)
                    ->findAll();
    }
}
