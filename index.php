<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Voting System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #0d1117;
      color: #e6edf3;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Navbar */
    .navbar {
      background: linear-gradient(90deg, #004d40, #8b0000);
      padding: 12px 0;
    }
    .navbar-brand {
      color: #fff !important;
      font-weight: bold;
      font-size: 22px;
    }
    .btn-custom {
      margin: 5px;
      border-radius: 25px;
      font-weight: 600;
      padding: 8px 20px;
      transition: 0.3s;
    }
    .btn-custom:hover {
      transform: scale(1.05);
      box-shadow: 0 0 12px rgba(255,255,255,0.4);
    }
    /* Election Card */
    .card {
      border-radius: 15px;
      background: #161b22;
      color: #fff;
      box-shadow: 0 0 15px rgba(0,255,127,0.2);
      transition: 0.3s;
      border: none;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 20px rgba(0,255,127,0.6);
    }
    /* Footer */
    .footer {
      margin-top: 50px;
      padding: 20px;
      text-align: center;
      background: linear-gradient(90deg, #004d40, #8b0000);
      color: white;
      border-radius: 10px;
    }
    /* Logo Section */
    .header-logo {
      text-align: center;
      margin: 20px 0;
    }
    .header-logo img {
      max-width: 130px;
      filter: drop-shadow(0 0 10px rgba(255,255,255,0.7));
    }
    .header-logo h3 {
      color: #00ff99;
      text-shadow: 0 0 10px rgba(0,255,153,0.6);
    }
    .vote-btn {
      border-radius: 25px;
      font-weight: 600;
      background: linear-gradient(90deg, #00c853, #1de9b6);
      color: black;
      transition: 0.3s;
    }
    .vote-btn:hover {
      background: linear-gradient(90deg, #1de9b6, #00c853);
      color: black;
      box-shadow: 0 0 15px rgba(0,255,127,0.7);
    }
  </style>
</head>
<body>
  
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <img src="image/bec_logo.png" alt="BEC Logo" style="width:30px; margin-right:8px;">
       <a class="navbar-brand" href="#"> Online Voting System </a>
      <div>
        <a href="register.php" class="btn btn-light btn-custom">Voter Register</a>
        <a href="login.php" class="btn btn-success btn-custom">Voter Login</a>
        <a href="admin_login.php" class="btn btn-dark btn-custom">Admin Login</a>
      </div>
    </div>
  </nav>

  <!-- Logo Header -->
  <div class="header-logo">
    <img src="image/bec_logo.png" alt="Bangladesh Election Commission Logo">
    <h3 class="fw-bold mt-3">Bangladesh Election Commission</h3>
  </div>

  <!-- Main Content -->
  <div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold text-warning">🔥 Active Elections</h2>
    <div class="row">
      <?php
        $stmt = $pdo->query("SELECT * FROM elections WHERE start_date <= CURDATE() AND end_date >= CURDATE()");
        while ($row = $stmt->fetch()) {
          echo "
          <div class='col-md-6 col-lg-4 mb-4'>
            <div class='card p-3'>
              <h5 class='fw-bold text-info'>{$row['title']}</h5>
              <p class='text-muted'>🗓 {$row['start_date']} to {$row['end_date']}</p>
              <a href='vote.php?election_id={$row['id']}' class='btn vote-btn mt-2'>Vote Now</a>
            </div>
          </div>";
        }
      ?>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>© <?php echo date("Y"); ?> Bangladesh Election Commission | All Rights Reserved</p>
  </div>

</body>
</html>
