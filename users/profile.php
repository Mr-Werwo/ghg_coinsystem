<?php
session_start();
require_once '../includes/db.php';

if (!isset($_GET['user_id'])) {
    die("Kein Benutzer angegeben!");
}

$user_id = $_GET['user_id'];

// Benutzer abrufen
$user_stmt = $pdo->prepare("SELECT username, coins FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch();

if (!$user) {
    die("Benutzer nicht gefunden!");
}

// Memes des Users abrufen
$memes_stmt = $pdo->prepare("SELECT * FROM memes WHERE user_id = ? AND status = 'approved'");
$memes_stmt->execute([$user_id]);
$memes = $memes_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Profil von <?= htmlspecialchars($user['username']); ?></title>
</head>
<body>
    <h1>👤 Profil von <?= htmlspecialchars($user['username']); ?></h1>
    <p>💰 Coins: <?= $user['coins']; ?></p>

    <h2>📸 Gepostete Memes</h2>
    <div>
        <?php foreach ($memes as $meme): ?>
            <div>
                <img src="../memes/images/<?= $meme['filename']; ?>" width="200">
                <p><?= htmlspecialchars($meme['text']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="../public/index.php">🏠 Zurück</a>
</body>
</html>
