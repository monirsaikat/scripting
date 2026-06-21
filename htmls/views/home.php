<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="hero">
    <div class="hero__panel">
        <div class="page-header__eyebrow">Raw PHP Framework</div>
        <h1 class="page-header__title">Server-rendered apps with modern frontend feel.</h1>
        <p class="page-header__text">
            Build simple PHP applications with routing, migrations, guards, Unpoly-enhanced navigation, and clean reusable views.
        </p>

        <div class="hero__actions">
            <?php if (user()): ?>
                <span class="button button--secondary">Hey, <?= @$user->first_name ?></span>
                <a href="<?= url('/logout') ?>" class="button button--danger">Logout</a>
            <?php endif; ?>

            <?php if (!user()): ?>
                <a href="<?= url('/login') ?>" class="button button--primary">Login</a>
                <a href="<?= url('/contact') ?>" class="button button--secondary">Contact</a>
            <?php endif; ?>
        </div>

        <div class="hero__meta">
            <span class="hero__pill">Multi-guard auth</span>
            <span class="hero__pill">Unpoly powered</span>
            <span class="hero__pill">No build step</span>
        </div>
    </div>
</section>
