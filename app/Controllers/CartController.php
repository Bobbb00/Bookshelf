<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\BukuModel;

class CartController extends BaseController
{
    protected $cartModel;
    protected $cartItemModel;
    protected $bukuModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->bukuModel = new BukuModel();
    }

    /**
     * Show the shopping cart items.
     */
    public function index()
    {
        $userId = user_id();
        
        // Find or create cart
        $cart = $this->cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            $cartId = $this->cartModel->insert(['user_id' => $userId]);
            $cart = $this->cartModel->find($cartId);
        }

        $items = $this->cartItemModel->getItemsWithBuku($cart['id']);
        
        $total = 0;
        foreach ($items as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('user/cart_index', [
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Add item to the shopping cart.
     */
    public function add()
    {
        $userId = user_id();
        $bukuId = $this->request->getPost('buku_id');
        $qty = intval($this->request->getPost('qty') ?? 1);
        $buyNow = $this->request->getPost('buy_now');

        if ($qty <= 0) {
            $qty = 1;
        }

        // Validate book existence
        $buku = $this->bukuModel->find($bukuId);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->back();
        }

        // Validate stock
        if ($buku['stok'] <= 0) {
            session()->setFlashdata('error', 'Stok buku ini sedang habis.');
            return redirect()->back();
        }

        // Find or create cart
        $cart = $this->cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            $cartId = $this->cartModel->insert(['user_id' => $userId]);
            $cart = $this->cartModel->find($cartId);
        }

        // Check if item already exists in cart
        $existingItem = $this->cartItemModel->where([
            'cart_id' => $cart['id'],
            'buku_id' => $bukuId
        ])->first();

        if ($existingItem) {
            $newQty = $existingItem['qty'] + $qty;
            if ($newQty > $buku['stok']) {
                session()->setFlashdata('error', 'Stok tidak mencukupi. Anda sudah memiliki ' . $existingItem['qty'] . ' pcs di keranjang dan stok maksimal adalah ' . $buku['stok'] . ' pcs.');
                return redirect()->back();
            }
            $this->cartItemModel->update($existingItem['id'], ['qty' => $newQty]);
        } else {
            if ($qty > $buku['stok']) {
                session()->setFlashdata('error', 'Stok tidak mencukupi. Stok maksimal adalah ' . $buku['stok'] . ' pcs.');
                return redirect()->back();
            }
            $this->cartItemModel->insert([
                'cart_id' => $cart['id'],
                'buku_id' => $bukuId,
                'qty'     => $qty
            ]);
        }

        session()->setFlashdata('success', 'Buku "' . $buku['judul'] . '" berhasil ditambahkan ke keranjang belanja!');

        // If Buy Now was clicked, redirect directly to Cart page
        if ($buyNow) {
            return redirect()->to('/cart');
        }

        return redirect()->back();
    }

    /**
     * Update quantity of a cart item.
     */
    public function update()
    {
        $itemId = $this->request->getPost('item_id');
        $qty = intval($this->request->getPost('qty'));

        if ($qty <= 0) {
            $qty = 1;
        }

        $item = $this->cartItemModel->find($itemId);
        if (!$item) {
            session()->setFlashdata('error', 'Item keranjang tidak ditemukan.');
            return redirect()->to('/cart');
        }

        $buku = $this->bukuModel->find($item['buku_id']);
        if ($qty > $buku['stok']) {
            session()->setFlashdata('error', 'Gagal memperbarui jumlah. Stok buku "' . $buku['judul'] . '" tidak mencukupi (Maksimal ' . $buku['stok'] . ' pcs).');
        } else {
            $this->cartItemModel->update($itemId, ['qty' => $qty]);
            session()->setFlashdata('success', 'Jumlah buku berhasil diperbarui!');
        }

        return redirect()->to('/cart');
    }

    /**
     * Remove item from the cart.
     */
    public function delete(int $id)
    {
        $item = $this->cartItemModel->find($id);
        if ($item) {
            $this->cartItemModel->delete($id);
            session()->setFlashdata('success', 'Buku berhasil dihapus dari keranjang.');
        } else {
            session()->setFlashdata('error', 'Item tidak ditemukan.');
        }

        return redirect()->to('/cart');
    }
}
