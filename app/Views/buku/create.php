<?= $this->extend('template/index') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tambah Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/buku') ?>">Data Buku</a></li>
            <li class="breadcrumb-item active">Tambah Buku</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-plus me-1"></i>
                Form Tambah Buku
            </div>
            <div class="card-body">
                <form action="<?= base_url('/buku/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold">
                            Judul Buku <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control <?= ($validation->hasError('judul')) ? 'is-invalid' : '' ?>"
                            id="judul" name="judul"
                            value="<?= old('judul') ?>"
                            placeholder="Masukkan judul buku">
                        <div class="invalid-feedback"><?= $validation->getError('judul') ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pengarang" class="form-label fw-semibold">
                                Pengarang <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= ($validation->hasError('pengarang')) ? 'is-invalid' : '' ?>"
                                id="pengarang" name="pengarang"
                                value="<?= old('pengarang') ?>"
                                placeholder="Nama pengarang">
                            <div class="invalid-feedback"><?= $validation->getError('pengarang') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="penerbit" class="form-label fw-semibold">
                                Penerbit <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= ($validation->hasError('penerbit')) ? 'is-invalid' : '' ?>"
                                id="penerbit" name="penerbit"
                                value="<?= old('penerbit') ?>"
                                placeholder="Nama penerbit">
                            <div class="invalid-feedback"><?= $validation->getError('penerbit') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label fw-semibold">ISBN</label>
                            <input type="text"
                                class="form-control"
                                id="isbn" name="isbn"
                                value="<?= old('isbn') ?>"
                                placeholder="Contoh: 978-602-123-456-7 (opsional)">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label fw-semibold">
                                Genre <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control <?= ($validation->hasError('genre')) ? 'is-invalid' : '' ?>"
                                id="genre" name="genre"
                                value="<?= old('genre') ?>"
                                placeholder="Contoh: Fiksi, Non-fiksi, Sains, dll.">
                            <div class="invalid-feedback"><?= $validation->getError('genre') ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label fw-semibold">
                                Harga (Rp) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?= ($validation->hasError('harga')) ? 'is-invalid' : '' ?>"
                                id="harga" name="harga"
                                value="<?= old('harga') ?>"
                                min="0" placeholder="0">
                            <div class="invalid-feedback"><?= $validation->getError('harga') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">
                                Stok <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control <?= ($validation->hasError('stok')) ? 'is-invalid' : '' ?>"
                                id="stok" name="stok"
                                value="<?= old('stok') ?>"
                                min="0" placeholder="0">
                            <div class="invalid-feedback"><?= $validation->getError('stok') ?></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                            placeholder="Sinopsis atau deskripsi singkat buku (opsional)"><?= old('deskripsi') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
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
