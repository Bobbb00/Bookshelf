<?php
/** @var array $buku */
$buku      = $buku ?? [];
$errors    = session('errors') ?? [];
$validator = \Config\Services::validation();
foreach ($errors as $field => $message) {
    $validator->setError($field, $message);
}
// Saat redirect()->back(), $buku kosong — ambil ID dari URL
$bukuId = !empty($buku['id']) ? $buku['id'] : service('request')->getUri()->getSegment(3);
?>
<?= $this->extend('template/index') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/buku') ?>">Data Buku</a></li>
            <li class="breadcrumb-item active">Edit Buku</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Form Edit Buku
            </div>
            <div class="card-body">
                <form action="<?= base_url('/buku/update/' . $bukuId) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold">
                            Judul Buku <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control <?= ($validator->hasError('judul')) ? 'is-invalid' : '' ?>"
                            id="judul" name="judul"
                            value="<?= old('judul', esc($buku['judul'])) ?>"
                            placeholder="Masukkan judul buku">
                        <div class="invalid-feedback"><?= $validator->getError('judul') ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pengarang" class="form-label fw-semibold">
                                Pengarang <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= ($validator->hasError('pengarang')) ? 'is-invalid' : '' ?>"
                                id="pengarang" name="pengarang"
                                value="<?= old('pengarang', esc($buku['pengarang'])) ?>"
                                placeholder="Nama pengarang">
                            <div class="invalid-feedback"><?= $validator->getError('pengarang') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="penerbit" class="form-label fw-semibold">
                                Penerbit <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= ($validator->hasError('penerbit')) ? 'is-invalid' : '' ?>"
                                id="penerbit" name="penerbit"
                                value="<?= old('penerbit', esc($buku['penerbit'])) ?>"
                                placeholder="Nama penerbit">
                            <div class="invalid-feedback"><?= $validator->getError('penerbit') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label fw-semibold">ISBN</label>
                            <input type="text"
                                class="form-control"
                                id="isbn" name="isbn"
                                value="<?= old('isbn', esc($buku['isbn'])) ?>"
                                placeholder="Contoh: 978-602-123-456-7 (opsional)">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label fw-semibold">
                                Genre <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= ($validator->hasError('genre')) ? 'is-invalid' : '' ?>"
                                id="genre" name="genre"
                                value="<?= old('genre', esc($buku['genre'])) ?>"
                                placeholder="Contoh: Fiksi, Non-fiksi, Sains, dll.">
                            <div class="invalid-feedback"><?= $validator->getError('genre') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label fw-semibold">
                                Harga (Rp) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?= ($validator->hasError('harga')) ? 'is-invalid' : '' ?>"
                                id="harga" name="harga"
                                value="<?= old('harga', $buku['harga']) ?>"
                                min="0" placeholder="0">
                            <div class="invalid-feedback"><?= $validator->getError('harga') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">
                                Stok <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?= ($validator->hasError('stok')) ? 'is-invalid' : '' ?>"
                                id="stok" name="stok"
                                value="<?= old('stok', $buku['stok']) ?>"
                                min="0" placeholder="0">
                            <div class="invalid-feedback"><?= $validator->getError('stok') ?></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                            placeholder="Sinopsis atau deskripsi singkat buku (opsional)"><?= old('deskripsi', esc($buku['deskripsi'])) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="<?= base_url('/buku') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
