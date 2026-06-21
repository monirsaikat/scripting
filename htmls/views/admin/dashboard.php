<?php $this->layout('layouts/admin', ['title' => $pageTitle]) ?>

<section class="page page--compact">
    <div class="page-header">
        <div>
            <div class="page-header__eyebrow">Admin</div>
            <h1 class="page-header__title page-header__title--sm">Dashboard</h1>
            <p class="page-header__text">Signed in as <?= $this->e($admin->name ?? 'Admin') ?>.</p>
        </div>

        <a href="<?= url('/staffs') ?>" class="button button--dark">Manage Users</a>
    </div>

    <div class="admin-dashboard__grid">
        <div class="stat-card">
            <div class="stat-card__label">Total Users</div>
            <div class="stat-card__value"><?= $stats['users'] ?></div>
            <div class="stat-card__accent"></div>
        </div>

        <div class="stat-card">
            <div class="stat-card__label">Total Admins</div>
            <div class="stat-card__value"><?= $stats['admins'] ?></div>
            <div class="stat-card__accent"></div>
        </div>
    </div>

    <div class="admin-note">
        <h2 class="admin-note__title">Admin Guard Active</h2>
        <p class="mb-0 text-muted">
            This page is protected by <code>#[Auth('admin', '/admin/login')]</code>.
            Normal users logged in on the default guard cannot access it.
        </p>
    </div>
</section>
