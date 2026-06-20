<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table            = 'cart_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['cart_id', 'buku_id', 'qty'];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all items in a cart with details of the books.
     */
    public function getItemsWithBuku(int $cartId): array
    {
        return $this->select('cart_items.*, buku.judul, buku.harga, buku.gambar, buku.stok, buku.pengarang, buku.genre')
                    ->join('buku', 'buku.id = cart_items.buku_id')
                    ->where('cart_items.cart_id', $cartId)
                    ->findAll();
    }
}
