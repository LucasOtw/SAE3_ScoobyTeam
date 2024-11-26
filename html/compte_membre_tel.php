<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

include("recupInfosCompte.php");

// if(isset($_GET['logout'])){
//    session_unset();
//    session_destroy();
//    header('location: connexion_membre.php');
//    exit;
// }

// if(!isset($_SESSION['membre'])){
//    header('location: connexion_membre.php');
//    exit;
// }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Profil</title>
    <link rel="stylesheet" href="compte_membre_tel.css">
</head>
<body>
    <header class="header-tel">
        <img class="background" src="images/icones/image_fond_gris.png" alt="image de fond">
        <div class="logo-tel">
            <img src="images/LogoCouleur.png" alt="PACT Logo">
        </div>
        <div class="profile-info">
            <img src="images/icones/icone_compte.png" alt="Photo de profil" class="profile-img">
            <h1><?php echo $monCompteMembre['prenom']." ".$monCompteMembre['nom']; ?></h1>
            <p><?php echo $mesInfos['mail']; ?> | <?php echo trim(preg_replace('/(\d{2})/', '$1 ', $mesInfos['telephone'])); ?></p>
        </div>
    </header>

    <main class="main_compte_membre_tel">
        <div class="actions">
            <a href="consulter_compte_membre.php">
                <button class="action-button">
                    <img src="images/icones/crayon.png" alt="crayon">
                    Éditer les informations
                </button>
            </a>
            <a href="modif_mdp_membre.php">
                <button class="action-button">
                    <img src="images/icones/bouclier.png" alt="bouclier">
                    Modifier mon mot de passe
                </button>
            </a>
            <!-- <button class="action-button">📜 Historique</button> -->
            <a href="voir_offres.php?deco=true">
                <button class="action-button">
                    <img src="images/icones/power.png" alt="power">
                    Déconnexion
                </button>
            </a>
            <!-- <button class="action-button danger">🗑️ Supprimer mon compte</button> -->
        </div>
    </main>
    <nav class="nav-bar">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
            <?php
                if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                    echo "compte_membre_tel.php";
                } else {
                    echo "connexion_membre.php";
                }
            ?>">
            <img src="images/icones/icones/User icon.png" alt="image de Personne">
    </nav>
</body>
</html>
