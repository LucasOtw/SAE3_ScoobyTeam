<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

if(!isset($_SESSION['membre'])){
   header('location: connexion_membre.php');
   exit;
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter Plateforme</title>
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

            <form action="poster_un_avis.php" method="POST">
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
</body>

</html>
