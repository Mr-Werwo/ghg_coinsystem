<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    die("Kein Zugriff!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bet_id = $_POST['bet_id'];
    $winning_option = $_POST['winning_option'];

    // Gewinner abrufen
    $stmt = $pdo->prepare("SELECT * FROM bets WHERE id = ?");
    $stmt->execute([$bet_id]);
    $bet = $stmt->fetch();

    $odds = json_decode($bet['odds'], true);

    $entries = $pdo->prepare("SELECT user_id, amount FROM bet_entries WHERE bet_id = ? AND chosen_option = ?");
    $entries->execute([$bet_id, $winning_option]);
    $winners = $entries->fetchAll();

    foreach ($winners as $winner) {
        $reward = $winner['amount'] * $odds[$winning_option]; // Gewinn nach Quote
        $pdo->prepare("UPDATE users SET coins = coins + ? WHERE id = ?")->execute([$reward, $winner['user_id']]);
        $pdo->prepare("INSERT INTO transactions (user_id, amount, description) VALUES (?, ?, 'Wettgewinn')")
            ->execute([$winner['user_id'], $reward]);
    }

    $pdo->prepare("UPDATE bets SET status = 'closed', winning_option = ? WHERE id = ?")->execute([$winning_option, $bet_id]);

    echo "Wette geschlossen!";
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Wette abschließen</title>
</head>
<body>
    <h1>🎲 Wette abschließen</h1>

    <?php foreach ($bets as $bet): ?>
        <form action="" method="POST">
            <h2><?= $bet['event_name']; ?></h2>
            <input type="hidden" name="bet_id" value="<?= $bet['id']; ?>">
            <select name="winning_option">
                <?php foreach (json_decode($bet['outcome_options']) as $option): ?>
                    <option value="<?= $option; ?>"><?= $option; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Wette beenden</button>
        </form>
    <?php endforeach; ?>

    <a href="../admin/dashboard.php">🔙 Zurück</a>
</body>
</html>
