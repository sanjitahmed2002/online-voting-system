<?php
require 'db.php';
session_start();

$err = '';
$registered = isset($_GET['registered']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u && password_verify($pass, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        $token = bin2hex(random_bytes(32));
        $upd = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $upd->execute([$token, $u['id']]);
        setcookie('remember_me', $token, time() + (30*24*60*60), "/"); 
        header('Location: voter_dashboard.php'); exit;
    } else {
        $err = "Invalid email or password.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Voter Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #055e36ff, #640b0bff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      color: white;
      width: 100%;
      max-width: 450px;
      transition: transform .3s;
    }
    .login-card:hover {
      transform: translateY(-5px);
    }
    .card-header {
      background: transparent;
      border-bottom: none;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-header img {
      height: 45px;
    }
    .card-header .btn-back {
      border-radius: 30px;
      padding: 5px 15px;
      font-size: 0.9rem;
      background: #ffffff22;
      border: 1px solid #fff;
      color: #fff;
      transition: 0.3s;
    }
    .card-header .btn-back:hover {
      background: #fff;
      color: #333;
    }
    .form-control {
      border-radius: 12px;
      padding: 12px;
      border: none;
      box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
    }
    .btn-primary {
      border-radius: 25px;
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      border: none;
      font-weight: 600;
      transition: transform .2s, box-shadow .2s;
    }
    .btn-primary:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0, 114, 255, 0.5);
    }
    .alert {
      border-radius: 12px;
      font-size: 0.9rem;
    }
    .card-footer {
      background: transparent;
      border-top: none;
      color: #ddd;
      font-size: 0.85rem;
    }
    a {
      color: #00c6ff;
      font-weight: 500;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-card shadow-lg p-3">
    <div class="card-header">
      <!-- Left: Logo -->
      <img src="image/bec_logo.png" alt="Bangladesh Election Commission">
      <!-- Right: Back button -->
      <a href="index.php" class="btn btn-sm btn-back">⬅ Back</a>
    </div>
    <div class="card-body">
      <h3 class="text-center mb-4">🗳 Voter Login</h3>

      <?php if ($registered): ?>
        <div class="alert alert-success">✅ Registration successful. Please login.</div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="alert alert-danger">❌ <?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" class="mt-3">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button class="btn btn-primary w-100">Login & Continue</button>
      </form>

      <div class="text-center mt-3">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
      </div>
    </div>
    <div class="card-footer text-center">
      © 2026 Bangladesh Election Commission | Online Voting System
    </div>
  </div>
</body>
</html>
