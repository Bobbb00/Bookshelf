<?php
/**
 * @var array $buku
 * @var array $genres
 * @var string|null $search
 * @var string|null $category
 */
?>
<?= $this->extend('template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Katalog Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Selamat datang di Bookshelf, silakan temukan buku favorit Anda!</li>
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

        <!-- Filter & Search Card -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body p-3">
                <form action="<?= base_url('/dashboard') ?>" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari judul buku, pengarang, atau penerbit..." 
                                   value="<?= esc($search ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-tags"></i>
                            </span>
                            <select name="category" class="form-select border-start-0 ps-0">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($genres as $genreName): ?>
                                    <option value="<?= esc($genreName) ?>" <?= ($category === $genreName) ? 'selected' : '' ?>>
                                        <?= esc($genreName) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <?php if (!empty($search) || !empty($category)): ?>
                            <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-secondary rounded-pill">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <?php if (empty($buku)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-store-slash fa-4x mb-3 text-secondary opacity-50"></i>
                    <h4 class="fw-light">Mohon maaf, belum ada buku yang sesuai dengan pencarian Anda.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($buku as $item): ?>
                    <?= view('components/book_card', ['item' => $item]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>
<?= $this->endSection() ?>