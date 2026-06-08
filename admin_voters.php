<?php
// admin_voters.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php'; // Database connection

// Voters list fetch
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Voters - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
<div class="d-flex justify-content-between align-items-center mb-4">
     <img src="image/bec_logo.png" alt="BEC Logo" style="width:40px; margin-right:10px;">
    <h1 class="m-0"> Manage voter</h1>
    <a href="admin_panel.php" class="btn btn-secondary">⬅ Back to dashbord</a>
  </div>
    <div class="card shadow-lg">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>NID</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>Age</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($voters): ?>
                        <?php foreach ($voters as $voter): ?>
                            <tr>
                                <td><?= htmlspecialchars($voter['id']) ?></td>
                                <td><?= htmlspecialchars($voter['name']) ?></td>
                                <td><?= htmlspecialchars($voter['nid']) ?></td>
                                <td><?= htmlspecialchars($voter['email']) ?></td>
                                <td><?= htmlspecialchars($voter['dob']) ?></td>
                                <td><?= htmlspecialchars($voter['age']) ?></td>
                                <td><?= htmlspecialchars($voter['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No voters registered yet.</td>
                        </tr>
                         
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
 
</body>
</html>
