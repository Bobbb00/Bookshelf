<?php
/**
 * @var object|array $user
 * @var \CodeIgniter\Validation\Validation $validation
 */
?>
<?= $this->extend(in_groups('admin') ? 'template/index' : 'template/user/user_layout') ?>
<?= $this->section('page-content') ?>
<main>
    <div class="container-fluid px-4 pb-5">
        <h1 class="mt-4">Profil Saya</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>

        <div class="row">
            <div class="col-lg-8">
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

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold text-dark"><i class="fas fa-user-edit me-2 text-primary"></i>Edit Informasi Profil</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= base_url('/profile/update') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fullname" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?= ($validation->hasError('fullname')) ? 'is-invalid' : '' ?>" 
                                           id="fullname" name="fullname" 
                                           value="<?= old('fullname', is_array($user) ? ($user['fullname'] ?? '') : ($user->fullname ?? '')) ?>" 
                                           placeholder="Nama Lengkap Anda">
                                    <div class="invalid-feedback"><?= $validation->getError('fullname') ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : '' ?>" 
                                           id="username" name="username" 
                                           value="<?= old('username', is_array($user) ? ($user['username'] ?? '') : ($user->username ?? '')) ?>" 
                                           placeholder="Username Anda">
                                    <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" 
                                       value="<?= old('email', is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '')) ?>" 
                                       placeholder="Email Aktif">
                                <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label fw-semibold">Nomor HP <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= ($validation->hasError('no_hp')) ? 'is-invalid' : '' ?>" 
                                       id="no_hp" name="no_hp" 
                                       value="<?= old('no_hp', is_array($user) ? ($user['no_hp'] ?? '') : ($user->no_hp ?? '')) ?>" 
                                       placeholder="Contoh: 08123456789">
                                <div class="invalid-feedback"><?= $validation->getError('no_hp') ?></div>
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : '' ?>" 
                                          id="alamat" name="alamat" rows="3" 
                                          placeholder="Alamat lengkap pengiriman barang"><?= old('alamat', is_array($user) ? ($user['alamat'] ?? '') : ($user->alamat ?? '')) ?></textarea>
                                <div class="invalid-feedback"><?= $validation->getError('alamat') ?></div>
                            </div>

                            <hr class="my-4">

                            <div class="alert alert-info py-2 mb-3 small">
                                <i class="fas fa-info-circle me-1"></i> Biarkan kolom password <strong>kosong</strong> jika tidak ingin mengganti password Anda.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" 
                                           class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                                           id="password" name="password" placeholder="Minimal 8 karakter" autocomplete="off">
                                    <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                                    
                                    <!-- Indikator Kekuatan Password -->
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
                                           id="pass_confirm" name="pass_confirm" placeholder="Ulangi password baru" autocomplete="off">
                                    <div class="invalid-feedback"><?= $validation->getError('pass_confirm') ?></div>
                                </div>
                            </div>

                            <div class="text-muted small mb-4">
                                <i class="fas fa-shield-alt text-success me-1"></i> Gunakan password minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, angka, dan simbol agar akun lebih aman.
                            </div>

                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
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
        
        // Cek Panjang
        if (password.length >= 8) score++;
        // Cek Huruf Besar
        if (/[A-Z]/.test(password)) score++;
        // Cek Huruf Kecil
        if (/[a-z]/.test(password)) score++;
        // Cek Angka
        if (/[0-9]/.test(password)) score++;
        // Cek Simbol
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
