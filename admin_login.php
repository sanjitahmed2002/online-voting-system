<?php
// Session error fix
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['username'];
        header("Location: admin_panel.php");
        exit;
    } else {
        $message = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - Online Voting System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #095909ff, #560810ff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      color: white;
      width: 100%;
      max-width: 420px;
      padding: 30px;
      position: relative;
    }
    .login-card h2 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      color: #fff;
    }
    .form-control {
      border-radius: 12px;
      padding: 12px;
      border: none;
      box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
    }
    .btn-custom {
      border-radius: 25px;
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      border: none;
      font-weight: 600;
      transition: transform .2s, box-shadow .2s;
      color: white;
    }
    .btn-custom:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0, 114, 255, 0.5);
    }
    .alert {
      border-radius: 12px;
      font-size: 0.9rem;
    }
    .logo {
      display: block;
      margin: 0 auto 15px auto;
      width: 80px;
    }
    .back-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(255,255,255,0.2);
      color: #fff;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.9rem;
      text-decoration: none;
      transition: background .2s;
    }
    .back-btn:hover {
      background: rgba(255,255,255,0.4);
      text-decoration: none;
      color: #fff;
    }
    .card-footer {
      margin-top: 15px;
      text-align: center;
      font-size: 0.85rem;
      color: #ddd;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <!-- Back Button -->
    <a href="index.php" class="back-btn">⬅ Back</a>
    <!-- Logo -->
    <img src="image/bec_logo.png" alt="BEC Logo" class="logo">

    <h2>🔑 Admin Login</h2>

    <?php if ($message): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-custom w-100">Login</button>
    </form>

    <div class="card-footer">
      © 2026 Bangladesh Election Commission | Online Voting System
    </div>
  </div>
</body>
</html>
