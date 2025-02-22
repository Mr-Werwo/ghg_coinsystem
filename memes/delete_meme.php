<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

$meme_id = $_GET['meme_id'];
$pdo->prepare("DELETE FROM memes WHERE id = ?")->execute([$meme_id]);

header("Location: moderate.php");
exit();
?>
