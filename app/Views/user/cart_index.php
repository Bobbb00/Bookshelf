<?php
/**
 * @var array $items
 * @var float|int $total
 */
?>
<?= $this->extend('template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Keranjang Belanja</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Keranjang Belanja</li>
        </ol>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="card shadow-sm border-0 rounded-3 text-center py-5">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-5x text-secondary opacity-30 mb-3"></i>
                    <h4 class="fw-light text-dark">Keranjang belanja Anda masih kosong.</h4>
                    <p class="text-muted small">Silakan kunjungi katalog untuk memilih buku favorit Anda.</p>
                    <a href="<?= url_to('dashboard') ?>" class="btn btn-primary rounded-pill mt-3 px-4">
                        <i class="fas fa-book-open me-1"></i> Mulai Belanja
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <!-- Tabel Item -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-shopping-cart text-primary me-2"></i>Daftar Buku Pilihan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4" width="10%">Cover</th>
                                            <th>Detail Buku</th>
                                            <th width="15%">Harga</th>
                                            <th width="20%">Jumlah</th>
                                            <th width="15%">Subtotal</th>
                                            <th class="text-center" width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td class="px-4">
                                                    <?php if (!empty($item['gambar']) && $item['gambar'] != 'default.png'): ?>
                                                        <img src="<?= base_url('img/buku/' . $item['gambar']) ?>" alt="Cover" class="img-thumbnail" style="max-height: 85px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light d-flex align-items-center justify-content-center border img-thumbnail" style="height: 80px; width: 60px;">
                                                            <i class="fas fa-book text-secondary opacity-40"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('/buku/detail/' . $item['buku_id']) ?>" class="text-decoration-none text-dark fw-bold hover-primary">
                                                        <?= esc($item['judul']) ?>
                                                    </a>
                                                    <span class="text-muted d-block small mt-1">
                                                        <i class="fas fa-pen-nib me-1"></i> <?= esc($item['pengarang']) ?>
                                                    </span>
                                                    <?php if ($item['qty'] > $item['stok']): ?>
                                                        <span class="badge bg-danger mt-1">Stok tidak cukup! (Maksimal: <?= $item['stok'] ?>)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                                <td>
                                                    <!-- Form Update Jumlah -->
                                                    <form action="<?= base_url('/cart/update') ?>" method="POST" class="d-flex align-items-center">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                                        <input type="number" name="qty" class="form-control form-control-sm text-center me-2" 
                                                               value="<?= $item['qty'] ?>" min="1" max="<?= $item['stok'] ?>" style="width: 70px;">
                                                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Perbarui Jumlah">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="fw-semibold text-success">
                                                    Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('/cart/delete/' . $item['id']) ?>" 
                                                       class="btn btn-outline-danger btn-sm rounded-circle" 
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini dari keranjang?')"
                                                       title="Hapus Buku">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Total & Pembayaran -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-semibold text-dark">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Buku</span>
                                <span class="fw-semibold"><?= count($items) ?> macam</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fs-5 text-dark fw-bold">Total Belanja</span>
                                <span class="fs-4 text-success fw-bold">Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>

                            <?php 
                            $canCheckout = true;
                            foreach ($items as $item) {
                                if ($item['qty'] > $item['stok']) {
                                    $canCheckout = false;
                                    break;
                                }
                            }
                            ?>

                            <?php if ($canCheckout): ?>
                                <a href="<?= base_url('/checkout') ?>" class="btn btn-success w-100 py-3 rounded-pill fw-bold fs-5 shadow-sm hover-grow">
                                    Lanjutkan ke Checkout <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            <?php else: ?>
                                <div class="alert alert-danger py-2 small mb-0">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat buku yang jumlahnya melebihi stok yang tersedia. Tolong kurangi jumlahnya terlebih dahulu agar dapat checkout.
                                </div>
                                <button class="btn btn-secondary w-100 py-3 rounded-pill fw-bold fs-5 mt-2" disabled>
                                    Lanjutkan ke Checkout
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?= $this->endSection() ?>
