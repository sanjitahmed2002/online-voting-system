<?php 
session_start(); 

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db.php';

// ---- Add Candidate ----
if (isset($_POST['add_candidate'])) {

    $name = $_POST['name'];
    $party = $_POST['party'];
    $election_id = $_POST['election_id'];

    $stmt = $pdo->prepare("
        INSERT INTO candidates (name, party, election_id) 
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$name, $party, $election_id]);

    $_SESSION['message'] = "✅ Candidate added successfully!";
    header("Location: admin_candidates.php");
    exit();
}

// ---- Delete Candidate ----
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM candidates WHERE id=?");
    $stmt->execute([$id]);

    $_SESSION['message'] = "🗑 Candidate deleted!";
    header("Location: admin_candidates.php");
    exit();
}

// ---- Search Candidate ----
$search = "";

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

    $stmt = $pdo->prepare("
        SELECT candidates.*, elections.title AS election_title
        FROM candidates
        LEFT JOIN elections 
        ON candidates.election_id = elections.id
        WHERE candidates.name LIKE ?
        ORDER BY candidates.created_at DESC
    ");

    $stmt->execute(["%$search%"]);

    $candidates = $stmt->fetchAll();

} else {

    // ---- Fetch All Candidates ----
    $stmt = $pdo->query("
        SELECT candidates.*, elections.title AS election_title
        FROM candidates
        LEFT JOIN elections 
        ON candidates.election_id = elections.id
        ORDER BY candidates.created_at DESC
    ");

    $candidates = $stmt->fetchAll();
}

// ---- Fetch All Elections ----
$stmt = $pdo->query("
    SELECT * FROM elections 
    ORDER BY created_at DESC
");

$elections = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Candidates</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #b8e3c1ff;
    }

    .container {
      margin-top: 40px;
    }

    .card {
      border-radius: 10px;
    }
  </style>
</head>

<body>

<div class="container">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">

    <div class="d-flex align-items-center">
      <img src="image/bec_logo.png" 
           alt="BEC Logo" 
           style="width:40px; margin-right:10px;">

      <h1 class="mb-0">👤 Manage Candidates</h1>
    </div>

    <a href="admin_panel.php" class="btn btn-secondary">
      ⬅ Back to Dashboard
    </a>

  </div>

  <!-- Message -->
  <?php if (isset($_SESSION['message'])): ?>

    <div class="alert alert-info">
      <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>

  <?php endif; ?>

  <!-- Add Candidate Form -->
  <div class="card mb-4">

    <div class="card-header">
      ➕ Add New Candidate
    </div>

    <div class="card-body">

      <form method="POST">

        <div class="mb-3">
          <label class="form-label">Candidate Name</label>

          <input type="text" 
                 name="name" 
                 class="form-control" 
                 required>
        </div>

        <div class="mb-3">
          <label class="form-label">Party</label>

          <input type="text" 
                 name="party" 
                 class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Election</label>

          <select name="election_id" 
                  class="form-control" 
                  required>

            <option value="">-- Select Election --</option>

            <?php foreach ($elections as $election): ?>

              <option value="<?= $election['id']; ?>">
                <?= htmlspecialchars($election['title']); ?>
              </option>

            <?php endforeach; ?>

          </select>
        </div>

        <button type="submit" 
                name="add_candidate" 
                class="btn btn-primary">

          Save Candidate

        </button>

      </form>

    </div>
  </div>

  <!-- Candidate List -->
  <div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

      <span>📋 All Candidates</span>

      <!-- Search Form -->
      <form method="GET" class="d-flex">

        <input type="text"
               name="search"
               class="form-control me-2"
               placeholder="Search candidate..."
               value="<?= htmlspecialchars($search); ?>">

        <button type="submit" class="btn btn-success">
          Search
        </button>

      </form>

    </div>

    <div class="card-body">

      <table class="table table-bordered table-hover">

        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Party</th>
          <th>Election</th>
          <th>Action</th>
        </tr>

        <?php if ($candidates): ?>

          <?php foreach ($candidates as $candidate): ?>

            <tr>

              <td><?= $candidate['id']; ?></td>

              <td><?= htmlspecialchars($candidate['name']); ?></td>

              <td><?= htmlspecialchars($candidate['party']); ?></td>

              <td><?= htmlspecialchars($candidate['election_title']); ?></td>

              <td>

                <!-- Edit Button -->
                <a href="edit_candidate.php?id=<?= $candidate['id']; ?>"
                   class="btn btn-warning btn-sm">

                  Edit

                </a>

                <!-- Delete Button -->
                <a href="admin_candidates.php?delete=<?= $candidate['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure?')">

                  Delete

                </a>

              </td>

            </tr>

          <?php endforeach; ?>

        <?php else: ?>

          <tr>
            <td colspan="5" class="text-center text-danger">
              No Candidate Found
            </td>
          </tr>

        <?php endif; ?>

      </table>

    </div>
  </div>

</div>

</body>
</html>