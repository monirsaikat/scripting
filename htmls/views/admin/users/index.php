<?php $this->layout('layouts/admin', ['title' => $pageTitle, 'page' => $page]) ?>

<!-- Page header -->
<div class="page-header">
  <div>
    <h1 class="page-title">Users</h1>
    <p class="page-subtitle"><?= number_format($total) ?> total users</p>
  </div>
  <a href="<?= url('/admin/users/create') ?>" class="btn-primary-action">
    <i class="bi bi-plus-lg"></i> Add User
  </a>
</div>

<!-- Table card -->
<div class="table-card">

  <!-- Filters -->
  <form method="GET" action="<?= url('/admin/users') ?>" class="table-controls" <?= up_form_attrs() ?> up-history="true">
    <div class="table-search-wrap">
      <i class="bi bi-search"></i>
      <input type="text" name="search" class="table-search" placeholder="Search name or email…"
             value="<?= htmlspecialchars($search) ?>">
    </div>
    <select name="role" class="table-select" onchange="this.form.submit()">
      <option value="">All Roles</option>
      <option value="admin"  <?= $role === 'admin'  ? 'selected' : '' ?>>Admin</option>
      <option value="editor" <?= $role === 'editor' ? 'selected' : '' ?>>Editor</option>
      <option value="viewer" <?= $role === 'viewer' ? 'selected' : '' ?>>Viewer</option>
    </select>
    <select name="status" class="table-select" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      <option value="active"   <?= $status === 'active'   ? 'selected' : '' ?>>Active</option>
      <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
      <option value="pending"  <?= $status === 'pending'  ? 'selected' : '' ?>>Pending</option>
    </select>
    <?php if ($search || $role || $status): ?>
    <a href="<?= url('/admin/users') ?>" class="btn-cancel" style="padding:6px 12px;font-size:11px">
      <i class="bi bi-x"></i> Clear
    </a>
    <?php endif; ?>
    <div class="table-controls-right">
      <button type="submit" class="btn-primary-action" style="padding:7px 14px">
        <i class="bi bi-search"></i> Search
      </button>
    </div>
  </form>

  <!-- Table -->
  <table class="data-table">
    <thead>
      <tr>
        <th>User</th>
        <th>Role</th>
        <th>Status</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($users)): ?>
      <tr>
        <td colspan="5">
          <div class="table-empty">
            <i class="bi bi-people"></i>
            No users match your filters
          </div>
        </td>
      </tr>
      <?php else: ?>
      <?php foreach ($users as $u): ?>
      <?php
        $initials = strtoupper(substr($u->first_name, 0, 1) . substr($u->last_name, 0, 1));
        $colors   = ['#667eea,#764ba2','#f093fb,#f5576c','#4facfe,#00f2fe','#43e97b,#38f9d7','#fa709a,#fee140','#a18cd1,#fbc2eb'];
        $color    = $colors[$u->id % count($colors)];
      ?>
      <tr>
        <td>
          <div class="table-user">
            <div class="table-avatar" style="background:linear-gradient(135deg,<?= $color ?>)">
              <?= $initials ?>
            </div>
            <div>
              <div class="u-name"><?= $this->e($u->first_name . ' ' . $u->last_name) ?></div>
              <div class="u-email"><?= $this->e($u->email) ?></div>
            </div>
          </div>
        </td>
        <td><span class="role-badge role-<?= htmlspecialchars($u->role) ?>"><?= ucfirst($u->role) ?></span></td>
        <td><span class="badge-status badge-<?= htmlspecialchars($u->status) ?>"><?= ucfirst($u->status) ?></span></td>
        <td><?= date('M j, Y', strtotime($u->created_at)) ?></td>
        <td>
          <div class="action-btns">
            <a href="<?= url('/admin/users/' . $u->id . '/edit') ?>" class="action-btn" title="Edit user">
              <i class="bi bi-pencil"></i>
            </a>
            <form method="POST" action="<?= url('/admin/users/' . $u->id . '/delete') ?>"
                  <?= up_form_attrs() ?>
                  onsubmit="return confirm('Delete <?= $this->e(addslashes($u->first_name . ' ' . $u->last_name)) ?>?')">
              <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
              <button type="submit" class="action-btn danger" title="Delete user" style="border:none;cursor:pointer">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Footer / Pagination -->
  <?php if ($totalPages > 1 || !empty($users)): ?>
  <div class="table-footer">
    <span class="table-info">
      Showing
      <?= number_format(($currentPage - 1) * $perPage + 1) ?>–<?= number_format(min($currentPage * $perPage, $total)) ?>
      of <?= number_format($total) ?> users
    </span>

    <?php if ($totalPages > 1): ?>
    <div class="pagination-wrap">
      <?php
        $qs = http_build_query(array_filter(['search' => $search, 'role' => $role, 'status' => $status]));
        $qs = $qs ? '&' . $qs : '';
        $prev = $currentPage - 1;
        $next = $currentPage + 1;
      ?>
      <a href="<?= url('/admin/users?page=' . $prev . $qs) ?>"
         class="page-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>"
         <?= $currentPage <= 1 ? 'aria-disabled="true"' : '' ?>>
        <i class="bi bi-chevron-left"></i>
      </a>
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <a href="<?= url('/admin/users?page=' . $p . $qs) ?>"
         class="page-btn <?= $p === $currentPage ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <a href="<?= url('/admin/users?page=' . $next . $qs) ?>"
         class="page-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"
         <?= $currentPage >= $totalPages ? 'aria-disabled="true"' : '' ?>>
        <i class="bi bi-chevron-right"></i>
      </a>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>
