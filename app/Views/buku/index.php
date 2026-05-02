<?= $this->extend('template/index') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Buku</li>
        </ol>

        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-book me-1"></i>
                    Daftar Buku
                </div>
                <a href="<?= base_url('/buku/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Buku
                </a>
            </div>
            <div class="card-body">
                <table id="tabelBuku" class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="4%">No</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Genre</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($buku)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-book-open fa-2x mb-2 d-block"></i>
                                    Belum ada data buku.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($buku as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong><?= esc($item['judul']) ?></strong>
                                        <?php if (!empty($item['isbn'])): ?>
                                            <br><small class="text-muted">ISBN: <?= esc($item['isbn']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item['pengarang']) ?></td>
                                    <td><?= esc($item['penerbit']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc($item['genre']) ?></span>
                                    </td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge <?= $item['stok'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $item['stok'] ?> pcs
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('/buku/edit/' . $item['id']) ?>"
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?= base_url('/buku/delete/' . $item['id']) ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                            <i class="fas fa-trash"></i> Hapus
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
</main>
<?= $this->endSection() ?>
