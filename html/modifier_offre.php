<?php
ob_start();
session_start();

include("recupInfosCompte.php");

if (isset($_GET["deco"])) {
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}
if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}

// Définir le filtre par défaut
$filter = "all";
if (isset($_GET["filter"])) {
    $filter = $_GET["filter"];
}

if(isset($_SESSION['aCreeUneOffre'])){
    unset($_SESSION['aCreeUneOffre']);
}
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Mes offres</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header-pc header_pro">
    <div class="logo">
    <a href="mes_offres.php">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </a>
    </div>
    <nav class="nav">
        <ul>
            <li><a href="mes_offres.php" class="active">Accueil</a></li>
            <li><a href="creation_offre.php">Publier</a></li>
            <li><a href="consulter_compte_pro.php">Mon Compte</a></li>
        </ul>
    </nav>
</header>
