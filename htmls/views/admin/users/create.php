<?php $this->layout('layouts/admin', ['title' => $pageTitle, 'page' => $page]) ?>

<div class="page-header">
  <div>
    <h1 class="page-title">Create User</h1>
    <p class="page-subtitle">Add a new user to the system</p>
  </div>
  <a href="<?= url('/admin/users') ?>" class="btn-cancel">
    <i class="bi bi-arrow-left"></i> Back to Users
  </a>
</div>

<div class="form-card">
  <form method="POST" action="<?= url('/admin/users/store') ?>" <?= up_form_attrs() ?>>
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">First Name <span style="color:#dc2626">*</span></label>
        <input type="text" name="first_name" class="form-control-fuse"
               value="<?= htmlspecialchars(old('first_name', '')) ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Last Name <span style="color:#dc2626">*</span></label>
        <input type="text" name="last_name" class="form-control-fuse"
               value="<?= htmlspecialchars(old('last_name', '')) ?>" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Email Address <span style="color:#dc2626">*</span></label>
        <input type="email" name="email" class="form-control-fuse"
               value="<?= htmlspecialchars(old('email', '')) ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password <span style="color:#dc2626">*</span></label>
        <input type="password" name="password" class="form-control-fuse"
               placeholder="Min. 8 characters" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Phone</label>
        <input type="tel" name="phone" class="form-control-fuse"
               value="<?= htmlspecialchars(old('phone', '')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-control-fuse">
          <option value="">— Select —</option>
          <option value="male"   <?= old('gender') === 'male'   ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= old('gender') === 'female' ? 'selected' : '' ?>>Female</option>
          <option value="other"  <?= old('gender') === 'other'  ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Role</label>
        <select name="role" class="form-control-fuse">
          <option value="viewer" <?= old('role', 'viewer') === 'viewer' ? 'selected' : '' ?>>Viewer</option>
          <option value="editor" <?= old('role') === 'editor' ? 'selected' : '' ?>>Editor</option>
          <option value="admin"  <?= old('role') === 'admin'  ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control-fuse">
          <option value="active"   <?= old('status', 'active') === 'active'   ? 'selected' : '' ?>>Active</option>
          <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
          <option value="pending"  <?= old('status') === 'pending'  ? 'selected' : '' ?>>Pending</option>
        </select>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-save">
        <i class="bi bi-check-lg"></i> Create User
      </button>
      <a href="<?= url('/admin/users') ?>" class="btn-cancel">Cancel</a>
    </div>
  </form>
</div>
