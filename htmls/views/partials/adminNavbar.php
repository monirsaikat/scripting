<nav class="admin-nav navbar navbar-expand-lg navbar-dark">
    <div class="admin-nav__inner">
        <a class="admin-nav__brand navbar-brand" href="<?= url('/admin') ?>">
            <span class="admin-nav__brand-mark">A</span>
            <span>Admin Panel</span>
        </a>
        <button class="admin-nav__toggle navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="admin-nav__menu navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="admin-nav__link nav-link" href="<?= url('/admin') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="admin-nav__link nav-link" href="<?= url('/staffs') ?>">Users</a>
                </li>
                <li class="nav-item">
                    <a class="admin-nav__link nav-link" href="<?= url('/') ?>">Site</a>
                </li>
                <li class="nav-item">
                    <a class="admin-nav__link admin-nav__link--danger nav-link" href="<?= url('/admin/logout') ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
