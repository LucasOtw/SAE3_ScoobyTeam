<?php
    ob_start(); // bufferisation, ça devrait marcher ?
    session_start();

include("recupInfosCompte.php");

if(isset($_GET['logout'])){
   session_unset();
   session_destroy();
   header('location: connexion_pro.php');
   exit;
}

if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
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
            </ul>
        </nav>
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





      
       <div class="avis-widget">
        <div class="avis-list">
        <?php
        // Préparer et exécuter la requête SQL
        $tout_les_avis = $dbh->prepare('SELECT 
    avis.code_avis AS avis_code_avis,
    avis.txt_avis AS avis_txt_avis,
    avis.note AS avis_note,
    avis.pouce_positif AS avis_pouce_positif,
    avis.pouce_negatif AS avis_pouce_negatif,
    avis.signaler AS avis_signaler,
    avis.code_compte AS avis_code_compte,
	membre.nom as avis_nom_membre,
	membre.prenom as avis_prenom_membre,
    avis.code_offre AS avis_code_offre,
    reponse.code_avis AS reponse_code_avis,
    reponse.txt_avis AS reponse_txt_avis,
    reponse.note AS reponse_note,
    reponse.pouce_positif AS reponse_pouce_positif,
    reponse.pouce_negatif AS reponse_pouce_negatif,
    reponse.signaler AS reponse_signaler,
    reponse.code_compte AS reponse_code_compte,
    reponse.code_offre AS reponse_code_offre,
	offre.titre_offre
FROM 
    tripenarvor._reponse
INNER JOIN 
    tripenarvor._avis AS avis 
    ON avis.code_avis = tripenarvor._reponse.code_avis
INNER JOIN 
    tripenarvor.membre AS membre 
    ON membre.code_compte = avis.code_compte
INNER JOIN 
    tripenarvor._avis AS reponse 
    ON reponse.code_avis = tripenarvor._reponse.code_reponse
INNER JOIN 
    tripenarvor._offre AS offre 
    ON offre.code_offre = avis.code_offre
	where reponse.code_compte = :code_compte;
');
        $tout_les_avis->bindValue(':code_compte', $_SESSION['pro']['code_compte'], PDO::PARAM_INT);
        $tout_les_avis->execute();
        $tout_les_avis = $tout_les_avis->fetchAll(PDO::FETCH_ASSOC);


        if (count($tout_les_avis) == 0){
            ?>
                <h2>Aucun Avis</h2>
            <?php
        }
        ?>

        

        <!-- Boucle pour afficher chaque avis -->
        <?php foreach ($tout_les_avis as $avis): 
            // Déterminer l'appréciation en fonction de la note
            $appreciation = "";
            switch ($avis["avis_note"]) {
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
                    <h3 class="avis" style="display: flex; justify-content: space-between;">
                        <span>
                            <?php 
                            if ($avis["avis_note"] != 0) {
                                echo htmlspecialchars($avis["avis_note"]) . ".0 ★ " . htmlspecialchars($appreciation); 
                            } else {
                                echo "Réponse"; 
                            }
                            ?>

                            <br><span class="nom_avis"><?php echo htmlspecialchars($avis["avis_prenom_membre"]) . " " . htmlspecialchars($avis["avis_nom_membre"]); ?></span> 
                            <span class="nom_visite"><?php echo htmlspecialchars($avis["titre_offre"]); ?></span>
                        </span>
                        <!-- Formulaire pour supprimer un avis -->
                        
                    </h3>
                    <p class="avis"><?php echo htmlspecialchars_decode($avis["avis_txt_avis"], ENT_QUOTES); ?></p>
          	    <div class="consulter_mes_reponses_pro_reponse" style="margin-left:5vw;">
	                    <span class="nom_reponse" style="color:var(--orange); font-weight:bold;"><?php echo "Mon entreprise"; ?></span>         
			    <div class="txt_reponse_poubelle" style="display: flex; justify-content: space-between;">
	                    	<p class="avis"><?php echo htmlspecialchars_decode($avis["reponse_txt_avis"], ENT_QUOTES); ?></p>

				<form method="POST" action="consulter_mes_reponses_pro.php" class="delete-form" style="padding:0px; width:auto; margin:0;">
	                            <input type="hidden" name="supprAvis" value="<?php echo htmlspecialchars($avis['reponse_code_avis']); ?>">
	                        <img src="images/trash.svg" alt="Supprimer" class="delete-icon" title="Supprimer cet avis" onclick="confirmDelete(event)">
	                    </form> 
			    </div>
	                    
		    </div>
                </div>
            </div>
            
            <!-- Boîte de dialogue personnalisée -->
            <div id="customConfirm" class="custom-confirm">
                <div class="custom-confirm-content">
                    <p>Êtes-vous sûr de vouloir supprimer cet avis ?</p>
                    <button onclick="submitForm()">Oui</button>
                    <button onclick="closeConfirm()">Non</button>
                </div>
            </div>            
            <script>
            let currentForm;
            
            function confirmDelete(event) {
                event.preventDefault();
                currentForm = event.target.closest('form');
                document.getElementById('customConfirm').style.display = 'block';
            }
            
            function submitForm() {
                document.getElementById('customConfirm').style.display = 'none';
                if (currentForm) {
                    currentForm.submit();
                }
            }
            
            function closeConfirm() {
                document.getElementById('customConfirm').style.display = 'none';
            }
            </script>



        <?php endforeach; ?>
    </div>
</div>


    </main>
</body>
    <footer class="footer_detail_avis">
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
                    <li><a href="creation_offre.php">Publier</a></li>
                    <li><a href="consulter_mes_avis.php">Historique</a></li>
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
