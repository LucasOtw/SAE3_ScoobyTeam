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
       'nom' => $monCompteMembre['nom'],
       'prenom' => $monCompteMembre['prenom'],
       'pseudo' => $monCompteMembre['pseudo'],
       'mail' => $compte['mail'],
       'telephone' => $compte['telephone'],
       'adresse_postal' => $_adresse['adresse_postal'],
       'code_postal' => $_adresse['code_postal'],
       'ville' => $_adresse['ville'],
   ];
   
   // Champs modifiés
   $champsModifies = [];

   echo "<pre>";
   var_dump($_POST);
   echo"</pre>";
   
   // Parcourir les données soumises
   foreach ($_POST as $champ => $valeur) {
       if (isset($valeursInitiales[$champ]) && $valeursInitiales[$champ] !== $valeur) {
           $champsModifies[$champ] = $valeur;
       }
   }
   
   // Mettre à jour seulement les champs modifiés
   if (!empty($champsModifies)) {
         $champs_valides_membre = ['nom', 'prenom', 'pseudo'];
         $champs_valides_compte = ['mail', 'telephone'];
         $champs_valides_adresse = ['adresse_postal', 'code_postal', 'ville'];
         
         if (empty($compte['code_compte']) || empty($_adresse['code_adresse'])) {
             echo "Erreur : Informations de compte ou d'adresse manquantes.";
             return;
         }
         
         foreach ($champsModifies as $champ => $valeur) {
             $valeurNettoye = trim(preg_replace('/\s+/', '', $valeur)); // Supprime les espaces en trop
         
             if (in_array($champ, $champs_valides_membre)) {
                 // Mise à jour pour _membre
                 $query = $dbh->prepare("UPDATE tripenarvor._membre SET $champ = :valeur WHERE code_compte = :code_compte");
                 $query->execute(['valeur' => $valeurNettoye, 'code_compte' => $compte['code_compte']]);
                 if ($query->rowCount() > 0) {
                     $_SESSION['membre'][$champ] = $valeurNettoye;
                 }
             } elseif (in_array($champ, $champs_valides_compte)) {
                 // Mise à jour pour _compte
                 $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
                 $query->execute(['valeur' => $valeurNettoye, 'code_compte' => $compte['code_compte']]);
                 if ($query->rowCount() > 0) {
                     $_SESSION['membre'][$champ] = $valeurNettoye;
                 }
             } elseif (in_array($champ, $champs_valides_adresse)) {
                 // Mise à jour pour _adresse
                 $query = $dbh->prepare("UPDATE tripenarvor._adresse SET $champ = :valeur WHERE code_adresse = :code_adresse");
                 $query->execute(['valeur' => $valeurNettoye, 'code_adresse' => $_adresse['code_adresse']]);
                 if ($query->rowCount() > 0) {
                     $_SESSION['membre'][$champ] = $valeurNettoye;
                 }
             } else {
                 echo "Champ non valide : $champ";
             }
         }
         
         // Récupérer les informations mises à jour
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
    <title>Modifier compte membre</title>
    <link rel="stylesheet" href="consulter_compte_membre.css">
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
        <div class="edit-profil">
            <a href="compte_membre_tel.php">
               <img src="images/Bouton_retour.png" alt="bouton retour">
            </a>
            <h1>Editer le profil</h1>
        </div>                
            <section class="tabs">
                <ul>
                    <li><a href="#" class="active">Informations personnelles</a></li>
                    <li><a href="modif_mdp_membre.php">Mot de passe et sécurité</a></li>
<!--                     <li><a href="historique_membre.php">Historique</a></li> -->
                </ul>
            </section>

           

        <form action="consulter_compte_membre.php" method="POST" id="compteForm">

            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Nom *</legend>
                    <input type="text" id="nom" name="nom" value="<?php echo $monCompteMembre['nom']; ?>" placeholder="Nom *" required>
                </fieldset>

                <fieldset>
                    <legend>Prénom *</legend>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $monCompteMembre['prenom']; ?>" placeholder="Prénom *" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Pseudo *</legend>
                <input type="text" id="pseudo" name="pseudo" value="<?php echo $monCompteMembre['pseudo']; ?>" placeholder="Pseudo *" required>
            </fieldset>

            <div class="crea_pro_mail_tel">
                <fieldset>
                    <legend>Email *</legend>
                    <input type="email" id="email" name="mail" value="<?php echo $compte['mail']; ?>" placeholder="Email *" required>
                </fieldset>

                <fieldset>
                    <legend>Téléphone *</legend>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo trim(preg_replace('/(\d{2})/', '$1 ', $compte['telephone'])) ?>" placeholder="Téléphone *" maxlength="14" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Adresse Postale *</legend>
                <input type="text" id="adresse" name="adresse" value="<?php echo $_adresse['adresse_postal']; ?>" placeholder="Adresse Postale *" required>
            </fieldset>
           
            <div class="crea_pro_mail_tel">
               <fieldset>
                   <legend>Code Postal *</legend>
                   <input type="text" id="code-postal" name="code-postal" value="<?php echo $_adresse['code_postal']; ?>" placeholder="Code Postal *" required>
               </fieldset>
   
               <fieldset>
                   <legend>Ville *</legend>
                   <input type="text" id="ville" name="ville" value="<?php echo $_adresse['ville']; ?>" placeholder="Ville *" required>
               </fieldset>
            </div>

            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
            </div>
            
            <div class="compte_membre_save_delete">
                <a href="voir_offres.php?deco=true" class="submit-btn1">Déconnexion</a>
                <button type="submit" name="modif_infos" class="submit-btn2" id="btn-enreg">Enregistrer</button>
            </div>

        </form>
        
    </main>
    <nav class="nav-bar">
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
    <script>
        // Désactiver le bouton par défaut
        const form = document.getElementById('compteForm');
        const saveButton = document.getElementById('btn-enreg');
        const inputs = form.querySelectorAll('input');
    
        // Sauvegarde des valeurs initiales
        const initialValues = {};
        inputs.forEach(input => {
            initialValues[input.name] = input.value;
        });
    
        // Vérifier si un champ a changé
        form.addEventListener('input', () => {
            let hasChanged = false;
            inputs.forEach(input => {
                if (input.value !== initialValues[input.name]) {
                    hasChanged = true;
                }
            });
            saveButton.disabled = !hasChanged; // Activer ou désactiver le bouton
        });
    </script>
</body>
</html>
