<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Bitte einloggen!");
}

$user_id = $_SESSION['user_id'];

// Wetten des Users abrufen
$stmt = $pdo->prepare("
    SELECT bets.event_name, bet_entries.chosen_option, bet_entries.amount, bets.winning_option, bets.status
    FROM bet_entries 
    JOIN bets ON bet_entries.bet_id = bets.id
    WHERE bet_entries.user_id = ?
    ORDER BY bets.created_at DESC
");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mein Wettverlauf</title>
</head>
<body>
    <h1>🎲 Mein Wettverlauf</h1>

    <table border="1">
        <tr>
            <th>Event</th>
            <th>Meine Wahl</th>
            <th>Gesetzte Coins</th>
            <th>Ergebnis</th>
        </tr>
        <?php foreach ($history as $bet): ?>
            <tr>
                <td><?= $bet['event_name']; ?></td>
                <td><?= $bet['chosen_option']; ?></td>
                <td><?= $bet['amount']; ?> Coins</td>
                <td>
                    <?php if ($bet['status'] == 'open'): ?>
                        ⏳ Läuft noch
                    <?php elseif ($bet['winning_option'] == $bet['chosen_option']): ?>
                        ✅ Gewonnen!
                    <?php else: ?>
                        ❌ Verloren
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="../public/index.php">🏠 Zurück</a>
</body>
</html>
