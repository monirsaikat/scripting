<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Admin Login</h1>
                    <p class="text-muted mb-4">Sign in with your admin account.</p>

                    <form action="<?= url('/admin/login') ?>" method="POST" <?= up_form_attrs() ?>>
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">Email address</label>
                            <input type="email" name="email" value="<?= old('email') ?>" autofocus class="form-control" id="adminEmail" required>
                        </div>

                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="adminPassword" required>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Login</button>
                    </form>

                    <div class="small text-muted mt-3">
                        Default admin: admin@example.com / admin123
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
