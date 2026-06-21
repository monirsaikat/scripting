<?php $this->layout('layouts/admin', ['title' => $pageTitle, 'page' => $page]) ?>

<div class="page-header">
  <div>
    <h1 class="page-title">Edit User</h1>
    <p class="page-subtitle"><?= $this->e($user->first_name . ' ' . $user->last_name) ?> — #<?= $user->id ?></p>
  </div>
  <a href="<?= url('/admin/users') ?>" class="btn-cancel">
    <i class="bi bi-arrow-left"></i> Back to Users
  </a>
</div>

<div class="form-card">
  <form method="POST" action="<?= url('/admin/users/' . $user->id . '/update') ?>" <?= up_form_attrs() ?>>
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">First Name <span style="color:#dc2626">*</span></label>
        <input type="text" name="first_name" class="form-control-fuse"
               value="<?= htmlspecialchars(old('first_name', $user->first_name)) ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Last Name <span style="color:#dc2626">*</span></label>
        <input type="text" name="last_name" class="form-control-fuse"
               value="<?= htmlspecialchars(old('last_name', $user->last_name)) ?>" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Email Address <span style="color:#dc2626">*</span></label>
        <input type="email" name="email" class="form-control-fuse"
               value="<?= htmlspecialchars(old('email', $user->email)) ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control-fuse"
               placeholder="Leave blank to keep current">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Phone</label>
        <input type="tel" name="phone" class="form-control-fuse"
               value="<?= htmlspecialchars(old('phone', $user->phone ?? '')) ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-control-fuse">
          <option value="">— Select —</option>
          <?php foreach (['male','female','other'] as $g): ?>
          <option value="<?= $g ?>" <?= old('gender', $user->gender ?? '') === $g ? 'selected' : '' ?>>
            <?= ucfirst($g) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Role</label>
        <select name="role" class="form-control-fuse">
          <?php foreach (['viewer','editor','admin'] as $r): ?>
          <option value="<?= $r ?>" <?= old('role', $user->role) === $r ? 'selected' : '' ?>>
            <?= ucfirst($r) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control-fuse">
          <?php foreach (['active','inactive','pending'] as $s): ?>
          <option value="<?= $s ?>" <?= old('status', $user->status) === $s ? 'selected' : '' ?>>
            <?= ucfirst($s) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-save">
        <i class="bi bi-check-lg"></i> Save Changes
      </button>
      <a href="<?= url('/admin/users') ?>" class="btn-cancel">Cancel</a>

      <!-- Delete in the same form area -->
      <form method="POST" action="<?= url('/admin/users/' . $user->id . '/delete') ?>"
            style="margin-left:auto"
            onsubmit="return confirm('Permanently delete <?= $this->e(addslashes($user->first_name . ' ' . $user->last_name)) ?>?')">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <button type="submit" class="btn-save" style="background:#dc2626">
          <i class="bi bi-trash"></i> Delete User
        </button>
      </form>
    </div>
  </form>
</div>
