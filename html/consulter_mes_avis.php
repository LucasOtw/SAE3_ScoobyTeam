<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

if (!empty($_POST['supprAvis']){
    $suppressionAvis = $dbh->prepare('DELETE FROM tripenarvor._avis WHERE code_avis = :code_avis;');

    $suppressionAvis->bindValue(':code_avis', $_POST['supprAvis'], PDO::PARAM_INT);

    $suppressionAvis->execute();

    $_POST['supprAvis'] = [];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="consulter_mes_avis.css">
</head>

<body>
    <div class="header-membre">
        <header class="header-pc">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <!-- Static example: User is connected -->
                    <li>
                        <a href="consulter_compte_membre.php" class="active">Mon compte</a>
                    </li>
                    <!-- Uncomment this to simulate user not connected -->
                    <!--
                <li>
                    <a href="connexion_membre.php">Se connecter</a>
                </li>
                -->
                </ul>
            </nav>
        </header>
    </div>
    <main class="main_consulter_compte_membre">
        <!-- POUR PC/TABLETTE -->
        <div class="profile">
            <div class="banner">
                <img src="images/Rectangle 3.png" alt="Bannière" class="header-img">
            </div>

            <div class="profile-info">
                <img src="images/icones/icone_compte.png" alt="Photo de profil" class="profile-img">
                <h1>John Doe (johnny)</h1> <!-- Static example -->
                <p>john.doe@example.com | 06 12 34 56 78</p> <!-- Static example -->
            </div>
        </div>
        <!-- POUR TEL -->
        <div class="edit-profil">
            <a href="compte_membre_tel.php">
                <img src="images/Bouton_retour.png" alt="bouton retour">
            </a>
            <h1>Mes Avis</h1>
        </div>
        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_membre.php">Informations personnelles</a></li>
                <li><a href="modif_mdp_membre.php">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_avis" class="active">Historique</a></li>
                <!-- Uncomment if needed -->
                <!-- <li><a href="historique_membre.php">Historique</a></li> -->
            </ul>
        </section>
        <div class="avis-widget">
            <div class="avis-list">

                <?php

                $tout_les_avis = $dbh->prepare('SELECT * FROM tripenarvor._avis NATURAL JOIN tripenarvor.membre NATURAL JOIN tripenarvor._offre WHERE code_offre = :code_offre');

                $tout_les_avis->bindValue(':code_offre', intval($code_offre), PDO::PARAM_INT);

                $tout_les_avis->execute();
                $tout_les_avis = $tout_les_avis->fetchAll(PDO::FETCH_ASSOC);

                ?>
                <div class="avis">
                    <?php
                    foreach ($tout_les_avis as $avis) {
                        $appreciation = "";

                        switch ($avis["note"]) {
                            case '1':
                                $appreciation = "Insatisfaisant";
                                break;

                            case '2':
                                $appreciation = "Passable";<?php echo $avis["id"]; ?>
                                break;

                            case '3':
                                $appreciation = "Correct";
                                break;

                            case '4':
                                $appreciation = "Excellent";
                                break;

                            case '5':
                                $appreciation = "Parfait";
                                break;

                            default:
                                break;
                        }
                        ?>
                        <div class="avis">
                            <div class="avis-content">
                                <h3 class="avis"
                                    style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>
                                        <?php echo $avis["note"] . ".0 $appreciation "; ?> | <span
                                            class="nom_avis"><?php echo $avis["prenom"]; ?> <?php echo $avis["nom"]; ?>
                                            &nbsp;</span>| &nbsp;<span
                                            class="nom_visite"><?php echo $avis["titre_offre"]; ?></span>
                                    </span>
                                    <form method="POST" action="consulter_mes_avis.php">
                                        <input type="hidden" name="supprAvis" value="<?php echo htmlspecialchars($avis["code_avis"]); ?>">
                                        <input id="delete-btn" type="submit" name="delete-btn" class="delete-btn" title="Supprimer cet avis"><img src="images/trash.svg" alt="Supprimer" class="delete-icon">
                                    </form>
                                </h3>
                                <p class="avis"><?php echo $avis["txt_avis"]; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
            </div>
        </div>
        </div>
        </div>
    </main>
    <nav class="nav-bar">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="r
                <?php
                if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
                    echo "consulter_compte_membre.php";
                } else {
                    echo "connexion_membre.php";
                }
                ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>

</body>

</html>
