<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\BukuModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\AppUserModel;

class CheckoutController extends BaseController
{
    protected $cartModel;
    protected $cartItemModel;
    protected $bukuModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->bukuModel = new BukuModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    /**
     * Show checkout summary and shipping form.
     */
    public function index()
    {
        $userId = user_id();
        $cart = $this->cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            session()->setFlashdata('error', 'Keranjang belanja kosong.');
            return redirect()->to('/cart');
        }

        $items = $this->cartItemModel->getItemsWithBuku($cart['id']);
        if (empty($items)) {
            session()->setFlashdata('error', 'Keranjang belanja kosong.');
            return redirect()->to('/cart');
        }

        // Re-validate stock
        foreach ($items as $item) {
            if ($item['qty'] > $item['stok']) {
                session()->setFlashdata('error', 'Buku "' . $item['judul'] . '" melebihi stok yang tersedia. Tolong sesuaikan jumlah.');
                return redirect()->to('/cart');
            }
        }

        // Fetch user default data to pre-populate shipping information
        $userModel = new AppUserModel();
        $user = $userModel->find($userId);

        $total = 0;
        foreach ($items as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('user/checkout_index', [
            'items'      => $items,
            'total'      => $total,
            'user'       => $user,
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Process checkout submission.
     */
    public function process()
    {
        $userId = user_id();
        $cart = $this->cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            session()->setFlashdata('error', 'Keranjang belanja kosong.');
            return redirect()->to('/cart');
        }

        $items = $this->cartItemModel->getItemsWithBuku($cart['id']);
        if (empty($items)) {
            session()->setFlashdata('error', 'Keranjang belanja kosong.');
            return redirect()->to('/cart');
        }

        // Validate form input
        $rules = [
            'nama_penerima' => 'required|min_length[3]|max_length[100]',
            'no_hp'         => 'required|min_length[8]|max_length[20]',
            'alamat'        => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            $userModel = new AppUserModel();
            $user = $userModel->find($userId);
            
            $total = 0;
            foreach ($items as $item) {
                $total += $item['harga'] * $item['qty'];
            }

            return view('user/checkout_index', [
                'items'      => $items,
                'total'      => $total,
                'user'       => $user,
                'validation' => $this->validator
            ]);
        }

        // Database Transaction to guarantee integrity
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Re-validate and update stock
        $total = 0;
        foreach ($items as $item) {
            $buku = $this->bukuModel->find($item['buku_id']);
            if (!$buku || $item['qty'] > $buku['stok']) {
                $db->transRollback();
                session()->setFlashdata('error', 'Buku "' . ($buku['judul'] ?? 'Unknown') . '" melebihi stok yang tersedia. Transaksi dibatalkan.');
                return redirect()->to('/cart');
            }
            $total += $item['harga'] * $item['qty'];
        }

        // Generate Order Number: ORD-YYYYMMDD-XXXXXX
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        // 2. Insert Order
        $orderId = $this->orderModel->insert([
            'user_id'           => $userId,
            'nomor_pesanan'     => $orderNumber,
            'tanggal_pembelian' => date('Y-m-d H:i:s'),
            'nama_penerima'     => $this->request->getPost('nama_penerima'),
            'alamat'            => $this->request->getPost('alamat'),
            'no_hp'             => $this->request->getPost('no_hp'),
            'catatan'           => $this->request->getPost('catatan'),
            'total_pembayaran'  => $total,
            'status_pesanan'    => 'Menunggu Konfirmasi'
        ]);

        // 3. Insert Order Items & Decrement Stock
        foreach ($items as $item) {
            $this->orderItemModel->insert([
                'order_id' => $orderId,
                'buku_id'  => $item['buku_id'],
                'qty'      => $item['qty'],
                'harga'    => $item['harga']
            ]);

            // Decrement Stock
            $buku = $this->bukuModel->find($item['buku_id']);
            $newStock = $buku['stok'] - $item['qty'];
            $this->bukuModel->update($item['buku_id'], ['stok' => $newStock]);
        }

        // 4. Clear Cart Items
        $this->cartItemModel->where('cart_id', $cart['id'])->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan sistem saat memproses checkout.');
            return redirect()->to('/cart');
        }

        session()->setFlashdata('success', 'Checkout berhasil! Pesanan Anda dengan nomor ' . $orderNumber . ' sedang menunggu konfirmasi admin.');
        return redirect()->to('/orders');
    }
}
