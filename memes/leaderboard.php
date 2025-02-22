<?php
session_start();
require_once '../includes/db.php';

// Top 3 Memes der Woche abrufen
$topMemes = $pdo->query("
    SELECT * FROM memes 
    WHERE status = 'approved' 
    ORDER BY likes DESC 
    LIMIT 3
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard - Meme-Wettbewerb</title>
</head>
<body>
    <h1>🏆 Meme Leaderboard (Top 3 der Woche)</h1>

    <?php foreach ($topMemes as $meme): ?>
        <div>
            <img src="images/<?= $meme['filename']; ?>" width="300">
            <p><?= $meme['text']; ?></p>
            <p>👍 <?= $meme['likes']; ?> Likes</p>
        </div>
    <?php endforeach; ?>

    <a href="view.php">🔙 Zurück zu allen Memes</a>
</body>
</html>
