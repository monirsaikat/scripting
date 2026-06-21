<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->e($title ?? 'Admin') ?> — FUSE</title>
  <link rel="stylesheet" href="<?= asset('/vendor/unpoly/unpoly.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('/vendor/unpoly/unpoly-bootstrap5.min.css') ?>">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/css/tokens.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/css/layout.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/css/sidebar.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/css/topbar.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/css/components.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/css/pages.css">
</head>
<body>
<?php
  $currentPage  = $page ?? 'dashboard';
  $adminUser    = user('admin');
  $initials     = strtoupper(substr($adminUser->name ?? 'A', 0, 1));
  $flashSuccess = \Src\Session::getFlash('success');
  $flashError   = \Src\Session::getFlash('error');

  function fNav(string $route, string $label, string $icon, string $current, string $href, array $extra = []): string {
    $active  = $current === $route ? ' active' : '';
    $upAttrs = up_link_attrs('[up-main]', $extra);
    return sprintf(
      '<a href="%s" class="sidebar-nav-link%s" %s><i class="bi %s nav-icon"></i><span>%s</span></a>',
      $href, $active, $upAttrs, $icon, htmlspecialchars($label)
    );
  }
?>

<div class="app-shell" id="adminShell">

  <!-- ───── SIDEBAR ───── -->
  <aside class="sidebar" id="fuseSidebar" up-hungry up-id="admin-sidebar">

    <div class="sidebar-logo">
      <div class="logo-icon">F</div>
      <span class="logo-text">FUSE</span>
      <i class="bi bi-list hamburger" id="hamburger" style="cursor:pointer;font-size:18px;margin-left:auto;color:rgba(255,255,255,.5)"></i>
    </div>

    <div class="sidebar-label">FUSE</div>
    <?= fNav('dashboard', 'Dashboard', 'bi-grid-1x2-fill', $currentPage, url('/admin')) ?>
    <?= fNav('project',   'Project',   'bi-kanban',         $currentPage, '#') ?>

    <div class="sidebar-label">APPS</div>
    <?= fNav('orders',   'E-Commerce', 'bi-bag',            $currentPage, '#') ?>
    <?= fNav('calendar', 'Calendar',   'bi-calendar3',      $currentPage, '#') ?>
    <?= fNav('chat',     'Chat',       'bi-chat-dots',      $currentPage, '#') ?>
    <?= fNav('mail',     'Mail',       'bi-envelope',       $currentPage, '#') ?>
    <?= fNav('files',    'Files',      'bi-folder2-open',   $currentPage, '#') ?>

    <div class="sidebar-label">MANAGEMENT</div>
    <?= fNav('users',    'Users',      'bi-people',         $currentPage, url('/admin/users')) ?>
    <?= fNav('contacts', 'Contacts',   'bi-person-lines-fill', $currentPage, '#') ?>
    <?= fNav('settings', 'Settings',   'bi-gear',           $currentPage, '#') ?>

    <div class="sidebar-spacer"></div>

    <div class="sidebar-logout">
      <a href="<?= url('/admin/logout') ?>" class="sidebar-nav-link logout-link">
        <i class="bi bi-box-arrow-left nav-icon"></i>
        <span>Logout</span>
      </a>
    </div>

  </aside>

  <!-- ───── MAIN AREA ───── -->
  <div class="main-area">

    <header class="topbar">
      <span class="topbar-breadcrumb"><?= $this->e($title ?? 'Dashboard') ?></span>
      <div class="topbar-right">
        <i class="bi bi-bell" style="font-size:15px"></i>
        <div class="topbar-sep"></div>
        <div class="topbar-avatar"><?= $initials ?></div>
        <span class="topbar-username"><?= $this->e($adminUser->name ?? 'Admin') ?></span>
      </div>
    </header>

    <div class="content-area">
      <div class="content-main" id="contentMain" up-main>

        <?php if ($flashSuccess): ?>
        <div class="fuse-alert fuse-alert--success">
          <i class="bi bi-check-circle-fill"></i>
          <?= $this->e($flashSuccess) ?>
          <button type="button" class="fuse-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
        </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
        <div class="fuse-alert fuse-alert--error">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?= $this->e($flashError) ?>
          <button type="button" class="fuse-alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
        </div>
        <?php endif; ?>

        <div class="inner-pad">
          <?= $this->section('content') ?>
        </div>

      </div>
    </div>

  </div>
</div>

<div class="fuse-toast" id="fuseToast">
  <i class="bi bi-check-circle-fill"></i>
  <span class="toast-msg"></span>
</div>

<script src="<?= baseUrl() ?>/admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('/vendor/unpoly/unpoly.min.js') ?>"></script>
<script>
  // Auto-follow all admin links and forms for SPA experience
  up.link.config.followSelectors.push('a[href]:not([href^="#"]):not([target]):not([data-no-up])');
  up.form.config.submitSelectors.push('form:not([data-no-up])');
  // Default target for links that don't specify one
  up.link.config.defaultValues['up-target'] = '[up-main]';

  // Re-attach hamburger on every sidebar render (sidebar is up-hungry so it gets replaced)
  up.compiler('#hamburger', el => {
    el.addEventListener('click', () => {
      document.getElementById('fuseSidebar').classList.toggle('collapsed');
    });
  });
</script>
<?= $this->section('scripts') ?>
</body>
</html>
