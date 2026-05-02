<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
        <!-- Badge Genre -->
        <div class="position-absolute" style="top: 10px; right: 10px; z-index: 2;">
            <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm">
                <?= esc($item['genre']) ?>
            </span>
        </div>

        <!-- Gambar Buku -->
        <?php if (!empty($item['gambar']) && $item['gambar'] != 'default.png'): ?>
            <img src="<?= base_url('img/buku/' . $item['gambar']) ?>" class="card-img-top" alt="<?= esc($item['judul']) ?>" style="height: 250px; object-fit: cover;">
        <?php else: ?>
            <div class="bg-light d-flex flex-column align-items-center justify-content-center border-bottom" 
                 style="height: 250px; background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);">
                <i class="fas fa-book-open fa-4x text-secondary mb-2 opacity-50"></i>
                <span class="small text-muted fw-semibold"><?= esc($item['penerbit']) ?></span>
            </div>
        <?php endif; ?>

        <!-- Detail Buku -->
        <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold text-dark mb-1">
                <?= esc($item['judul']) ?>
            </h5>
            <p class="card-text text-muted small mb-3">
                <i class="fas fa-pen-nib me-1"></i> <?= esc($item['pengarang']) ?>
            </p>
            
            <div class="mt-auto">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fs-5 fw-bold text-success">
                        Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                    </span>
                    <span class="badge bg-light text-dark border">
                        Stok: <?= $item['stok'] ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Tombol Beli -->
        <div class="card-footer bg-white border-top-0 pb-3 pt-0 px-3">
            <button class="btn btn-outline-primary w-100 rounded-pill fw-semibold" 
                    onclick="alert('Fitur keranjang belanja segera hadir!')">
                <i class="fas fa-shopping-cart me-1"></i> Beli Buku
            </button>
        </div>
    </div>
</div>
