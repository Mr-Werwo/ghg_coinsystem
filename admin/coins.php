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

    echo "Coins erfolgreich übertragen!";
}

// Alle User abrufen
$users = $pdo->query("SELECT id, username, coins FROM users ORDER BY username ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin: Coins verwalten</title>
</head>
<body>
    <h1>💰 Coinsystem Verwaltung</h1>

    <h2>Benutzern Coins senden</h2>
    <form action="" method="POST">
        <select name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id']; ?>"><?= $user['username']; ?> (<?= $user['coins']; ?> Coins)</option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="amount" placeholder="Coins (+/-)" required>
        <input type="text" name="description" placeholder="Beschreibung" required>
        <button type="submit">Senden</button>
    </form>

    <a href="dashboard.php">🔙 Zurück zum Admin-Dashboard</a>
</body>
</html>
