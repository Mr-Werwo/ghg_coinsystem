<?php
// Datenbankverbindung
$host = "localhost";
$dbname = "ghg_coin";
$username = "ghg_coin";  // Ändere das für deinen Server
$password = "123ghgCOIN";      // Ändere das für deinen Server

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}
?>
