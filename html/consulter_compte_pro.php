<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

include("recupInfosCompte.php");

if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
   exit;
}

if(isset($_GET['deco'])){
   if($_GET['deco'] == true){
      session_unset();
      session_destroy();
      header('location: voir_offres.php');
      exit;
   }
}

if (isset($_POST['modif_infos'])){
    // Récupérer les valeurs initiales (par exemple, depuis la base de données)
   $valeursInitiales = [
       'mail' => $compte['mail'],
       'telephone' => $compte['telephone'],
       'adresse_postal' => $_adresse['adresse_postal'],
      'complement_adresse' => $_adresse['complement_adresse'],
       'code_postal' => $_adresse['code_postal'],
       'ville' => $_adresse['ville'],
   ];
   
   // Champs modifiés
   $champsModifies = [];
   
   // Parcourir les données soumises
   foreach ($_POST as $champ => $valeur) {
       if (isset($valeursInitiales[$champ]) && $valeursInitiales[$champ] !== $valeur) {
           $champsModifies[$champ] = $valeur;
       }
   }
   
   // Mettre à jour seulement les champs modifiés
   if (!empty($champsModifies)) {
       foreach ($champsModifies as $champ => $valeur) {
           switch ($champ) {
               case 'mail':
                   $valeurSansEspaces = trim(preg_replace('/\s+/', '', trim($valeur)));
                   $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
                   $query->execute(['valeur' => $valeurSansEspaces, 'code_compte' => $compte['code_compte']]);
                   if($query){
                      $_SESSION['pro']['mail'] = $valeurSansEspaces;
                   }
                   break;
               case 'telephone':
                   $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
                   $query->execute(['valeur' => trim(preg_replace('/\s+/', '', trim($valeur))), 'code_compte' => $compte['code_compte']]);
                   if($query){
                      $_SESSION['pro']['telephone'] = trim(preg_replace('/\s+/', '', trim($valeur)));
                   }
                   break;
   
               case 'adresse_postal':
               case 'complement_adresse':
               case 'code_postal':
               case 'ville':
                   $query = $dbh->prepare("UPDATE tripenarvor._adresse SET $champ = :valeur WHERE code_adresse = :code_adresse");
                   $query->execute(['valeur' => trim($valeur), 'code_adresse' => $_adresse['code_adresse']]);
                   if($query){
                     $_SESSION['pro'][$champ] = $valeur;  
                   }
                   break;
           }
       }
       // echo "Les informations ont été mises à jour.";
       include("recupInfosCompte.php");
   } else {
       echo "Aucune modification détectée.";
   }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Visualiser Profil</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
    
     <header class="header_pro">
        <div class="logo">
           <a href="mes_offres.php">
                  <img src="images/logo_blanc_pro.png" alt="PACT Logo">
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
                <li>
    <a href="#" class="notification-icon" id="notification-btn">
        <img src="images/notif.png" alt="cloche notification" class="nouvelle-image" style="margin-top: -5px;">
        <span class="notification-badge" style="display:none"></span>
    </a>
    <div id="notification-popup">
        <ul>
            <li>
                <img src="images/user2.png" alt="photo de profil" class="profile-img">
                <div class="notification-content">
                    <strong>Quentin Uguen</strong>
                    <span class="notification-location"> | Abbaye de Monfort</span>
                    <p>Déplorable, environnement moche</p>
                    <span class="notification-time">1 mois</span>
                    <span class="new-notif-dot"></span>
                </div>
            </li>
        </ul>
    </div>
</li>
    </header>
    
    <main class="main-consulter-compte-pro">
        <section class="profile">
            <div class="banner">
                <img src="images/Fond.png" alt="Bannière de profil">
            </div>
            <div class="profile-info">
                <img class="profile-picture" src="images/hotel.jpg" alt="Profil utilisateur">
                <h1><?php echo $monComptePro['raison_sociale']; ?></h1>
                <p><?php echo $compte['mail'] ." | ". $compte['telephone']; ?></p>
            </div>
        </section>
    
        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php" class="active">Informations personnelles</a></li>
                <li><a href="mes_offres.php" >Mes offres</a></li>
                <?php if ( isset($monComptePro['raison_sociale'])) { ?>
                   <li><a href="modif_bancaire.php">Compte bancaire</a></li>
                <?php } ?>
                <li><a href="modif_mdp_pro.php">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_reponses_pro.php">Mes réponses</a></li>
            </ul>
        </section>

        <form action="consulter_compte_pro.php" method="POST">
            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset disabled>
                    <legend>Raison Sociale</legend>
                    <input type="text" id="raison-sociale" name="raison-sociale*" placeholder="Raison Sociale*" value="<?php echo $monComptePro['raison_sociale']; ?>" required>
                </fieldset>

               <?php 
               if (isset( $monComptePro['num_siren']))
               {
               ?>
                   <fieldset disabled>
                       <legend>N° de Siren</legend>
                       <input type="text" id="siren" name="siren" placeholder="N° de Siren" value="<?php echo $monComptePro['num_siren']; ?>">
                   </fieldset>
               <?php
               }
               ?>
            </div>

           
            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Email *</legend>
                    <input type="email" id="email" name="mail" placeholder="Email *" value="<?php echo $compte['mail'] ?>" required>
                </fieldset>

                <fieldset>
                    <legend>Téléphone *</legend>
                    <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *" value=" <?php echo $compte['telephone']; ?> " required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Adresse Postale *</legend>
                <input type="text" id="adresse" name="adresse_postal" placeholder="Adresse postale *" value="<?php echo trim(preg_replace('/(\d{2})/', '$1 ', $_adresse['adresse_postal'])) ?>" required>
            </fieldset>

            <fieldset>
                <legend>Complément d'adresse</legend>
                <input type="text" id="complement_adresse" name="complement_adresse" placeholder="Complément d'adresse" value="<?php echo $_adresse['complement_adresse']; ?>">
            </fieldset>
            
            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Code Postal *</legend>
                    <input type="text" id="code_postal" name="code_postal" placeholder="code_postal *" value="<?php echo $_adresse['code_postal']; ?>" required>
                </fieldset>
                
                <fieldset>
                    <legend>Ville *</legend>
                    <input type="text" id="ville" name="ville" placeholder="Ville *" value="<?php echo $_adresse['ville']; ?>" required>
                </fieldset>
            </div>
            
            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
            </div>
            <div class="compte_pro_save_delete_remove">
                <button type="button" name="suppr-compte" class="btn-suppr-compte" id="btn-suppr-compte">Supprimer le compte</button>
                
                <div class="compte_pro_save_delete">
                   <a href="?deco=true" class="submit-btn1">Déconnexion</a>
                   <button type="submit" name="modif_infos" class="submit-btn3">Enregistrer</button>
               </div>
            </div>
            
        </form>
    </main>

    <footer class="footer_detail_avis">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <li><a href="connexion_memebre.php">Se Connecter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="contacter_plateforme.php">Signaler un problème</a></li>
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

   <script>

        ////////////////////////////////////////////////////////////////////////////////////
        ///                            Supprimer compte                                  ///
        ////////////////////////////////////////////////////////////////////////////////////

        document.getElementById('btn-suppr-compte').addEventListener('click', function () {
            const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce compte ?");
            if (confirmation) {
                const compteId = <?php echo json_encode($compte['code_compte']); ?>;
        
                fetch('/supprimer_compte.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: compteId })
                })
                .then(response => response.json()) // Parse la réponse JSON
                .then(data => {
                    if (data.success) {
                        alert('Compte supprimé avec succès.');
                        window.location.href = '/consulter_compte_pro.php?deco=true'; // Redirection côté client
                    } else {
                        alert(data.message || 'Erreur lors de la suppression du compte.');
                    }
                })
                .catch(error => {
                    console.error('Erreur réseau ou serveur :', error);
                    alert('Impossible de supprimer le compte.');
                });
            }
        });
    
    </script>
     <script>
       document.addEventListener('DOMContentLoaded', () => {
    const notificationBtn = document.getElementById('notification-btn');
    const notificationPopup = document.getElementById('notification-popup');

    // Ajouter la classe hidden pour masquer le pop-up au démarrage
    notificationPopup.classList.add('hidden');

    notificationBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le comportement par défaut de l'ancre
        notificationPopup.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!notificationPopup.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationPopup.classList.add('hidden');
        }
    });
});


    </script>

</body>
</html>
