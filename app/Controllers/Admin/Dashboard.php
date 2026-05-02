<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $bukuModel = new BukuModel();
        $semuaBuku = $bukuModel->findAll();

        $totalBuku  = count($semuaBuku);
        $totalStok  = array_sum(array_column($semuaBuku, 'stok'));
        $totalNilai = array_sum(array_map(fn($b) => $b['harga'] * $b['stok'], $semuaBuku));
        $stokHabis  = count(array_filter($semuaBuku, fn($b) => $b['stok'] == 0));

        $data = [
            'totalBuku'  => $totalBuku,
            'totalStok'  => $totalStok,
            'totalNilai' => $totalNilai,
            'stokHabis'  => $stokHabis,
            'bukuTerbaru' => $bukuModel->orderBy('id', 'DESC')->limit(5)->findAll(),
        ];

        return view('admin/index', $data);
    }
}
