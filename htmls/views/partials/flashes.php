<div class="container mt-3">
    <?php if ($message = Src\Session::getFlash('success')): ?>
        <div class="alert alert-success" data-auto-dismiss><?= $message ?></div>
    <?php endif ?>

    <?php if ($message = Src\Session::getFlash('error')): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif ?>
</div>
