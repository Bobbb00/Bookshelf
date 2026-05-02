<div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid <?= $colorHex ?? '#4e73df' ?> !important;">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div class="text-uppercase text-muted small fw-bold mb-1"><?= esc($title) ?></div>
                <div class="fs-2 fw-bold text-dark"><?= esc($value) ?></div>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:56px;height:56px;background:<?= $bgLight ?? '#eef0fb' ?>;">
                <i class="<?= esc($icon) ?> fa-lg" style="color:<?= $colorHex ?? '#4e73df' ?>;"></i>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 pt-0">
            <?php if (!empty($link)): ?>
                <a href="<?= base_url($link) ?>" class="small text-decoration-none" style="color:<?= $colorHex ?? '#4e73df' ?>;">
                    <?= esc($footerText) ?> <i class="fas fa-arrow-right ms-1"></i>
                </a>
            <?php else: ?>
                <span class="small text-muted"><?= esc($footerText) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
