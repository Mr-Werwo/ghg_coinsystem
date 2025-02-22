<?php
session_start();
require_once 'db.php';

// Benutzer einloggen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // **Hier richtige Weiterleitung setzen**
        header("Location: /ghg/public/index.php");
        exit();
    } else {
        echo "Falsche Login-Daten!";
    }
}

// Benutzer nur als Admin erstellen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    if ($_SESSION['role'] !== 'admin') {
        die("Nur Admins können Benutzer erstellen.");
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role']; // Nur admin kann Rollen setzen

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);

    echo "Benutzer erfolgreich erstellt!";
}
?>
