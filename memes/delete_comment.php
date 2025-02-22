<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    die("Kein Zugriff!");
}

$comment_id = $_GET['comment_id'];
$pdo->prepare("DELETE FROM comments WHERE id = ?")->execute([$comment_id]);

header("Location: moderate.php");
exit();
?>
