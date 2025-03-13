<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

require_once('recupInfosCompte.php');

if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
   exit;
}

$details_offre = $_SESSION["detail_offre"];

// Vérifie si HTTP_REFERER est défini
if (isset($_SERVER['HTTP_REFERER'])) {
    // Vérifie que la page précédente est "detail_offre.php"
    if ($_SERVER['HTTP_REFERER'] === "https://scooby-team.ventsdouest.dev/detail_offre.php" && !isset($_POST['publier'])) {
        // Action si les conditions sont respectées
        $details_offre = $_SESSION['detail_offre'];
    }
}

// Vérifie si le formulaire a été soumis    
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

// Créer une instance PDO avec les bons paramètres
$dbh = new PDO($dsn, $username, $password);

$stmt = $dbh->prepare('SELECT url_image FROM tripenarvor._son_image natural join tripenarvor._image WHERE code_offre = :code_offre;');
$stmt->execute([':code_offre' => $details_offre["code_offre"]]);
$image_offre = $stmt->fetch(PDO::FETCH_NUM);

/*
echo "<pre>";
   var_dump($image_offre);
echo "</pre>";
echo "<pre>";
var_dump($details_offre);
echo "</pre>";
echo "<pre>";
var_dump($compte);
echo "</pre>";
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['unAvis'])){
        // echo "<pre>";
        // var_dump(unserialize($_POST['unAvis']));
        // echo "</pre>";

        $avis = unserialize($_POST['unAvis']);
        $_SESSION['modif_avis'] = $avis;
    }
    if(isset($_POST['modifier'])){
        $modifOk = false;

        // Un avis pro est forcément une réponse !
        if(isset($_POST['textAreaAvis']) && !empty($_POST['textAreaAvis'])){
            $modifOk = true;
        }

        if($modifOk === true){
            // si les modifications sont ok

            $champsModifies = array();
            $params = array();

            $n_texteAvis = trim($_POST['textAreaAvis']);
            $n_note = (int) $_POST['note'];
            $code_avis = (int) $_POST['code_avis'];


            $modifications = [];
            $params = [];
            
            if ($n_note !== $_SESSION['modif_avis']['note']) {
                $modifications[] = "note = :note";
                $params[':note'] = $n_note;
            }
            
            if ($n_texteAvis !== trim($_SESSION['modif_avis']['txt_avis'])) {
                $modifications[] = "txt_avis = :texteAvis";
                $params[':texteAvis'] = $n_texteAvis;
            }
            
            if (!empty($modifications)) {
                $sql = "UPDATE tripenarvor._avis SET " . implode(', ', $modifications) . " WHERE code_avis = :codeAvis";
                $params[':codeAvis'] = $code_avis;
            
                $stmt = $dbh->prepare($sql);
                $stmt->execute($params);
            }
            
            header('location: detail_offre_pro.php');
            exit;
        } else {
            ?>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>

                $(document).ready(function() {
                    function afficherPOPup() {
                        $("#customConfirmBox").fadeIn();     
                    }   
                    // Fermer la popup quand l'utilisateur clique sur "Fermer"
                    $('#confirmButton').on('click', function() {
                        $('#customConfirmBox').fadeOut();
                    });
                    afficherPOPup();  
                });

            </script>
            <?php
        }
    }
}
if(isset($_SESSION['modif_avis'])){
    $avis = $_SESSION['modif_avis'];
}

$note = $avis['note'];

// On vérifie si cet avis est une réponse

$isAnswer = $dbh->prepare('SELECT COUNT(*) FROM tripenarvor._reponse
WHERE code_reponse = :code_rep');
$isAnswer->bindValue(':code_rep',$avis['code_avis']);
$isAnswer->execute();

$isAnswer = $isAnswer->fetchColumn();

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier  un avis</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script> <!-- Pour les icones -->
</head>
<body>
    <header class="header-pc header_pro">
        <div class="logo-pc">
           <a href="voir_offres.php">
            <img src="images/logoBlanc.png" alt="PACT Logo">
         </a>

        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
      </header> 


        <header class="header-tel header_pro">
            <div class="logo-tel">
                <a href="mes_offres.php"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
            </div>
        </header>
        <main class="main_poster_avis">
        <div class="poster_un_avis_container">
            <div class="poster_un_avis_back_button">
                  <form id="back_button" action="detail_offre.php" method="POST">
                       <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                       <a href="detail_offre.php"><img src="images/Bouton_retour.png" class="back-button"></a>
                   </form>
            <h1 class="titre_poster_un_avis_format_tel">Modifier un avis</h1>
            </div>
            <h1 class="poster_un_avis_titre">Récapitulatif</h1>
            <div class="poster_un_avis_recap_card">
                <div class="poster_un_avis_info">
                    <h2 class="poster_un_avis_nom"><?php echo $details_offre["titre_offre"]; ?><!-- - <?php // echo $details_offre["titre_offre"]; ?>--></h2>
                    <p class="poster_un_avis_location">📍 <?php echo $details_offre["ville"]; ?>, <?php echo $details_offre["code_postal"]; ?></p>
                    <form id="form-voir-offre" action="detail_offre_pro.php" method="POST">
                       <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                       <input id="btn-voir-offre" class="poster_un_avis_btn_offre_pro" type="submit" name="vueDetails" value="Voir l'offre &#10132;">
                   </form>
                </div>
                <div class="poster_un_avis_images">
                         <img src="<?php echo $image_offre[0]; ?>" alt=""  class="poster_un_avis_image"> 
                </div>
            </div>

              <!-- Popup de confirmation -->
            <div class="custom-confirm" id="customConfirmBox">
                <div class="custom-confirm-content">
                    <p>Vous ne pouvez pas poster d'avis sans notation ou de message !</p>
                    <button id="confirmButton">Fermer</button>
                </div>
            </div>

            <form  id="avisForm" action="#" method="POST">
               <div class="poster_un_avis_section">
                   <h2 class="poster_un_avis_section_titre">Votre avis</h2>

                   <textarea placeholder="Écrivez votre avis ici..." class="poster_un_avis_textarea" name="textAreaAvis" id="textAreaAvis"><?php echo trim($avis['txt_avis']); ?></textarea>
                   <p class="message-erreur avis-vide">Vous devez remplir ce champ</p>
                   <p class="message-erreur avis-trop-long">L'avis ne doit pas dépasser 500 caractères.</p>     
                   <?php
                    if($isAnswer === 0){
                        ?>
                        <div class="poster_un_avis_footer">

                            <div class="poster_un_avis_note">
                                <h2 class="poster_un_avis_note_titre">Votre note</h2>

                                <fieldset class="notation">
                                            <input type="radio" id="star5" name="note" value="5" />
                                            <label for="star5" title="5 étoiles"></label>

                                            <input type="radio" id="star4" name="note" value="4" />
                                            <label for="star4" title="4 étoiles"></label>

                                            <input type="radio" id="star3" name="note" value="3" />
                                            <label for="star3" title="3 étoiles"></label>

                                            <input type="radio" id="star2" name="note" value="2" />
                                            <label for="star2" title="2 étoiles"></label>

                                            <input type="radio" id="star1" name="note" value="1" />
                                            <label for="star1" title="1 étoile"></label>
                                </fieldset>
                                <p class="message-erreur pas-de-note">Veuillez sélectionner une note valide.</p>
                            </div>
                        </div>
                        <?php
                    }
                   ?>
                       <p class="poster_un_avis_disclaimer">En modifiant votre avis, vous acceptez les conditions générales d'utilisation (CGU).</p>
                       <div class="poster_un_avis_buttons">
                            <!--<button class="poster_un_avis_btn_annuler">Annuler</button>-->
                            <input type="hidden" name="code_avis" value="<?php echo $avis['code_avis']; ?>">
                            <input type="hidden" name="isAnswer" value="<?php echo $isAnswer; ?>">
                            <button class="poster_un_avis_btn_publier_pro" type="button" name="modifier" onclick="afficherMessage(event)">Modifier →</button>
                        </div>

                        <div id="message-container" style="display:none; color: red;">
                            <p>Erreur : Votre avis a été modifié. Veuillez réessayer.</p>
                        </div>

                        <script>
                            function afficherMessage(event) {
                                event.preventDefault();  // Empêche la soumission du formulaire immédiate
                                
                                var messageContainer = document.getElementById("message-container");
                                messageContainer.style.display = "block";  // Affiche le message d'erreur

                                // Affiche la pop-up immédiatement
                                $(document).ready(function() {
                                    function afficherPOPup() {
                                        $("#customConfirmBox").fadeIn();  // Affiche la pop-up de confirmation
                                    }
                                    afficherPOPup();  // Afficher la pop-up après avoir cliqué sur le bouton
                                });

                                // Fermer la pop-up quand l'utilisateur clique sur "Fermer"
                                $('#confirmButton').on('click', function() {
                                    $('#customConfirmBox').fadeOut();  // Ferme la pop-up
                                    setTimeout(function() {
                                        document.querySelector("form").submit();  // Soumettre le formulaire après que la pop-up soit fermée
                                    }, 500);  // Délai de 500ms pour s'assurer que la pop-up soit fermée avant la soumission
                                });
                            }
                        </script>

     
                    </div>
                  </div>
               </div>
            </form>
           
           
        <nav class="nav-bar">
            <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
            <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
            <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
            <a href="
                <?php
                    if(isset($_SESSION["pro"]) || !empty($_SESSION["pro"])){
                        echo "consulter_compte_pro.php";
                    } else {
                        echo "connexion_pro.php";
                    }
                ?>">
                <img src="images/icones/User icon.png" alt="image de Personne"></a>
        </nav>
    </main> 

    <footer class="footer footer_pro">
        
        <div class="footer-links">
            <div class="logo\avis">
                <img src="images/logoBlanc.png" alt="Logo PACT">
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
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="creation_offre.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["pro"]) && !empty($_SESSION["pro"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_pro.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_pro.php">Se connecter</a>
                        </li>
                        <?php
                    }
                    ?>
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
        document.addEventListener('DOMContentLoaded',function(){
            var note = <?php echo $note; ?>;
            console.log(`star${note}`);
            // on récupère une étoile en fonction de la note
            const numEtoile = document.getElementById(`star${note}`);
            numEtoile.toggleAttribute("checked");
        });
    </script>
</body>
</html>