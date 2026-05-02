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
                Form Edit User: <?= esc($user->username) ?>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/user/update/' . $user->id) ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : '' ?>"
                            id="username" name="username"
                            value="<?= old('username', esc($user->username)) ?>"
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
<?= $this->endSection() ?>
