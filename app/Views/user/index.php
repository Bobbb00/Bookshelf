<?= $this->extend('template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Katalog Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Selamat datang di Bookshelf, silakan temukan buku favorit Anda!</li>
        </ol>

        <div class="row g-4 mt-2">
            <?php if (empty($buku)): ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-store-slash fa-4x mb-3 text-light"></i>
                    <h4 class="fw-light">Mohon maaf, belum ada buku yang tersedia saat ini.</h4>
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