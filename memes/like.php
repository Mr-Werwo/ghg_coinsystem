<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Nicht eingeloggt!");
}

$user_id = $_SESSION['user_id'];
$meme_id = $_POST['meme_id'];

// Prüfen, ob der User bereits ein Like vergeben hat
$stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND meme_id = ?");
$stmt->execute([$user_id, $meme_id]);
$likeCount = $stmt->fetchColumn();

if ($likeCount == 0) {
    $pdo->prepare("INSERT INTO likes (user_id, meme_id) VALUES (?, ?)")->execute([$user_id, $meme_id]);
    $pdo->prepare("UPDATE memes SET likes = likes + 1 WHERE id = ?")->execute([$meme_id]);

    echo "✔ Like gespeichert!";
} else {
    echo "❌ Du hast bereits ein Meme geliked.";
}
?>
