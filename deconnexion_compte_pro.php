<?php
session_start();
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session active
header("Location: connexion_pro.php"); // Redirection vers la page de connexion
exit();
?>
