<?= $this->extend('template/index') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tambah Barang</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/barang') ?>">Data Barang</a></li>
            <li class="breadcrumb-item active">Tambah Barang</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-plus me-1"></i>
                Form Tambah Barang
            </div>
            <div class="card-body">
                <form action="<?= base_url('/barang/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nama_barang" class="form-label fw-semibold">
                            Nama Barang <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control <?= ($validation->hasError('nama_barang')) ? 'is-invalid' : '' ?>"
                            id="nama_barang"
                            name="nama_barang"
                            value="<?= old('nama_barang') ?>"
                            placeholder="Masukkan nama barang"
                        >
                        <div class="invalid-feedback">
                            <?= $validation->getError('nama_barang') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-semibold">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control <?= ($validation->hasError('kategori')) ? 'is-invalid' : '' ?>"
                            id="kategori"
                            name="kategori"
                            value="<?= old('kategori') ?>"
                            placeholder="Contoh: Elektronik, Pakaian, dll."
                        >
                        <div class="invalid-feedback">
                            <?= $validation->getError('kategori') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label fw-semibold">
                                Harga (Rp) <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                class="form-control <?= ($validation->hasError('harga')) ? 'is-invalid' : '' ?>"
                                id="harga"
                                name="harga"
                                value="<?= old('harga') ?>"
                                min="0"
                                placeholder="0"
                            >
                            <div class="invalid-feedback">
                                <?= $validation->getError('harga') ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">
                                Stok <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                class="form-control <?= ($validation->hasError('stok')) ? 'is-invalid' : '' ?>"
                                id="stok"
                                name="stok"
                                value="<?= old('stok') ?>"
                                min="0"
                                placeholder="0"
                            >
                            <div class="invalid-feedback">
                                <?= $validation->getError('stok') ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea
                            class="form-control"
                            id="deskripsi"
                            name="deskripsi"
                            rows="3"
                            placeholder="Deskripsi singkat tentang barang (opsional)"
                        ><?= old('deskripsi') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="<?= base_url('/barang') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
