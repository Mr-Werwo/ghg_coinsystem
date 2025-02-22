<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Bitte einloggen!");
}

$user_id = $_SESSION['user_id'];

// Offene Wetten abrufen
$bets = $pdo->query("SELECT * FROM bets WHERE status = 'open'")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bet_id = $_POST['bet_id'];
    $chosen_option = $_POST['chosen_option'];
    $amount = intval($_POST['amount']);

    // User Coins abrufen
    $stmt = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $coins = $stmt->fetchColumn();

    if ($coins < $amount) {
        die("Nicht genug Coins!");
    }

    // Coins abziehen
    $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?")->execute([$amount, $user_id]);

    // Einsatz speichern
    $pdo->prepare("INSERT INTO bet_entries (bet_id, user_id, chosen_option, amount) VALUES (?, ?, ?, ?)")
        ->execute([$bet_id, $user_id, $chosen_option, $amount]);

    // Quoten berechnen
    $stmt = $pdo->prepare("SELECT * FROM bets WHERE id = ?");
    $stmt->execute([$bet_id]);
    $bet = $stmt->fetch();

    $options = json_decode($bet['outcome_options'], true);
    $current_odds = json_decode($bet['odds'], true);

    // Alle Einsätze pro Option summieren
    $bet_entries = $pdo->prepare("SELECT chosen_option, SUM(amount) AS total FROM bet_entries WHERE bet_id = ? GROUP BY chosen_option");
    $bet_entries->execute([$bet_id]);
    $totals = $bet_entries->fetchAll(PDO::FETCH_KEY_PAIR);

    $total_pot = array_sum($totals);

    // Neue Quoten berechnen (Gesamtpot / gesetzter Betrag pro Option)
    foreach ($options as $option) {
        $option_total = $totals[$option] ?? 1; // Falls niemand gesetzt hat, Standardwert 1
        $current_odds[$option] = round($total_pot / $option_total, 2);
    }

    // Aktualisierte Quoten speichern
    $pdo->prepare("UPDATE bets SET odds = ? WHERE id = ?")->execute([json_encode($current_odds), $bet_id]);

    echo "Wette platziert!";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Wette platzieren</title>
</head>
<body>
    <h1>🎲 Wetten platzieren</h1>

    <?php foreach ($bets as $bet): ?>
        <form action="" method="POST">
            <h2><?= $bet['event_name']; ?></h2>
            <input type="hidden" name="bet_id" value="<?= $bet['id']; ?>">
            <select name="chosen_option">
                <?php foreach (json_decode($bet['outcome_options']) as $option): ?>
                    <option value="<?= $option; ?>"><?= $option; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="amount" placeholder="Coins setzen" required>
            <button type="submit">Wette platzieren</button>
        </form>
    <?php endforeach; ?>

    <a href="../public/index.php">🏠 Zurück</a>
</body>
</html>
