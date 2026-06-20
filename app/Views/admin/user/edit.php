<?php
/**
 * @var \stdClass $user
 * @var \CodeIgniter\Validation\Validation $validation
 * @var array $roles
 * @var int|string $currentRole
 */
?>
<?= $this->extend('template/admin/admin_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Edit User</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('/user') ?>">Kelola User</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Form Edit User: <?= esc((string) $user->username) ?>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/user/update/' . $user->id) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : '' ?>"
                            id="username" name="username"
                            value="<?= old('username', esc((string) $user->username)) ?>"
                            placeholder="Username (tanpa spasi)">
                        <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email"
                            class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>"
                            id="email" name="email"
                            value="<?= old('email', esc($user->email)) ?>"
                            placeholder="Alamat email aktif">
                        <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                    </div>

                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Biarkan kolom password <strong>kosong</strong> jika Anda tidak ingin mereset password user ini.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <input type="password"
                                class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                id="password" name="password"
                                placeholder="Minimal 8 karakter (Opsional)">
                            <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                            
                            <!-- Strength Indicator -->
                            <div id="password-strength-container" class="mt-2 d-none">
                                <div class="progress" style="height: 6px;">
                                    <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="small text-muted">Kekuatan: <span id="strength-text" class="fw-bold">-</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pass_confirm" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password"
                                class="form-control <?= ($validation->hasError('pass_confirm')) ? 'is-invalid' : '' ?>"
                                id="pass_confirm" name="pass_confirm"
                                placeholder="Ulangi password (Opsional)">
                            <div class="invalid-feedback"><?= $validation->getError('pass_confirm') ?></div>
                        </div>
                    </div>

                    <div class="text-muted small mb-4">
                        <i class="fas fa-shield-alt text-success me-1"></i> Gunakan password minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, angka, dan simbol agar akun lebih aman.
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label fw-semibold">Role / Hak Akses <span class="text-danger">*</span></label>
                        <select class="form-select <?= ($validation->hasError('role')) ? 'is-invalid' : '' ?>" id="role" name="role" <?= ($user->id == user_id()) ? 'disabled' : '' ?>>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->id ?>" <?= (old('role', $currentRole) == $role->id) ? 'selected' : '' ?>>
                                    <?= ucfirst($role->name) ?> - <?= $role->description ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                        <?php if ($user->id == user_id()): ?>
                            <input type="hidden" name="role" value="<?= $currentRole ?>">
                            <div class="form-text text-danger">Anda tidak bisa mengubah hak akses akun Anda sendiri.</div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Update User
                        </button>
                        <a href="<?= base_url('/user') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthContainer = document.getElementById('password-strength-container');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        if (password.length === 0) {
            strengthContainer.classList.add('d-none');
            return;
        }

        strengthContainer.classList.remove('d-none');
        
        let score = 0;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        let pct = (score / 5) * 100;
        strengthBar.style.width = pct + '%';

        if (score <= 2) {
            strengthBar.className = 'progress-bar bg-danger';
            strengthText.innerText = 'Password lemah';
            strengthText.className = 'fw-bold text-danger';
        } else if (score <= 4) {
            strengthBar.className = 'progress-bar bg-warning';
            strengthText.innerText = 'Password sedang';
            strengthText.className = 'fw-bold text-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            strengthText.innerText = 'Password kuat';
            strengthText.className = 'fw-bold text-success';
        }
    });
});
</script>
<?= $this->endSection() ?>
