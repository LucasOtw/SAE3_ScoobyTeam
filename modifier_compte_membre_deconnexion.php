<?php
session_start(); // Démarrer la session
session_destroy(); // Détruire toutes les données de session
header("Location: login.php"); // Rediriger l'utilisateur vers la page de connexion
exit();
?>
