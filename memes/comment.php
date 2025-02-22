<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Bitte melde dich an, um zu kommentieren.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['meme_id'], $_POST['comment'])) {
    $meme_id = $_POST['meme_id'];
    $user_id = $_SESSION['user_id'];
    $comment = htmlspecialchars($_POST['comment']); // XSS-Schutz

    // Prüfen, ob der Benutzer bereits einen Kommentar zu diesem Meme geschrieben hat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE meme_id = ? AND user_id = ?");
    $stmt->execute([$meme_id, $user_id]);
    $commentCount = $stmt->fetchColumn();

    if ($commentCount > 0) {
        header("Location: view.php?error=already_commented");
        exit();
    }

    // Kommentar speichern
    $stmt = $pdo->prepare("INSERT INTO comments (meme_id, user_id, comment) VALUES (?, ?, ?)");
    if ($stmt->execute([$meme_id, $user_id, $comment])) {
        header("Location: view.php?success=comment_added");
        exit();
    } else {
        header("Location: view.php?error=comment_failed");
        exit();
    }
}
?>
