<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

// Alle Transaktionen abrufen
$transactions = $pdo->query("
    SELECT transactions.amount, transactions.description, transactions.created_at, users.username 
    FROM transactions 
    JOIN users ON transactions.user_id = users.id 
    ORDER BY transactions.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin: Transaktionen</title>
</head>
<body>
    <h1>💳 Alle Transaktionen</h1>

    <table border="1">
        <tr>
            <th>Benutzer</th>
            <th>Betrag</th>
            <th>Beschreibung</th>
            <th>Datum</th>
        </tr>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['username']; ?></td>
                <td><?= $t['amount']; ?> Coins</td>
                <td><?= $t['description']; ?></td>
                <td><?= $t['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">🔙 Zurück zum Admin-Dashboard</a>
</body>
</html>
