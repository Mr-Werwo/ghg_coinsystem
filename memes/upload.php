<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'viewer') {
    die("Kein Zugriff!");
}

// Upload-Handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $text = $_POST['text'];

    // Bild-Upload
    $imageFile = $_FILES['image'];
    $imagePath = "../memes/images/" . time() . "_" . basename($imageFile['name']);
    move_uploaded_file($imageFile['tmp_name'], $imagePath);

    // Sound-Upload (optional)
    $soundPath = null;
    if (!empty($_FILES['sound']['name'])) {
        $soundFile = $_FILES['sound'];
        $soundPath = "../memes/sounds/" . time() . "_" . basename($soundFile['name']);
        move_uploaded_file($soundFile['tmp_name'], $soundPath);
    }

    // Meme in die Datenbank einfügen
    $stmt = $pdo->prepare("INSERT INTO memes (user_id, filename, text, sound, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, basename($imagePath), $text, $soundPath ? basename($soundPath) : null]);

    // Prüfen, ob User bereits ein Meme hochgeladen hat
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM memes WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$_SESSION['user_id']]);
    $memeCount = $stmt->fetchColumn();

    if ($memeCount > 0) {
        die("❌ Du kannst nur ein Meme pro Wettbewerb hochladen!");
    }


    echo "Meme erfolgreich hochgeladen! Es muss noch genehmigt werden.";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meme hochladen</title>
</head>
<body>
    <h1>Meme hochladen</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="text" placeholder="Kurzer Text zum Meme" required>
        <input type="file" name="sound" accept="audio/*">  <!-- Optionaler Sound -->
        <button type="submit">Hochladen</button>
    </form>

    <a href="view.php">📜 Alle Memes ansehen</a>
</body>
</html>
