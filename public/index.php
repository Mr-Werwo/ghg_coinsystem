<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GHG Coinsystem</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/animations.js" defer></script>
</head>
<body>

    <!-- 🔹 Navigation -->
    <nav>
        <ul>
            <li><a href="/ghg/memes/view.php">📸 Meme-Wettbewerb</a></li>
            <li><a href="/ghg/wetten/place.php">🎲 Wetten</a></li>
            <li><a href="/ghg/library.php">📂 Meme-Library</a></li>
            <li><a href="/ghg/profile.php">👤 Mein Profil</a></li>
            <?php if ($_SESSION['role'] == 'moderator' || $_SESSION['role'] == 'admin'): ?>
                <li><a href="/ghg/wetten/create.php">⚖ Wetten erstellen</a></li>
                <li><a href="/ghg/memes/moderate.php">🛠 Meme-Moderation</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li><a href="/ghg/admin/dashboard.php">🔧 Admin-Bereich</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- 🔹 Begrüßung -->
    <div class="container">
        <h1>Willkommen zum <span class="neon-text">GHG Coinsystem</span></h1>
        <p>Verdiene Coins durch Meme-Wettbewerbe & Wetten!</p>

        <!-- 🔹 Login & Logout -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="login-container">
                <h2>🔑 Login</h2>
                <form action="/ghg/includes/auth.php" method="POST">
                    <input type="text" name="username" placeholder="Benutzername" required>
                    <input type="password" name="password" placeholder="Passwort" required>
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        <?php else: ?>
            <p>Angemeldet als: <strong class="highlight"><?= $_SESSION['role']; ?></strong></p>
            <a href="/ghg/public/logout.php" class="btn-logout">🚪 Logout</a>
        <?php endif; ?>

        <!-- 🔹 Fehleranzeigen -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>

</body>
</html>
