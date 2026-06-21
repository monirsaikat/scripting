<nav class="site-nav navbar navbar-expand-lg">
    <div class="site-nav__inner">
        <a class="site-nav__brand navbar-brand" href="<?= url('/') ?>">
            <img class="site-nav__logo" src="<?= asset('/images/logo.svg') ?>" alt="logo" />
        </a>
        <button class="site-nav__toggle navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="site-nav__menu navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="site-nav__link nav-link" href="<?= url('/') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="site-nav__link nav-link" href="<?= user('admin') ? router()->route('staff.all') : url('/admin/login') ?>">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="site-nav__link nav-link" href="<?= url('/about') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="site-nav__link nav-link" href="<?= url('/contact') ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
