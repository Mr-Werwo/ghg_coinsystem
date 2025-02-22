<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

// Status abrufen
$competition = $pdo->query("SELECT * FROM competition_status WHERE id = 1")->fetch();

// Wettbewerb stoppen
if (isset($_GET['stop'])) {
    $pdo->query("UPDATE competition_status SET is_active = FALSE WHERE id = 1");
    include '../coins/reward.php'; // Coins an Gewinner verteilen
    echo "Wettbewerb gestoppt und Gewinner belohnt!";
}



// Wettbewerb neu starten
if (isset($_GET['start'])) {
    $pdo->query("UPDATE competition_status SET is_active = TRUE WHERE id = 1");
    echo "Neuer Wettbewerb gestartet!";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meme-Wettbewerb Verwaltung</title>
</head>
<body>
    <h1>Meme-Wettbewerb Verwaltung</h1>

    <p>Status: <?= $competition['is_active'] ? "Aktiv 🟢" : "Gestoppt 🔴"; ?></p>

    <?php if ($competition['is_active']): ?>
        <a href="?stop=true">🚫 Wettbewerb stoppen</a>
    <?php else: ?>
        <a href="?start=true">✅ Neuen Wettbewerb starten</a>
    <?php endif; ?>

    <a href="../admin/dashboard.php">🔙 Zurück zum Admin-Bereich</a>
</body>
</html>
