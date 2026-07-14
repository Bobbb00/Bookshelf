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

    public function register()
    {
        if (service('authentication')->check()) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    public function dashboard()
    {
        // Redirect Admin ke Admin Dashboard
        if (in_groups('admin')) {
            return redirect()->to('/admin/dashboard');
        }

        $bukuModel = new BukuModel();

        // 1. Fetch distinct genres for filter list (lakukan SEBELUM kondisi search agar tidak mereset state Model)
        $genres = $bukuModel->select('genre')->distinct()->findAll();
        $genresList = array_column($genres, 'genre');

        // 2. Ambil parameter pencarian
        $search = $this->request->getGet('q');
        $category = $this->request->getGet('category');

        // 3. Susun query untuk menampilkan buku
        // Kita tidak memakai where('stok >', 0) agar semua buku tampil (termasuk yang stok habis)
        // Jika butuh disembunyikan, aktifkan kembali baris ini.
        if (!empty($search)) {
            $bukuModel->groupStart()
                  ->like('judul', $search)
                  ->orLike('pengarang', $search)
                  ->orLike('penerbit', $search)
                  ->groupEnd();
        }

        if (!empty($category)) {
            $bukuModel->where('genre', $category);
        }

        $data = [
            'buku'     => $bukuModel->orderBy('id', 'DESC')->findAll(),
            'genres'   => $genresList,
            'search'   => $search,
            'category' => $category,
        ];

        return view('user/index', $data);
    }

    public function detail(int $id)
    {
        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($id);

        if (!$buku) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku dengan ID ' . $id . ' tidak ditemukan.');
        }

        return view('user/buku_detail', [
            'buku' => $buku
        ]);
    }
}

