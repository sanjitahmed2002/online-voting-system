<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

require 'db.php';

// Candidate ID check
if (!isset($_GET['id'])) {
    header("Location: admin_candidates.php");
    exit();
}

$id = $_GET['id'];

// Fetch candidate data
$stmt = $pdo->prepare("SELECT * FROM candidates WHERE id=?");
$stmt->execute([$id]);
$candidate = $stmt->fetch();

if (!$candidate) {
    die("Candidate not found!");
}

// Fetch elections
$stmt = $pdo->query("SELECT * FROM elections ORDER BY created_at DESC");
$elections = $stmt->fetchAll();

// Update candidate
if (isset($_POST['update_candidate'])) {

    $name = $_POST['name'];
    $party = $_POST['party'];
    $election_id = $_POST['election_id'];

    $stmt = $pdo->prepare("
        UPDATE candidates
        SET name=?, party=?, election_id=?
        WHERE id=?
    ");

    $stmt->execute([$name, $party, $election_id, $id]);

    $_SESSION['message'] = "✅ Candidate updated successfully!";

    header("Location: admin_candidates.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Candidate</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: rgb(4, 67, 18);
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <img src="image/bec_logo.png" alt="BEC Logo" style="width:40px; margin-right:10px;">
    <h1>Edit Candidate</h1>

    <a href="admin_candidates.php" class="btn btn-secondary">
        ⬅ Back
    </a>
</div>

<div class="card">
<div class="card-header">
✏ Update Candidate
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label class="form-label">Candidate Name</label>

<input type="text"
       name="name"
       class="form-control"
       value="<?= htmlspecialchars($candidate['name']); ?>"
       required>
</div>

<div class="mb-3">
<label class="form-label">Party</label>

<input type="text"
       name="party"
       class="form-control"
       value="<?= htmlspecialchars($candidate['party']); ?>">
</div>

<div class="mb-3">
<label class="form-label">Election</label>

<select name="election_id" class="form-control" required>

<?php foreach ($elections as $election): ?>

<option value="<?= $election['id']; ?>"

<?php
if ($candidate['election_id'] == $election['id']) {
    echo "selected";
}
?>

>
<?= htmlspecialchars($election['title']); ?>

</option>

<?php endforeach; ?>

</select>
</div>

<button type="submit"
        name="update_candidate"
        class="btn btn-primary">

Update Candidate

</button>

</form>

</div>
</div>
</div>

</body>
</html>