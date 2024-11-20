<?php
 session_start();

    // Adresse que tu veux convertir
    $adresse = "7 rue les hautes fontennelles";

    // Encode l'adresse pour l'URL
    $adresse_enc = urlencode($adresse);

    // Clé API Google obtenue après inscription
    $api_key = "AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig";

    // URL de l'API Geocoding
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$adresse_enc&key=$api_key";

    // Appel de l'API Google Geocoding
    $response = file_get_contents($url);
    $json = json_decode($response, true);

    // Vérifie si la réponse contient des résultats
    if(isset($json['results'][0])) {
        $latitude = $json['results'][0]['geometry']['location']['lat'];
        $longitude = $json['results'][0]['geometry']['location']['lng'];
    } else {
        // echo "Adresse non trouvée.";
    }




if(!isset($_POST["code_offre"])){
    echo "Erreur : aucune offre"; // à remplacer
} else {
    // si le formulaire est bien récupéré
    $code_offre = $_POST["code_offre"]; // on récupère le code de l'offre envoyé

    // On vérifie si le code existe dans la base de données (AU CAS OU !!!)
    $existeOffre = $bdd->query("SELECT * FROM _offre WHERE code_offre = $code_offre");
    if(!empty($existeOffre)){ // si l'offre existe
        $details_offre = $existeOffre->fetch(); // on récupère son contenu

            // Une offre a forcément au moins une image. 
            // On récupère l'image (ou les images) associée(s)

        $images_offre = $bdd->query('SELECT url_image FROM _image WHERE code_image = (SELECT code_image FROM son_image WHERE code_offre = '.$code_offre.')');

            // On récupère aussi l'adresse indiquée, ainsi que les horaires (si non nulles)

        $adresse_offre = $bdd->query('SELECT * FROM _adresse WHERE code_adresse = '.$details_offre["code_adresse"].'');
        $horaires = $bdd->query('SELECT DISTINCT h.* FROM _offre o JOIN _horaire h ON h.code_horaire IN (o.lundi, o.mardi, o.mercredi, o.jeudi, o.vendredi, o.samedi, o.dimanche
        WHERE o.lundi IS NOT NULL
        OR o.mardi IS NOT NULL
        OR o.mercredi IS NOT NULL
        OR o.jeudi IS NOT NULL
        OR o.vendredi IS NOT NULL
        OR o.samedi IS NOT NULL
        OR o.dimanche IS NOT NULL');
    }
} 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PACT</title>
    <link rel="stylesheet" href="detail_offre.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script> <!-- Pour les icones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <script src="scroll.js"></script>


    <script>
    function initMap() {
        var location = {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: location
        });
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
    </script>


</head>
<body onload="initMap()">
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
                    <!-- <div class="detail_offre_rating">
                        ⭐ 5.0 (255 avis)
                    </div> -->
                </div>
                <div class="detail_offre_price-button">
                    <p class="detail_offre_price">50€</p>
                    <button class="visit-button_detailoffre">Voir le site ➔</button>
                </div>
            </div>

            <div class="a-la-une-wrapper">
            <button class="card-scroll-btn card-scroll-btn-left" onclick="scrollcontentLeft()">&#8249;</button>
            <section class="a-la-une">
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
                <article class="card-a-la-une">
                    <div class="image-background-card-a-la-une">
                        <img src="images/tiallannec.png" alt="">
                        <div class="raison-sociale-card-a-la-une">
                            <p>Ti Al Lannec</p>
                        </div>
                    </div>
                </article>
            <!-- Ajoutez d'autres cartes ici -->
            </section>
            <button class="card-scroll-btn card-scroll-btn-right" onclick="scrollcontentRight()">&#8250;</button>
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
                    <p>Adapté handicap</p>
                </div>
                <div class="detail_offre_icon">
                    <p></span></p>
                    <p>Wifi</p>
                </div>
                <div class="detail_offre_icon">
                    <p>Chiens Autorisés</p>
                </div>
                <div class="detail_offre_icon">
                    <p>Tabac Interdit</p>
                </div>
            </div>
        </div>
        <div class="detail_offre_localisation">
        <h2>Localisation</h2>
        <iframe class="map-frame"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig&q=<?php echo $latitude; ?>,<?php echo $longitude; ?>"
                style="border:0;margin: auto 1em; width:95vw; height:70vh" allowfullscreen="" loading="lazy">
        </iframe>


        <div class="Detail_offre_ouverture_global_desktop">
                <h2>Horaires</h2>
            <ul class="hours_desktop_detail_offre">
                <li><span>Lundi</span>: 19h30 - 21h30</li>
                <li><span>Mardi</span>: 19h30 - 21h30</li>
                <li><span>Mercredi</span>: 19h30 - 21h30</li>
                <li><span>Jeudi</span>: 19h30 - 21h30</li>
                <li><span>Vendredi</span>: 19h30 - 21h30</li>
                <li><span>Samedi</span>: 12h30 - 13h30, 19h30 - 21h30</li>
                <li><span>Dimanche</span>: 12h30 - 13h30, 19h30 - 21h30</li>
            </ul>
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
                <a href="https://www.tiallannec.com/FR/index.php" class="description-link"><h3>Site Web</h3></a>
    </div>
            <!-- <div class="rating">
                <span>
                    <img class="icone" src="images/etoile.png">
                </span>
                <span>
                    4.7 (2 avis)
                </span>
            </div> -->
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
            <h3>Localisation</h3>
            <iframe class="map-frame"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig&q=<?php echo $latitude; ?>,<?php echo $longitude; ?>"
            style="border:0;margin: auto 0.5em; width:85vw; height:50vh" allowfullscreen="" loading="lazy">
        </iframe>
    </div>

            <div class="Detail_offre_ouverture_global">
                <h3>Horaires</h3>
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
     const imagesTrack = document.querySelector(".carousel-images");
const images = document.querySelectorAll(".carousel-images img");
const totalSlides = images.length;

let currentIndex = 0;

// Gère les boutons
function updateCarousel() {
    const translateX = -currentIndex * 100;
    imagesTrack.style.transform = `translateX(${translateX}%)`;
}

function prevSlide() {
    if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
    }
}

function nextSlide() {
    if (currentIndex < totalSlides - 1) {
        currentIndex++;
        updateCarousel();
    }
}

// Gère les gestes tactiles
let startX = 0;
let isDragging = false;

imagesTrack.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
});

imagesTrack.addEventListener("touchmove", (e) => {
    if (!isDragging) return;

    const currentX = e.touches[0].clientX;
    const deltaX = currentX - startX;

    if (deltaX > 50 && currentIndex > 0) {
        prevSlide();
        isDragging = false;
    } else if (deltaX < -50 && currentIndex < totalSlides - 1) {
        nextSlide();
        isDragging = false;
    }
});

imagesTrack.addEventListener("touchend", () => {
    isDragging = false;
});


    </script>
</html>
