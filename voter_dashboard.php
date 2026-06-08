  <?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$uid = $_SESSION['user_id'];
$elections = $pdo->query("SELECT * FROM elections ORDER BY start_date DESC")->fetchAll();

// history
$hist = $pdo->prepare("SELECT v.*, c.name AS candidate_name, e.title AS election_title FROM votes v
 JOIN candidates c ON c.id=v.candidate_id
 JOIN elections e ON e.id=v.election_id
 WHERE v.user_id=? ORDER BY v.voted_at DESC");
$hist->execute([$uid]); $history = $hist->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #064e3b, #7f1d1d); /* deep green + deep red gradient */
      color: #fff;
    }
    .card {
      background-color: #0f172a; /* dark navy for contrast */
      border: 1px solid #7f1d1d; /* deep red border */
    }
    .card h5, .card p, small, table {
      color: #f1f5f9; /* light text */
    }
    .btn-primary {
      background-color: #064e3b;
      border-color: #064e3b;
    }
    .btn-success {
      background-color: #7f1d1d;
      border-color: #7f1d1d;
    }
    .btn-link {
      color: #f87171;
    }
    .btn-outline-secondary {
      border-color: #7f1d1d;
      color: #fff;
    }
    .btn-outline-secondary:hover {
      background-color: #7f1d1d;
      color: #fff;
    }
    .table thead {
      background-color: #7f1d1d;
      color: #fff;
    }
    .table tbody tr {
      background-color: #14532d; /* deep green */
    }
  </style>
</head>
<body class="p-4">
<div class="container">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Welcome, <?=htmlspecialchars($_SESSION['user_name'])?></h2>
    <a class="btn btn-outline-secondary" href="logout.php">Logout</a>
  </div>
  <hr>
  <h4>All Elections</h4>
  <div class="row">
  <?php foreach($elections as $e):
    $today = date('Y-m-d');
    $status = ($today >= $e['start_date'] && $today <= $e['end_date']) ? 'Ongoing' : (($today < $e['start_date'])?'Upcoming':'Finished');
    $chk = $pdo->prepare("SELECT id FROM votes WHERE election_id=? AND user_id=?");
    $chk->execute([$e['id'],$uid]); 
    $voted=(bool)$chk->fetch();
  ?>
    <div class="col-md-6">
      <div class="card mb-3 shadow">
        <div class="card-body">
          <h5><?=htmlspecialchars($e['title'])?></h5>
          <p><?=htmlspecialchars($e['description'])?></p>
          <small><?=$e['start_date']?> to <?=$e['end_date']?> — <strong><?=$status?></strong></small>
          <div class="mt-2">
            <a class="btn btn-primary" href="view_election.php?id=<?=$e['id']?>">View</a>
            <?php if ($status === 'Ongoing'): ?>
              <?php if ($voted): ?>
                <button class="btn btn-secondary" disabled>Voted</button>
              <?php else: ?>
                <a class="btn btn-success" href="vote.php?eid=<?=$e['id']?>">Vote</a>
              <?php endif; ?>
            <?php endif; ?>
            <a class="btn btn-link" href="results.php?id=<?=$e['id']?>">Results</a>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>

  <h4>Your Voting History</h4>
  <?php if (!$history) echo '<div class="alert alert-info bg-dark text-light border-0">No history yet.</div>'; else { ?>
    <table class="table table-bordered">
      <thead>
        <tr><th>Election</th><th>Candidate</th><th>Voted At</th></tr>
      </thead>
      <tbody>
      <?php foreach ($history as $h): ?>
        <tr>
          <td><?=htmlspecialchars($h['election_title'])?></td>
          <td><?=htmlspecialchars($h['candidate_name'])?></td>
          <td><?=$h['voted_at']?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php } ?>
</div>
</body>
</html>
