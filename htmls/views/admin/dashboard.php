<?php $this->layout('layouts/admin', ['title' => $pageTitle]) ?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <div class="text-muted">Signed in as <?= $this->e($admin->name ?? 'Admin') ?></div>
    </div>

    <a href="<?= url('/staffs') ?>" class="btn btn-dark">Manage Users</a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">Total Users</div>
                <div class="display-6 fw-semibold"><?= $stats['users'] ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">Total Admins</div>
                <div class="display-6 fw-semibold"><?= $stats['admins'] ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h2 class="h5">Admin Guard Active</h2>
        <p class="mb-0 text-muted">
            This page is protected by <code>#[Auth('admin', '/admin/login')]</code>.
            Normal users logged in on the default guard cannot access it.
        </p>
    </div>
</div>
