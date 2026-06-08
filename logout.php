 <?php
require 'db.php';
if (isset($_SESSION['user_id'])) {
    // remove remember_token from DB
    $upd = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $upd->execute([$_SESSION['user_id']]);
}
// clear cookie and session
setcookie('remember_me', '', time() - 3600, "/");
session_unset();
session_destroy();
header('Location: index.php');
exit;
