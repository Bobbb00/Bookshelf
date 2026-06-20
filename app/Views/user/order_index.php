<?php
/**
 * @var array $orders
 */
?>
<?= $this->extend('template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Riwayat Pembelian</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Riwayat Pembelian</li>
        </ol>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($orders)): ?>
            <div class="card shadow-sm border-0 rounded-3 text-center py-5">
                <div class="card-body">
                    <i class="fas fa-history fa-5x text-secondary opacity-30 mb-3"></i>
                    <h4 class="fw-light text-dark">Anda belum pernah melakukan pembelian.</h4>
                    <p class="text-muted small">Ayo cari dan temukan buku favorit Anda sekarang!</p>
                    <a href="<?= url_to('dashboard') ?>" class="btn btn-primary rounded-pill mt-3 px-4">
                        <i class="fas fa-book-open me-1"></i> Cari Buku
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-list text-primary me-2"></i>Daftar Transaksi Pembelian</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4" width="20%">Nomor Pesanan</th>
                                    <th width="20%">Tanggal Pembelian</th>
                                    <th width="20%">Total Pembayaran</th>
                                    <th width="20%">Status</th>
                                    <th class="text-center" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="px-4 fw-bold text-dark"><?= esc($order['nomor_pesanan']) ?></td>
                                        <td><?= date('d M Y, H:i', strtotime($order['tanggal_pembelian'])) ?> WIB</td>
                                        <td class="fw-semibold text-success">
                                            Rp <?= number_format($order['total_pembayaran'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $order['status_pesanan'];
                                            $badgeClass = 'bg-secondary';
                                            if ($status === 'Menunggu Konfirmasi') $badgeClass = 'bg-warning text-dark';
                                            elseif ($status === 'Diproses') $badgeClass = 'bg-info';
                                            elseif ($status === 'Dikirim') $badgeClass = 'bg-primary';
                                            elseif ($status === 'Selesai') $badgeClass = 'bg-success';
                                            elseif ($status === 'Dibatalkan') $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill small">
                                                <?= esc($status) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/orders/detail/' . $order['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?= $this->endSection() ?>
