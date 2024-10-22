<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PACT</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script> <!-- Pour les icones -->
    <style>
        .hours {
            list-style-type: none; /* Enlève les pastilles */
            padding: 0; /* Enlève le padding par défaut */
            margin: 0; /* Enlève la marge par défaut */
        }
    </style>
</head>
<body>
    <div id="body_offre_desktop">
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
                <img src="images/Tiallannec1.png" alt="Hôtel extérieur" class="main-image">
                <div class="thumbnail-grid">
                    <img src="images/Tiallannec2.png" alt="Hôtel de nuit">
                    <img src="images/tiallannec3.png" alt="Salle à manger">
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
    </div>
    </div>
    <div id="body_offre_mobile">
    <header class="header">
            <a href="#" class="back-button">&larr;</a>
            <h1>Détails</h1>
        </header>

        <!-- Carrousel d'images -->
        <div class="carousel">
                <div class="carousel-images">
                    <img src="images/tiallannec.png" alt="Golf de St-Samson Image 1">
                    <img src="images/tiallannec3.png" alt="Golf de St-Samson Image 2">
                    <img src="images/tiallannec6.png" alt="Golf de St-Samson Image 3">
                </div>
                <div class="carousel-buttons">
                    <button class="carousel-button prev" onclick="prevSlide()">&#10094;</button>
                    <button class="carousel-button next" onclick="nextSlide()">&#10095;</button>
                </div>
            </div>
        </div>
   

        <!-- Détails de l'offre -->
        <div class="details_offres_infos">
            <div class="titre_detail_offre_responsive">
                <h1>Ti Al Lannec</h1>
                <p>Direction</p>
    </div>
            <div class="rating">
                <span>
                    <img class="icone" src="images/etoile.png">
                </span>
                <span>
                    4.7 (2 avis)
                </span>
            </div>
            <p class="address">
                <img class="icone" src="images/icones/pin.png">
                34 Av. du Général de Gaulle, 22300 Lannion</p>
            <section>
                <div class="detail_offre_resumer_titre">
                <article>
                    <h3>Résumé</h3>
                    </div>
                    <p class="detail_offre_resumer">Choix des derniers technologies, tout y a été pensé pour votre confort.</p>
                </article>
                <div class="detail_offre_resumer_titre">
                <article>
                    <h3>Description</h3>
                    </div>
                    <p class="detail_offre_resumer">C'est très décontracté en terrasse, on sait tout par la force et la beauté du panorama à perte de vue.</p>
                </article>
            </section>

            <div class="global_service_detail_offre">
            <h3>Services</h3>
            <div class="services">
                <span class="service">
                    <p>Wifi</p>
                </span>
                <span class="service">
                    <p>Repas</p>
                </span>
                <span class="service">
                    <p>Bar</p>
                </span>
                <span class="service">
                    <p>Piscine</p>
                </span>
            </div>
            </div>

            <div class="Detail_offre_ouverture_global">
            <ul class="hours">
                <li><span>Lundi</span>: 19h30 - 21h30</li>
                <li><span>Mardi</span>: 19h30 - 21h30</li>
                <li><span>Mercredi</span>: 19h30 - 21h30</li>
                <li><span>Jeudi</span>: 19h30 - 21h30</li>
                <li><span>Vendredi</span>: 19h30 - 21h30</li>
                <li><span>Samedi</span>: 12h30 - 13h30, 19h30 - 21h30</li>
                <li><span>Dimanche</span>: 12h30 - 13h30, 19h30 - 21h30</li>
            </ul>
        </div>

        <!-- Bouton publier un avis -->
        <article class="publier_avis">
            <span>
                <p>Tarif minimal</p>
                <p id="prix">70€</p>
            </span>
            <!--<button id="bouton_publier">Publier un avis →</button>-->
        </article>
    </div>
</body>
  <!-- JavaScript pour le carrousel -->
  <script>
        let currentIndex = 0;
        const images = document.querySelectorAll('.carousel-images img');

        function showSlide(index) {
            const totalSlides = images.length;
            if (index >= totalSlides) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = totalSlides - 1;
            } else {
                currentIndex = index;
            }
            const offset = -currentIndex * 100;
            document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }
    </script>
</html>