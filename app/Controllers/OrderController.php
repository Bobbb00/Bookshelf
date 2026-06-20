<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    /**
     * Show the logged-in customer's order history.
     */
    public function index()
    {
        $userId = user_id();
        $orders = $this->orderModel->where('user_id', $userId)
                                   ->orderBy('tanggal_pembelian', 'DESC')
                                   ->findAll();

        return view('user/order_index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show details of a specific order for the customer.
     */
    public function detail(int $id)
    {
        $userId = user_id();
        $order = $this->orderModel->find($id);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pesanan tidak ditemukan.');
        }

        // Security / Access Control Check: ensure this order belongs to the logged-in user
        if ($order['user_id'] != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses untuk melihat pesanan ini.');
        }

        $items = $this->orderItemModel->getItemsWithBuku($id);

        return view('user/order_detail', [
            'order' => $order,
            'items' => $items
        ]);
    }
}
