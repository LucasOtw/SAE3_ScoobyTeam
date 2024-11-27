<?php
session_start();

if(isset($_GET["deco"])){
    session_destroy();
};

if(isset($_SESSION['membre'])){
   $donneesSession = json_encode($_SESSION['membre'],JSON_PRETTY_PRINT);
} else {
    $donneesSession = null;
}

function tempsEcouleDepuisPublication($offre){
    // date d'aujourd'hui
    $date_actuelle = new DateTime();
    // conversion de la date de publication en objet DateTime
    $date_ajout_offre = new DateTime($offre['date_publication']);
    // calcul de la différence en jours
    $diff = $date_ajout_offre->diff($date_actuelle);
    // récupération des différentes unités de temps
    $jours = $diff->days; // total des jours de différence
    $mois = $diff->m + ($diff->y * 12); // mois totaux
    $annees = $diff->y;

    $retour = null;

    // calcul du nombre de jours dans le mois précédent
    $date_mois_precedent = clone $date_actuelle;
    $date_mois_precedent->modify('-1 month');
    $jours_dans_mois_precedent = (int)$date_mois_precedent->format('t'); // 't' donne le nombre de jours dans le mois

    if($jours == 0){
        $retour = "Aujourd'hui";
    } elseif($jours == 1){
        $retour = "Hier";
    } elseif($jours > 1 && $jours < 7){
        $retour = $jours." jour(s)";
    } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent){
        $semaines = floor($jours / 7);
        $retour = $semaines." semaine(s)";
    } elseif ($mois < 12){
        $retour = $mois." mois";
    } else {
        $retour = $annees." an(s)";
    }

    return $retour;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Offres PACT</title>
    <link rel="stylesheet" href="voir_offres.css?ad">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="filtre.js"></script>
    <script src="scroll.js"></script>
</head>
<body>
<div class="half-background">
        <!-- Le contenu de la page ici -->
         <div class="conteneur_titre_voir_offre">
        <h1 class="h1_voir_offre1">Découvrez la</h1>
        <h1 class="h1_voir_offre2">Bretagne !</h1>
</div>
    </div>
    <!-- Code pour le pop-up --> 
    <div id="customPopup">
        <img src="images/robot_popup.png" width="50" height="70" class="customImage">
        <p><a href="creation_compte_membre.php" class="txt_popup">Créez votre compte</a> en quelques clics et accédez à un monde de possibilités ! </p>
        <!--<a id="connexion" href="creation_compte_membre.php">S'inscrire</a>-->
        <img id="closePopup" src="images/erreur.png" width="15" height="15">
    </div>

    <script>
    const donneesSessionMembre = <?php echo json_encode($donneesSession); ?>;
    document.addEventListener("DOMContentLoaded", () => {
        function afficherPopupAvecDelai() {
            const popup = document.getElementById("customPopup");
            popup.style.display = "flex"; 
            setTimeout(() => {
                popup.classList.add("visible"); // Ajoute l'effet de transition
            }, 10); // Légère pause pour déclencher la transition
        }

        function fermerPopup() {
            const popup = document.getElementById("customPopup");
            popup.classList.remove("visible"); // Retire la classe pour l'effet inverse
            setTimeout(() => {
                popup.style.display = "none"; // Cache après l'animation
            }, 500); // Correspond à la durée de la transition CSS
        }

        const closeButton = document.getElementById("closePopup");
        if (closeButton) {
            closeButton.addEventListener("click", fermerPopup);
        } else {
            console.error("Le bouton avec l'ID 'closePopup' est introuvable.");
        }

        if (!donneesSessionMembre) {
            setTimeout(afficherPopupAvecDelai, 5000);
        }
    });
</script>




    <div class="header-membre">
        <header class="header-pc">
            <div class="logo-pc" style="z-index: 1">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="voir_offres.php" class="active">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                        if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                           ?>
                           <li>
                               <a href="consulter_compte_membre.php">Mon compte</a>
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
        <header class="header-tel">
            <div class="logo-tel">
                <img src="images/LogoCouleur.png" alt="PACT Logo">
            </div>
            
        </header>
    </div>
    

    <div class="header-pro">
        <header class="header-pc">
            <div class="logo-pc">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </div>
            
            <nav>
                <ul>
                    <li><a href="voir_offres.php" >Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <li><a href="connexion_membre.php" class="active">Mon Compte</a></li>
                </ul>
            </nav>
        </header>
        <header class="header-tel">
            <div class="logo-tel">
                <img src="images/logoNoir.png" alt="PACT Logo">
            </div>
            
        </header>
    </div>
   
    
    <main class="toute_les_offres_main">
    
        <div class="search-bar">
            <div class="search-top">
            <input type="text" class="search-input" placeholder="Recherchez parmi les offres" >
                <button class="search-button">Rechercher</button>
            </div>
            <div class="search-options">
                <select class="search-select">
                    <option value="" hidden selected>Catégories</option>
                    <option value="">Activité</option>
                    <option value="">Restaurant</option>
                    <option value="">Visite</option>
                    <option value="">Spectacle</option>
                    <option value="">Parc d'attraction</option>
                </select>
                <select class="search-select">
                    <option value="" hidden selected>Prix</option>
                    <option value="">Décroissant</option>
                    <option value="">Croissant</option>
                </select>
                <select class="search-select">
                    <option value="" hidden selected>Note</option>
                    <option value="">Décroissant</option>
                    <option value="">Croissant</option>
                </select>
                <button id="openMenu" class="search-select">Autres</button>
            </div>
        </div>
        <div id="overlay"></div>
    

        <div class="filter-menu" id="filterMenu">
    <button id="closeMenu" class="close-btn">&times;</button>
    <h2>Filtres</h2>
    <form>
        <!-- Lieu -->
        <label for="voir_offres_lieu">Lieu</label>
        <input type="text" id="location" placeholder="Ex : Lannion">

        <!-- Note générale des avis -->
        <label for="category">Note générale des avis</label>
        <select id="category">
            <option value="all">Les notes</option>
            <option value="1">1 étoile</option>
            <option value="2">2 étoiles</option>
            <option value="3">3 étoiles</option>
            <option value="4">4 étoiles</option>
            <option value="5">5 étoiles</option>
        </select>

        <!-- Fourchette de prix avec un slider -->
        <label for="price-range">Fourchette de prix (€)</label>
        <div id="price-slider">
            <input type="range" id="price-min" name="price-min" min="0" max="1000" step="10" value="100">
            <input type="range" id="price-max" name="price-max" min="0" max="1000" step="10" value="900">
            <p>
                De : <span id="price-min-display">100</span> € à : <span id="price-max-display">900</span> €
            </p>
        </div>

        <!-- Statut -->
        <label for="sort">Statut</label>
        <select id="sort">
            <option value="relevance">Ouverture</option>
            <option value="open">Ouvert</option>
            <option value="closed">Fermé</option>
            <option value="closing-soon">Ferme bientôt !</option>
        </select>

        <!-- Dates d'événement -->
        <label for="event-date">Date d’évènement</label>
        <input type="date" id="event-date">

        <!-- Dates d'ouverture -->
        <label for="opening-dates">Dates d’ouverture</label>
        <input type="date" id="opening-start-date" placeholder="Début">
        <input type="date" id="opening-end-date" placeholder="Fin">

        <!-- Bouton Appliquer -->
        <button id="voir_offres_menu" type="submit">Appliquer les filtres</button>
    </form>
</div>



        

        <!-- Section cachée pour les filtres -->
        <div id="filters-section" class="hidden">
            <h3>Filtres supplémentaires</h3>
            <span>
            <label for="prix_min">
                Prix min :
            </label>
            <input id="prix_min" type="number" placeholder="€" class="price-input">
            <label for="prix_max">
                Prix max :
            </label>
            <input id="prix_max" type="number" placeholder="€" class="price-input">
            </span>
            <button class="apply-filters">Appliquer les filtres</button>
        </div>

        <div class="a-la-une-titre-carrousel">
            <h2 class="titre-a-la-une">A La Une</h2>
    
            <div class="a-la-une-carrousel">
                <button class="card-scroll-btn card-scroll-btn-left" onclick="scrollcontentLeft()">&#8249;</button>
                <section class="a-la-une">
                <?php
                    try {
                        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
                        $username = "sae";
                        $password = "philly-Congo-bry4nt";
        
                        // Créer une instance PDO
                        $dbh = new PDO($dsn, $username, $password);
                    } 
                    catch (PDOException $e) 
                    {
                        print "Erreur!: ". $e->getMessage(). "<br/>";
                        die();
                    }
                    // On récupère toutes les offres (titre,ville,images)
                    $infosOffre = $dbh->query('SELECT * FROM tripenarvor._offre');
                    $infosOffre = $infosOffre->fetchAll(PDO::FETCH_ASSOC);
        
                    foreach($infosOffre as $offre){
                        // Récupérer la ville
                        $villeOffre = $dbh->prepare('SELECT ville FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
                        $villeOffre->bindParam(":code_adresse", $offre["code_adresse"]);
                        $villeOffre->execute();
                        $villeOffre = $villeOffre->fetch(); // Récupérer la ville (ou NULL si pas trouvé)
                        
                        // Récupérer les images
                        $imagesOffre = $dbh->prepare('SELECT code_image FROM tripenarvor._son_image WHERE code_offre = :code_offre');
                        $imagesOffre->bindParam(":code_offre", $offre["code_offre"]);
                        $imagesOffre->execute();
                        
                        // on recupère toutes les images sous forme de tableau
                        $images = $imagesOffre->fetchAll(PDO::FETCH_ASSOC);
        
                        if(!empty($images)){ // si le tableau n'est pas vide...
                            /* On récupère uniquement la première image.
                            Une offre peut avoir plusieurs images. Mais on n'en affiche qu'une seule sur cette page.
                            On pourrait afficher aléatoirement chaque image, mais on serait vite perdus...*/
                                            
                            $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image');
                            $recupLienImage->bindValue(":code_image",$images[0]['code_image']);
                            $recupLienImage->execute();
            
                            $offre_image = $recupLienImage->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $offre_image = "";
                        }
                        if (!empty($offre["option_a_la_une"]))
                        {
                            if ($offre["en_ligne"])
                            {
                            ?>
                                
                                    <article class="card-a-la-une">
                                        <form id="form-voir-offre" action="detail_offre.php" method="POST" class="form-voir-offre">
                                            <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                            <div class="image-background-card-a-la-une">
                                                <img src="<?php echo './'.$offre_image['url_image']; ?>" alt="">
                                                <div class="raison-sociale-card-a-la-une">
                                                    <p><?php echo $offre["titre_offre"]; ?></p>
                                                    <!-- Le bouton est maintenant juste après le texte dans la même zone -->
                                                    <input id="btn-voir-offre" type="submit" name="vueDetails" value="Voir l'offre &#10132;">
                                                </div>
                                            </div>
                                        </form>
                                    </article>
                                    
                            <?php
                            }
                        }
                    }
                ?>
                </section>
                <button class="card-scroll-btn card-scroll-btn-right" onclick="scrollcontentRight()">&#8250;</button>
            </div>
        </div>

        
        <h2 class="titre-les-offres">Les offres</h2>
        
        <section id="offers-list">
        <?php
            try {
                $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
                $username = "sae";
                $password = "philly-Congo-bry4nt";

                // Créer une instance PDO
                $dbh = new PDO($dsn, $username, $password);
            } 
            catch (PDOException $e) 
            {
                print "Erreur!: ". $e->getMessage(). "<br/>";
                die();
            }
            // On récupère toutes les offres (titre,ville,images)
            $infosOffre = $dbh->query('SELECT * FROM tripenarvor._offre');
            $infosOffre = $infosOffre->fetchAll(PDO::FETCH_ASSOC);

            foreach($infosOffre as $offre){
                // Récupérer la ville
                $villeOffre = $dbh->prepare('SELECT ville FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
                $villeOffre->bindParam(":code_adresse", $offre["code_adresse"]);
                $villeOffre->execute();
                $villeOffre = $villeOffre->fetch(); // Récupérer la ville (ou NULL si pas trouvé)
                
                // Récupérer les images
                $imagesOffre = $dbh->prepare('SELECT code_image FROM tripenarvor._son_image WHERE code_offre = :code_offre');
                $imagesOffre->bindParam(":code_offre", $offre["code_offre"]);
                $imagesOffre->execute();
                
                // on recupère toutes les images sous forme de tableau
                $images = $imagesOffre->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($images)){ // si le tableau n'est pas vide...
                    /* On récupère uniquement la première image.
                    Une offre peut avoir plusieurs images. Mais on n'en affiche qu'une seule sur cette page.
                    On pourrait afficher aléatoirement chaque image, mais on serait vite perdus...*/
                                    
                    $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image');
                    $recupLienImage->bindValue(":code_image",$images[0]['code_image']);
                    $recupLienImage->execute();
    
                    $offre_image = $recupLienImage->fetch(PDO::FETCH_ASSOC);
                } else {
                    $offre_image = "";
                }
                if ($offre["en_ligne"])
                {
                ?>
                    <article class="offer">
                        <img src=<?php echo "./".$offre_image['url_image'] ?> alt="aucune image">
                        <div class="offer-details">
                            <h2><?php echo $offre["titre_offre"] ?></h2>
                            <p><?php echo $villeOffre["ville"] ?></p>
                            <span><?php echo tempsEcouleDepuisPublication($offre); ?></span>
                            <form id="form-voir-offre" action="detail_offre.php" method="POST">
                                <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                <input id="btn-voir-offre" type="submit" name="vueDetails" value="Voir l'offre &#10132;">
                            </form>
                        </div>
                    </article>
                <?php
                }
            }

        ?>
        </section>

    </main>
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
    <footer>
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
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
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
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
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
