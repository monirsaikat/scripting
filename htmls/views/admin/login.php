<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FUSE — Sign In</title>
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= baseUrl() ?>/admin/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{min-height:100vh;background:linear-gradient(135deg,#2d3142 0%,#3f4565 100%);display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',system-ui,sans-serif}
    .login-wrap{width:400px}
    .login-card{background:#fff;border-radius:10px;padding:38px 34px;box-shadow:0 20px 60px rgba(0,0,0,.3)}
    .login-logo{width:46px;height:46px;background:#29b6d2;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:21px;font-weight:800;color:#fff;margin:0 auto 16px}
    .login-title{text-align:center;font-size:21px;font-weight:700;color:#2d3142;margin-bottom:4px}
    .login-sub{text-align:center;font-size:12.5px;color:#9098ad;margin-bottom:28px}
    .field{margin-bottom:16px}
    .field label{display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px}
    .field .input-wrap{position:relative}
    .field .input-wrap i{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9098ad;font-size:14px}
    .field input{width:100%;padding:10px 14px 10px 34px;border:1px solid #e8eaf0;border-radius:5px;font-size:13px;color:#2d3142;outline:none;transition:border-color .15s}
    .field input:focus{border-color:#4472ca;box-shadow:0 0 0 3px rgba(68,114,202,.12)}
    .btn-login{width:100%;padding:11px;background:#4472ca;color:#fff;border:none;border-radius:5px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s;display:flex;align-items:center;justify-content:center;gap:6px}
    .btn-login:hover{background:#3561b5}
    .alert-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:5px;padding:10px 14px;font-size:12px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
    .alert-success{background:#e8f7ef;border:1px solid #6ee7b7;color:#065f46;border-radius:5px;padding:10px 14px;font-size:12px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
  </style>
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-logo">F</div>
    <h1 class="login-title">Welcome back</h1>
    <p class="login-sub">Sign in to your FUSE admin account</p>

    <?php $err = \Src\Session::getFlash('error'); $suc = \Src\Session::getFlash('success'); ?>
    <?php if ($err): ?>
    <div class="alert-error"><i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>
    <?php if ($suc): ?>
    <div class="alert-success"><i class="bi bi-check-circle-fill"></i><?= htmlspecialchars($suc) ?></div>
    <?php endif; ?>

    <form action="<?= url('/admin/login') ?>" method="POST">
      <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

      <div class="field">
        <label>Email address</label>
        <div class="input-wrap">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" value="<?= htmlspecialchars(old('email', '')) ?>" placeholder="admin@example.com" autofocus required>
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-wrap">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" placeholder="••••••••" required>
        </div>
      </div>

      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </button>
    </form>
  </div>
</div>
</body>
</html>
