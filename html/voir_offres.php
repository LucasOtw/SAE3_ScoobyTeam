<?php
session_start();

if(isset($_GET["deco"])){
    header('location: connexion_membre.php');
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
    <script src="filtre.js"></script>

    
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Code pour le pop-up --> 
    <div id="customPopup">
        <img src="images/robot_popup.png" width="50" height="70" class="customImage">
        <p>Créez votre compte en quelques clics et accédez à un monde de possibilités ! </p>
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
            <div class="logo-pc">
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
                            <li>
                                <a href="voir_offres.php?deco=true">Se déconnecter</a>
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
                <input type="text" class="search-input" placeholder="Recherchez parmi mes offres" />
                <button class="search-button">Rechercher</button>
            </div>
            <div class="search-options">
                <select class="search-select">
                    <option value="">Catégories</option>
                </select>
                <select class="search-select">
                    <option value="">Lieu</option>
                    <option value="">Lieu ></option>
                </select>
                <select class="search-select">
                    <option value="" hidden selected>Ouverture</option>
                    <option value="">Ouvert</option>
                    <option value="">Fermé</option>
                </select>
                <button id="autres-button" class="search-select">Autres</button>
            </div>
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
        <header>
            <h2>Les offres</h2>
            <!-- <span id="filter">
                <button><img src="images/tri.png">Filtrer</button>
            </span> -->
        </header>

        <section id="offers-list">

      <!--  <article class="offer">
            <img src="images/offre2.png" alt="Image de l'offre Armor'Park">
            <div class="offer-details">
                <h2>Armor'Park</h2>
                <p>Lannion</p>
                <span>3 mois</span>
                <span>
                     <img src="images/etoile.png" class="img-etoile">
                    <p>4 <span class="nb_avis">(50 avis)</span></p> 
                </span>
                <button>Voir l'offre →</button>
            </div>
        </article> -->
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
