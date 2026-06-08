<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id=?"); 
$stmt->execute([$id]); 
$e = $stmt->fetch();
if (!$e) exit('Not found.');

$q = $pdo->prepare("SELECT c.id, c.name, COUNT(v.id) AS votes 
FROM candidates c 
LEFT JOIN votes v ON v.candidate_id=c.id 
WHERE c.election_id=? 
GROUP BY c.id 
ORDER BY votes DESC");
$q->execute([$id]); 
$rows=$q->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Results</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #064e3b, #7f1d1d); /* deep green + deep red gradient */
      color: #fff;
    }
    .table {
      background-color: #0f172a;
      color: #f8fafc;
      border-radius: 8px;
      overflow: hidden;
    }
    .table th {
      background-color: #14532d;
      color: #f1f5f9;
    }
    .table td {
      background-color: #1e293b;
      color: #e2e8f0;
    }
    .btn-secondary {
      background-color: #14532d;
      border-color: #14532d;
      color: #fff;
    }
    .btn-secondary:hover {
      background-color: #7f1d1d;
      border-color: #7f1d1d;
    }
    h2 {
      color: #f1f5f9;
    }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <h2>Results — <?=htmlspecialchars($e['title'])?></h2>
    <table class="table mt-3">
      <thead>
        <tr>
          <th>Candidate</th>
          <th>Votes</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?=htmlspecialchars($r['name'])?></td>
          <td><?=$r['votes']?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <a class="btn btn-secondary mt-3" href="voter_dashboard.php">⬅ Back</a>
  </div>
</body>
</html>
