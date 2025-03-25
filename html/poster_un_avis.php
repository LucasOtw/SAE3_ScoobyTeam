<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

if(!isset($_SESSION['membre'])){
   header('location: connexion_membre.php');
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
require_once __DIR__ . ("/../.security/config.php");

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
     if(isset($_POST['publier'])){
        if((isset($_POST['note']) && !empty($_POST['note'])) && (isset($_POST['textAreaAvis']) && !empty($_POST['textAreaAvis']))) {
           
            $texte_avis = trim(isset($_POST['textAreaAvis']) ? htmlspecialchars($_POST['textAreaAvis']) : '');
            $note = isset($_POST['note']) ? $_POST['note'] : '';
   
            $compte = $_SESSION['membre'];
            $code_compte = $compte['code_compte'];           
            $code_offre = $_SESSION['detail_offre']['code_offre'];
   
            $erreurs = [];
            $erreur_a_afficher = [];
            if (empty($texte_avis)) {
                $erreurs[] = "Vous devez remplir ce champ";
                $erreur_a_afficher[] = "avis-vide";
            } elseif (strlen($texte_avis)>500) {
                $erreurs[] = "L'avis ne doit pas dépasser 500 caractères.";
                $erreur_a_afficher[] = "avis-trop-long";
            }
   
           if (empty($note) || !is_numeric($note) || $note < 1 || $note > 5) {
                $erreurs[] = "Veuillez sélectionner une note valide."; 
                $erreur_a_afficher[] = "pas-de-note";
            }
   
           if (empty($erreurs)) {
             $creerAvis = $dbh->prepare("INSERT INTO tripenarvor._avis (txt_avis, note, code_compte, code_offre) VALUES (:texte_avis, :note, :code_compte, :code_offre)");
   
             $creerAvis->bindParam(':texte_avis', $texte_avis);
             $creerAvis->bindParam(':note', $note, PDO::PARAM_INT);
             $creerAvis->bindParam(':code_offre', $code_offre);
             $creerAvis->bindParam(':code_compte', $code_compte);
             $creerAvis->execute();

             $creerNotif = $dbh->query("INSERT INTO tripenarvor._notification (code_avis) SELECT currval('tripenarvor._avis_code_avis_seq');");
   
             header('location: detail_offre.php');
             exit;
   
            } else {
               /*
              foreach($erreurs as $erreur){
                    echo $erreur;
              }*/
   
               foreach($erreur_a_afficher as $classe_erreur){
                  // echo $classe_erreur;
                    ?> 
   
                    <style>
                        main.main_poster_avis .<?php echo $classe_erreur ?> {
                           display:block;
                        }         
                       ?>
                    </style> 
                    <?php
              }
            }
        } else {
           // echo "ok";
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


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster un avis</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="header-pc header_membre">
        <div class="logo-pc">
           <a href="voir_offres.php">
            <img src="images/logoBlanc.png" alt="PACT Logo">
         </a>

        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="consulter_compte_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
      </header> 


        <header class="header-tel header_membre">
            <div class="logo-tel">
                <a href="voir_offres.php"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
            </div>
        </header>

        <main class="main_poster_avis">
        <div class="poster_un_avis_container">
            <div class="poster_un_avis_back_button">
                  <form id="back_button" action="detail_offre.php" method="POST">
                       <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                       <a href="detail_offre.php"><img src="images/Bouton_retour.png" class="back-button"></a>
                   </form>
            <h1 class="titre_poster_un_avis_format_tel">Publier un avis</h1>
            </div>
            <h1 class="poster_un_avis_titre">Récapitulatif</h1>
            <div class="poster_un_avis_recap_card">
                <div class="poster_un_avis_info">
                    <h2 class="poster_un_avis_nom"><?php echo $details_offre["titre_offre"]; ?><!-- - <?php // echo $details_offre["titre_offre"]; ?>--></h2>
                    <p class="poster_un_avis_location">📍 <?php echo $details_offre["ville"]; ?>, <?php echo $details_offre["code_postal"]; ?></p>
<!--                     <button class="poster_un_avis_btn_offre">Voir l'offre →</button> -->
                    <form id="form-voir-offre" action="detail_offre.php" method="POST">
                       <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                       <input id="btn-voir-offre" class="poster_un_avis_btn_offre" type="submit" name="vueDetails" value="Voir l'offre &#10132;">
                   </form>
                </div>
                <div class="poster_un_avis_images">
                         <img src="<?php echo $image_offre[0]; ?>" alt=""  class="poster_un_avis_image"> 
<!--                     <img src="images/tiallannec1.png" alt="Image 1" class="poster_un_avis_image"> -->
                </div>
            </div>

              <!-- Popup de confirmation -->
            <div class="custom-confirm" id="customConfirmBox">
                <div class="custom-confirm-content">
                    <p>Vous ne pouvez pas poster d'avis sans notation ou de message !</p>
                    <button id="confirmButton">Fermer</button>
                </div>
            </div>

            <form  id="avisForm" action="poster_un_avis.php" method="POST">
               <div class="poster_un_avis_section">
                   <h2 class="poster_un_avis_section_titre">Votre avis</h2>

                   <textarea placeholder="Écrivez votre avis ici..." class="poster_un_avis_textarea" name="textAreaAvis" id="textAreaAvis"></textarea>
                   <p class="message-erreur avis-vide">Vous devez remplir ce champ</p>
                   <p class="message-erreur avis-trop-long">L'avis ne doit pas dépasser 500 caractères.</p>                  
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
                       <p class="poster_un_avis_disclaimer">En publiant votre avis, vous acceptez les conditions générales d'utilisation (CGU).</p>
                       <div class="poster_un_avis_buttons">
                           <!--<button class="poster_un_avis_btn_annuler">Annuler</button>-->
                           <button class="poster_un_avis_btn_publier" type="submit"- name="publier">Publier →</button>
                       </div>
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
                    if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                        echo "consulter_compte_membre.php";
                    } else {
                        echo "connexion_membre.php";
                    }
                ?>">
                <img src="images/icones/User icon.png" alt="image de Personne"></a>
        </nav>
    </main> 

    <footer class="footer footer_membre">
        <div class="newsletter">
        <div class="newsletter-content">
            <h2>Inscrivez-vous à notre Newsletter</h2>
            <p>PACT</p>
            <p>découvrez la Bretagne !</p>
            <form class="newsletter-form" id="newsletterForm">
                <input type="email" id="newsletterEmail" placeholder="Votre adresse mail" required>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
        <div class="newsletter-image">
            <img src="images/Boiteauxlettres.png" alt="Boîte aux lettres">
        </div>
    </div>

    <div id="newsletterConfirmBox" style="display: none;">
        <div class="popup-content">
            <p class="popup-message"></p>
            <button id="closeNewsletterPopup">Fermer</button>
        </div>
    </div>

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
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const newsletterForm = document.getElementById('newsletterForm');
    const emailInput = document.getElementById('newsletterEmail');
    const newsletterPopup = document.getElementById('newsletterConfirmBox');
    const closePopupButton = document.getElementById('closeNewsletterPopup');

    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        if (email) {
            fetch('envoyer_email3.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email=${encodeURIComponent(email)}`
            })
                .then(() => {
                    afficherPopup("Votre inscription à la newsletter a bien été prise en compte !");
                    
                })
                .catch(() => {
                    afficherPopup("Votre inscription à la newsletter a bien été prise en compte !");
                });
        } else {
            alert("Veuillez entrer une adresse email valide.");
        }
    });

    function afficherPopup(message) {
        newsletterPopup.querySelector('.popup-message').innerText = message;
        newsletterPopup.style.display = 'block';
    }

    closePopupButton.addEventListener('click', () => {
        newsletterPopup.style.display = 'none';
    });
});

</script>


