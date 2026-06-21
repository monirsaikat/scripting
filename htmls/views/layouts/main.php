<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('/images/favicon.jpg') ?>" type="image/x-icon">
    <title><?= $this->e($title ?? '') ?></title>

    <link rel="stylesheet" href="<?= asset('/vendor/unpoly/unpoly.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/vendor/unpoly/unpoly-bootstrap5.min.css') ?>">
    <script src="<?= asset('/vendor/unpoly/unpoly.min.js') ?>" defer></script>
    <script src="<?= asset('/js/unpoly-app.js') ?>" defer></script>

    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/styles.css') ?>">
</head>

<body>
    <div id="page" up-main>
        <?= $this->insert('partials/navbar') ?>

        <?= $this->insert('partials/confirmationModal') ?>


        <main id="app">
            <div class="container mt-3">
                <?php
                if ($message = Src\Session::getFlash('success')) {
                    echo "<div class='alert alert-success' data-auto-dismiss>{$message}</div>";
                }

                if ($message = Src\Session::getFlash('error')) {
                    echo "<div class='alert alert-danger'>{$message}</div>";
                }
                ?>
            </div>

            <div class="container">
                <?= $this->section('content') ?>
            </div>
        </main>
    </div>


    <script src="<?= asset('/js/bootstrap.bundle.min.js') ?>"></script>

    <?= $this->section('scripts') ?>
</body>

</html>
