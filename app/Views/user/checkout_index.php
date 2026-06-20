<?php
/**
 * @var array $items
 * @var float|int $total
 * @var object|array $user
 * @var \CodeIgniter\Validation\Validation $validation
 */
?>
<?= $this->extend('template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Checkout</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/cart') ?>">Keranjang Belanja</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>

        <div class="row g-4">
            <!-- Order Summary (Left) -->
            <div class="col-lg-5 order-lg-last">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-shopping-bag text-primary me-2"></i>Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush mb-4">
                            <?php foreach ($items as $item): ?>
                                <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-start">
                                    <div class="me-auto">
                                        <div class="fw-bold text-dark mb-1"><?= esc($item['judul']) ?></div>
                                        <span class="text-muted small"><?= $item['qty'] ?> pcs × Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                                    </div>
                                    <span class="fw-semibold text-success">
                                        Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="fs-5 text-dark fw-bold">Total Pembayaran</span>
                            <span class="fs-3 text-success fw-bold">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Form (Right) -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-truck text-primary me-2"></i>Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= base_url('/checkout/process') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="nama_penerima" class="form-label fw-semibold">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= ($validation->hasError('nama_penerima')) ? 'is-invalid' : '' ?>" 
                                       id="nama_penerima" name="nama_penerima" 
                                       value="<?= old('nama_penerima', is_array($user) ? ($user['fullname'] ?? '') : ($user->fullname ?? ($user->username ?? ''))) ?>" 
                                       placeholder="Nama Lengkap Penerima">
                                <div class="invalid-feedback"><?= $validation->getError('nama_penerima') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label fw-semibold">Nomor HP Penerima <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= ($validation->hasError('no_hp')) ? 'is-invalid' : '' ?>" 
                                       id="no_hp" name="no_hp" 
                                       value="<?= old('no_hp', is_array($user) ? ($user['no_hp'] ?? '') : ($user->no_hp ?? '')) ?>" 
                                       placeholder="Nomor HP Aktif yang bisa dihubungi">
                                <div class="invalid-feedback"><?= $validation->getError('no_hp') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                                <textarea class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : '' ?>" 
                                          id="alamat" name="alamat" rows="4" 
                                          placeholder="Tulis alamat lengkap (Jalan, Nomor Rumah, RT/RW, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos)"><?= old('alamat', is_array($user) ? ($user['alamat'] ?? '') : ($user->alamat ?? '')) ?></textarea>
                                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
                            </div>

                            <div class="mb-4">
                                <label for="catatan" class="form-label fw-semibold">Catatan Pembelian (Opsional)</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Catatan tambahan untuk kurir atau penjual..."><?= old('catatan') ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-5 py-3 rounded-pill fw-bold fs-5 shadow-sm hover-grow flex-grow-1">
                                    <i class="fas fa-lock me-1"></i> Buat Pesanan Sekarang
                                </button>
                                <a href="<?= base_url('/cart') ?>" class="btn btn-outline-secondary px-4 py-3 rounded-pill fw-semibold d-flex align-items-center">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
