<?= $this->extend('template/admin/admin_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- Stat Cards -->
        <div class="row g-4 mb-4">
            <?= view('components/stat_card', [
                'title'      => 'Total Judul Buku',
                'value'      => $totalBuku,
                'icon'       => 'fas fa-book',
                'colorHex'   => '#4e73df',
                'bgLight'    => '#eef0fb',
                'link'       => '/buku',
                'footerText' => 'Lihat semua buku'
            ]) ?>

            <?= view('components/stat_card', [
                'title'      => 'Total Stok',
                'value'      => number_format($totalStok),
                'icon'       => 'fas fa-layer-group',
                'colorHex'   => '#1cc88a',
                'bgLight'    => '#e8faf4',
                'link'       => '',
                'footerText' => 'Total eksemplar tersedia'
            ]) ?>

            <?= view('components/stat_card', [
                'title'      => 'Nilai Inventori',
                'value'      => 'Rp ' . number_format($totalNilai, 0, ',', '.'),
                'icon'       => 'fas fa-coins',
                'colorHex'   => '#f6c23e',
                'bgLight'    => '#fef9ec',
                'link'       => '',
                'footerText' => 'Harga × stok semua buku'
            ]) ?>

            <?= view('components/stat_card', [
                'title'      => 'Stok Habis',
                'value'      => $stokHabis,
                'icon'       => 'fas fa-exclamation-triangle',
                'colorHex'   => '#e74a3b',
                'bgLight'    => '#fdf0ef',
                'link'       => '',
                'footerText' => 'Judul dengan stok = 0'
            ]) ?>
        </div>

        <!-- Tabel Buku Terbaru -->
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-clock me-1"></i>
                    Buku Terbaru Ditambahkan
                </div>
                <a href="<?= base_url('/buku/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Buku
                </a>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="8%" class="text-center">Cover</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Genre</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bukuTerbaru)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-book-open fa-2x mb-2 d-block"></i>
                                    Belum ada buku. <a href="<?= base_url('/buku/create') ?>">Tambah sekarang</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bukuTerbaru as $item): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if (!empty($item['gambar']) && $item['gambar'] != 'default.png'): ?>
                                            <img src="<?= base_url('img/buku/' . $item['gambar']) ?>" alt="Cover" class="img-thumbnail" style="max-height: 60px;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center img-thumbnail mx-auto" style="height: 60px; width: 45px;">
                                                <i class="fas fa-image text-secondary opacity-50"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= esc($item['judul']) ?></strong></td>
                                    <td><?= esc($item['pengarang']) ?></td>
                                    <td><span class="badge bg-secondary"><?= esc($item['genre']) ?></span></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge <?= $item['stok'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $item['stok'] ?> pcs
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/buku/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('/buku/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin hapus buku ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($bukuTerbaru)): ?>
            <div class="card-footer text-end">
                <a href="<?= base_url('/buku') ?>" class="btn btn-outline-primary btn-sm">
                    Lihat Semua Buku <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>
</main>
<?= $this->endSection() ?>
