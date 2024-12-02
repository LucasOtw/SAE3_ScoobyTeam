<?php
    ob_start(); // bufferisation, ça devrait marcher ?
    session_start();

include("recupInfosCompte.php");

if(isset($_GET['logout'])){
   session_unset();
   session_destroy();
   header('location: connexion_membre.php');
   exit;
}

if(!isset($_SESSION['membre'])){
   header('location: connexion_membre.php');
   exit;
}

if (!empty($_POST['supprAvis'])){
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
    <title>Mes Avis</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header_membre">
        <header class="header-pc">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                        if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                        ?>
                        <li>
                            <a href="consulter_compte_membre.php" class="active">Mon compte</a>
                        </li>
                            <?php
                        } else {
                            ?>
                        <li>
                            <a href="connexion_membre.php">Se connecter</a>
                        </li>
                        <?php
                        }
                    ?>
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
                <h1><?php echo $monCompteMembre['prenom']." ".$monCompteMembre['nom']." (".$monCompteMembre['pseudo'].")"; ?></h1>
                <p><?php echo $compte['mail']; ?> | <?php echo trim(preg_replace('/(\d{2})/', '$1 ', $compte['telephone'])); ?></p>
            </div>
        </div>
<!-- POUR TEL -->
<style>
    .edit-profil {
        display: none;
    }
</style>

<div class="edit-profil">
    <a href="compte_membre_tel.php">
        <img src="images/Bouton_retour.png" alt="bouton retour">
    </a>
    <h1>Editer le profil</h1>
</div>           
            <section class="tabs">
                <ul>
                    <li><a href="consulter_compte_membre.php">Informations personnelles</a></li>
                    <li><a href="modif_mdp_membre.php">Mot de passe et sécurité</a></li>
                    <li><a href="consulter_mes_avis.php"  class="active">Historique</a></li>
<!--                     <li><a href="historique_membre.php">Historique</a></li> -->
                </ul>
            </section>
       <div class="avis-widget">
    <div class="avis-list">
        <?php
        // Préparer et exécuter la requête SQL
        $tout_les_avis = $dbh->prepare('SELECT * FROM tripenarvor._avis NATURAL JOIN tripenarvor._membre NATURAL JOIN tripenarvor._offre WHERE code_compte = :code_compte');
        $tout_les_avis->bindValue(':code_compte', $compte['code_compte'], PDO::PARAM_INT);
        $tout_les_avis->execute();
        $tout_les_avis = $tout_les_avis->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Boucle pour afficher chaque avis -->
        <?php foreach ($tout_les_avis as $avis): ?>
            <?php
            // Déterminer l'appréciation en fonction de la note
            $appreciation = "";
            switch ($avis["note"]) {
                case '1': $appreciation = "Insatisfaisant"; break;
                case '2': $appreciation = "Passable"; break;
                case '3': $appreciation = "Correct"; break;
                case '4': $appreciation = "Excellent"; break;
                case '5': $appreciation = "Parfait"; break;
                default: $appreciation = "Non noté"; break;
            }
            ?>
            <div class="avis">
                <div class="avis-content">
                    <h3 class="avis" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            <?php echo htmlspecialchars($avis["note"]) . ".0 $appreciation "; ?> | 
                            <span class="nom_avis"><?php echo htmlspecialchars($avis["prenom"]) . " " . htmlspecialchars($avis["nom"]); ?></span> | 
                            <span class="nom_visite"><?php echo htmlspecialchars($avis["titre_offre"]); ?></span>
                        </span>
                        <!-- Formulaire pour supprimer un avis -->
                        <form method="POST" action="consulter_mes_avis.php">
                            <input type="hidden" name="supprAvis" value="<?php echo htmlspecialchars($avis['code_avis']); ?>">
                            <img src="images/trash.svg" alt="Supprimer" class="delete-icon" title="Supprimer cet avis" onclick="this.closest('form').submit()">
                        </form>
                    </h3>
                    <!-- Remplacer &#039; par ' dans le texte de l'avis -->
                    <p class="avis"><?php echo htmlspecialchars_decode($avis["txt_avis"], ENT_QUOTES); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


    </main>
    <nav class="nav-bar_consulter_mes_avis">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
                <?php
                if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
                    echo "compte_membre_tel.php";
                } else {
                    echo "connexion_membre.php";
                }
                ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>

</body>
<footer class="footer footer_membre">
        

        <div class="footer-links">
            <div class="logo\avis">
                <img src="images/logoBlanc.png" alt="Logo PACT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Publier</a></li>
                    <li><a href="#">Historique</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Signaler un problème</a></li>
                    <li><a href="#">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </footer>

</html>
