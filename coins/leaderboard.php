<?php
session_start();
require_once '../includes/db.php';

// Top 10 User abrufen
$users = $pdo->query("SELECT username, coins FROM users ORDER BY coins DESC LIMIT 10")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Coins Leaderboard</title>
</head>
<body>
    <h1>🏆 Top 10 Reichste User</h1>

    <table border="1">
        <tr>
            <th>Benutzername</th>
            <th>Coins</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['username']; ?></td>
                <td><?= $user['coins']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="../public/index.php">🏠 Zurück zur Startseite</a>
</body>
</html>
