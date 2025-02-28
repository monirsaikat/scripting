<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('/images/favicon.jpg') ?>" type="image/x-icon">
    <title><?= $this->e($title ?? '') ?></title>

    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/styles.css') ?>">
</head>

<body>
    <?= $this->insert('partials/navbar') ?>
    
    <?= $this->insert('partials/confirmationModal') ?>


    <div class="container mt-3">
        <?php
        if ($message = Src\Session::getFlash('success')) {
            echo "<div class='alert alert-success'>{$message}</div>";
        }

        if ($message = Src\Session::getFlash('error')) {
            echo "<div class='alert alert-danger'>{$message}</div>";
        }
        ?>
    </div>



    <div class="container">
        <?= $this->section('content') ?>
    </div>


    <script src="<?= asset('/js/bootstrap.bundle.min.js') ?>"></script>

    <?= $this->section('scripts') ?>
</body>

</html>