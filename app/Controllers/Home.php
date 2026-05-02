<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Home extends BaseController
{
    public function index()
    {
        if (service('authentication')->check()) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function register(): string
    {
        return view('auth/register');
    }

    public function dashboard()
    {
        // Redirect Admin ke Admin Dashboard
        if (in_groups('admin')) {
            return redirect()->to('/admin/dashboard');
        }

        $bukuModel = new BukuModel();

        // Tampilkan buku yang stoknya masih ada (> 0)
        $data = [
            'buku' => $bukuModel->where('stok >', 0)->orderBy('id', 'DESC')->findAll(),
        ];

        return view('user/index', $data);
    }
}

