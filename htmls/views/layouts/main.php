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
    <div id="page" up-main>
        <?= $this->insert('partials/navbar') ?>

        <?= $this->insert('partials/confirmationModal') ?>


        <main id="app">
            <?= $this->insert('partials/flashes') ?>

            <div class="container">
                <?= $this->section('content') ?>
            </div>
        </main>
    </div>


    <?= $this->insert('partials/scripts') ?>

    <?= $this->section('scripts') ?>
</body>

</html>
