<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="py-5">

    <?php if (user()): ?>
        <h2>Hey, <?= @$user->first_name ?></h2>

        <a href="<?= url('/logout') ?>" class="btn btn-danger">Logout</a>
    <?php endif; ?>

    <?php if (!user()): ?>
        <a href="<?= url('/login') ?>" class="btn btn-primary">Please Login</a>
    <?php endif; ?>

</section>