<?php
session_start();
require_once '../includes/db.php';

// Admin-Berechtigung prüfen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("⚠ Kein Zugriff! Admin-Login erforderlich.");
}

try {
    // Wettbewerb abrufen
    $competition = $pdo->query("SELECT * FROM competition_status WHERE is_active = 1 LIMIT 1")->fetch();

    // Memes zählen
    $memes_count = $pdo->query("SELECT COUNT(*) FROM memes WHERE status = 'approved'")->fetchColumn();

    // Top 3 Memes abrufen
    $top_memes = $pdo->query("SELECT filename, likes FROM memes WHERE status = 'approved' ORDER BY likes DESC LIMIT 3")->fetchAll();

    // Wetten abrufen
    $latest_bets = $pdo->query("SELECT * FROM bets ORDER BY created_at DESC LIMIT 3")->fetchAll();

    // Aktive Nutzer abrufen
    $active_users = $pdo->query("SELECT username, role, coins FROM users ORDER BY last_active DESC LIMIT 5")->fetchAll();

} catch (Exception $e) {
    die("❌ Fehler: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <!-- 🔹 Seitenlayout -->
    <div class="admin-container">
        <!-- 🔹 Seitenleiste -->
        <nav class="sidebar">
            <h2>Admin-Menü</h2>
            <ul>
                <li><a href="users.php">👤 Benutzerverwaltung</a></li>
                <li><a href="moderate.php">🛠 Meme-Moderation</a></li>
                <li><a href="manage_bets.php">🎲 Wetten verwalten</a></li>
                <li><a href="../public/index.php">🏠 Zurück zur Startseite</a></li>
            </ul>
        </nav>

        <!-- 🔹 Hauptbereich -->
        <main class="content">
            <h1>📊 Admin Dashboard</h1>

            <!-- 🔹 Meme-Wettbewerb -->
            <section class="dashboard-box">
                <h2>📸 Aktueller Meme-Wettbewerb</h2>
                <p>Wettbewerb endet in: <?= isset($competition['end_time']) ? $competition['end_time'] : "Kein aktiver Wettbewerb"; ?></p>
                <p>Memes eingereicht: <?= $memes_count; ?></p>

                <h3>🏆 Top 3 Memes:</h3>
                <ul>
                    <?php foreach ($top_memes as $meme): ?>
                        <li><img src="../memes/images/<?= $meme['filename']; ?>" width="50"> - <?= $meme['likes']; ?> Likes</li>
                    <?php endforeach; ?>
                </ul>

                <form action="manage_meme.php" method="POST">
                    <button type="submit" name="action" value="stop">🛑 Wettbewerb stoppen</button>
                    <button type="submit" name="action" value="restart">🔄 Neuen Wettbewerb starten</button>
                </form>
            </section>

            <!-- 🔹 Wetten -->
            <section class="dashboard-box">
                <h2>🎲 Aktuelle Wetten</h2>
                <ul>
                    <?php foreach ($latest_bets as $bet): ?>
                        <li><?= htmlspecialchars($bet['description']); ?> - Einsatz: <?= $bet['bet_amount']; ?> Coins</li>
                    <?php endforeach; ?>
                </ul>
                <a href="manage_bets.php" class="btn">➕ Neue Wette erstellen</a>
            </section>
        </main>

        <!-- 🔹 Aktive Nutzer -->
        <aside class="user-list">
            <h2>👥 Aktive Nutzer</h2>
            <ul>
                <?php foreach ($active_users as $user): ?>
                    <li><?= htmlspecialchars($user['username']); ?> - <?= ucfirst($user['role']); ?> (💰 <?= $user['coins']; ?>)</li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>

</body>
</html>
