<?php
session_start();
require_once '../includes/db.php';

// Zugriff prüfen (nur Admins & Moderatoren)
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    die("Kein Zugriff!");
}

// Alle Memes abrufen (inkl. genehmigte & nicht genehmigte)
$memes = $pdo->query("
    SELECT memes.*, users.username 
    FROM memes 
    JOIN users ON memes.user_id = users.id 
    ORDER BY memes.status ASC, memes.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meme-Moderation</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/animations.js" defer></script>
</head>
<body>

    <h1>🛠 Meme-Moderation</h1>
    <p>Hier kannst du Memes genehmigen, ablehnen und Kommentare verwalten.</p>

    <div class="meme-gallery">
        <?php foreach ($memes as $meme): ?>
            <div class="meme-card">
                <img src="images/<?= $meme['filename']; ?>" alt="Meme">
                <p><?= htmlspecialchars($meme['text']); ?></p>
                <p>Gepostet von: <a href="../users/profile.php?user_id=<?= $meme['user_id']; ?>"><?= htmlspecialchars($meme['username']); ?></a></p>

                <!-- Status-Anzeige -->
                <p>Status: <?= ($meme['status'] == 'approved') ? "✅ Genehmigt" : "⏳ Ausstehend"; ?></p>

                <!-- Buttons für Moderation -->
                <div class="meme-actions">
                    <?php if ($meme['status'] == 'pending'): ?>
                        <a href="approve_meme.php?meme_id=<?= $meme['id']; ?>" class="btn-approve">✔ Genehmigen</a>
                        <a href="reject_meme.php?meme_id=<?= $meme['id']; ?>" class="btn-reject">❌ Ablehnen</a>
                    <?php endif; ?>
                    <a href="delete_meme.php?meme_id=<?= $meme['id']; ?>" class="btn-delete">🗑 Löschen</a>
                </div>

                <!-- Kommentare anzeigen & löschen -->
                <div class="comments">
                    <h3>💬 Kommentare:</h3>
                    <?php
                    $stmt = $pdo->prepare("SELECT comments.id, comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.meme_id = ?");
                    $stmt->execute([$meme['id']]);
                    $comments = $stmt->fetchAll();

                    foreach ($comments as $comment): ?>
                        <p><strong><?= htmlspecialchars($comment['username']); ?>:</strong> <?= htmlspecialchars($comment['comment']); ?>
                            <a href="delete_comment.php?comment_id=<?= $comment['id']; ?>" class="btn-delete-comment">🗑</a>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="../admin/dashboard.php" class="btn-nav">🔙 Zurück zum Admin-Dashboard</a>

</body>
</html>
