<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster un avis</title>
    <link rel="stylesheet" href="poster_un_avis.css">
</head>

<body>
    <header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="connexion_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <header class="header-tel">
        <div class="logo-tel">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
    </header>

    <div class="poster_un_avis_container">
        <h1 class="titre_poster_un_avis_format_tel">Publier un avis</h1>
        <h1 class="poster_un_avis_titre">Récapitulatif</h1>
        <div class="poster_un_avis_recap_card">
            <div class="poster_un_avis_info">
                <h2 class="poster_un_avis_nom">Ti Al Lannec - Hôtel & Restaurant</h2>
                <p class="poster_un_avis_location">📍 Trébeurden, Bretagne 22300</p>
                <button class="poster_un_avis_btn_offre">Voir l'offre →</button>
            </div>
            <div class="poster_un_avis_images">
                <img src="images/Tiallannec1.png" alt="Image 1" class="poster_un_avis_image">
                <img src="images/Tiallannec3.png" alt="Image 2" class="poster_un_avis_image">
            </div>
        </div>

        <div class="poster_un_avis_section">
            <h2 class="poster_un_avis_section_titre">Votre avis</h2>
            <textarea placeholder="Écrivez votre avis ici..." class="poster_un_avis_textarea"></textarea>
            <div class="poster_un_avis_footer">
                <div class="poster_un_avis_note">
                    <h2 class="poster_un_avis_note_titre">Votre note</h2>
                    <div class="poster_un_avis_stars">
                        <span class="poster_un_avis_star">⭐</span>
                        <span class="poster_un_avis_star">⭐</span>
                        <span class="poster_un_avis_star">⭐</span>
                        <span class="poster_un_avis_star">⭐</span>
                        <span class="poster_un_avis_star">⭐</span>
                    </div>
                    <p class="poster_un_avis_disclaimer">
                        En publiant votre avis, vous acceptez les conditions générales d'utilisation (CGU).
                    </p>
                </div>
                <div class="poster_un_avis_buttons">
                    <button class="poster_un_avis_btn_annuler">Annuler</button>
                    <button class="poster_un_avis_btn_publier">Publier →</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer_poster_avis">
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
</body>

</html>
