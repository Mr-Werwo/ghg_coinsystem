<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

// Meme genehmigen oder ablehnen
if (isset($_GET['approve_id'])) {
    $stmt = $pdo->prepare("UPDATE memes SET status = 'approved' WHERE id = ?");
    $stmt->execute([$_GET['approve_id']]);
}
if (isset($_GET['reject_id'])) {
    $stmt = $pdo->prepare("UPDATE memes SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$_GET['reject_id']]);
}

// Alle ungelösten Memes abrufen
$memes = $pdo->query("SELECT * FROM memes WHERE status = 'pending'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meme-Moderation</title>
</head>
<body>
    <h1>Meme-Moderation</h1>

    <h2>Wartende Memes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Bild</th>
            <th>Text</th>
            <th>Aktionen</th>
        </tr>
        <?php foreach ($memes as $meme): ?>
            <tr>
                <td><?= $meme['id']; ?></td>
                <td><img src="../uploads/<?= $meme['filename']; ?>" width="100"></td>
                <td><?= $meme['text']; ?></td>
                <td>
                    <a href="?approve_id=<?= $meme['id']; ?>">✅ Genehmigen</a>
                    <a href="?reject_id=<?= $meme['id']; ?>" style="color: red;">❌ Ablehnen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">🔙 Zurück zum Admin-Dashboard</a>
</body>
</html>
