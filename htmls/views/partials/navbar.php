<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="<?= url('/') ?>">
            <img src="<?= asset('/images/logo.svg') ?>" alt="logo" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= url('/') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= user('admin') ? router()->route('staff.all') : url('/admin/login') ?>">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/about') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url('/contact') ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
