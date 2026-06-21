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
