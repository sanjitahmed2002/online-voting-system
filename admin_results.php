<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT c.name AS candidate, c.party, e.title AS election, COUNT(v.id) AS votes
    FROM votes v
    JOIN candidates c ON v.candidate_id = c.id
    JOIN elections e ON v.election_id = e.id
    GROUP BY c.id, e.id
    ORDER BY votes DESC
");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Election Results - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-4">
     <img src="image/bec_logo.png" alt="BEC Logo" style="width:40px; margin-right:10px;">
       <h1 class="mb-4">📊 Election Results</h1>
    <a href="admin_panel.php" class="btn btn-secondary">⬅ Back to dashbord</a>
  </div>

  <div class="card shadow-lg">
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>Candidate</th>
            <th>Party</th>
            <th>Election</th>
            <th>Total Votes</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['candidate']) ?></td>
            <td><?= htmlspecialchars($row['party']) ?></td>
            <td><?= htmlspecialchars($row['election']) ?></td>
            <td><?= htmlspecialchars($row['votes']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>