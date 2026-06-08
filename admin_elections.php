<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db.php';

// ---- Add Election ----
if (isset($_POST['add_election'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $pdo->prepare("INSERT INTO elections (title, description, start_date, end_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $start_date, $end_date]);

    $_SESSION['message'] = "✅ Election added successfully!";
    header("Location: admin_elections.php");
    exit();
}

// ---- Delete Election ----
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM elections WHERE id=?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "🗑 Election deleted!";
    header("Location: admin_elections.php");
    exit();
}

// ---- Fetch All Elections ----
$stmt = $pdo->query("SELECT * FROM elections ORDER BY created_at DESC");
$elections = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Elections</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #aacfb6ff; }
    .container { margin-top: 40px; }
    .card { border-radius: 10px; }
  </style>
</head>
<body>
<div class="container">
  <!-- Header with Go Back Button -->
  <div class="d-flex justify-content-between align-items-center mb-4">
     <img src="image/bec_logo.png" alt="BEC Logo" style="width:40px; margin-right:10px;">
    <h1 class="m-0"> Manage Elections</h1>
    <a href="admin_panel.php" class="btn btn-secondary">⬅ Back to dashbord</a>
  </div>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
      <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <!-- Add Election Form -->
  <div class="card mb-4">
    <div class="card-header">➕ Add New Election</div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Election Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control" required>
        </div>
        <button type="submit" name="add_election" class="btn btn-primary">Save Election</button>
      </form>
    </div>
  </div>

  <!-- Election List -->
  <div class="card">
    <div class="card-header">📋 All Elections</div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Start</th>
          <th>End</th>
          <th>Action</th>
        </tr>
        <?php foreach ($elections as $election): ?>
          <tr>
            <td><?= $election['id']; ?></td>
            <td><?= htmlspecialchars($election['title']); ?></td>
            <td><?= $election['start_date']; ?></td>
            <td><?= $election['end_date']; ?></td>
            <td>
              <a href="admin_elections.php?delete=<?= $election['id']; ?>" 
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>
</body>
</html>


