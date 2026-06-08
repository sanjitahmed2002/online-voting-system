 <?php
 
// db.php

$host = '127.0.0.1';
$db   = 'voting_db';
$user = 'root';
$pass = ''; // XAMPP default
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
  die('Database connection failed: ' . $e->getMessage());
}

// Auto-login with remember cookie (if session not set)
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    // find user with this token
    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE remember_token = ?");
    $stmt->execute([$token]);
    $u = $stmt->fetch();
    if ($u) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        // optionally refresh cookie expiry
        setcookie('remember_me', $token, time() + (30*24*60*60), "/"); // 30 days
    } else {
        // invalid token, clear cookie
        setcookie('remember_me', '', time() - 3600, "/");
    }
}
?>

