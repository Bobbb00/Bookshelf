<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'judul',
        'pengarang',
        'penerbit',
        'isbn',
        'genre',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'judul'     => 'required|min_length[3]|max_length[150]',
        'pengarang' => 'required|max_length[100]',
        'penerbit'  => 'required|max_length[100]',
        'genre'     => 'required|max_length[50]',
        'harga'     => 'required|numeric',
        'stok'      => 'required|integer',
    ];

    protected $validationMessages = [
        'judul' => [
            'required'   => 'Judul buku wajib diisi.',
            'min_length' => 'Judul minimal 3 karakter.',
        ],
        'pengarang' => [
            'required' => 'Nama pengarang wajib diisi.',
        ],
        'penerbit' => [
            'required' => 'Nama penerbit wajib diisi.',
        ],
        'genre' => [
            'required' => 'Genre wajib diisi.',
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
