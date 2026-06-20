<?= $this->extend('auth/templates/index') ?>
<?= $this->section('content') ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="text-center text-white mb-4">
                    <h2 class="fw-bold mb-1">Bookshelf Store</h2>
                    <p class="mb-0">Buat akun baru untuk mulai berbelanja</p>
                </div>
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4"><h3 class="text-center fw-semibold my-2"><?=lang('Auth.register')?></h3></div>
                    <div class="card-body">
                        <?= view('Myth\Auth\Views\_message_block') ?>
                        <form action="<?= url_to('register') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-floating mb-3">
                                <input class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" type="text" placeholder="<?=lang('Auth.username')?>" value="<?= old('username') ?>" name="username" id="inputUsername" />
                                <label for="inputUsername"><?=lang('Auth.username')?></label>
                                <div class="invalid-feedback">
                                    <?= session('errors.username') ?>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" type="email" placeholder="<?=lang('Auth.email')?>" value="<?= old('email') ?>" name="email" id="inputEmail" />
                                <label for="inputEmail"><?=lang('Auth.email')?></label>
                                <small id="emailHelp" class="form-text text-muted"><?=lang('Auth.weNeverShare')?></small>
                                <div class="invalid-feedback">
                                    <?= session('errors.email') ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" name="password" id="inputPassword" type="password" placeholder="<?=lang('Auth.password')?>" autocomplete="off" />
                                        <label for="inputPassword"><?=lang('Auth.password')?></label>
                                        <div class="invalid-feedback">
                                            <?= session('errors.password') ?>
                                        </div>
                                    </div>
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
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" name="pass_confirm" type="password" placeholder="<?=lang('Auth.confirmPassword')?>" autocomplete="off" />
                                        <label for="inputPasswordConfirm"><?=lang('Auth.repeatPassword')?></label>
                                        <div class="invalid-feedback">
                                            <?= session('errors.pass_confirm') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-muted small mb-4">
                                <i class="fas fa-shield-alt text-success me-1"></i> Gunakan password minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, angka, dan simbol agar akun lebih aman.
                            </div>

                            <div class="mt-4 mb-0">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-block" type="submit"><?=lang('Auth.register')?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 bg-light border-0 rounded-bottom-4">
                        <div class="small">
                            <?=lang('Auth.alreadyRegistered')?> <a href="<?= url_to('login') ?>"><?=lang('Auth.signIn')?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('inputPassword');
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
