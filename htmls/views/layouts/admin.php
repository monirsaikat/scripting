<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('/images/favicon.jpg') ?>" type="image/x-icon">
    <title><?= $this->e($title ?? 'Admin') ?></title>

    <link rel="stylesheet" href="<?= asset('/vendor/unpoly/unpoly.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/vendor/unpoly/unpoly-bootstrap5.min.css') ?>">
    <script src="<?= asset('/vendor/unpoly/unpoly.min.js') ?>" defer></script>
    <script src="<?= asset('/js/unpoly-app.js') ?>" defer></script>

    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/styles.css') ?>">
</head>

<body>
    <div id="page" class="bg-light min-vh-100" up-main>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-semibold" href="<?= url('/admin') ?>">Admin Panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/admin') ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/staffs') ?>">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/') ?>">Site</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="<?= url('/admin/logout') ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

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

            <div class="container py-4">
                <?= $this->section('content') ?>
            </div>
        </main>
    </div>

    <script src="<?= asset('/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->section('scripts') ?>
</body>

</html>
