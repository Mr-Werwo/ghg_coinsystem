<?php
session_start();
require_once '../includes/db.php';

// Alle genehmigten Memes abrufen
$memes = $pdo->query("
    SELECT memes.*, users.username 
    FROM memes 
    JOIN users ON memes.user_id = users.id 
    WHERE memes.status = 'approved' 
    ORDER BY memes.likes DESC, memes.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meme-Wettbewerb</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/animations.js" defer></script>
</head>
<body>

    <!-- 🔹 Top-Navigation -->
    <nav class="top-bar">
        <a href="../public/index.php" class="btn-nav">🏠 Zurück zur Startseite</a>
        <?php
            if (isset($_SESSION['user_id'])) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM memes WHERE user_id = ? AND status != 'rejected'");
                $pdo->prepare("SELECT COUNT(*) FROM memes WHERE user_id = ? AND status = 'approved'");
                $stmt->execute([$_SESSION['user_id']]);
                $memeCount = $stmt->fetchColumn();

                if ($memeCount == 0): ?>
                    <a href="upload.php" class="btn-upload">📤 Meme hochladen</a>
                <?php else: ?>
                    <p>✅ Du hast bereits ein Meme hochgeladen.</p>
                <?php endif;
            }
            ?>
    </nav>

    <h1>📸 Meme-Wettbewerb</h1>
    <p>Vote für deine Lieblingsmemes!</p>

    <div class="meme-gallery">
        <?php foreach ($memes as $meme): ?>
            <div class="meme-card">
                <img src="images/<?= $meme['filename']; ?>" alt="Meme">
                <p><?= htmlspecialchars($meme['text']); ?></p>
                <p>Gepostet von: <a href="../users/profile.php?user_id=<?= $meme['user_id']; ?>"><?= htmlspecialchars($meme['username']); ?></a></p>
                
                <?php if ($meme['sound']): ?>
                    <audio controls>
                        <source src="sounds/<?= $meme['sound']; ?>" type="audio/mpeg">
                    </audio>
                <?php endif; ?>

                <div class="meme-actions">
                    <button onclick="likeMeme(<?= $meme['id']; ?>)">👍 Like</button>
                    <span id="likes-<?= $meme['id']; ?>"><?= $meme['likes']; ?></span> Likes
                </div>

                <!-- Kommentare anzeigen -->
                <div class="comments">
                    <h3>💬 Kommentare:</h3>
                    <div id="comments-<?= $meme['id']; ?>">
                        <?php
                        $stmt = $pdo->prepare("SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.meme_id = ?");
                        $stmt->execute([$meme['id']]);
                        $comments = $stmt->fetchAll();
                        foreach ($comments as $comment): ?>
                            <p><strong><?= htmlspecialchars($comment['username']); ?>:</strong> <?= htmlspecialchars($comment['comment']); ?></p>
                        <?php endforeach; ?>
                    </div>

                    <!-- Kommentarformular -->
                    <?php
                        if (isset($_SESSION['user_id'])) {
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE meme_id = ? AND user_id = ?");
                            $stmt->execute([$meme['id'], $_SESSION['user_id']]);
                            $commentCount = $stmt->fetchColumn();

                            if ($commentCount == 0): ?>
                                <form onsubmit="postComment(event, <?= $meme['id']; ?>)">
                                    <input type="text" id="comment-text-<?= $meme['id']; ?>" placeholder="Dein Kommentar" required>
                                    <button type="submit">Senden</button>
                                </form>
                            <?php else: ?>
                                <p>✅ Du hast bereits einen Kommentar zu diesem Meme geschrieben.</p>
                            <?php endif;
                        }
                        ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
