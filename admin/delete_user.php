<?php
session_start();
require_once '../includes/db.php';

// Nur Admins dürfen löschen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

if (!isset($_GET['user_id'])) {
    die("Kein Benutzer ausgewählt!");
}

$user_id = $_GET['user_id'];

// Lösche den Benutzer
$pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);

header("Location: users.php?success=deleted");
exit();
?>
