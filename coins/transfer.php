<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Bitte einloggen!");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_id = $_POST['recipient_id'];
    $amount = intval($_POST['amount']);

    // Eigene Coins prüfen
    $stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $coins = $stmt->fetchColumn();

    if ($coins < $amount || $amount <= 0) {
        die("Ungültige Menge!");
    }

    // Coins übertragen
    $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?")->execute([$amount, $user_id]);
    $pdo->prepare("UPDATE users SET coins = coins + ? WHERE id = ?")->execute([$amount, $recipient_id]);

    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, 'Coins gesendet')")
        ->execute([$user_id, -$amount]);
    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, 'Coins erhalten')")
        ->execute([$recipient_id, $amount]);

    echo "Coins gesendet!";
}
?>
