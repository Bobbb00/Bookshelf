<?php
/**
 * @var array $buku
 */
?>
<?= $this->extend('template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Detail Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Detail Buku</li>
        </ol>

        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success m-3 mb-0" role="alert">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger m-3 mb-0" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <div class="row g-0">
                <!-- Cover Image -->
                <div class="col-md-4 bg-light border-end d-flex align-items-center justify-content-center" style="min-height: 400px;">
                    <?php if (!empty($buku['gambar']) && $buku['gambar'] != 'default.png'): ?>
                        <img src="<?= base_url('img/buku/' . $buku['gambar']) ?>" class="img-fluid w-100 h-100" style="object-fit: contain; max-height: 500px;" alt="<?= esc($buku['judul']) ?>">
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-6x text-secondary opacity-50 mb-3"></i>
                            <p class="text-muted fw-semibold">Tidak ada cover gambar</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Detail Metadata & Purchase Form -->
                <div class="col-md-8">
                    <div class="card-body p-4">
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-2"><?= esc($buku['genre']) ?></span>
                        <h2 class="fw-bold text-dark mb-1"><?= esc($buku['judul']) ?></h2>
                        <p class="text-muted mb-4 fs-5"><i class="fas fa-pen-nib me-1"></i> Ditulis oleh <strong class="text-dark"><?= esc($buku['pengarang']) ?></strong></p>

                        <!-- Price & Stock Section -->
                        <div class="p-3 rounded-3 bg-light d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <span class="text-muted small d-block">Harga Spesial</span>
                                <span class="fs-3 fw-bold text-success">Rp <?= number_format($buku['harga'], 0, ',', '.') ?></span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted small d-block">Ketersediaan</span>
                                <span class="badge <?= $buku['stok'] > 0 ? 'bg-success' : 'bg-danger' ?> px-3 py-2">
                                    <?= $buku['stok'] > 0 ? 'Stok: ' . $buku['stok'] . ' pcs' : 'Habis' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Book Specs Table -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2">Informasi Buku</h5>
                            <div class="row g-2 pt-2">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block small">Penerbit</span>
                                    <span class="fw-semibold text-dark"><?= esc($buku['penerbit']) ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block small">ISBN</span>
                                    <span class="fw-semibold text-dark"><?= !empty($buku['isbn']) ? esc($buku['isbn']) : '-' ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Form -->
                        <?php if ($buku['stok'] > 0): ?>
                            <form action="<?= base_url('/cart/add') ?>" method="POST" class="mb-4">
                                <?= csrf_field() ?>
                                <input type="hidden" name="buku_id" value="<?= $buku['id'] ?>">
                                
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-auto">
                                        <label for="qty" class="col-form-label fw-semibold">Jumlah Beli:</label>
                                    </div>
                                    <div class="col-auto" style="width: 120px;">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decQty()">-</button>
                                            <input type="number" id="qty" name="qty" class="form-control text-center" value="1" min="1" max="<?= $buku['stok'] ?>" readonly>
                                            <button type="button" class="btn btn-outline-secondary" onclick="incQty()">+</button>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <span class="text-muted small">Maksimal <?= $buku['stok'] ?> pcs</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-2">
                                    <button type="submit" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-semibold">
                                        <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                                        <i class="fas fa-shopping-bag me-2"></i> Beli Sekarang
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-danger mb-4">
                                <i class="fas fa-exclamation-triangle me-1"></i> Maah, stok buku ini sedang habis sehingga tidak dapat dibeli.
                            </div>
                        <?php endif; ?>

                        <!-- Description Section -->
                        <div>
                            <h5 class="fw-bold text-dark border-bottom pb-2">Sinopsis / Deskripsi</h5>
                            <p class="text-muted leading-relaxed" style="white-space: pre-line;">
                                <?= !empty($buku['deskripsi']) ? esc($buku['deskripsi']) : 'Belum ada deskripsi untuk buku ini.' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="<?= url_to('dashboard') ?>" class="btn btn-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Katalog
            </a>
        </div>
    </div>
</main>

<script>
function decQty() {
    const qtyInput = document.getElementById('qty');
    let val = parseInt(qtyInput.value);
    if (val > 1) {
        qtyInput.value = val - 1;
    }
}
function incQty() {
    const qtyInput = document.getElementById('qty');
    const maxVal = parseInt(qtyInput.getAttribute('max'));
    let val = parseInt(qtyInput.value);
    if (val < maxVal) {
        qtyInput.value = val + 1;
    }
}
</script>
<?= $this->endSection() ?>
