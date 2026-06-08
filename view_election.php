<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id=?"); 
$stmt->execute([$id]); 
$e=$stmt->fetch();
if (!$e) exit('Election not found.');
$cstmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id=?"); 
$cstmt->execute([$id]); 
$cands=$cstmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?=htmlspecialchars($e['title'])?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #004d00, #8b0000); /* Deep green to deep red */
      color: #fff;
    }
    .card {
      background-color: rgba(255, 255, 255, 0.9);
      color: #000;
      border-radius: 10px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
    }
    .btn-secondary {
      background-color: #333;
      border: none;
    }
    .btn-secondary:hover {
      background-color: #555;
    }
    .btn-success {
      background-color: #006400;
      border: none;
    }
    .btn-success:hover {
      background-color: #228B22;
    }
  </style>
</head>
<body class="p-4">
<div class="container">
  <h2><?=htmlspecialchars($e['title'])?></h2>
  <p><?=nl2br(htmlspecialchars($e['description']))?></p>
  <p><small><?=$e['start_date']?> to <?=$e['end_date']?></small></p>

  <div class="row">
    <?php foreach($cands as $c): ?>
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5><?=htmlspecialchars($c['name'])?></h5>
            <p><?=nl2br(htmlspecialchars($c['details']))?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php
  $today=date('Y-m-d'); 
  $status = ($today >= $e['start_date'] && $today <= $e['end_date']) ? 'Ongoing' : (($today < $e['start_date'])?'Upcoming':'Finished');
  if (isset($_SESSION['user_id']) && $status==='Ongoing') {
    $chk = $pdo->prepare("SELECT id FROM votes WHERE election_id=? AND user_id=?"); 
    $chk->execute([$e['id'], $_SESSION['user_id']]);
    if ($chk->fetch()) {
      echo '<div class="alert alert-info bg-dark text-white border-0">You have already voted in this election.</div>';
    } else {
      echo '<a class="btn btn-success" href="vote.php?eid='.$e['id'].'">Vote Now</a>';
    }
  }
  ?>
  <a class="btn btn-secondary" href="voter_dashboard.php">Back</a>
</div>
</body>
</html>
