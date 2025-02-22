<?php
session_start();
require_once '../includes/db.php';

// Nur Admins haben Zugriff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

if (!isset($_GET['user_id'])) {
    die("Kein Benutzer ausgewählt!");
}

$user_id = $_GET['user_id'];
$stmt = $pdo->prepare("SELECT id, username, role, coins FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Benutzer nicht gefunden!");
}

// Benutzer aktualisieren
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_role = $_POST['role'];
    $new_coins = $_POST['coins'];

    $update = $pdo->prepare("UPDATE users SET role = ?, coins = ? WHERE id = ?");
    $update->execute([$new_role, $new_coins, $user_id]);

    header("Location: users.php?success=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzer bearbeiten</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <h1>✏ Benutzer bearbeiten</h1>
    <form action="" method="POST">
        <label>Benutzername:</label>
        <input type="text" value="<?= htmlspecialchars($user['username']); ?>" disabled>

        <label>Rolle:</label>
        <select name="role">
            <option value="viewer" <?= ($user['role'] == 'viewer') ? 'selected' : ''; ?>>Viewer</option>
            <option value="user" <?= ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
            <option value="moderator" <?= ($user['role'] == 'moderator') ? 'selected' : ''; ?>>Moderator</option>
            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>

        <label>Coins:</label>
        <input type="number" name="coins" value="<?= $user['coins']; ?>" required>

        <button type="submit" class="btn-edit">✔ Speichern</button>
    </form>

    <a href="users.php" class="btn-nav">🔙 Zurück</a>

</body>
</html>
