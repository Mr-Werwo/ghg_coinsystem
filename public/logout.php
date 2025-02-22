<?php
session_start();
session_unset(); // Alle Session-Variablen löschen
session_destroy(); // Die Session beenden

// Weiterleitung zur Startseite
header("Location: /ghg/public/index.php");
exit();
?>
