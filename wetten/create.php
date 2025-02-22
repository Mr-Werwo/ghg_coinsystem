<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')) {
    die("Kein Zugriff!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $options = explode(",", $_POST['options']);
    $encoded_options = json_encode($options);
    
    // Initiale Quoten alle auf 1.0 setzen
    $initial_odds = array_fill_keys($options, 1.0);
    $encoded_odds = json_encode($initial_odds);

    $pdo->prepare("INSERT INTO bets (event_name, outcome_options, odds) VALUES (?, ?, ?)")
        ->execute([$event_name, $encoded_options, $encoded_odds]);

    echo "Wette erfolgreich erstellt!";
}
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Wette erstellen</title>
</head>
<body>
    <h1>🎲 Neue Wette erstellen</h1>
    <form action="" method="POST">
        <input type="text" name="event_name" placeholder="Event-Name" required>
        <input type="text" name="options" placeholder="Optionen (Komma-getrennt)" required>
        <button type="submit">Wette erstellen</button>
    </form>

    <a href="../admin/dashboard.php">🔙 Zurück</a>
</body>
</html>
