<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    die("Kein Zugriff!");
}

$meme_id = $_GET['meme_id'];
$pdo->prepare("UPDATE memes SET status = 'approved' WHERE id = ?")->execute([$meme_id]);

header("Location: moderate.php");
exit();
?>
