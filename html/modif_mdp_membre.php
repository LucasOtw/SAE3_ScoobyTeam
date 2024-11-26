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

if (isset($_POST['modif_infos'])){
    // Récupérer les valeurs initiales (par exemple, depuis la base de données)
   $valeursInitiales = [
       'mdp' => $compte['mdp'],
   ];
   
   // Champs modifiés
   $champsModifies = [];
   
   // Parcourir les données soumises
   foreach ($_POST as $champ => $valeur) {
       if (isset($valeursInitiales[$champ]) && $valeursInitiales[$champ] !== $valeur) {
           $champsModifies[$champ] = $valeur;
       }
   }

   echo "<pre>";
   var_dump($champsModifies);
   echo"</pre>";
   
   // Mettre à jour seulement les champs modifiés
   if (!empty($champsModifies))
   {
      // $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
      // $query->execute(['valeur' => trim(preg_replace('/\s+/', '', trim($valeur))), 'code_compte' => $compte['code_compte']]); 
      
       // echo "Les informations ont été mises à jour.";
       include("recupInfosCompte.php");
   } else {
       echo "Aucune modification détectée.";
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Mot de passe</title>
    <link rel="stylesheet" href="modif_mdp_membre.css">
</head>
<body>
    <header>
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
    <main class="main_modif_mdp_membre">
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
        <div class="mdp_securite">
            <a href="compte_membre_tel.php"><img src="images/Bouton_retour.png" alt="Bouton retour"></a>
            <h1>Mot de passe</h1>
        </div>
        <div class="illustration">
            <img src="images/mdp_securite_illustration.png" alt="Illustration" class="illustration_mdp_securite">
        </div>
        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_membre.php">Informations personnelles</a></li>
                <li><a href="#" class="active">Mot de passe et sécurité</a></li>
<!--                     <li><a href="historique_membre.php">Historique</a></li> -->
            </ul>
        </section>
        <form action="modif_mdp_membre.php" method="POST">
           <h3>Modifiez votre mot de passe</h3>
            <fieldset>
                <legend>Entrez votre mot de passe actuel *</legend>
                <input type="password" id="mdp_actuel" name="mdp_actuel" placeholder="Entrez votre mot de passe actuel *" required>
            </fieldset>

            <fieldset>
                <legend>Définissez votre nouveau mot de passe *</legend>
                <input type="password" id="mdp_nv1" name="mdp_nv1" placeholder="Definissez votre nouveau mot de passe *" required>
            </fieldset>
            <fieldset>

                <legend>Confirmer votre nouveau mot de passe *</legend>
                <input type="password" id="mdp_nv2" name="mdp_nv2" placeholder="Confirmer votre nouveau mot de passe *" required>
            </fieldset>
            <div class="compte_membre_save_delete">
                <!-- <button type="submit" class="submit-btn1">Supprimer mon compte</button> -->
                <button type="submit" name="modif_infos" class="submit-btn2">Enregistrer</button>
            </div>
        </form>
    </main>
    <nav class="nav-bar-tel">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
            <?php
                if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                    echo "consulter_compte_membre.php";
                } else {
                    echo "connexion_membre.php";
                }
            ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
    <footer class="footer_detail_avis">
        <div class="newsletter">
            <div class="newsletter-content">
                <h2>Inscrivez-vous à notre Newsletter</h2>
                <p>PACT</p>
                <p>Redécouvrez la Bretagne !</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Votre adresse mail" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
            <div class="newsletter-image">
                <img src="images/Boiteauxlettres.png" alt="Boîte aux lettres">
            </div>
        </div>
        
        <div class="footer-links">
            <div class="logo">
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
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
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
</body>
</html>
