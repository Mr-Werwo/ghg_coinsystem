<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $amount = intval($_POST['amount']);
    $description = $_POST['description'];

    // Coins aktualisieren
    $pdo->prepare("UPDATE users SET coins = coins + ? WHERE id = ?")->execute([$amount, $user_id]);

    // Transaktion speichern
    $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, ?)")
        ->execute([$user_id, $amount, $description]);

    echo "Transaktion erfolgreich!";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Coins übertragen</title>
</head>
<body>
    <h1>Coins überweisen</h1>
    <form action="" method="POST">
        <input type="number" name="user_id" placeholder="User-ID" required>
        <input type="number" name="amount" placeholder="Coins (+/-)" required>
        <input type="text" name="description" placeholder="Beschreibung" required>
        <button type="submit">Transaktion ausführen</button>
    </form>

    <a href="../admin/dashboard.php">🔙 Zurück zum Admin-Bereich</a>
</body>
</html>
