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
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Avis</title>
    <link rel="stylesheet" href="styles.css?">
</head>
<body>
    <div class="header_membre">
        <header class="header-pc">
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
    </div>
    <header class="header-tel">
        <svg
            width="428"
            height="202"
            viewBox="0 0 428 202"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 126.87L0 0H428V134.241C374.61 176.076 300.465 202 218.5 202C131.823 202 53.891 173.01 0 126.87Z"
                fill="#E8E8E8"
            ></path>
        </svg>
            <div  class="logo-tel">
                <a href="voir_offres.php">
                    <img src="images/LogoCouleur.png" alt="PACT Logo" style="margin-top:2.5em;">
                </a>
            </div>
        </header>
    <main class="main_consulter_compte_membre" style=" margin-top:2em;">
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
<style>
    .edit-profil {
        display: none;
    }
</style>

      
        <div class="edit-profil">
            <a href="compte_membre_tel.php">
                <img src="images/Bouton_retour.png" alt="bouton retour">
            </a>
            <h1 class ="mes_avis">Mes Avis</h1>
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
        $tout_les_avis = $dbh->prepare('SELECT * FROM tripenarvor._avis NATURAL JOIN tripenarvor._membre NATURAL JOIN tripenarvor._offre NATURAL join tripenarvor._adresse WHERE code_compte = :code_compte');
        $tout_les_avis->bindValue(':code_compte', $compte['code_compte'], PDO::PARAM_INT);
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
                    <h3 class="avis" style="display: flex; justify-content: space-between;">
                        <span>
                            <?php 
                            if ($avis["note"] != 0) {
                                echo htmlspecialchars($avis["note"]) . ".0 ★ " . htmlspecialchars($appreciation); 
                            } else {
                                echo "Réponse"; 
                            }
                            ?>

                            <br><span class="nom_avis"><?php echo htmlspecialchars($avis["prenom"]) . " " . htmlspecialchars($avis["nom"]); ?></span> 
                            <span class="nom_visite"><?php echo htmlspecialchars($avis["titre_offre"]); ?></span>
                        </span>
                        <!-- Formulaire pour supprimer un avis -->
                        <form method="POST" action="consulter_mes_avis.php" class="delete-form">
                            <input type="hidden" name="supprAvis" value="<?php echo htmlspecialchars($avis['code_avis']); ?>">
                            <img src="images/trash.svg" alt="Supprimer" class="delete-icon" title="Supprimer cet avis" onclick="confirmDelete(event)" style="filter: brightness(0.1);">
                        </form>
                        <form action="modif_avis_membre.php" method="POST">
                            <input type="hidden" name="unAvis" value="<?php echo htmlspecialchars(serialize($avis)); ?>">
                            <input type="image" src="images/icones/modif.png" alt="Modifier l'avis" class="modif-icon" title="Modifier cet avis">
                        </form>

                    </h3>
                    <p class="avis"><?php echo htmlspecialchars_decode($avis["txt_avis"], ENT_QUOTES); ?></p>
                </div>
            </div>
            
            <!-- Boîte de dialogue personnalisée -->
            <div id="customConfirm" class="custom-confirm">
                <div class="custom-confirm-content">
                    <p style="margin-bottom: 122px; text-align : center;">Êtes-vous sûr de vouloir supprimer cet avis ?</p>
                    <span style="display: flex; justify-content: center;">
                    <button onclick="submitForm()">Oui</button>
                    <button onclick="closeConfirm()">Non</button>
                    </span>
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
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.php">Mentions Légales</a></li>
                    <li><a href="cgu.php">GGU</a></li>
                    <li><a href="cgv.php">CGV</a></li>
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

</html>
