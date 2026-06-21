<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="page page--compact">
    <div class="auth-card">
        <h1 class="auth-card__title">Admin Login</h1>
        <p class="auth-card__text">Sign in with your admin account.</p>

        <form class="form-grid" action="<?= url('/admin/login') ?>" method="POST" <?= up_form_attrs() ?>>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-field">
                <label for="adminEmail" class="form-field__label">Email address</label>
                <input type="email" name="email" value="<?= old('email') ?>" autofocus class="form-control" id="adminEmail" required>
            </div>

            <div class="form-field">
                <label for="adminPassword" class="form-field__label">Password</label>
                <input type="password" name="password" class="form-control" id="adminPassword" required>
            </div>

            <button type="submit" class="button button--dark w-100">Login</button>
        </form>

        <div class="auth-card__hint">
            Default admin: admin@example.com / admin123
        </div>
    </div>
</section>
