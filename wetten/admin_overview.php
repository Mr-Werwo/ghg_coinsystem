<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    die("Kein Zugriff!");
}

// Offene & abgeschlossene Wetten abrufen
$open_bets = $pdo->query("SELECT * FROM bets WHERE status = 'open' ORDER BY created_at DESC")->fetchAll();
$closed_bets = $pdo->query("SELECT * FROM bets WHERE status = 'closed' ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Wettübersicht</title>
</head>
<body>
    <h1>📊 Wettübersicht</h1>

    <h2>🔵 Offene Wetten</h2>
    <table border="1">
        <tr>
            <th>Event</th>
            <th>Optionen</th>
            <th>Aktionen</th>
        </tr>
        <?php foreach ($open_bets as $bet): ?>
            <tr>
                <td><?= $bet['event_name']; ?></td>
                <td><?= implode(", ", json_decode($bet['outcome_options'])); ?></td>
                <td><a href="close.php?bet_id=<?= $bet['id']; ?>">❌ Wette schließen</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>✅ Abgeschlossene Wetten</h2>
    <table border="1">
        <tr>
            <th>Event</th>
            <th>Gewinner</th>
        </tr>
        <?php foreach ($closed_bets as $bet): ?>
            <tr>
                <td><?= $bet['event_name']; ?></td>
                <td><?= $bet['winning_option']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="../admin/dashboard.php">🔙 Zurück zum Admin-Dashboard</a>
</body>
</html>
