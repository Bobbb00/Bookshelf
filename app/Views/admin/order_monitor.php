<?php
/**
 * @var array $orders
 * @var string|null $search
 * @var string|null $status
 * @var string|null $date
 */
?>
<?= $this->extend('template/admin/admin_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Pemantauan Riwayat Pembelian</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Riwayat Pembelian Customer</li>
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

        <!-- Filters Section -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body p-3">
                <form action="<?= base_url('/admin/orders') ?>" method="GET" class="row g-3 align-items-end">
                    <!-- Search Input -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Cari Customer / Nomor Pesanan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0" 
                                   placeholder="Username, Penerima, No Order..." value="<?= esc($search ?? '') ?>">
                        </div>
                    </div>
                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Menunggu Konfirmasi" <?= ($status === 'Menunggu Konfirmasi') ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                            <option value="Diproses" <?= ($status === 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                            <option value="Dikirim" <?= ($status === 'Dikirim') ? 'selected' : '' ?>>Dikirim</option>
                            <option value="Selesai" <?= ($status === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                            <option value="Dibatalkan" <?= ($status === 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <!-- Date Filter -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Tanggal Pembelian</label>
                        <input type="date" name="date" class="form-control" value="<?= esc($date ?? '') ?>">
                    </div>
                    <!-- Action Buttons -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            Cari
                        </button>
                        <?php if (!empty($search) || !empty($status) || !empty($date)): ?>
                            <a href="<?= base_url('/admin/orders') ?>" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-shopping-bag text-primary me-2"></i>Daftar Pesanan Customer</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4" width="15%">No. Pesanan</th>
                                <th width="20%">Customer</th>
                                <th width="15%">Penerima</th>
                                <th width="15%">Tanggal</th>
                                <th width="15%">Total</th>
                                <th width="10%">Status</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-2 d-block opacity-40"></i>
                                        Tidak ada data pesanan yang cocok dengan kriteria filter.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="px-4 fw-bold text-dark"><?= esc($order['nomor_pesanan']) ?></td>
                                        <td>
                                            <strong class="text-dark"><?= esc($order['username']) ?></strong>
                                            <span class="text-muted d-block small"><?= esc($order['email']) ?></span>
                                        </td>
                                        <td><?= esc($order['nama_penerima']) ?></td>
                                        <td class="small"><?= date('d M Y, H:i', strtotime($order['tanggal_pembelian'])) ?></td>
                                        <td class="fw-semibold text-success">
                                            Rp <?= number_format($order['total_pembayaran'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <?php
                                            $st = $order['status_pesanan'];
                                            $badgeClass = 'bg-secondary';
                                            if ($st === 'Menunggu Konfirmasi') $badgeClass = 'bg-warning text-dark';
                                            elseif ($st === 'Diproses') $badgeClass = 'bg-info';
                                            elseif ($st === 'Dikirim') $badgeClass = 'bg-primary';
                                            elseif ($st === 'Selesai') $badgeClass = 'bg-success';
                                            elseif ($st === 'Dibatalkan') $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 small">
                                                <?= esc($st) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/admin/orders/detail/' . $order['id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                                                <i class="fas fa-edit me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
