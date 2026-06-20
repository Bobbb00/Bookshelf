<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\BukuModel;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $bukuModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->bukuModel = new BukuModel();
    }

    /**
     * Show admin dashboard of all orders.
     */
    public function index()
    {
        $search = $this->request->getVar('q');
        $status = $this->request->getVar('status');
        $date = $this->request->getVar('date');

        $orders = $this->orderModel->getOrdersForAdmin($search, $status, $date);

        return view('admin/order_monitor', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'date'   => $date
        ]);
    }

    /**
     * Show order detail for admin.
     */
    public function detail(int $id)
    {
        $order = $this->orderModel->select('orders.*, users.username, users.email')
                                  ->join('users', 'users.id = orders.user_id')
                                  ->where('orders.id', $id)
                                  ->first();

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pesanan tidak ditemukan.');
        }

        $items = $this->orderItemModel->getItemsWithBuku($id);

        return view('admin/order_detail', [
            'order' => $order,
            'items' => $items
        ]);
    }

    /**
     * Update order status.
     */
    public function updateStatus(int $id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            session()->setFlashdata('error', 'Pesanan tidak ditemukan.');
            return redirect()->to('/admin/orders');
        }

        $oldStatus = $order['status_pesanan'];
        $newStatus = $this->request->getPost('status_pesanan');

        $validStatuses = ['Menunggu Konfirmasi', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
        if (!in_array($newStatus, $validStatuses)) {
            session()->setFlashdata('error', 'Status pesanan tidak valid.');
            return redirect()->to('/admin/orders/detail/' . $id);
        }

        if ($oldStatus === $newStatus) {
            return redirect()->to('/admin/orders/detail/' . $id);
        }

        // Database transaction to handle stock adjustments on status changes
        $db = \Config\Database::connect();
        $db->transStart();

        $items = $this->orderItemModel->where('order_id', $id)->findAll();

        // If order becomes Cancelled (Dibatalkan) and it wasn't already: restore stock
        if ($newStatus === 'Dibatalkan' && $oldStatus !== 'Dibatalkan') {
            foreach ($items as $item) {
                $buku = $this->bukuModel->find($item['buku_id']);
                if ($buku) {
                    $newStock = $buku['stok'] + $item['qty'];
                    $this->bukuModel->update($item['buku_id'], ['stok' => $newStock]);
                }
            }
        }
        // If order changes FROM Cancelled (Dibatalkan) to a processing status: deduct stock again
        elseif ($oldStatus === 'Dibatalkan' && $newStatus !== 'Dibatalkan') {
            foreach ($items as $item) {
                $buku = $this->bukuModel->find($item['buku_id']);
                if (!$buku || $buku['stok'] < $item['qty']) {
                    $db->transRollback();
                    session()->setFlashdata('error', 'Gagal merubah status. Stok buku "' . ($buku['judul'] ?? 'Unknown') . '" tidak mencukupi untuk melakukan pemrosesan ulang.');
                    return redirect()->to('/admin/orders/detail/' . $id);
                }
                $newStock = $buku['stok'] - $item['qty'];
                $this->bukuModel->update($item['buku_id'], ['stok' => $newStock]);
            }
        }

        $this->orderModel->update($id, ['status_pesanan' => $newStatus]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan sistem saat memperbarui status pesanan.');
        } else {
            session()->setFlashdata('success', 'Status pesanan ' . $order['nomor_pesanan'] . ' berhasil diubah menjadi "' . $newStatus . '"!');
        }

        return redirect()->to('/admin/orders/detail/' . $id);
    }
}
