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
           <a href="voir_offres.html">
            <img src="images/logoBlanc.png" alt="PACT Logo">
         </a>

        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.html">Accueil</a></li>
                <li><a href="connexion_pro.html">Publier</a></li>
                <li><a href="consulter_compte_membre.html" class="active">Mon Compte</a></li>
            </ul>
        </nav>
      </header> 

        <header class="header-tel header_membre">
            <div class="logo-tel">
                <a href="voir_offres.html"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
            </div>
        </header>

        <main class="main_contacter_plateforme">
        <div class="contacter_plateforme_container">
            <div class="contacter_plateforme_back_button">
                  <form id="back_button" action="detail_offre.html" method="POST">
                       <a href="detail_offre.html"><img src="images/Bouton_retour.png" class="back-button"></a>
                   </form>
            <h1 class="titre_contacter_plateforme_format_tel">Besoin d'aide ?</h1>
            </div>
            <h1 class="contacter_plateforme_titre">Récapitulatif</h1>
            <div class="contacter_plateforme_recap_card">
                <div class="contacter_plateforme_info">
                    <form id="form-voir-offre" action="detail_offre.html" method="POST">
                       <input id="btn-voir-offre" class="contacter_plateforme_btn_offre" type="submit" name="vueDetails" value="Voir l'offre &#10132;">
                   </form>
                </div>
                <div class="contacter_plateforme_images">
                </div>
            </div>

            <form action="poster_un_avis.html" method="POST">
               <div class="contacter_plateforme_section">
                   <h2 class="contacter_plateforme_section_titre">Votre question</h2>

                   <textarea placeholder="Écrivez votre avis ici..." class="contacter_plateforme_textarea" name="textAreaAvis" id="textAreaAvis"></textarea>
                   <p class="message-erreur avis-vide">Vous devez remplir ce champ</p>
                   <p class="message-erreur avis-trop-long">La question ne doit pas dépasser 500 caractères.</p>                  
                   <div class="contacter_plateforme_footer">

                       <p class="contacter_plateforme_disclaimer">En nous envoyant votre question, vous acceptez les conditions générales d'utilisation (CGU).</p>
                       <div class="contacter_plateforme_buttons">
                           <button class="contacter_plateforme_btn_publier" type="submit" name="publier">Publier &#8594;</button>
                       </div>
                    </div>
                  </div>
               </div>
            </form>
        <nav class="nav-bar">
            <a href="voir_offres.html"><img src="images/icones/House icon.png" alt="image de maison"></a>
            <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
            <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
            <a href="consulter_compte_membre.html">
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
