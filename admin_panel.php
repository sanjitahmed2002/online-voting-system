<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Online Voting System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #0a3c13ff;
      font-family: 'Segoe UI', sans-serif;
      color: white;
    }
    .sidebar {
        background: linear-gradient(180deg, #1a5c3fff, #570d14ff);
        min-height: 100vh;
        padding: 20px;
        color: white;
        box-shadow: 2px 0 8px rgba(0,0,0,0.2);
    }
    .sidebar .logo {
        text-align: center;
        margin-bottom: 25px;
    }
    .sidebar .logo img {
        height: 70px;
        margin-bottom: 10px;
    }
    .sidebar h4 {
        font-weight: bold;
        margin-bottom: 20px;
    }
    .sidebar a {
        display: block;
        color: white;
        padding: 12px;
        margin-bottom: 8px;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }
    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(4px);
    }
    .content {
        padding: 50px;
        text-align: center;
    }
    .content h1 {
        font-size: 2.8rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .bec-logo-big {
        display: block;
        margin: 20px auto;
        max-width: 350px;
        opacity: 0.25; 
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <div class="logo">
        <img src="image/bec_logo.png" alt="BEC Logo">
        <h4>Admin Panel</h4>
      </div>
      <a href="admin_panel.php">📊 Dashboard</a>
      <a href="admin_elections.php">🗳 Manage Elections</a>
      <a href="admin_candidates.php">👤 Manage Candidates</a>
      <a href="admin_voters.php">🧑‍🤝‍🧑 Manage Voters</a>
      <a href="admin_results.php">📈 View Results</a>
      <a href="logout.php" class="text-danger">🚪 Logout</a>
    </div>

    <!-- Content -->
    <div class="col-md-10 content">
        <h1>👋 Welcome, Admin!</h1>
        <img src="image/bec_logo.png" alt="Bangladesh Election Commission" class="bec-logo-big">
        <p class="lead">Use the menu on the left to manage the Online Voting System efficiently.</p>
    </div>
  </div>
</div>
</body>
</html>
