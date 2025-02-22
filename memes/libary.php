<?php
session_start();
require_once '../includes/db.php';

// Alle genehmigten Memes abrufen
$memes_stmt = $pdo->query("SELECT memes.*, users.username FROM memes 
                           JOIN users ON memes.user_id = users.id 
                           WHERE status = 'approved' 
                           ORDER BY id DESC");
$memes = $memes_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meme-Library</title>
</head>
<body>
    <h1>📂 Meme-Library</h1>

    <div>
        <?php foreach ($memes as $meme): ?>
            <div>
                <img src="images/<?= $meme['filename']; ?>" width="200">
                <p><?= htmlspecialchars($meme['text']); ?></p>
                <p>Gepostet von: <a href="../users/profile.php?user_id=<?= $meme['user_id']; ?>">
                    <?= htmlspecialchars($meme['username']); ?>
                </a></p>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="../public/index.php">🏠 Zurück</a>
</body>
</html>
