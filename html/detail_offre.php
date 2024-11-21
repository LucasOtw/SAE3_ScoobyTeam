
<?php
    session_start();

    // Vérifie si le formulaire a été soumis    
    $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
    $username = "sae";  // Utilisateur PostgreSQL défini dans .env
    $password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

    // Créer une instance PDO avec les bons paramètres
    $dbh = new PDO($dsn, $username, $password);

    if(!isset($_POST["vueDetails"]))
    {
        echo "Erreur : aucune offre"; // à remplacer
    }
    else
    {
        echo $_POST["uneOffre"];
        
        // si le formulaire est bien récupéré
        $details_offre = unserialize($_POST["uneOffre"]);// on récupère son contenu
        
        $code_offre = $details_offre["code_offre"]; // on récupère le code de l'offre envoyé

        if(!empty($details_offre))
        { // si l'offre existe
    
                // Une offre a forcément au moins une image. 
                // On récupère l'image (ou les images) associée(s)
    
            $images_offre = $dbh->query('SELECT url_image FROM tripenarvor._image WHERE code_image = (SELECT code_image FROM tripenarvor._son_image WHERE code_offre = '.$code_offre.')');
            $images_offre = $images_offre->fetch();

            $tags_offre = $dbh->query('SELECT nom_tag FROM tripenarvor._tags WHERE code_tag = (SELECT code_tag FROM tripenarvor._son_tag WHERE code_offre = '.$code_offre.')');
            $tags_offre = $tags_offre->fetch();

         
            $offre_r = $dbh->query('select * from tripenarvor.offre_restauration where code_offre = '.$code_offre.';');
            $offre_p = $dbh->query('select * from tripenarvor.offre_parc_attractions where code_offre = '.$code_offre.';');
            $offre_s = $dbh->query('select * from tripenarvor.offre_spectacle where code_offre = '.$code_offre.';');
            $offre_v = $dbh->query('select * from tripenarvor.offre_visite where code_offre = '.$code_offre.';');
            $offre_a = $dbh->query('select * from tripenarvor.offre_activite where code_offre = '.$code_offre.';');

             if (!empty($offre_r))
             {
                 $type_offre = "restauration";
                 $details_offre = $offre_r->fetch(PDO::FETCH_ASSOC);
             }
             else if (!empty($offre_p))
             {
                 $type_offre = "parc d'attraction";
                 $details_offre = $offre_p->fetch(PDO::FETCH_ASSOC);
             }
             else if (!empty($offre_s))
             {
                 $type_offre = "spectacle";
                 $details_offre = $offre_s->fetch(PDO::FETCH_ASSOC);
             }
             else if (!empty($offre_v))
             {
                 $type_offre = "visite";
                 $details_offre = $offre_v->fetch(PDO::FETCH_ASSOC);
             }
             else if (!empty($offre_a))
             {
                 $type_offre = "activite";
                 $details_offre = $offre_a->fetch(PDO::FETCH_ASSOC);
             }
         

            // On récupère aussi l'adresse indiquée, ainsi que les horaires (si non nulles)
    
            $adresse_offre = $dbh->query('SELECT * FROM tripenarvor._adresse WHERE code_adresse = '.$details_offre["code_adresse"].'');
            $adresse_offre = $adresse_offre->fetch(PDO::FETCH_ASSOC);
         
            
        }
    }

    // Adresse que tu veux convertir
    $adresse = $adresse_offre["adresse_postal"]." ".$adresse_offre["ville"];

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
        echo "Adresse non trouvée.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <!-- Détails de l'offre sur Desktop -->
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
                    <h1><?php echo $details_offre["titre_offre"];?></h1>

                    <p>📍 <?php echo $adresse_offre["ville"].", ".$adresse_offre["code_postal"];?></p>

                    <p><i class="fas fa-clock"></i> Publié il y a 3 jours</p>

                    <!-- <div class="detail_offre_rating">
                        ⭐ 5.0 (255 avis)
                    </div> -->
                </div>

                <div class="detail_offre_price-button">

                    <p class="detail_offre_price"><?php echo $details_offre["tarif"];?>€</p>

                    <a href=$details_offre["site_web"]><button class="visit-button_detailoffre">Voir le site ➔</button></a>

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
                </section>

                <button class="card-scroll-btn card-scroll-btn-right" onclick="scrollcontentRight()">&#8250;</button>

            </div>

            <div class="detail_offre_description">

                    <h2>Résumé</h2>
                    <p>
                        Venez découvrir un hôtel chic dans l'esprit victorien.
                    </p>

                    <h2>Description</h2>
                    <p>
                        Équipées des dernières technologies, tout a été pensé pour votre confort. Côté jardin, on apprécie le calme de la verdure, le chant des oiseaux et le ruissellement mélodieux de la fontaine. Côté mer, des balcons et terrasses, on est saisi par la force et la beauté du panorama à perte de vue.
                    </p>
                    <p>
                    &nbsp <!-- Pour mettre un espace -->
                    </p>
                    <p>27 chambres et 6 suites au décor raffiné et cosy.</p>

                    <h2>Nos services</h2>
                        <div class="info-dropdown">

                            <button class="info-button" onclick="toggleInfoBox()">
                                Détails
                                <span class="arrow">&#9662;</span>
                            </button>
                            <div class="info-box" id="infoBox" style="max-height: 507px;padding: 15px;overflow-y: auto;width:25.5em;">

                                <h3 style="margin-top: 1em;">Durée</h3>
                                <p>4h (en moyenne)</p>

                                <h3 style="margin-top: 1em;">Age</h3>
                                <p>Tout publics</p>
                                
                                <h3 style="margin-top: 1em;">Visite Guidée</h3>
                                <p>Oui</p>
                                
                                <h3 style="margin-top: 1em;">Prestations incluses</h3>
                                <p>Cours de golf</p>
                                
                                <h3 style="margin-top: 1em;">Prestations non-incluses</h3>
                                <p>Spa</p>
                                
                                <h3 style="margin-top: 1em;">Capacité d'acceuil</h3>
                                <p> 500pers environ</p>
                                
                                <h3 style="margin-top: 1em;">Repas</h3>
                                <p>Oui</p>
                                
                                <h3 style="margin-top: 1em;">Nombre d'attractions</h3>
                                <p>23 attractions</p>
               
                            </div>
                        </div>
            </div>
               
            <div class="accessibilite_infos_detail_offre">
                <h2>Accessibilité</h2>
                <p>
                    - Handicapé</br>
                    -Trisomique
                </p>
            </div>

            <div class="detail_offre_icons">
                <h2>Tags</h2>

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

        <div class="detail_offre_localisation">
            <h2>Localisation</h2>
            <iframe class="map-frame"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig&q=<?php echo $latitude; ?>,<?php echo $longitude; ?>"
                    style="border:0;margin: auto 11em; width: 79vw; height:70vh" allowfullscreen="" loading="lazy">
                    border: 0;
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

    </div>



    <!-- Détails de l'offre sur MOBILE -->
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

                <div>
                <h3 style="margin-top: 1em;">Age</h3>
                <p class="detail_offre_resumer">Tout publics</p>

                <h3 style="margin-top: 1em;">Visite Guidée</h3>
                <p class="detail_offre_resumer">Oui</p>

                <h3 style="margin-top: 1em;">Durée</h3>
                <p class="detail_offre_resumer">4h (en moyenne)</p>
                
                <h3 style="margin-top: 1em;">Prestations incluses</h3>
                <p class="detail_offre_resumer">Cours de golf</p>

                <h3 style="margin-top: 1em;">Prestations non-incluses</h3>
                <p class="detail_offre_resumer">Spa</p>

                <h3 style="margin-top: 1em;">Capacité d'acceuil</h3>
                <p class="detail_offre_resumer"> 500pers environ</p>
                
                <h3 style="margin-top: 1em;">Repas</h3>
                <p class="detail_offre_resumer">Oui</p>
                
                <h3 style="margin-top: 1em;">Nombre d'attractions</h3>
                <p class="detail_offre_resumer">23 attractions</p>
                </div>

            </section>

            <div class="global_service_detail_offre">

                <h3 style="margin-left: 1.1em;">Services</h3>

                <div class="services">
                    <div class="service">
                        <p>Wifi</p>
                    </div>

                    <div class="service">
                        <p>Repas</p>
                    </div>

                    <div class="service">
                        <p>Bar</p>
                    </div>

                    <div class="service">
                        <p>Piscine</p>
                    </div>
                </div>
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

            <div class="Detail_offre_ouverture_global">
                <h3>Localisation</h3>
                <iframe class="map-frame"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig&q=<?php echo $latitude; ?>,<?php echo $longitude; ?>"
                style="border:0;margin: auto; width:85vw; height:50vh" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <div>
                <!-- Bouton publier un avis -->
                <article class="publier_avis">
                    <span>
                        <p style="margin-left: 2em;">Tarif minimal</p>
                        <p id="prix" style="margin-left: 1.5em;">70€</p>
                    </span>
                    <!--<button id="bouton_publier">Publier un avis →</button>-->
                </article>
            </div>
        </div>
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
