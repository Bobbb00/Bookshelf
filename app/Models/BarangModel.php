<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nama_barang',
        'kategori',
        'harga',
        'stok',
        'deskripsi',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_barang' => 'required|min_length[3]|max_length[100]',
        'kategori'    => 'required|max_length[50]',
        'harga'       => 'required|numeric',
        'stok'        => 'required|integer',
    ];

    protected $validationMessages = [
        'nama_barang' => [
            'required'   => 'Nama barang wajib diisi.',
            'min_length' => 'Nama barang minimal 3 karakter.',
        ],
        'kategori' => [
            'required' => 'Kategori wajib diisi.',
        ],
        'harga' => [
            'required' => 'Harga wajib diisi.',
            'numeric'  => 'Harga harus berupa angka.',
        ],
        'stok' => [
            'required' => 'Stok wajib diisi.',
            'integer'  => 'Stok harus berupa bilangan bulat.',
        ],
    ];

    protected $skipValidation = false;
}
