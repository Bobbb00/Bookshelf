<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;

    public function __construct()
    {
        $this->bukuModel = new BukuModel();
    }

    /**
     * Tampilkan daftar semua buku
     */
    public function index(): string
    {
        $data = [
            'title' => 'Data Buku',
            'buku'  => $this->bukuModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('admin/buku/index', $data);
    }

    /**
     * Tampilkan form tambah buku
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Tambah Buku',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/buku/create', $data);
    }

    /**
     * Simpan data buku baru ke database
     */
    public function store()
    {
        $rules = [
            'judul'     => 'required|min_length[3]|max_length[150]',
            'pengarang' => 'required|max_length[100]',
            'penerbit'  => 'required|max_length[100]',
            'genre'     => 'required|max_length[50]',
            'harga'     => 'required|numeric',
            'stok'      => 'required|integer',
            'gambar'    => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (! $this->validate($rules)) {
            return view('admin/buku/create', [
                'title'      => 'Tambah Buku',
                'validation' => $this->validator,
            ]);
        }

        // Handle File Upload
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->getError() == 4) {
            $namaGambar = 'default.png';
        } else {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/buku', $namaGambar);
        }

        $this->bukuModel->insert([
            'judul'     => $this->request->getPost('judul'),
            'pengarang' => $this->request->getPost('pengarang'),
            'penerbit'  => $this->request->getPost('penerbit'),
            'isbn'      => $this->request->getPost('isbn'),
            'genre'     => $this->request->getPost('genre'),
            'harga'     => $this->request->getPost('harga'),
            'stok'      => $this->request->getPost('stok'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ]);

        session()->setFlashdata('success', 'Buku berhasil ditambahkan!');
        return redirect()->to('/buku');
    }

    /**
     * Tampilkan form edit buku
     */
    public function edit(int $id): string
    {
        $buku = $this->bukuModel->find($id);

        if (!$buku) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Buku',
            'buku'       => $buku,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/buku/edit', $data);
    }

    /**
     * Update data buku di database
     */
    public function update(int $id)
    {
        $rules = [
            'judul'     => 'required|min_length[3]|max_length[150]',
            'pengarang' => 'required|max_length[100]',
            'penerbit'  => 'required|max_length[100]',
            'genre'     => 'required|max_length[50]',
            'harga'     => 'required|numeric',
            'stok'      => 'required|integer',
            'gambar'    => 'max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (! $this->validate($rules)) {
            return view('admin/buku/edit', [
                'title'      => 'Edit Buku',
                'buku'       => $this->bukuModel->find($id),
                'validation' => $this->validator,
            ]);
        }

        // Handle File Upload
        $fileGambar = $this->request->getFile('gambar');
        $bukuLama = $this->bukuModel->find($id);

        if ($fileGambar && $fileGambar->getError() == 4) {
            $namaGambar = $bukuLama['gambar'];
        } else {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('img/buku', $namaGambar);

            // Hapus file lama jika bukan default
            if (!empty($bukuLama['gambar']) && $bukuLama['gambar'] != 'default.png' && file_exists(FCPATH . 'img/buku/' . $bukuLama['gambar'])) {
                unlink(FCPATH . 'img/buku/' . $bukuLama['gambar']);
            }
        }

        $this->bukuModel->update($id, [
            'judul'     => $this->request->getPost('judul'),
            'pengarang' => $this->request->getPost('pengarang'),
            'penerbit'  => $this->request->getPost('penerbit'),
            'isbn'      => $this->request->getPost('isbn'),
            'genre'     => $this->request->getPost('genre'),
            'harga'     => $this->request->getPost('harga'),
            'stok'      => $this->request->getPost('stok'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ]);

        session()->setFlashdata('success', 'Buku berhasil diperbarui!');
        return redirect()->to('/buku');
    }

    /**
     * Hapus data buku dari database
     */
    public function delete(int $id)
    {
        $buku = $this->bukuModel->find($id);

        if ($buku) {
            // Hapus file gambar jika bukan default
            if (!empty($buku['gambar']) && $buku['gambar'] != 'default.png' && file_exists(FCPATH . 'img/buku/' . $buku['gambar'])) {
                unlink(FCPATH . 'img/buku/' . $buku['gambar']);
            }
            $this->bukuModel->delete($id);
        }

        session()->setFlashdata('success', 'Buku berhasil dihapus!');
        return redirect()->to('/buku');
    }
}
