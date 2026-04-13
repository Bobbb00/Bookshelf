<?= $this->extend('auth/templates/index') ?>
<?= $this->section('content') ?>
<?php $config = config('Auth'); ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="text-center text-white mb-4">
                    <h2 class="fw-bold mb-1">Bookshelf Store</h2>
                    <p class="mb-0">Masuk untuk mulai belanja buku favoritmu</p>
                </div>
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-white border-0 pt-4">
                        <h3 class="text-center fw-semibold my-2"><?=lang('Auth.loginTitle')?></h3>
                    </div>
                    <div class="card-body">
                        <?= view('Myth\Auth\Views\_message_block') ?>
                        <form action="<?= url_to('login') ?>" method="post">
                            <?= csrf_field() ?>
                            <!-- Email/Username Field -->
                            <div class="form-floating mb-3">
                                <input name="login" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" id="inputLogin" type="text" placeholder="<?=lang('Auth.emailOrUsername')?>" />
                                <label for="inputLogin"><?=lang('Auth.emailOrUsername')?></label>
                                <div class="invalid-feedback">
                                    <?= session('errors.login') ?>
                                </div>
                            </div>
                            <!-- Password Field -->
                            <div class="form-floating mb-3">
                                <input name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" id="inputPassword" type="password" placeholder="<?=lang('Auth.password')?>" />
                                <label for="inputPassword"><?=lang('Auth.password')?></label>
                                <div class="invalid-feedback">
                                    <?= session('errors.password') ?>
                                </div>
                            </div>
                            <?php if ($config->allowRemembering): ?>
                            <div class="form-check mb-3">
                                <label class="form-check-label">
                                    <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                    <?=lang('Auth.rememberMe')?>
                                </label>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button type="submit" class="btn btn-primary w-100"><?=lang('Auth.loginAction')?></button>
                            </div>
                        </form>
                    </div>
                    <?php if ($config->allowRegistration) : ?>
                    <div class="card-footer text-center py-3 bg-light border-0 rounded-bottom-4">
                        <div class="small"><a href="<?= url_to('register') ?>">Need an account? <?=lang('Auth.signup')?></a></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>