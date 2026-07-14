/**
 * testData.ts
 * Data fixture untuk Playwright tests — Bookshelf CI4
 *
 * Semua nilai di sini sinkron dengan database aktual.
 */

// ─── Akun Testing ──────────────────────────────────────────────────────────

export const ADMIN = {
  email:    'test.admin@bookshelf.test',
  username: 'test_admin',
  password: 'AdminTest@123',
};

export const USER = {
  email:    'test.user@bookshelf.test',
  username: 'test_user',
  password: 'UserTest@123',
};

// ─── Buku Testing (sesuai database) ────────────────────────────────────────

export const BUKU = {
  alpha: {
    id:        9001,
    judul:     '[TEST] Buku Playwright Alpha',
    pengarang: 'Test Author',
    penerbit:  'Test Publisher',
    genre:     'Pengujian',
    harga:     50_000,
    stok:      10,
  },
  beta: {
    id:        9002,
    judul:     '[TEST] Buku Playwright Beta',
    pengarang: 'Test Author',
    penerbit:  'Test Publisher',
    genre:     'Pengujian',
    harga:     75_000,
    stok:      6,
  },
  habis: {
    id:        9003,
    judul:     '[TEST] Buku Stok Habis',
    pengarang: 'Test Author',
    penerbit:  'Test Publisher',
    genre:     'Pengujian',
    harga:     30_000,
    stok:      0,
  },
};

// ─── Buku baru untuk skenario Admin tambah buku ──────────────────────────

export const NEW_BUKU = {
  judul:     '[TEST] Buku Baru Playwright',
  pengarang: 'Pengarang Testing',
  penerbit:  'Penerbit Testing',
  isbn:      '999-888-777',
  genre:     'Pengujian',
  harga:     '100000',
  stok:      '15',
  deskripsi: 'Deskripsi buku testing yang baru ditambahkan.',
};

// ─── Data untuk skenario edit buku ──────────────────────────────────────

export const EDIT_BUKU = {
  harga: '120000',
  stok:  '20',
};

// ─── Data untuk registrasi baru (timestamp agar unik tiap run) ──────────

const ts = Date.now();

export const NEW_USER = {
  username: `pengguna${ts}`,
  email:    `user_${ts}@testing.test`,
  password: 'RahasiaNegara@2026',
};

// ─── Data checkout ──────────────────────────────────────────────────────

export const CHECKOUT_DATA = {
  nama_penerima: 'Penerima Testing',
  no_hp:         '081234567890',
  alamat:        'Jl. Testing No. 1, Kota Test, Provinsi Test, 12345',
  catatan:       'Ini catatan test otomatis Playwright',
};
