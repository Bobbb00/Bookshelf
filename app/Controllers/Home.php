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

        $search = $this->request->getVar('q');
        $category = $this->request->getVar('category');

        $query = $bukuModel->where('stok >', 0);

        if (!empty($search)) {
            $query->groupStart()
                  ->like('judul', $search)
                  ->orLike('pengarang', $search)
                  ->orLike('penerbit', $search)
                  ->groupEnd();
        }

        if (!empty($category)) {
            $query->where('genre', $category);
        }

        // Fetch distinct genres for filter list
        $genres = $bukuModel->select('genre')->distinct()->findAll();
        $genresList = array_column($genres, 'genre');

        $data = [
            'buku'     => $query->orderBy('id', 'DESC')->findAll(),
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

