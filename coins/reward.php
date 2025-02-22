<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

// Nur ausführen, wenn Wettbewerb gestoppt wurde
$competition = $pdo->query("SELECT is_active FROM competition_status WHERE id = 1")->fetch();
if ($competition['is_active']) {
    die("Der Wettbewerb läuft noch!");
}

// Top 3 Gewinner abrufen
$topMemes = $pdo->query("
    SELECT user_id, id FROM memes 
    WHERE status = 'approved' 
    ORDER BY likes DESC 
    LIMIT 3
")->fetchAll();

$rewards = [1 => 100, 2 => 50, 3 => 25]; // Coins für Platz 1, 2, 3

foreach ($topMemes as $index => $meme) {
    $place = $index + 1;
    $reward = $rewards[$place];

    // Coins vergeben
    $pdo->prepare("UPDATE users SET coins = coins + ? WHERE id = ?")->execute([$reward, $meme['user_id']]);

    // Transaktion speichern
    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")
        ->execute([$meme['user_id'], $reward, "Platz $place im Meme-Wettbewerb"]);
}

echo "Gewinner wurden belohnt!";
?>
