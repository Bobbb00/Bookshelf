<?php
/**
 * @var array $order
 * @var array $items
 */
?>
<?= $this->extend('template/admin/admin_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Detail Pesanan Customer</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/admin/orders') ?>">Riwayat Pembelian Customer</a></li>
            <li class="breadcrumb-item active">Detail Pesanan</li>
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

        <div class="row g-4">
            <!-- Order Meta & Items (Left) -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-book-reader text-primary me-2"></i>Daftar Buku yang Dibeli</h5>
                        <span class="text-muted small">No: <strong class="text-dark"><?= esc($order['nomor_pesanan']) ?></strong></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4" width="10%">Cover</th>
                                        <th>Detail Buku</th>
                                        <th width="15%">Harga Beli</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td class="px-4">
                                                <?php if (!empty($item['gambar']) && $item['gambar'] != 'default.png'): ?>
                                                    <img src="<?= base_url('img/buku/' . $item['gambar']) ?>" alt="Cover" class="img-thumbnail" style="max-height: 70px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center border img-thumbnail" style="height: 65px; width: 50px;">
                                                        <i class="fas fa-book text-secondary opacity-40"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark d-block"><?= esc($item['judul']) ?></span>
                                                <span class="text-muted small"><i class="fas fa-pen-nib me-1"></i> <?= esc($item['pengarang']) ?> | <?= esc($item['genre']) ?></span>
                                            </td>
                                            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                            <td><?= $item['qty'] ?> pcs</td>
                                            <td class="fw-semibold text-success">
                                                Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery, Customer details & Status Action (Right) -->
            <div class="col-lg-4">
                <!-- Status Control Card -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold text-dark">Kelola Status Pesanan</h5>
                    </div>
                    <div class="card-body py-4">
                        <div class="text-center mb-3">
                            <?php
                            $status = $order['status_pesanan'];
                            $badgeClass = 'bg-secondary';
                            $iconClass = 'fa-info-circle text-secondary';
                            if ($status === 'Menunggu Konfirmasi') {
                                $badgeClass = 'bg-warning text-dark';
                                $iconClass = 'fa-clock text-warning';
                            } elseif ($status === 'Diproses') {
                                $badgeClass = 'bg-info';
                                $iconClass = 'fa-cogs text-info';
                            } elseif ($status === 'Dikirim') {
                                $badgeClass = 'bg-primary';
                                $iconClass = 'fa-shipping-fast text-primary';
                            } elseif ($status === 'Selesai') {
                                $badgeClass = 'bg-success';
                                $iconClass = 'fa-check-circle text-success';
                            } elseif ($status === 'Dibatalkan') {
                                $badgeClass = 'bg-danger';
                                $iconClass = 'fa-times-circle text-danger';
                            }
                            ?>
                            <i class="fas <?= $iconClass ?> fa-3x mb-3"></i>
                            <h4 class="fw-bold text-dark mb-1"><?= esc($status) ?></h4>
                            <span class="text-muted small d-block mb-3">Total: <strong class="text-success">Rp <?= number_format($order['total_pembayaran'], 0, ',', '.') ?></strong></span>
                        </div>
                        
                        <form action="<?= base_url('/admin/orders/update-status/' . $order['id']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="status_pesanan" class="form-label small fw-bold text-muted">Ubah Status</label>
                                <select name="status_pesanan" id="status_pesanan" class="form-select">
                                    <option value="Menunggu Konfirmasi" <?= ($status === 'Menunggu Konfirmasi') ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                                    <option value="Diproses" <?= ($status === 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                                    <option value="Dikirim" <?= ($status === 'Dikirim') ? 'selected' : '' ?>>Dikirim</option>
                                    <option value="Selesai" <?= ($status === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                                    <option value="Dibatalkan" <?= ($status === 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-pill">
                                <i class="fas fa-save me-1"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold text-dark">Detail Akun Customer</h5>
                    </div>
                    <div class="card-body p-3 small">
                        <div class="mb-2">
                            <span class="text-muted d-block">Username</span>
                            <span class="fw-bold text-dark"><?= esc($order['username']) ?></span>
                        </div>
                        <div class="mb-0">
                            <span class="text-muted d-block">Alamat Email</span>
                            <span class="fw-semibold text-dark"><?= esc($order['email']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Details Card -->
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold text-dark">Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="text-muted small d-block">Nama Penerima</span>
                            <span class="fw-bold text-dark"><?= esc($order['nama_penerima']) ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Nomor HP</span>
                            <span class="fw-semibold text-dark"><?= esc($order['no_hp']) ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small d-block">Alamat Pengiriman</span>
                            <span class="text-dark small d-block leading-relaxed" style="white-space: pre-line;"><?= esc($order['alamat']) ?></span>
                        </div>
                        <?php if (!empty($order['catatan'])): ?>
                            <div class="p-3 bg-light rounded-3 mt-3">
                                <span class="text-muted small d-block mb-1">Catatan Pembeli:</span>
                                <span class="small text-dark leading-relaxed">"<?= esc($order['catatan']) ?>"</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="<?= base_url('/admin/orders') ?>" class="btn btn-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Pemantauan
            </a>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
