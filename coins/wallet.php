<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Bitte einloggen!");
}

$user_id = $_SESSION['user_id'];

// Coins abrufen
$user = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
$user->execute([$user_id]);
$coins = $user->fetchColumn();

// Transaktionsverlauf abrufen
$transactions = $pdo->prepare("SELECT amount, description, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$transactions->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meine Coins</title>
</head>
<body>
    <h1>💰 Mein Coins-Konto</h1>
    <p>Aktuelle Coins: <strong><?= $coins; ?></strong></p>

    <h2>Transaktionen:</h2>
    <table border="1">
        <tr>
            <th>Betrag</th>
            <th>Beschreibung</th>
            <th>Datum</th>
        </tr>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['amount']; ?> Coins</td>
                <td><?= $t['description']; ?></td>
                <td><?= $t['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="../public/index.php">🏠 Zurück zur Startseite</a>
</body>
</html>
