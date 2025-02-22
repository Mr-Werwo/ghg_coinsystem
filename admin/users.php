<?php
session_start();
require_once '../includes/db.php';

// Nur Admins haben Zugriff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff!");
}

// Alle Benutzer abrufen
$users = $pdo->query("SELECT id, username, role, coins FROM users ORDER BY role DESC, id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h1>👤 Benutzerverwaltung</h1>
    <p>Hier kannst du Benutzerrollen verwalten und Benutzer bearbeiten oder löschen.</p>

    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Benutzername</th>
                <th>Rolle</th>
                <th>Coins</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id']; ?></td>
                    <td><?= htmlspecialchars($user['username']); ?></td>
                    <td><?= ucfirst($user['role']); ?></td>
                    <td><?= $user['coins']; ?></td>
                    <td>
                        <a href="edit_user.php?user_id=<?= $user['id']; ?>" class="btn-edit">✏ Bearbeiten</a>
                        <a href="delete_user.php?user_id=<?= $user['id']; ?>" class="btn-delete">🗑 Löschen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn-nav">🔙 Zurück zum Admin-Dashboard</a>

</body>
</html>
