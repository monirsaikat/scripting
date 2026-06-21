<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="page page--compact">
    <div class="auth-card">
        <h1 class="auth-card__title">Login</h1>
        <p class="auth-card__text">Access your account using your email address.</p>

        <form class="form-grid" action="<?= url('/login') ?>" method="POST" <?= up_form_attrs() ?>>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-field">
                <label for="exampleInputEmail1" class="form-field__label">Email address</label>
                <input type="email" name="email" autofocus class="form-control" id="exampleInputEmail1">
            </div>

            <div class="form-field">
                <label for="exampleInputPassword1" class="form-field__label">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1">
            </div>

            <button type="submit" class="button button--primary w-100">Submit</button>
        </form>
    </div>
</section>
