<?php
session_start();

if (isset($_GET["deco"])) {
    session_destroy();
    exit; // Ajout d'une sortie après la destruction de la session pour éviter d'autres traitements
}

if (isset($_SESSION['membre'])) {
    $donneesSession = $_SESSION['membre'];
    $donneesSessionJson = json_encode($donneesSession, JSON_PRETTY_PRINT); // Si le JSON est nécessaire
} else {
    $donneesSession = null;
    $donneesSessionJson = null;
}

if (isset($_SESSION['detail_offre'])) {
    unset($_SESSION['detail_offre']);
}

/*
?>
<pre>
    <?php var_dump($_COOKIE); ?>
</pre>
<?php
*/
require_once __DIR__ . ("/../.security/config.php");

// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);


if ($donneesSession && isset($donneesSession["code_compte"])) {
    $code_compte = $donneesSession["code_compte"];

    $compte = $dbh->prepare('SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte');
    $compte->bindValue(":code_compte", $code_compte);
    $compte->execute();

    $resultat = $compte->fetch(PDO::FETCH_ASSOC);
}


function tempsEcouleDepuisPublication($offre)
{
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
    $jours_dans_mois_precedent = (int) $date_mois_precedent->format('t'); // 't' donne le nombre de jours dans le mois

    if ($jours == 0) {
        $retour = "Aujourd'hui";
    } elseif ($jours == 1) {
        $retour = "Hier";
    } elseif ($jours > 1 && $jours < 7) {
        $retour = $jours . " jour(s)";
    } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent) {
        $semaines = floor($jours / 7);
        $retour = $semaines . " semaine(s)";
    } elseif ($mois < 12) {
        $retour = $mois . " mois";
    } else {
        $retour = $annees . " an(s)";
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
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <script src="filtre.js"></script>
    <script src="scroll.js"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <link rel="stylesheet" href="leaflet.css" crossorigin="" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js"></script>
</head>

<body>
    <div class="half-background">
        <!-- Le contenu de la page ici -->
        <div class="conteneur_titre_voir_offre">

            <h1 class="h1_voir_offre1">Découvrez la</h1>
            <h1 class="h1_voir_offre2">Bretagne<?php
            if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
                echo ", " . $resultat['prenom'];
            }
            ?> !</h1>
        </div>
    </div>
    <!-- Code pour le pop-up -->
    <div id="customPopup"><a href="creation_compte_membre.php">
            <img src="images/robot_popup.png" width="50" height="70" class="customImage" alt="Robot Image">
        </a>

        <p><a href="creation_compte_membre.php" class="txt_popup">Créez votre compte</a> en quelques clics et accédez à
            un monde de possibilités ! </p>
        <!--<a id="connexion" href="creation_compte_membre.php">Inscrivez-vous</a>-->
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
                <a href="voir_offres.php">
                    <img src="images/logoBlanc.png" alt="PACT Logo">
                </a>

            </div>

            <nav>
                <ul>
                    <li><a href="voir_offres.php" class="active">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
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
            </nav>
        </header>
        <header class="header-tel">
            <div class="logo-tel">
                <img src="images/LogoCouleur.png" alt="PACT Logo" style="margin-top:-0.5em;">
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
                    <li><a href="voir_offres.php">Accueil</a></li>
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
                <input type="text" class="search-input" placeholder="Rechercher parmi les offres">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <div class="search-options">
                <select class="search-select">
                    <option value='all' selected>Catégories</option>
                    <option value='restauration'>Restaurant</option>
                    <option value='parc_attractions'>Parc d'attractions</option>
                    <option value='spectacle'>Spectacle</option>
                    <option value='visite'>Visite</option>
                    <option value='activite'>Activité</option>
                </select>

                <select class="search-select">
                    <option value="" selected>Prix</option>
                    <option value="decroissantP">Décroissant</option>
                    <option value="croissantP">Croissant</option>
                </select>
                <select class="search-select">
                    <option value="" selected>Notes</option>
                    <option value="decroissantN">Décroissant</option>
                    <option value="croissantN">Croissant</option>
                </select>
                <button id="openMenu" class="search-select">Autres</button>
            </div>
        </div>


        <div id="overlay">
        </div>

        <div class="filter-menu" id="filterMenu">
            <button id="closeMenu" class="close-btn">&times;</button>
            <h2>Filtres</h2>


            <!-- Note générale des avis -->
            <label for="select-rate">Note générale des avis</label>
            <select id="select-rate" class="search-select">
                <option value="">Les notes</option>
                <option value="1">1 étoile</option>
                <option value="2">2 étoiles</option>
                <option value="3">3 étoiles</option>
                <option value="4">4 étoiles</option>
                <option value="5">5 étoiles</option>
            </select>

            <!-- Fourchette de prix avec un slider -->
            <label for="price-range">Fourchette de prix (€)</label>
            <div id="price-slider">
                <input type="range" id="price-min" name="price-min" min="0" max="200" step="10" value="0">
                <input type="range" id="price-max" name="price-max" min="0" max="200" step="10" value="200">
                <p>
                    De : <span id="price-min-display">0</span> € à : <span id="price-max-display">200</span> €
                </p>
            </div>

            <!-- Statut -->
            <label for="select-statut">Statut</label>
            <select id="select-statut" class="search-select">
                <option value=""></option>
                <option value="opening-soon">Ouvre bientôt</option>
                <option value="open">Ouvert</option>
                <option value="closed">Fermé</option>
                <option value="closing-soon">Ferme bientôt</option>
            </select>

            <!-- Dates d'événement -->
            <label for="event-date">Date d’évènement</label>
            <input type="date" id="event-date">

            <!-- Dates d'ouverture -->
            <label for="opening-dates">Dates d’ouverture</label>
            <input type="date" id="opening-start-date" placeholder="Début">
            <input type="date" id="opening-end-date" placeholder="Fin">
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
                    // On récupère toutes les offres (titre,ville,images)
                    $infosOffre = $dbh->query('SELECT * FROM tripenarvor._offre');
                    $infosOffre = $infosOffre->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($infosOffre as $offre) {
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

                        if (!empty($images)) { // si le tableau n'est pas vide...
                            /* On récupère uniquement la première image.
                            Une offre peut avoir plusieurs images. Mais on n'en affiche qu'une seule sur cette page.
                            On pourrait afficher aléatoirement chaque image, mais on serait vite perdus...*/

                            $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image');
                            $recupLienImage->bindValue(":code_image", $images[0]['code_image']);
                            $recupLienImage->execute();

                            $offre_image = $recupLienImage->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $offre_image = "";
                        }
                        if (!empty($offre["option_a_la_une"])) {
                            if ($offre["en_ligne"]) {
                                ?>

                                <article class="card-a-la-une">
                                    <form id="form-voir-offre" action="detail_offre.php" method="POST" class="form-voir-offre">
                                        <input type="hidden" name="uneOffre"
                                            value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                        <input type="hidden" name="vueDetails" value="1">
                                        <div class="image-background-card-a-la-une">
                                            <img src="<?php echo './' . $offre_image['url_image']; ?>" alt="">
                                            <div class="raison-sociale-card-a-la-une">
                                                <p><?php echo $offre["titre_offre"]; ?></p>
                                                <input id="btn-voir-offre" type="submit" name="vueDetails"
                                                    value="Voir l'offre &#10132;">
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



        <div class="titres-offres">
            <h2 class="titre-les-offres">Nouveautés</h2>
        </div>

        <section id="offers-list-new">
            <?php

            // On récupère toutes les offres (titre,ville,images)
            $infosOffre = $dbh->query('SELECT * FROM tripenarvor._offre order by date_publication desc;');
            $infosOffre = $infosOffre->fetchAll(PDO::FETCH_ASSOC);

            $nbOffreAfficher = 0;

            $traductionDate = [
                "Monday" => "lundi",
                "Tuesday" => "mardi",
                "Wednesday" => "mercredi",
                "Thursday" => "jeudi",
                "Friday" => "vendredi",
                "Saturday" => "samedi",
                "Sunday" => "dimanche"
            ];

            $date = new DateTime();
            $dateFr = $traductionDate[$date->format('l')];

            foreach ($infosOffre as $offre) {

                // Récupérer la ville
                $villeOffre = $dbh->prepare('SELECT ville FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
                $villeOffre->bindParam(":code_adresse", $offre["code_adresse"]);
                $villeOffre->execute();
                $villeOffre = $villeOffre->fetch(); // Récupérer la ville (ou NULL si pas trouvé)
            

                $queries = [
                    'restauration' => 'SELECT * FROM tripenarvor.offre_restauration WHERE code_offre = :code_offre',
                    'parc_attractions' => 'SELECT * FROM tripenarvor.offre_parc_attractions WHERE code_offre = :code_offre',
                    'spectacle' => 'SELECT * FROM tripenarvor.offre_spectacle WHERE code_offre = :code_offre',
                    'visite' => 'SELECT * FROM tripenarvor.offre_visite WHERE code_offre = :code_offre',
                    'activite' => 'SELECT * FROM tripenarvor.offre_activite WHERE code_offre = :code_offre'
                ];

                $type_offre = null;
                $details_offre = null;

                // Parcourez les requêtes et exécutez-les
                foreach ($queries as $type => $sql) {
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':code_offre', $offre["code_offre"], PDO::PARAM_INT);
                    $stmt->execute();

                    // Vérifiez si une ligne est retournée
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $type_offre = $type;
                        $details_offre = $result;
                        break;
                    }
                }


                // Récupérer les images
                $imagesOffre = $dbh->prepare('SELECT code_image FROM tripenarvor._son_image WHERE code_offre = :code_offre');
                $imagesOffre->bindParam(":code_offre", $offre["code_offre"]);
                $imagesOffre->execute();

                // on recupère toutes les images sous forme de tableau
                $images = $imagesOffre->fetchAll(PDO::FETCH_ASSOC);


                $horaireOffre = $dbh->prepare('SELECT ouverture, fermeture FROM tripenarvor._horaire WHERE code_horaire = (SELECT ' . $dateFr . ' FROM tripenarvor._offre WHERE code_offre = :code_offre);');
                $horaireOffre->bindParam(":code_offre", $offre["code_offre"]);
                $horaireOffre->execute();

                $horaire = ($horaireOffre->fetch(PDO::FETCH_ASSOC));


                if ($type_offre == 'parc_attractions' || $type_offre == 'restauration' || $type_offre == 'activite') {
                    $periodeOffre = $dbh->prepare('SELECT date_ouverture, date_fermeture FROM tripenarvor._offre_' . $type_offre . ' WHERE code_offre = :code_offre;');
                    $periodeOffre->bindParam(":code_offre", $offre["code_offre"]);
                    $periodeOffre->execute();

                    $periode = ($periodeOffre->fetch(PDO::FETCH_ASSOC));
                } else {
                    $periode = "";
                }


                if (!empty($horaire)) {

                    // Exemple d'horaires d'ouverture et de fermeture (remplacer par vos valeurs réelles)
                    $ouverture = new DateTime($date->format("Y-m-d ") . $horaire["ouverture"]);
                    $fermeture = new DateTime($date->format("Y-m-d ") . $horaire["fermeture"]);

                    // Comparer les horaires
                    if ($ouverture <= $date && $fermeture > $date) {
                        // Si on est dans l'intervalle d'ouverture
                        $interval = $fermeture->diff($date);

                        if (($interval->h < 1) || ($interval->h == 1 && $interval->i == 0)) {
                            // Si la fermeture est dans moins de 1 heure
                            $dataStatusEng = "closing-soon";
                            $dataStatusFr = "Ferme bientôt";
                        } else {
                            // Si on est ouvert normalement
                            $dataStatusEng = "open";
                            $dataStatusFr = "Ouvert";
                        }
                    } elseif ($ouverture > $date || $fermeture <= $date) {
                        // Si on est avant l'ouverture
                        $interval = $ouverture->diff($date);

                        if (($interval->h < 1) || ($interval->h == 1 && $interval->i == 0)) {
                            // Si l'ouverture est dans moins de 1 heure
                            $dataStatusEng = "opening-soon";
                            $dataStatusFr = "Ouvre bientôt";
                        } else {
                            // Si on est fermé
                            $dataStatusEng = "closed";
                            $dataStatusFr = "Fermé";
                        }
                    }
                } else if (empty($horaire)) {

                    $dataStatusEng = "closed";
                    $dataStatusFr = "Fermé";

                } else if ($type_offre === 'spectacle') {
                    // Si il n'a pas d'horaire du tt
                    $dataStatusEng = "xx";
                    $dataStatusFr = "xx";
                }


                if ($type_offre == 'visite' || $type_offre == 'spectacle') {
                    $eventOffre = $dbh->prepare('SELECT date_' . $type_offre . ', heure_' . $type_offre . ' FROM tripenarvor._offre_' . $type_offre . ' WHERE code_offre = :code_offre;');
                    $eventOffre->bindParam(":code_offre", $offre["code_offre"]);
                    $eventOffre->execute();

                    $event = ($eventOffre->fetch(PDO::FETCH_ASSOC));
                } else {
                    $event = "";
                }


                if (!empty($images)) { // si le tableau n'est pas vide...
                    /* On récupère uniquement la première image.
                    Une offre peut avoir plusieurs images. Mais on n'en affiche qu'une seule sur cette page.
                    On pourrait afficher aléatoirement chaque image, mais on serait vite perdus...*/

                    $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image');
                    $recupLienImage->bindValue(":code_image", $images[0]['code_image']);
                    $recupLienImage->execute();

                    $offre_image = $recupLienImage->fetch(PDO::FETCH_ASSOC);
                } else {
                    $offre_image = "";
                }


                if ($offre["en_ligne"] && $nbOffreAfficher < 10) {
                    ?>
                    <article class="offer-new">

                        <img src="<?php echo "./" . $offre_image['url_image']; ?>" alt="aucune image">

                        <div class="offer-new-details">
                            <form id="form-voir-offre" action="detail_offre.php" method="POST">
                                <h2><?php echo $offre["titre_offre"]; ?></h2>

                                <p>
                                    <span class="iconify" data-icon="mdi:map-marker"
                                        style="color: #BDC426; font-size: 1.2em; margin-right: 5px; margin-bottom: -4px;"></span>
                                    <?php echo $villeOffre["ville"]; ?>
                                </p>

                                <p>
                                    <?php
                                    if (!empty($offre["note_moyenne"])) {
                                        echo '⭐ ' . $offre["note_moyenne"];
                                    } else {
                                        echo "Aucune note";
                                    }
                                    ?>
                                </p>

                                <p style="color: #2DD7A4; font-weight: bold;"><?php echo $offre["tarif"]; ?>€</p>

                                <p>
                                    <?php
                                    if ($type_offre != 'spectacle') {
                                        echo $dataStatusFr;
                                    }
                                    ?>
                                </p>

                                <?php if (($type_offre == "visite" || $type_offre == "spectacle") && !empty($event['date_' . $type_offre])) { ?>
                                    <p><?php echo $event['date_' . $type_offre] . ' à ' . $event['heure_' . $type_offre]; ?></p>
                                <?php } ?>

                                <p class="recent">Posté récemment : <?php echo tempsEcouleDepuisPublication($offre); ?></p>


                                <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                <input type="hidden" name="vueDetails" value="1">
                            </form>
                        </div>

                    </article>
                    <?php
                }
                $nbOffreAfficher++;
            }

            ?>
        </section>

        <div class="vu-recemment-titre-carrousel">
            <h2 class="titre-vu-recemment">Vu récemment</h2>

            <?php

            $AConsulterRecemment = false; 
            
            foreach ($_COOKIE as $name => $value) {
                if (strpos($name, 'consulte_recemment') === 0) {
                    $AConsulterRecemment = true;
                    break; 
                }
            }

            if (isset($AConsulterRecemment)) {
                ?>
                <div class="vu-recemment-carrousel">
                    <button class="card-scroll-btn card-scroll-btn-left" onclick="scrollcontentLeftR()">&#8249;</button>
                    <section class="vu-recemment">
                        <?php
                        try {
                            require_once __DIR__ . ("/../.security/config.php");

                            // Créer une instance PDO
                            $dbh = new PDO($dsn, $username, $password);
                        } catch (PDOException $e) {
                            print "Erreur!: " . $e->getMessage() . "<br/>";
                            die();
                        }


                        foreach (array_reverse($_COOKIE) as $cookie => $code_offre) {
                            if (strpos($cookie, 'consulte_recemment') === 0) {

                                // Récupérer l'offre
                                $offreStmt = $dbh->prepare('SELECT * FROM tripenarvor._offre WHERE code_offre = :code_offre');
                                $offreStmt->bindParam(":code_offre", $code_offre, PDO::PARAM_INT);
                                $offreStmt->execute();
                                $offre = $offreStmt->fetch(PDO::FETCH_ASSOC);

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

                                if (!empty($images)) { // si le tableau n'est pas vide...
                                    /* On récupère uniquement la première image.
                                    Une offre peut avoir plusieurs images. Mais on n'en affiche qu'une seule sur cette page.
                                    On pourrait afficher aléatoirement chaque image, mais on serait vite perdus...*/

                                    $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image');
                                    $recupLienImage->bindValue(":code_image", $images[0]['code_image']);
                                    $recupLienImage->execute();

                                    $offre_image = $recupLienImage->fetch(PDO::FETCH_ASSOC);
                                } else {
                                    $offre_image = "";
                                }

                                $consulter = $dbh->prepare('select * from tripenarvor._consulte where code_offre = :code_offre');
                                $consulter->bindValue(":code_offre", $offre["code_offre"]);
                                $consulter->execute();
                                $consulter = $consulter->fetch();

                                if (!empty($consulter)) {
                                    if ($offre["en_ligne"]) {
                                        ?>

                                        <article class="card-vu-recemment">
                                            <form id="form-voir-offre" action="detail_offre.php" method="POST" class="form-voir-offre">
                                                <input type="hidden" name="uneOffre"
                                                    value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                                <input type="hidden" name="vueDetails" value="1">
                                                <div class="image-background-card-vu-recemment">
                                                    <img src="<?php echo './' . $offre_image['url_image']; ?>" alt="">
                                                    <div class="raison-sociale-card-vu-recemment">
                                                        <p><?php echo $offre["titre_offre"]; ?></p>

                                                    </div>

                                                </div>
                                            </form>
                                        </article>

                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </section>
                    <button class="card-scroll-btn card-scroll-btn-right" onclick="scrollcontentRightR()">&#8250;</button>
                </div>
                <?php
            } else {
                ?>
                <a class="bloc_consulter_recemment_a" href="connexion_membre.php">
                    <section class="bloc_consulter_recemment">
                        <img src="images/introuvable.png" alt="image pour la connexion">
                        <p>Vous n'avez consulté d'offres pour le moment</p>
                    </section>
                </a>

                <?php
                echo "";
            }
            ?>
        </div>

        <div class="titres-offres">
            <h2 class="titre-les-offres">A proximité de moi</h2>
        </div>

        <div id="map" style="height: 618px; width: 82%; margin: auto; z-index: 2;"></div>



        <div class="titres-offres">
            <h2 class="titre-les-offres">Toutes les offres</h2>
        </div>

        <section id="offers-list" style="margin-bottom: 12vh;">
            <?php

            // On récupère toutes les offres (titre,ville,images)
            $infosOffre = $dbh->query('SELECT * FROM tripenarvor._offre');
            $infosOffre = $infosOffre->fetchAll(PDO::FETCH_ASSOC);

            $traductionDate = [
                "Monday" => "lundi",
                "Tuesday" => "mardi",
                "Wednesday" => "mercredi",
                "Thursday" => "jeudi",
                "Friday" => "vendredi",
                "Saturday" => "samedi",
                "Sunday" => "dimanche"
            ];

            $date = new DateTime();
            $dateFr = $traductionDate[$date->format('l')];

            foreach ($infosOffre as $offre) {


                // Récupérer la ville
                $villeOffre = $dbh->prepare('SELECT ville FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
                $villeOffre->bindParam(":code_adresse", $offre["code_adresse"]);
                $villeOffre->execute();
                $villeOffre = $villeOffre->fetch(); // Récupérer la ville (ou NULL si pas trouvé)
            

                $queries = [
                    'restauration' => 'SELECT * FROM tripenarvor.offre_restauration WHERE code_offre = :code_offre',
                    'parc_attractions' => 'SELECT * FROM tripenarvor.offre_parc_attractions WHERE code_offre = :code_offre',
                    'spectacle' => 'SELECT * FROM tripenarvor.offre_spectacle WHERE code_offre = :code_offre',
                    'visite' => 'SELECT * FROM tripenarvor.offre_visite WHERE code_offre = :code_offre',
                    'activite' => 'SELECT * FROM tripenarvor.offre_activite WHERE code_offre = :code_offre'
                ];

                $type_offre = null;
                $details_offre = null;

                // Parcourez les requêtes et exécutez-les
                foreach ($queries as $type => $sql) {
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':code_offre', $offre["code_offre"], PDO::PARAM_INT);
                    $stmt->execute();

                    // Vérifiez si une ligne est retournée
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $type_offre = $type;
                        $details_offre = $result;
                        break;
                    }
                }


                // Récupérer les images
                $imagesOffre = $dbh->prepare('SELECT code_image FROM tripenarvor._son_image WHERE code_offre = :code_offre');
                $imagesOffre->bindParam(":code_offre", $offre["code_offre"]);
                $imagesOffre->execute();

                // on recupère toutes les images sous forme de tableau
                $images = $imagesOffre->fetchAll(PDO::FETCH_ASSOC);


                $horaireOffre = $dbh->prepare('SELECT ouverture, fermeture FROM tripenarvor._horaire WHERE code_horaire = (SELECT ' . $dateFr . ' FROM tripenarvor._offre WHERE code_offre = :code_offre);');
                $horaireOffre->bindParam(":code_offre", $offre["code_offre"]);
                $horaireOffre->execute();

                $horaire = ($horaireOffre->fetch(PDO::FETCH_ASSOC));


                if ($type_offre == 'parc_attractions' || $type_offre == 'restauration' || $type_offre == 'activite') {
                    $periodeOffre = $dbh->prepare('SELECT date_ouverture, date_fermeture FROM tripenarvor._offre_' . $type_offre . ' WHERE code_offre = :code_offre;');
                    $periodeOffre->bindParam(":code_offre", $offre["code_offre"]);
                    $periodeOffre->execute();

                    $periode = ($periodeOffre->fetch(PDO::FETCH_ASSOC));
                } else {
                    $periode = "";
                }


                if (!empty($horaire)) {

                    // Exemple d'horaires d'ouverture et de fermeture (remplacer par vos valeurs réelles)
                    $ouverture = new DateTime($date->format("Y-m-d ") . $horaire["ouverture"]);
                    $fermeture = new DateTime($date->format("Y-m-d ") . $horaire["fermeture"]);

                    // Comparer les horaires
                    if ($ouverture <= $date && $fermeture > $date) {
                        // Si on est dans l'intervalle d'ouverture
                        $interval = $fermeture->diff($date);

                        if (($interval->h < 1) || ($interval->h == 1 && $interval->i == 0)) {
                            // Si la fermeture est dans moins de 1 heure
                            $dataStatusEng = "closing-soon";
                            $dataStatusFr = "Ferme bientôt";
                        } else {
                            // Si on est ouvert normalement
                            $dataStatusEng = "open";
                            $dataStatusFr = "Ouvert";
                        }
                    } elseif ($ouverture > $date || $fermeture <= $date) {
                        // Si on est avant l'ouverture
                        $interval = $ouverture->diff($date);

                        if (($interval->h < 1) || ($interval->h == 1 && $interval->i == 0)) {
                            // Si l'ouverture est dans moins de 1 heure
                            $dataStatusEng = "opening-soon";
                            $dataStatusFr = "Ouvre bientôt";
                        } else {
                            // Si on est fermé
                            $dataStatusEng = "closed";
                            $dataStatusFr = "Fermé";
                        }
                    }
                } else if (empty($horaire)) {

                    $dataStatusEng = "closed";
                    $dataStatusFr = "Fermé";

                } else if ($type_offre === 'spectacle') {
                    // Si il n'a pas d'horaire du tt
                    $dataStatusEng = "xx";
                    $dataStatusFr = "xx";
                }


                if ($type_offre == 'visite' || $type_offre == 'spectacle') {
                    $eventOffre = $dbh->prepare('SELECT date_' . $type_offre . ', heure_' . $type_offre . ' FROM tripenarvor._offre_' . $type_offre . ' WHERE code_offre = :code_offre;');
                    $eventOffre->bindParam(":code_offre", $offre["code_offre"]);
                    $eventOffre->execute();

                    $event = ($eventOffre->fetch(PDO::FETCH_ASSOC));
                } else {
                    $event = "";
                }


                if (!empty($images)) { // si le tableau n'est pas vide...
                    /* On récupère uniquement la première image.
                    Une offre peut avoir plusieurs images. Mais on n'en affiche qu'une seule sur cette page.
                    On pourrait afficher aléatoirement chaque image, mais on serait vite perdus...*/

                    $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image');
                    $recupLienImage->bindValue(":code_image", $images[0]['code_image']);
                    $recupLienImage->execute();

                    $offre_image = $recupLienImage->fetch(PDO::FETCH_ASSOC);
                } else {
                    $offre_image = "";
                }


                if ($offre["en_ligne"]) {
                    /* echo $villeOffre["ville"]; */
                    ?>
                    <article class="offer <?php if (!empty($offre['option_en_relief']) || !empty($offre['option_a_la_une'])) {
                        echo "en_relief";
                    } ?>" data-category="<?php echo $type_offre; ?>" data-price="<?php echo $offre["tarif"]; ?>"
                        data-rate="<?php echo $offre["note_moyenne"]; ?>" location="<?php echo $villeOffre["ville"]; ?>"
                        data-status="<?php echo $dataStatusEng; ?>" data-event=<?php if (!empty($event)) {
                               echo $event['date_' . $type_offre];
                           } else {
                               echo "";
                           } ?> data-period-o=<?php if (!empty($periode)) {
                                 echo $periode['date_ouverture'];
                             } else {
                                 echo "";
                             } ?> data-period-c=<?php if (!empty($periode)) {
                                   echo $periode['date_fermeture'];
                               } else {
                                   echo "";
                               } ?>" data-title="<?php echo trim($offre["titre_offre"]); ?>">

                        <img src="<?php echo "./" . $offre_image['url_image']; ?>" alt="aucune image">

                        <div class="offer-details">
                            <h2><?php echo $offre["titre_offre"]; ?></h2>

                            <p>
                                <span class="iconify" data-icon="mdi:map-marker"
                                    style="color: #BDC426; font-size: 1.2em; margin-right: 5px; margin-bottom: -4px;"></span>
                                <?php echo $villeOffre["ville"]; ?>
                            </p>

                            <span><?php echo tempsEcouleDepuisPublication($offre); ?></span>

                            <p>
                                <?php
                                if (!empty($offre["note_moyenne"])) {
                                    echo '⭐ ' . $offre["note_moyenne"];
                                } else {
                                    echo "Aucune note";
                                }
                                ?>
                            </p>

                            <p style="color: #2DD7A4; font-weight: bold;"><?php echo $offre["tarif"]; ?>€</p>
                            <p>
                                <?php
                                if ($type_offre != 'spectacle') {
                                    echo $dataStatusFr;
                                }
                                ?>
                            </p>

                            <?php if (($type_offre == "visite" || $type_offre == "spectacle") && !empty($event['date_' . $type_offre])) { ?>
                                <p><?php echo $event['date_' . $type_offre] . ' à ' . $event['heure_' . $type_offre]; ?></p>
                            <?php } ?>

                            <form id="form-voir-offre" action="detail_offre.php" method="POST">
                                <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                <input type="hidden" name="vueDetails" value="1">

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
        <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
        <?php
        if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
            echo "compte_membre_tel.php";
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

    <link rel="stylesheet" href="leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.Default.css" />
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
    <script>
        let markersArray = [];

        var markers = L.markerClusterGroup({
            zoomToBoundsOnClick: true,
            spiderfyOnMaxZoom: false,
            showCoverageOnHover: false,
            disableClusteringAtZoom: 35,
            removeOutsideVisibleBounds: false
        });

        document.addEventListener("DOMContentLoaded", function () {
            const mapElement = document.getElementById('map');

            if (mapElement) {
                try {
                    // Création de la carte et centrage sur la Bretagne
                    var map = L.map('map', { zoomControl: false }).setView([48.2020, -2.9326], 8);

                    L.tileLayer('https://tile.thunderforest.com/atlas/{z}/{x}/{y}.png?apikey=a62b465402a64a49862f451a157e69ca', {
                        attribution: '&copy; Thunderforest',
                        maxZoom: 20
                    }).addTo(map);

                    //ajout des controles de la map
                    L.control.zoom({
                        position: 'topleft'
                    }).addTo(map);

                    let index = 0;
                    <?php
                    $adresses = $dbh->query('SELECT o.*, a.*, 
                           (SELECT i.url_image 
                            FROM tripenarvor._son_image si 
                            JOIN tripenarvor._image i ON si.code_image = i.code_image 
                            WHERE si.code_offre = o.code_offre 
                            LIMIT 1) AS url_image
                           FROM tripenarvor._offre o 
                           JOIN tripenarvor._adresse a ON o.code_adresse = a.code_adresse 
                           WHERE o.en_ligne = true');

                    $api_key = "AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig";

                    foreach ($adresses as $adr) {
                        $adresse_complete = $adr['adresse_postal'] . ', ' . $adr['code_postal'] . ' ' . $adr['ville'] . ', France';
                        $adresse_enc = urlencode($adresse_complete);
                        $adresse_maps = $adresse_complete;
                        $adresse_code = $adr['code_offre'];

                        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$adresse_enc&key=$api_key";
                        $response = file_get_contents($url);
                        $json = json_decode($response, true);

                        if (isset($json['results'][0])) {
                            $latitude = $json['results'][0]['geometry']['location']['lat'];
                            $longitude = $json['results'][0]['geometry']['location']['lng'];

                            // URL d'itinéraire Google Maps
                            $url_maps = "https://www.google.com/maps/dir/?api=1&destination=" . urlencode($adresse_maps);









                            // Recherche de l'offre correspondant à l'adresse actuelle
                            $monOffre = null;
                            foreach ($infosOffre as $offre) {
                                if ($offre['code_offre'] == $adr["code_offre"]) {
                                    $monOffre = $offre;
                                    break;
                                }
                            }

                            // Récupérer la ville
                            $villeOffre = $dbh->prepare('SELECT ville FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
                            $villeOffre->bindParam(":code_adresse", $monOffre["code_adresse"]);
                            $villeOffre->execute();
                            $villeOffre = $villeOffre->fetch(); // Récupérer la ville (ou NULL si pas trouvé)
                    

                            $queries = [
                                'restauration' => 'SELECT * FROM tripenarvor.offre_restauration WHERE code_offre = :code_offre',
                                'parc_attractions' => 'SELECT * FROM tripenarvor.offre_parc_attractions WHERE code_offre = :code_offre',
                                'spectacle' => 'SELECT * FROM tripenarvor.offre_spectacle WHERE code_offre = :code_offre',
                                'visite' => 'SELECT * FROM tripenarvor.offre_visite WHERE code_offre = :code_offre',
                                'activite' => 'SELECT * FROM tripenarvor.offre_activite WHERE code_offre = :code_offre'
                            ];

                            $type_offre = null;

                            // Parcourez les requêtes et exécutez-les
                            foreach ($queries as $type => $sql) {
                                $stmt = $dbh->prepare($sql);
                                $stmt->bindParam(':code_offre', $monOffre["code_offre"], PDO::PARAM_INT);
                                $stmt->execute();

                                // Vérifiez si une ligne est retournée
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                if ($result) {
                                    $type_offre = $type;
                                    break;
                                }
                            }

                            $horaireOffre = $dbh->prepare('SELECT ouverture, fermeture FROM tripenarvor._horaire WHERE code_horaire = (SELECT ' . $dateFr . ' FROM tripenarvor._offre WHERE code_offre = :code_offre);');
                            $horaireOffre->bindParam(":code_offre", $monOffre["code_offre"]);
                            $horaireOffre->execute();

                            $horaire = ($horaireOffre->fetch(PDO::FETCH_ASSOC));


                            if ($type_offre == 'parc_attractions' || $type_offre == 'restauration' || $type_offre == 'activite') {
                                $periodeOffre = $dbh->prepare('SELECT date_ouverture, date_fermeture FROM tripenarvor._offre_' . $type_offre . ' WHERE code_offre = :code_offre;');
                                $periodeOffre->bindParam(":code_offre", $monOffre["code_offre"]);
                                $periodeOffre->execute();

                                $periode = ($periodeOffre->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $periode = "";
                            }


                            if (!empty($horaire)) {

                                // Exemple d'horaires d'ouverture et de fermeture (remplacer par vos valeurs réelles)
                                $ouverture = new DateTime($date->format("Y-m-d ") . $horaire["ouverture"]);
                                $fermeture = new DateTime($date->format("Y-m-d ") . $horaire["fermeture"]);

                                // Comparer les horaires
                                if ($ouverture <= $date && $fermeture > $date) {
                                    // Si on est dans l'intervalle d'ouverture
                                    $interval = $fermeture->diff($date);

                                    if (($interval->h < 1) || ($interval->h == 1 && $interval->i == 0)) {
                                        // Si la fermeture est dans moins de 1 heure
                                        $dataStatusEng = "closing-soon";
                                    } else {
                                        // Si on est ouvert normalement
                                        $dataStatusEng = "open";
                                    }
                                } elseif ($ouverture > $date || $fermeture <= $date) {
                                    // Si on est avant l'ouverture
                                    $interval = $ouverture->diff($date);

                                    if (($interval->h < 1) || ($interval->h == 1 && $interval->i == 0)) {
                                        // Si l'ouverture est dans moins de 1 heure
                                        $dataStatusEng = "opening-soon";
                                    } else {
                                        // Si on est fermé
                                        $dataStatusEng = "closed";
                                    }
                                }
                            } else if (empty($horaire)) {

                                $dataStatusEng = "closed";

                            } else if ($type_offre === 'spectacle') {
                                // Si il n'a pas d'horaire du tt
                                $dataStatusEng = "xx";
                            }


                            if ($type_offre == 'visite' || $type_offre == 'spectacle') {
                                $eventOffre = $dbh->prepare('SELECT date_' . $type_offre . ', heure_' . $type_offre . ' FROM tripenarvor._offre_' . $type_offre . ' WHERE code_offre = :code_offre;');
                                $eventOffre->bindParam(":code_offre", $monOffre["code_offre"]);
                                $eventOffre->execute();

                                $event = ($eventOffre->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $event = "";
                            }









                            // Contenu de la popup avec styles améliorés
                            $popupContent = "<div class='popup-container' style='width:230px; border-radius:8px; overflow:hidden; font-family:\"K2D\", sans-serif;'>";

                            // Image avec overlay dégradé
                            if (!empty($adr['url_image'])) {
                                $popupContent .= "<div style='position:relative;'>";
                                $popupContent .= "<img src='./" . $adr['url_image'] . "' style='width:95%; height:100px;'>";
                                $popupContent .= "<div style='position:absolute; top:0; right:0; background-color:#F28322; color:white; padding:4px 8px; border-bottom-left-radius:8px; font-size:14px; font-weight:bold;'>" . $adr['tarif'] . " €</div>";
                                $popupContent .= "</div>";
                            }

                            // Contenu texte
                            $popupContent .= "<div style='padding: 12px; background-color: white;'>";
                            $popupContent .= "<h3 style='margin:0 0 8px 0; color:#333; font-size:16px; font-weight:600; line-height:1.2;'>" . addslashes($adr['titre_offre']) . "</h3>";
                            $popupContent .= "<p style='margin:0 0 8px 0; font-size:14px; color:#555;'><span class=\"iconify\" data-icon=\"mdi:map-marker\" style=\"color: #BDC426; font-size: 1.2em; vertical-align: middle; margin-right: 3px;\"></span> " . addslashes($adr['ville']) . "</p>";

                            // Boutons d'action
                            $popupContent .= "<div style='display:flex; justify-content:space-between; margin-top:10px;'>";

                            $popupContent .= '<form action="detail_offre.php" method="POST" class="voir_offre_carte">';
                            $popupContent .= '<input type="hidden" name="uneOffre" value="' . htmlspecialchars(serialize($monOffre)) . '" class="data_offre_carte">';
                            $popupContent .= '<input type="hidden" name="vueDetails" value="1">';
                            $popupContent .= '<button type="submit" style="padding:6px 12px; background-color:#2DD7A4; color:white; border:none; cursor:pointer; border-radius:4px; font-size:12px; font-weight:500; transition:all 0.2s;">Voir l\'offre</button>';
                            $popupContent .= '</form>';



                            $popupContent .= "<a href='" . $url_maps . "' target='_blank' style='display:inline-block; padding:6px 12px; background-color:#F28322; color:white; text-decoration:none; border-radius:4px; font-size:12px; font-weight:500; transition:all 0.2s;'><span class=\"iconify\" data-icon=\"mdi:navigation\" style=\"font-size: 1.1em; vertical-align: middle; margin-right: 3px;\"></span>Itinéraire</a>";
                            $popupContent .= "</div>";

                            $popupContent .= "</div>";
                            $popupContent .= "</div>";

                            echo "var marker = L.marker([$latitude, $longitude], {icon: customIcon, dataOffer: '" . htmlspecialchars(json_encode($monOffre), ENT_QUOTES, 'UTF-8') . "',dataStatus: '" . json_encode($dataStatusEng) . "', dataCategory: '" . json_encode($type_offre) . "', dataCity: '" . json_encode($villeOffre) . "'});";
                            echo "markersArray.push(marker);";

                            echo "var popup = L.popup({closeButton: false, autoClose: false, closeOnClick: false, className: 'custom-popup'}).setContent(\"" . addslashes($popupContent) . "\");";
                            echo "marker.bindPopup(popup);";

                            // Ajouter les événements de survol améliorés
                            echo "marker.on('mouseover', function(e) { this.openPopup(); });";

                            // Utiliser une variable pour suivre l'état de survol
                            echo "var isMouseOverPopup = false;";

                            // Gestion du mouseout sur le marqueur
                            echo "marker.on('mouseout', function(e) {";
                            echo "    setTimeout(() => {";
                            echo "        if (!isMouseOverPopup) {";
                            echo "            this.closePopup();";
                            echo "        }";
                            echo "    }, 50);";
                            echo "});";

                            echo "markers.addLayer(marker);";
                            //echo "map.addLayer(marker);";
                            echo "index++;";

                        }
                    }
                    ?>

                    map.addLayer(markers);
                    console.log("Carte Leaflet avec clusters initialisée avec succès");

                    // Ajouter des écouteurs d'événements aux popups après leur création
                    setTimeout(function () {
                        document.querySelectorAll('.leaflet-popup').forEach(function (popup) {
                            popup.addEventListener('mouseenter', function () {
                                isMouseOverPopup = true;
                            });

                            popup.addEventListener('mouseleave', function () {
                                isMouseOverPopup = false;
                                // Trouver le marqueur associé et fermer la popup
                                markers.eachLayer(function (marker) {
                                    if (marker.isPopupOpen()) {
                                        marker.closePopup();
                                    }
                                });
                            });
                        });
                    }, 1000); // Attendre que les popups soient créées

                    // Ajouter des écouteurs sur les popups à chaque fois qu'une popup s'ouvre
                    map.on('popupopen', function (e) {
                        setTimeout(function () {
                            let popup = e.popup._container;
                            if (popup) {
                                popup.addEventListener('mouseenter', function () {
                                    isMouseOverPopup = true;
                                });

                                popup.addEventListener('mouseleave', function () {
                                    isMouseOverPopup = false;
                                    e.target.closePopup();
                                });
                            }
                        }, 10);
                    });


                } catch (error) {
                    console.error("Erreur lors de l'initialisation de la carte :", error);
                }
            } else {
                console.error("L'élément #map n'existe pas dans le DOM");
            }
        });

        var customIcon = L.icon({
            iconUrl: './images/ping.png',
            iconSize: [50, 40],
            iconAnchor: [15, 40],
            popupAnchor: [0, -35]
        });

        toggleMarkerVisibility = (index, visible) => {
            if (markersArray[index]) {
                //markersArray[index].setOpacity(visible ? 1 : 0);

                const marker = markersArray[index];

                // Si le marqueur fait partie d'un cluster, on doit le retirer ou l'ajouter au groupe de clusters
                if (visible) {
                    markers.addLayer(marker);  // Ajouter le marqueur au groupe de clusters
                } else {
                    markers.removeLayer(marker);  // Retirer le marqueur du groupe de clusters
                }
            }
        }

        // Vérifie si l'appareil est tactile
        if ('ontouchstart' in window || navigator.maxTouchPoints) {
            map.eachLayer(function (layer) {
                if (layer.getPopup) {
                    layer.on('click', function () {
                        this.openPopup();
                    });
                }
            });
        }


        // Ajoutez du CSS pour améliorer l'apparence
        document.head.insertAdjacentHTML('beforeend', `
    <style>
    .custom-popup .leaflet-popup-content-wrapper {
        padding: 0;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 3px 14px rgba(0,0,0,0.2);
    }
    .custom-popup .leaflet-popup-content {
        margin: 0;
        width: auto !important;
    }
    .custom-popup .leaflet-popup-tip-container {
        left: 50%;
        margin-left: -10px;
    }
    </style>
`);
    </script>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            // Récupération des éléments
            const offerItems = document.querySelectorAll('.offer');

            const searchInput = document.querySelector('.search-input');

            const searchSelect = document.querySelectorAll('.search-select');
            const container = document.querySelector('#offers-list');

            const selectRate = document.querySelector('#select-rate');

            const priceMinInput = document.getElementById("price-min");
            const priceMaxInput = document.getElementById("price-max");
            const priceMinDisplay = document.getElementById("price-min-display");
            const priceMaxDisplay = document.getElementById("price-max-display");

            const selectStatus = document.querySelector('#select-statut');

            const eventDate = document.querySelector('#event-date');

            const openingStartDate = document.getElementById('opening-start-date');
            const openingEndDate = document.getElementById('opening-end-date');

            ///////////////////////////////////////////////////
            ///           Fonction filtre leaflet           ///
            ///////////////////////////////////////////////////
            function leafletFilters() {
                const query = searchInput.value.toLowerCase().trim();
                
                const category = document.querySelector('.search-select:nth-of-type(1)').value;
                const rate = document.querySelector('#select-rate').value;
                const status = document.querySelector('#select-statut').value;
                
                markers.clearLayers();  // Effacer tous les marqueurs existants du groupe de clusters
                markersArray.forEach((marker, index) => {
                    const offerData = marker.options.dataOffer;
                    let afficher = true;

                    if (offerData) {
                        let correctedJsonString = offerData.replace(/&quot;/g, '"').replace(/&#039;/g, "'");
                        let offer = JSON.parse(correctedJsonString); // Convertir en objet
                        
                        let offerText = offer.titre_offre.toLowerCase(); // Prendre le titre de l’offre
                        let offerCity = marker.options.dataCity.toLowerCase();

                        let offerCategory = marker.options.dataCategory; // Prendre le titre de l’offre
                        const offerRate = offerData.note_moyenne;
                        const offerStatus = marker.options.dataStatus;

                        
                        // Si l'offre correspond à la recherche, on la montre
                        if (offerText.includes(query) || offerCity.includes(query)) {
                            
                        } else {
                            afficher=false;
                        }

                        // Si l'offre correspond à la recherche, on la montre
                        if ((category === 'all' || category === offerCategory) &&
                            (!rate || rate === offerRate || (offerRate > rate && offerRate < rate + 1)) &&
                            (!status || status === offerStatus)) {
                            
                        } else {
                            afficher=false;
                        }

                        if (afficher) {
                            toggleMarkerVisibility(index, 1); // Rendre visible
                        } else {
                            toggleMarkerVisibility(index, 0); // Cacher le marqueur
                        }
                    }
                });
                
            }
            
            ///////////////////////////////////////////////////
            ///            Barre de recherche               ///
            ///////////////////////////////////////////////////
            // Barre de recherche
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase().trim();
                
                // Parcourir chaque offre et vérifier si elle correspond à la recherche
                offerItems.forEach(offer => {
                    const offerLoc = offer.getAttribute('location').toLowerCase();
                    const offerText = offer.querySelector('h2').textContent.toLowerCase().trim();
                    
                    if (offerLoc.includes(query) || offerText.includes(query)) {
                        offer.classList.remove('hidden');
                    } else {
                        offer.classList.add('hidden');
                    }
                });

                // Réinitialiser les marqueurs
                // markers.clearLayers();  // Effacer tous les marqueurs existants du groupe de clusters
                // markersArray.forEach((marker, index) => {
                //     const offerData = marker.options.dataOffer;

                //     if (offerData) {
                //         let correctedJsonString = offerData.replace(/&quot;/g, '"').replace(/&#039;/g, "'");
                //         let offer = JSON.parse(correctedJsonString); // Convertir en objet
                //         let offerText = offer.titre_offre.toLowerCase(); // Prendre le titre de l’offre
                //         let offerCity = marker.options.dataCity.toLowerCase();

                //         // Si l'offre correspond à la recherche, on la montre
                //         if (offerText.includes(query) || offerCity.includes(query)) {
                //             toggleMarkerVisibility(index, 1); // Rendre visible
                //         } else {
                //             toggleMarkerVisibility(index, 0); // Cacher le marqueur
                //         }
                //     }
                // });
                leafletFilters();

            });


            ///////////////////////////////////////////////////
            ///            Selecteur cat, d et c            ///
            ///////////////////////////////////////////////////
            // Selecteurs de tri
            searchSelect.forEach(select => {
                select.addEventListener('change', function () {
                    const category = document.querySelector('.search-select:nth-of-type(1)').value;
                    const priceOrder = document.querySelector('.search-select:nth-of-type(2)').value;
                    const noteOrder = document.querySelector('.search-select:nth-of-type(3)').value;
                    const rate = document.querySelector('#select-rate').value;
                    const status = document.querySelector('#select-statut').value;

                    // Filtrer par catégorie
                    offerItems.forEach(offer => {
                        const offerCategory = offer.getAttribute('data-category');
                        const offerRate = offer.getAttribute('data-rate');
                        const offerStatus = offer.getAttribute('data-status');

                        if ((category === 'all' || category === offerCategory) &&
                            (!rate || rate === offerRate || (offerRate > rate && offerRate < rate + 1)) &&
                            (!status || status === offerStatus)) {
                            offer.style.removeProperty('display');
                        } else {
                            offer.style.display = "none";
                        }
                    });

                    // Réinitialiser les marqueurs
                    markers.clearLayers();  // Effacer tous les marqueurs existants du groupe de clusters
                    markersArray.forEach((marker, index) => {
                        const offerData = marker.options.dataOffer;
    
                        if (offerData) {
                            let correctedJsonString = offerData.replace(/&quot;/g, '"').replace(/&#039;/g, "'");
                            let offer = JSON.parse(correctedJsonString); // Convertir en objet
                            let offerCategory = marker.options.dataCategory; // Prendre le titre de l’offre
                            const offerRate = offerData.note_moyenne;
                            const offerStatus = marker.options.dataStatus;
    
                            // Si l'offre correspond à la recherche, on la montre
                            if ((category === 'all' || category === offerCategory) &&
                                (!rate || rate === offerRate || (offerRate > rate && offerRate < rate + 1)) &&
                                (!status || status === offerStatus)) {
                                toggleMarkerVisibility(index, 1); // Rendre visible
                            } else {
                                toggleMarkerVisibility(index, 0); // Cacher le marqueur
                            }
                        }
                    });

                    // Trier les offres visibles
                    let offers = Array.from(document.querySelectorAll('.offer:not(.hidden)'));

                    if (priceOrder) {
                        offers.sort((a, b) => {
                            const priceA = parseFloat(a.getAttribute('data-price')) || 0;
                            const priceB = parseFloat(b.getAttribute('data-price')) || 0;
                            return priceOrder === 'croissantP' ? priceA - priceB : priceB - priceA;
                        });
                    }

                    if (noteOrder) {
                        offers.sort((a, b) => {
                            const noteA = parseFloat(a.getAttribute('data-rate')) || 0;
                            const noteB = parseFloat(b.getAttribute('data-rate')) || 0;
                            return noteOrder === 'croissantN' ? noteA - noteB : noteB - noteA;
                        });
                    }

                    // Réorganiser dans le DOM
                    container.innerHTML = ''; // Clear container
                    offers.forEach(offer => {
                        container.appendChild(offer); // Append sorted offers
                    });
                });
            });


            ///////////////////////////////////////////////////
            ///      Selecteur de la fourchette de prix     ///
            ///////////////////////////////////////////////////
            // Mettre à jour les affichages du prix
            function updatePriceDisplay() {
                // Empêche price-min de dépasser price-max
                if (parseInt(priceMinInput.value) > parseInt(priceMaxInput.value)) {
                    priceMinInput.value = priceMaxInput.value;
                }

                // Empêche price-max d'être inférieur à price-min
                if (parseInt(priceMaxInput.value) < parseInt(priceMinInput.value)) {
                    priceMaxInput.value = priceMinInput.value;
                }

                priceMinDisplay.textContent = priceMinInput.value;
                priceMaxDisplay.textContent = priceMaxInput.value;
            }

            // Filtrer les offres en fonction de la fourchette de prix
            function filterOffers() {
                const minPrice = parseFloat(priceMinInput.value);
                const maxPrice = parseFloat(priceMaxInput.value);

                offerItems.forEach(offer => {
                    const offerPrice = parseFloat(offer.getAttribute('data-price'));

                    // Vérifier si le prix de l'offre est dans la fourchette
                    if (offerPrice >= minPrice && offerPrice <= maxPrice) {
                        offer.style.removeProperty('display'); // Afficher l'offre
                    } else {
                        offer.style.display = "none"; // Cacher l'offre
                    }
                });
            }

            // Ajouter des événements sur les sliders
            priceMinInput.addEventListener("input", () => {
                updatePriceDisplay(); // Mettre à jour l'affichage des prix
                filterOffers(); // Appliquer le filtre
            });

            priceMaxInput.addEventListener("input", () => {
                updatePriceDisplay(); // Mettre à jour l'affichage des prix
                filterOffers(); // Appliquer le filtre
            });


            ///////////////////////////////////////////////////
            ///            Selecteur date event             ///
            ///////////////////////////////////////////////////
            eventDate.addEventListener('change', function () {
                const date = eventDate.value;

                // Filtrer par catégorie
                offerItems.forEach(offer => {

                    const offerEvent = offer.getAttribute('data-event');
                    if (!date || date === offerEvent) {
                        offer.style.removeProperty('display');
                    } else {
                        offer.style.display = "none";
                    }
                });
            });



            ///////////////////////////////////////////////////
            ///           Selecteur date periode            ///
            ///////////////////////////////////////////////////
            function filterByOpeningDates() {
                const startDate = openingStartDate.value;
                const endDate = openingEndDate.value;

                // Parcourir chaque offre et vérifier les critères
                offerItems.forEach(offer => {
                    const offerPeriodStart = offer.getAttribute('data-period-o');
                    const offerPeriodEnd = offer.getAttribute('data-period-c');
                    const offerCategory = offer.getAttribute('data-category');

                    // Condition pour afficher l'offre
                    if (((!startDate || (!offerPeriodEnd && offerCategory != 'spectacle' && offerCategory != 'visite')) &&
                        (!endDate || (!offerPeriodEnd && offerCategory != 'spectacle' && offerCategory != 'visite'))) ||
                        ((startDate <= offerPeriodEnd && startDate >= offerPeriodStart) || (endDate >= offerPeriodStart && endDate <= offerPeriodEnd))) {
                        offer.style.removeProperty('display'); // Afficher l'offre
                    } else {
                        offer.style.display = "none"; // Masquer l'offre
                    }
                    if (offerCategory == 'parc_attractions') {
                        console.log(offerCategory);
                        if (!startDate || (!offerPeriodEnd && offerCategory != 'spectacle' && offerCategory != 'visite')) {
                            console.log("Boucle n1 : ok\n");
                        }
                        if (!endDate || (!offerPeriodEnd && offerCategory != 'spectacle' && offerCategory != 'visite')) {
                            console.log("Boucle n2 : ok\n");
                        }
                        if (startDate <= offerPeriodEnd && startDate >= offerPeriodStart) {
                            console.log("Boucle n3 : ok\n");
                        }
                        if (endDate >= offerPeriodStart && endDate <= offerPeriodEnd) {
                            console.log("Boucle n4 : ok\n");
                        }
                    }
                });
            }

            // Ajouter un écouteur d'événement sur les champs de date
            openingStartDate.addEventListener('change', filterByOpeningDates);
            openingEndDate.addEventListener('change', filterByOpeningDates);

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* GESTION DE L'ENVOI DES FORMULAIRES SUR CARDS CLIQUABLES */


            // Cartes des offres à la une


            let CartesAlaUne = document.getElementsByClassName('card-a-la-une');

            Array.from(CartesAlaUne).forEach(card => {
                let formALaUne = card.querySelector('form');

                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    formALaUne.submit();
                });
            });

            // Cartes des nouvelles offres

            let CartesNouveautes = document.getElementsByClassName('offer-new');

            Array.from(CartesNouveautes).forEach(card => {
                let formNouveaute = card.querySelector('form');
                /*             let CardDetails = card.getElementsByClassName('offer-new-details');
                            Array.from(CardDetails).forEach(detailedCard => {
                                let formNouveaute = detailedCard.querySelector('form');
                            }); */

                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    formNouveaute.submit();
                });
            });

            // Cartes des offres vues récemment

            let CartesVuesRecemment = document.getElementsByClassName('card-vu-recemment');

            Array.from(CartesVuesRecemment).forEach(card => {

                let formVuRecemment = card.querySelector('form');

                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    formVuRecemment.submit();
                });

            });

            // Cartes de TOUTES les offres

            let toutesCards = document.getElementById('offers-list').getElementsByClassName('offer');

            Array.from(toutesCards).forEach(card => {
                let formCard = card.querySelector('form');

                card.addEventListener('click', function (e) {
                    e.preventDefault();
                    formCard.submit();
                });
            });
        });

    </script>
    <script>
        function updateCategoryText() {
            let categoryOption = document.querySelector('.search-select option[value="all"]');
            if (window.innerWidth <= 429) {
                categoryOption.textContent = "Catégorie";
            } else {
                categoryOption.textContent = "Catégories";
            }
        }

        window.addEventListener("resize", updateCategoryText);
        window.addEventListener("DOMContentLoaded", updateCategoryText);
    </script>
</body>

</html>
