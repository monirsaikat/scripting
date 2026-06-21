<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('/images/favicon.jpg') ?>" type="image/x-icon">
    <title><?= $this->e($title ?? 'Admin') ?></title>

    <?= $this->insert('partials/assets') ?>
</head>

<body>
    <div id="page" class="bg-light min-vh-100" up-main>
        <?= $this->insert('partials/adminNavbar') ?>

        <?= $this->insert('partials/confirmationModal') ?>

        <main id="app">
            <?= $this->insert('partials/flashes') ?>

            <div class="container py-4">
                <?= $this->section('content') ?>
            </div>
        </main>
    </div>

    <?= $this->insert('partials/scripts') ?>
    <?= $this->section('scripts') ?>
</body>

</html>
