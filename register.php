 <?php
require 'db.php';
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nid = trim($_POST['nid'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$nid || !$dob || !$password) $errors[] = "All fields are required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if (!preg_match('/^[0-9A-Za-z\-]{6,30}$/', $nid)) $errors[] = "Invalid NID format.";

    $dob_time = strtotime($dob);
    if (!$dob_time) $errors[] = "Invalid date of birth.";
    else {
        $age = (int)((time() - $dob_time) / (365.25*24*60*60));
        if ($age < 18) $errors[] = "You must be 18 or older to register.";
    }

    if (!$errors) {
        $s = $pdo->prepare("SELECT id FROM users WHERE email = ? OR nid = ?");
        $s->execute([$email, $nid]);
        if ($s->fetch()) {
            $errors[] = "Email or NID already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (name,email,nid,dob,age,password,created_at) VALUES (?,?,?,?,?,?,NOW())");
            $ins->execute([$name,$email,$nid,$dob,$age,$hash]);

            $success = "✅ Registration Successful!<br>Now you can login with your email and password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Voter Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #16171bff, #151617ff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      transition: background 0.4s, color 0.4s;
    }
    .navbar {
      background: linear-gradient(90deg, #22107cff, #1a3e8aff);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .navbar-brand {
      font-weight: bold;
      color: rgba(250, 247, 247, 1) !important;
    }
    .hero {
      text-align: center;
      padding: 40px 20px;
      background: linear-gradient(120deg, #065d2fff, #660707ff);
      color: white;
      border-radius: 0 0 25px 25px;
      margin-bottom: 30px;
    }
    .hero img {
      width: 90px;
      margin-bottom: 10px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: transform .3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .btn-primary {
      background: linear-gradient(90deg, #0d563cff, #640c18ff);
      border: none;
      font-weight: 600;
      padding: 10px;
      border-radius: 25px;
      transition: background .3s;
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #086441ff, #690b11ff);
    }
    .form-control {
      border-radius: 10px;
      padding: 10px;
    }
    .footer {
      margin-top: 40px;
      padding: 15px;
      text-align: center;
      background: #2808a7ff;
      color: white;
      border-radius: 15px 15px 0 0;
    }

    /* Dark Mode */
    body.dark-mode {
      background: #1e1e2f;
      color: #566b22ff;
    }
    body.dark-mode .navbar {
      background: linear-gradient(90deg, #111827, #1f2937);
    }
    body.dark-mode .hero {
      background: linear-gradient(120deg, #1f2937, #374151);
    }
    body.dark-mode .card {
      background: #2d2d3a;
      color: #697935ff;
      box-shadow: 0 6px 20px rgba(0,0,0,0.6);
    }
    body.dark-mode .form-control {
      background: #3b3b4f;
      border: 1px solid #555;
      color: #6f7b3cff;
    }
    body.dark-mode .footer {
      background: #111827;
    }
    .dark-toggle {
      cursor: pointer;
      border: none;
      border-radius: 25px;
      padding: 6px 14px;
      font-size: 14px;
      font-weight: 600;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container d-flex justify-content-between">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="image/bec_logo.png" alt="BEC Logo" style="width:30px; margin-right:8px;">
      Online Voting
    </a>
    <div>
      <button class="btn btn-light dark-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>
      <a href="index.php" class="btn btn-sm btn-light">⬅ Back</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="hero">
  <img src="image/bec_logo.png" alt="Bangladesh Election Commission Logo">
  <h2>Voter Registration Portal</h2>
  <p>Register to take part in the democratic process of Bangladesh</p>
</div>

<!-- Main Form -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card p-4">
        <div class="card-body">
            
          <h3 class="mb-3 text-center text-primary">📝 Register as Voter</h3>

          <?php if ($errors): ?>
            <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success text-center">
              <?= $success ?><br>
              <a href="login.php" class="btn btn-success mt-3">Go to Login</a>
            </div>
          <?php else: ?>
            <form method="post" novalidate>
              <div class="mb-3">
                <input name="name" class="form-control" placeholder="Full name" required>
              </div>
              <div class="mb-3">
                <input name="email" type="email" class="form-control" placeholder="Email" required>
              </div>
              <div class="mb-3">
                <input name="nid" class="form-control" placeholder="NID (National ID)" required>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">Date of Birth</label>
                <input name="dob" type="date" class="form-control" required>
              </div>
              <div class="mb-3">
                <input name="password" type="password" class="form-control" placeholder="Password" required>
              </div>
              <button class="btn btn-primary w-100">Register</button>
              <div class="mt-3 text-center">
                <a href="login.php" class="text-decoration-none">Already registered? Login</a>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  © 2026 Bangladesh Election Commission | Online Voting System
</div>

<script>
  function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    const btn = document.querySelector(".dark-toggle");
    if (document.body.classList.contains("dark-mode")) {
      btn.textContent = "☀ Light Mode";
      btn.classList.remove("btn-light");
      btn.classList.add("btn-warning");
    } else {
      btn.textContent = "🌙 Dark Mode";
      btn.classList.remove("btn-warning");
      btn.classList.add("btn-light");
    }
  }
</script>

</body>
</html>
