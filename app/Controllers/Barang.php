<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    /**
     * Tampilkan daftar semua barang
     */
    public function index(): string
    {
        $data = [
            'title'  => 'Data Barang',
            'barang' => $this->barangModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('barang/index', $data);
    }

    /**
     * Tampilkan form tambah barang
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Tambah Barang',
            'validation' => \Config\Services::validation(),
        ];

        return view('barang/create', $data);
    }

    /**
     * Simpan data barang baru ke database
     */
    public function store()
    {
        $rules = [
            'nama_barang' => 'required|min_length[3]|max_length[100]',
            'kategori'    => 'required|max_length[50]',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return view('barang/create', [
                'title'      => 'Tambah Barang',
                'validation' => $this->validator,
            ]);
        }

        $this->barangModel->insert([
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ]);

        session()->setFlashdata('success', 'Barang berhasil ditambahkan!');
        return redirect()->to('/barang');
    }

    /**
     * Tampilkan form edit barang
     */
    public function edit(int $id): string
    {
        $barang = $this->barangModel->find($id);

        if (!$barang) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Barang',
            'barang'     => $barang,
            'validation' => \Config\Services::validation(),
        ];

        return view('barang/edit', $data);
    }

    /**
     * Update data barang di database
     */
    public function update(int $id)
    {
        $rules = [
            'nama_barang' => 'required|min_length[3]|max_length[100]',
            'kategori'    => 'required|max_length[50]',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return view('barang/edit', [
                'title'      => 'Edit Barang',
                'barang'     => $this->barangModel->find($id),
                'validation' => $this->validator,
            ]);
        }

        $this->barangModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ]);

        session()->setFlashdata('success', 'Barang berhasil diperbarui!');
        return redirect()->to('/barang');
    }

    /**
     * Hapus data barang dari database
     */
    public function delete(int $id)
    {
        $this->barangModel->delete($id);
        session()->setFlashdata('success', 'Barang berhasil dihapus!');
        return redirect()->to('/barang');
    }
}
