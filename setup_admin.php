<?php
require_once 'includes/db.php';

$password = password_hash("admin123", PASSWORD_BCRYPT);
$username = "admin";

$stmt = $pdo->prepare("INSERT INTO users (username, password, role, coins) VALUES (?, ?, 'admin', 1000)");
$stmt->execute([$username, $password]);

echo "Admin-Benutzer erfolgreich erstellt!";
?>
