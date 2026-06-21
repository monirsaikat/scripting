<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('/images/favicon.jpg') ?>" type="image/x-icon">
    <title><?= $this->e($title ?? '') ?></title>

    <?= $this->insert('partials/assets') ?>
</head>

<body>
    <div id="page" class="app-shell" up-main>
        <?= $this->insert('partials/navbar') ?>

        <?= $this->insert('partials/confirmationModal') ?>


        <main id="app" class="app-main">
            <?= $this->insert('partials/flashes') ?>

            <div class="app-container">
                <?= $this->section('content') ?>
            </div>
        </main>
    </div>


    <?= $this->insert('partials/scripts') ?>

    <?= $this->section('scripts') ?>
</body>

</html>
