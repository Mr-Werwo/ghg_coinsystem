<?php
session_start();
require_once '../includes/db.php';

// Überprüfen, ob der Benutzer ein Admin ist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Kein Zugriff! Admin-Login erforderlich.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == "stop") {
        // Aktiven Wettbewerb beenden
        $stmt = $pdo->prepare("UPDATE competition_status SET is_active = 0 WHERE is_active = 1");
        $stmt->execute();
        header("Location: dashboard.php?success=stopped");
        exit();
    } elseif ($_POST['action'] == "restart") {
        // Alten Wettbewerb beenden
        $pdo->prepare("UPDATE competition_status SET is_active = 0 WHERE is_active = 1")->execute();

        // Neuen Wettbewerb starten
        $stmt = $pdo->prepare("INSERT INTO competition_status (is_active, start_time, end_time) VALUES (1, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY))");
        $stmt->execute();

        header("Location: dashboard.php?success=restarted");
        exit();
    }
}

header("Location: dashboard.php?error=invalid_request");
exit();
