<?php
session_start();
require('db.php');

if (!isset($_SESSION["auth"]) || $_SESSION["auth"] === false) {
    header("Location: index.php");
    exit();
}
$token = isset($_COOKIE["token"]) ? $_COOKIE["token"] : null;

$current_time = date('Y-m-d H:i:s');
$user_id = $_SESSION['user_id'];

if ($token) {
    $deleteTokenStmt = $conn->prepare("DELETE FROM tokens WHERE token = ?");
    $deleteTokenStmt->bind_param('s', $token);
    $deleteTokenStmt->execute();
    $deleteTokenStmt->close();
}

setcookie("token", "", time() - 3600, "/");

session_unset();
session_destroy();

header("Location: index.php");
exit();
?>
