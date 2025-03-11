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

$modif_mdp = null;

if (isset($_POST['modif_infos'])){
   
   // Champs modifiés
   $champsModifies = [];
   
   // Parcourir les données soumises
   foreach ($_POST as $champ => $valeur) {
      if ($champ != 'modif_infos')
      {
         $champsModifies[$champ] = trim($valeur);
      }
   }
   
   // Mettre à jour seulement les champs modifiés
   if (!empty($champsModifies))
   {
      if (password_verify($champsModifies['mdp_actuel'],$compte['mdp']))
      {
         if (trim($champsModifies['mdp_nv1']) === trim($champsModifies['mdp_nv2']))
         {
            $mdp_modif = password_hash($champsModifies['mdp_nv1'], PASSWORD_DEFAULT);
            $query = $dbh->prepare("UPDATE tripenarvor._compte SET mdp = :valeur WHERE code_compte = :code_compte");
            $query->bindValue(":valeur",$mdp_modif);
            $query->bindValue(":code_compte",$compte['code_compte']);
            $query->execute();
                
            $rowsAffected = $query->rowCount();
            if ($rowsAffected > 0) {
               $modif_mdp = true;
               $_SESSION['membre']['mdp'] = $mdp_modif;
            } else {
               $modif_mdp = false;
            }

         } else {
            $modif_mdp = false;
         }
      } else {
         echo "Test";
         $modif_mdp = false;
      }
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
   <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Mot de passe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header_membre">
        <div class="logo">
         <a href="voir_offres.php">
            <img src="images/logoBlanc.png" alt="PACT Logo">
         </a>
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <?php
                    if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                       ?>
                       <li>
                           <a href="consulter_compte_membre.php" class="active">Mon Compte</a>
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
                <img src=<?php echo $compte_pp; ?> alt="Photo de profil" class="profile-img">
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
                <li><a href="modif_mdp_membre.php" class="active">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_avis.php">Historique</a></li>
<!--<li><a href="historique_membre.php">Historique</a></li>-->
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
                <legend>Confirmez votre nouveau mot de passe *</legend>
                <input type="password" id="mdp_nv2" name="mdp_nv2" placeholder="Confirmez votre nouveau mot de passe *" required>
            </fieldset>
            

        
           
            <div class="compte_membre_save_delete">
                <button type="submit" name="modif_infos" class="submit-btn2">Enregistrer</button>
            </div>
        </form>
          <?php

            if($modif_mdp !== null){
               if($modif_mdp == true){
                  $img_success = "images/verifier.png";
                  $msg_modif = "Mot de passe modifié avec succès&nbsp!";
               } else {
                  $img_success = "images/erreur.png";
                  $msg_modif = "Erreur lors du changement du mot de passe&nbsp!";
               }
               ?>
               <div class="creation-success" id="modif_mdp_membre">
                  <img src="<?php echo $img_success ?>" alt="Succès">
                  <h2><?php echo $msg_modif; ?></h2>
               </div>
               <?php
            }
            ?>
       
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
   
    <footer class="footer footer_membre">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
                    <li><a href="cgu.html">GGU</a></li>
                    <li><a href="cgv.html">CGV</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["membre"]) && !empty($_SESSION["membre"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_membre.php">Mon Compte</a>
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

            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Nous Connaitre</a></li>
                    <li><a href="obtenir_aide.php">Obtenir de l'aide</a></li>
                    <li><a href="contacter_plateforme.php">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <!--<li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>-->
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
