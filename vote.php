 <?php 
require 'db.php'; 
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; } 
$user = intval($_SESSION['user_id']); 
$eid = intval($_GET['eid'] ?? $_POST['eid'] ?? 0); 
if ($eid <= 0) exit('Invalid election.');  

$st = $pdo->prepare("SELECT * FROM elections WHERE id=?"); 
$st->execute([$eid]); 
$e=$st->fetch(); 
if (!$e) exit('Election not found.'); 

$today = date('Y-m-d'); 
if (!($today >= $e['start_date'] && $today <= $e['end_date'])) exit('This election is not active.');  

$chk = $pdo->prepare("SELECT id FROM votes WHERE election_id=? AND user_id=?"); 
$chk->execute([$eid,$user]); 
$already = (bool)$chk->fetch();  

$msg=''; 
if ($_SERVER['REQUEST_METHOD']==='POST' && !$already) {   
  $cid = intval($_POST['candidate_id'] ?? 0);   
  if ($cid<=0) $msg='Please select a candidate.';   
  else {     
    // verify candidate belongs to election     
    $cchk = $pdo->prepare("SELECT id FROM candidates WHERE id=? AND election_id=?"); 
    $cchk->execute([$cid,$eid]);     
    if (!$cchk->fetch()) $msg='Invalid candidate.';     
    else {       
      try {         
        $ins=$pdo->prepare("INSERT INTO votes (election_id,candidate_id,user_id) VALUES (?,?,?)");         
        $ins->execute([$eid,$cid,$user]);         
        $msg='Vote cast successfully.';         
        $already = true;       
      } catch (PDOException $ex) {         
        if ($ex->getCode()==='23000') $msg='You have already voted.';         
        else $msg='Database error.';       
      }     
    }   
  } 
}  

$cstmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id=?"); 
$cstmt->execute([$eid]); 
$cands=$cstmt->fetchAll(); 
?> 

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Vote</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #064e3b, #7f1d1d); /* deep green + deep red gradient */
      color: #fff;
    }
    .card, .alert, form {
      background-color: #0f172a; 
      border: 1px solid #7f1d1d;
      padding: 15px;
      border-radius: 8px;
    }
    .btn-primary {
      background-color: #064e3b;
      border-color: #064e3b;
    }
    .btn-success {
      background-color: #7f1d1d;
      border-color: #7f1d1d;
    }
    .btn-secondary {
      background-color: #14532d;
      border-color: #14532d;
    }
    h2, p, label {
      color: #f1f5f9;
    }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <h2>Vote — <?=htmlspecialchars($e['title'])?></h2>
    <p><?=htmlspecialchars($e['description'])?></p>
    <?php if ($msg) echo '<div class="alert alert-info">'.htmlspecialchars($msg).'</div>'; ?>
    <?php if ($already): ?>
      <div class="alert alert-secondary">You have already voted.</div>
      <a class="btn btn-primary" href="results.php?id=<?=$eid?>">View Results</a>
    <?php else: ?>
      <form method="post">
        <input type="hidden" name="eid" value="<?=$eid?>">
        <?php foreach ($cands as $c): ?>
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="candidate_id" id="c<?=$c['id']?>" value="<?=$c['id']?>" required>
            <label class="form-check-label" for="c<?=$c['id']?>"><?=htmlspecialchars($c['name'])?></label>
          </div>
        <?php endforeach; ?>
        <button class="btn btn-success mt-2">Submit Vote</button>
        <a class="btn btn-secondary" href="voter_dashboard.php">Back</a>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
