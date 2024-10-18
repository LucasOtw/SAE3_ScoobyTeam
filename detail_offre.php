<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PACT</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script> <!-- Pour les icones -->
</head>
<body id="body_offre_desktop">
    <header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#" class="active">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="detail_offre_hotel-detail">
        <div class="detail_offre_hotel-header">
            <div class="detail_offre_hotel-info">
                <h1>Ti Al Lannec – Hôtel & Restaurant</h1>
                <p>📍 Trébeurden, Bretagne 22300</p>
                <div class="detail_offre_rating">
                    ⭐ 5.0 (255 avis)
                </div>
            </div>
            <div class="detail_offre_price-button">
                <p class="detail_offre_price">50€</p>
                <button class="visit-button_detailoffre">Voir le site ➔</button>
            </div>
        </div>

        <div class="detail_offre_gallery">
            <img src="images/tiallannec1.png" alt="Hôtel extérieur" class="main-image">
            <div class="thumbnail-grid">
                <img src="images/Tiallannec2.png" alt="Hôtel de nuit">
                <img src="images/Tiallannec3.png" alt="Salle à manger">
            </div>
            
            <!-- Nouvelle structure pour les miniatures -->
            
                
                <div class="detail_offre_thumbnail-grid3">
                    <img src="images/tiallannec8.png" alt="Chambre">
                    <img src="images/tiallannec9.png" alt="Vue sur la mer">
                </div>

        </div>

        <div class="detail_offre_description">
            <h2>Description</h2>
            <p>
                Équipées des dernières technologies, tout a été pensé pour votre confort. Côté jardin, on apprécie le calme de la verdure, le chant des oiseaux et le ruissellement mélodieux de la fontaine. Côté mer, des balcons et terrasses, on est saisi par la force et la beauté du panorama à perte de vue.
            </p>
            <p>
            &nbsp <!-- Pour mettre un espace -->
            </p>
            <p>27 chambres et 6 suites au décor raffiné et cosy.</p>
        </div>

        <div class="detail_offre_icons">
            <div class="detail_offre_icon">
                <p>5.0</p> 
                <p>Très bien</p>
                <p>255 avis</p>
            </div>
            <div class="detail_offre_icon">
                <p><span class="bx--handicap"></span>Adapté</p>
            </div>
            <div class="detail_offre_icon">
                <p><span class="iconify" data-icon="mdi:wifi" data-inline="false"></span></p>
                <p>Wifi</p>
            </div>
            <div class="detail_offre_icon">
                <p><span class="mdi--dog"></span>Autorisés</p>
            </div>
            <div class="detail_offre_icon">
                <p><span class="ph--cigarette-slash-bold"></span>Interdit</p>
            </div>
        </div>
    </div>
    <div class="detail_offre_localisation">
    <h2>Localisation</h2>
    <iframe class="map-frame" 
        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2629.6630360674853!2d-3.5818007!3d48.7692309!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4813d30ea0739aa5%3A0xa08df5c6c4d0aae5!2sTi%20Al%20Lannec%20Sa!5e0!3m2!1sfr!2sfr!4v1729076393212!5m2!1sfr!2sfr" 
        width="1650" height="700" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>
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
</body>
</html>