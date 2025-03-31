<?php
require_once __DIR__ . ("/../.security/config.php");

if (headers_sent($file, $line)) {
    die("Les en-têtes ont déjà été envoyés dans le fichier $file à la ligne $line.");
}
ob_start();
session_start();




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
        $retour = "il y a " . $jours . " jour(s)";
    } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent) {
        $semaines = floor($jours / 7);
        $retour = "il y a " . $semaines . " semaine(s)";
    } elseif ($mois < 12) {
        $retour = "il y a " . $mois . " mois";
    } else {
        $retour = "il y a " . $annees . " an(s)";
    }

    return $retour;
}

function afficherHoraire($jour)
{
    if (!empty($jour["ouverture"])) {
        $dateTimeO = DateTime::createFromFormat('H:i:s', $jour["ouverture"]);
        $dateTimeF = DateTime::createFromFormat('H:i:s', $jour["fermeture"]);

        $horaire = ": " . $dateTimeO->format('H') . "h" . $dateTimeO->format('i') . " - " . $dateTimeF->format('H') . "h" . $dateTimeF->format('i');
    } else {
        $horaire = ": Fermé";
    }
    return $horaire;
}

// Vérifie si le formulaire a été soumis    

// Créer une instance PDO avec les bons paramètres
// $dbh = new PDO($dsn, $username, $password);

$details_offre = null;

if (!isset($_POST['vueDetails']) && !isset($_SESSION['detail_offre'])) {
    header('location: voir_offres.php');
    exit;
}

/* DUREE DU COOKIE */
$duréeCookie = 7;


if (isset($_POST['vueDetails']) || isset($_SESSION['detail_offre'])) {
    //echo $_POST["uneOffre"];

    // si le formulaire est bien récupéré
    if (isset($_POST['vueDetails'])) {
        $details_offre = unserialize($_POST["uneOffre"]); // on récupère son contenu
    } else {
        // si on n'a pas de POST alors on ne vient pas de l'accueil, DONC il y a une session.
        $details_offre = $_SESSION['detail_offre'];
    }

    $code_offre = $details_offre["code_offre"]; // on récupère le code de l'offre envoyé

    if (isset($_COOKIE[$cookie_name])) {
        setcookie('consulte_recemment' . $code_offre, '', time() - 3600, "/"); // Expire immédiatement
    }
    setcookie('consulte_recemment' . $code_offre, $code_offre, time() + (60 * 60 * 24 * 1), "/");

    if (!empty($details_offre)) { // si l'offre existe

        if (isset($_SESSION["membre"]) && !empty($_SESSION["membre"])) {
            try {
                $consulter = $dbh->prepare('
                    INSERT INTO tripenarvor._consulte (consulter, date_consultation, code_compte, code_offre)
                    VALUES (TRUE, NOW(), :code_compte, :code_offre);
                ');

                $consulter->execute([
                    ':code_compte' => $_SESSION["membre"]["code_compte"],
                    ':code_offre' => $code_offre
                ]);

            } catch (PDOException $e) {
                $consulter = $dbh->prepare('
                    update tripenarvor._consulte set date_consultation = NOW() 
                        where code_offre = :code_offre and code_compte = :code_compte;
                ');

                $consulter->execute([
                    ':code_compte' => $_SESSION["membre"]["code_compte"],
                    ':code_offre' => $code_offre
                ]);
                // Ici, vous pouvez enregistrer l'erreur dans un fichier log ou en base de données
                error_log("Erreur lors de l'insertion dans _consulte: " . $e->getMessage());
                // Ne rien faire d'autre pour éviter l'affichage d'erreurs
                // Vous pouvez aussi afficher un message générique à l'utilisateur si nécessaire
            }
        }


        // Une offre a forcément au moins une image. 
        // On récupère l'image (ou les images) associée(s)

        $stmt = $dbh->prepare('SELECT url_image FROM tripenarvor._son_image natural join tripenarvor._image WHERE code_offre = :code_offre;');
        $stmt->execute([':code_offre' => $code_offre]);
        $images_offre = $stmt->fetchAll(PDO::FETCH_NUM);



        $tags_offre_stmt = $dbh->prepare('
                SELECT nom_tag 
                FROM tripenarvor._tags 
                WHERE code_tag IN (
                    SELECT code_tag 
                    FROM tripenarvor._son_tag 
                    WHERE code_offre = :code_offre
                )
            ');
        $tags_offre_stmt->bindValue(':code_offre', $code_offre, PDO::PARAM_INT);
        $tags_offre_stmt->execute();
        $tags_offre = $tags_offre_stmt->fetchAll(PDO::FETCH_NUM);

        if (!empty($details_offre["lundi"])) {
            $h_lundi = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["lundi"] . ";");
            $h_lundi = $h_lundi->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_lundi = null;
        }
        if (!empty($details_offre["mardi"])) {
            $h_mardi = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["mardi"] . ";");
            $h_mardi = $h_mardi->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_mardi = null;
        }
        if (!empty($details_offre["mercredi"])) {
            $h_mercredi = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["mercredi"] . ";");
            $h_mercredi = $h_mercredi->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_mercredi = null;
        }
        if (!empty($details_offre["jeudi"])) {
            $h_jeudi = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["jeudi"] . ";");
            $h_jeudi = $h_jeudi->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_jeudi = null;
        }
        if (!empty($details_offre["vendredi"])) {
            $h_vendredi = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["vendredi"] . ";");
            $h_vendredi = $h_vendredi->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_vendredi = null;
        }
        if (!empty($details_offre["samedi"])) {
            $h_samedi = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["samedi"] . ";");
            $h_samedi = $h_samedi->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_samedi = null;
        }
        if (!empty($details_offre["dimanche"])) {
            $h_dimanche = $dbh->query('select * from tripenarvor._horaire where code_horaire = ' . $details_offre["dimanche"] . ";");
            $h_dimanche = $h_dimanche->fetch(PDO::FETCH_ASSOC);
        } else {
            $h_dimanche = null;
        }

        // Utilisez des requêtes préparées
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
            $stmt->bindParam(':code_offre', $code_offre, PDO::PARAM_INT);
            $stmt->execute();

            // Vérifiez si une ligne est retournée
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $type_offre = $type;
                $details_offre = $result;
                break;
            }
        }




        // On récupère aussi l'adresse indiquée, ainsi que les horaires (si non nulles)

        $adresse_offre = $dbh->prepare('SELECT * FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
        $adresse_offre->bindValue(":code_adresse", $details_offre["code_adresse"]);
        $adresse_offre->execute();
        $adresse_offre = $adresse_offre->fetch(PDO::FETCH_ASSOC);

        $_SESSION['detail_offre'] = $details_offre;
    }
}

// Adresse que tu veux convertir
$adresse = $adresse_offre["adresse_postal"] . " " . $adresse_offre["ville"];

// Encode l'adresse pour l'URL
$adresse_enc = urlencode($adresse);

$api_key = "AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig";

// URL de l'API Geocoding
$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$adresse_enc&key=$api_key";

// Appel de l'API Google Geocoding
$response = file_get_contents($url);
$json = json_decode($response, true);

// Vérifie si la réponse contient des résultats
if (isset($json['results'][0])) {
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
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <title>Détail offre</title>
    <link rel="stylesheet" href="styles.css">
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
            var location = {
                lat: <?php echo $latitude; ?>,
                lng: <?php echo $longitude; ?>
            };
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

            <div class="detail_offre_hotel-detail">

                <div class="detail_offre_hotel-header">

                    <div class="detail_offre_hotel-info">
                        <h1 style="
                        margin-top: -1vh;
                        display: flex;
                        font-size: 25px;"> <?php echo $details_offre["titre_offre"]; ?></h1>

                        <p>📍 <?php echo $adresse_offre["ville"] . ", " . $adresse_offre["code_postal"]; ?></p>

                        <p><i class="fas fa-clock"></i> Publié
                            <?php echo tempsEcouleDepuisPublication($details_offre); ?>
                        </p>

                        <!-- <div class="detail_offre_rating">
                        ⭐ 5.0 (255 avis)
                    </div> -->
                    </div>

                    <div class="detail_offre_price-button">

                        <p class="detail_offre_price"><?php echo $details_offre["tarif"]; ?>€</p>
                        <div class="btn-poster-avis-voir-site">
                            <form action="poster_un_avis.php" method="POST">
                                <input type="hidden" name="uneOffre"
                                    value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                                <input id="btn-poster-avis" type="submit" name="posterAvis" value="Poster un avis">
                            </form>

                            <?php if (!empty($details_offre["site_web"])) { ?> <a
                                    href="<?php echo $details_offre["site_web"]; ?>"><button
                                        class="visit-button_detailoffre">Voir le site ➔</button></a> <?php } ?>
                        </div>
                    </div>

                </div>

                <div class="a-la-une-wrapper">

                    <button class="card-scroll-btn card-scroll-btn-left" onclick="scrollcontentLeft()">&#8249;</button>
                    <section class="a-la-une">
                        <?php
                        foreach ($images_offre as $photo) {
                            ?>

                            <article class="card-a-la-une">
                                <div class="image-background-card-a-la-une">
                                    <img src="<?php echo $photo[0]; ?>" alt="">
                                </div>
                            </article>

                            <?php
                        }
                        ?>
                    </section>

                    <button class="card-scroll-btn card-scroll-btn-right"
                        onclick="scrollcontentRight()">&#8250;</button>

                </div>

                <div class="detail_offre_description">

                    <h2>Résumé</h2>
                    <p><?php echo $details_offre["_resume"]; ?></p>

                    <h2>Description</h2>
                    <p><?php echo $details_offre["_description"]; ?></p>

                    <h2>Nos services</h2>
                    <div class="info-dropdown">

                        <button class="info-button" onclick="toggleInfoBox()">
                            Détails
                            <span class="arrow">&#9662;</span>
                        </button>
                        <div class="info-box" id="infoBox"
                            style="max-height: 0; padding: 0; overflow: hidden; width: 25.5em; transition: max-height 0.3s ease, padding 0.3s ease;">

                            <?php
                            if ($type_offre === "restauration") {
                                ?>
                                <h3 style="margin-top: 1em;">Repas</h3>
                                <p><?php echo $details_offre["repas"]; ?></p>

                                <h3 style="margin-top: 1em;">Gamme de prix</h3>
                                <p><?php echo $details_offre["gamme_prix"]; ?></p>
                                <?php
                            } else if ($type_offre === "parc_attractions") {
                                ?>
                                    <h3 style="margin-top: 1em;">Age requis</h3>
                                    <p><?php echo $details_offre["age_requis"]; ?></p>

                                    <h3 style="margin-top: 1em;">Nombre d'attractions</h3>
                                    <p><?php echo $details_offre["nombre_attractions"]; ?></p>
                                <?php
                            } else if ($type_offre === "spectacle") {
                                ?>
                                        <h3 style="margin-top: 1em;">Capacité d'accueil</h3>
                                        <p><?php echo $details_offre["capacite_accueil"]; ?></p>

                                        <h3 style="margin-top: 1em;">Durée</h3>
                                        <p><?php echo $details_offre["duree"]; ?></p>

                                <?php
                            } else if ($type_offre === "visite") {
                                ?>
                                            <h3 style="margin-top: 1em;">Visite Guidée</h3>
                                            <p>Oui</p>

                                            <h3 style="margin-top: 1em;">Durée</h3>
                                            <p><?php echo $details_offre["duree"]; ?></p>
                                <?php
                            } else if ($type_offre === "activite") {
                                ?>
                                                <h3 style="margin-top: 1em;">Durée</h3>
                                                <p><?php echo $details_offre["duree"]; ?></p>

                                                <h3 style="margin-top: 1em;">Age requis</h3>
                                                <p><?php echo $details_offre["age_requis"]; ?></p>

                                                <h3 style="margin-top: 1em;">Prestations incluses</h3>
                                                <p><?php echo $details_offre["prestations_incluses"]; ?></p>

                                                <h3 style="margin-top: 1em;">Prestations non-incluses</h3>
                                                <p><?php echo $details_offre["prestations_non_incluses"]; ?></p>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>

                <div class="accessibilite_infos_detail_offre">
                    <h2>Accessibilité</h2>
                    <p><?php echo $details_offre["accessibilite"]; ?></p>
                </div>

                <div class="detail_offre_icons">
                    <h2 style="margin-left: 0.1em;">Tags</h2>

                    <?php
                    foreach ($tags_offre as $tag) {
                        ?>
                        <div class="detail_offre_icon">
                            <p><?php echo $tag[0]; ?></p>
                        </div>
                        <?php
                    }
                    ?>



                </div>
            </div>

            <?php
            if ($type_offre === "restauration") {
                ?>
                <div class="Detail_offre_periode">
                    <h2>Périodes d'ouverture</h2>
                    <p>
                        <?php
                        // Vérifiez si les champs date_ouverture et date_fermeture existent et ne sont pas vides
                        if (!empty($details_offre["date_ouverture"]) && !empty($details_offre["date_fermeture"])) {
                            // Formatez les dates d'ouverture et de fermeture
                            $date_ouverture = date("j F Y", strtotime($details_offre["date_ouverture"]));
                            $date_fermeture = date("j F Y", strtotime($details_offre["date_fermeture"]));
                            echo "De <span>$date_ouverture</span> à <span>$date_fermeture</span>";
                        } else {
                            // Si les champs sont vides, afficher "Ouvert toute l'année"
                            echo "Ouvert toute l'année";
                        }
                        ?>
                    </p>
                </div>
                <?php
            } else if ($type_offre === "parc_attractions") {
                ?>
                    <div class="Detail_offre_periode">
                        <h2>Périodes d'ouverture</h2>
                        <p>
                            <?php
                            // Vérifiez si les champs date_ouverture et date_fermeture existent et ne sont pas vides
                            if (!empty($details_offre["date_ouverture"]) && !empty($details_offre["date_fermeture"])) {
                                // Formatez les dates d'ouverture et de fermeture
                                $date_ouverture = date("j F Y", strtotime($details_offre["date_ouverture"]));
                                $date_fermeture = date("j F Y", strtotime($details_offre["date_fermeture"]));
                                echo "De <span>$date_ouverture</span> à <span>$date_fermeture</span>";
                            } else {
                                // Si les champs sont vides, afficher "Ouvert toute l'année"
                                echo "Ouvert toute l'année";
                            }
                            ?>
                        </p>
                    </div>
                <?php
            } else if ($type_offre === "spectacle") {
                ?>

                        <div class="Detail_offre_horaire">
                            <h2>Horaire du Spectacle</h2>
                            <p>Date :
                                <span>
                                <?php
                                // Vérifiez si la date est définie et non nulle
                                if (isset($details_offre["date_spectacle"])) {
                                    // Formatez la date SQL (YYYY-MM-DD) en format lisible
                                    echo date("l, j F Y", strtotime($details_offre["date_spectacle"]));
                                } else {
                                    echo "Date non disponible";
                                }
                                ?>
                                </span>
                            </p>
                            <p>Heure :
                                <span>
                                <?php
                                // Vérifiez si l'heure est définie et non nulle
                                if (isset($details_offre["heure_spectacle"])) {
                                    // Formatez l'heure SQL (HH:MM:SS) en format lisible
                                    echo date("H\h i", strtotime($details_offre["heure_spectacle"]));
                                } else {
                                    echo "Heure non disponible";
                                }
                                ?>
                                </span>
                            </p>
                        </div>

                <?php
            } else if ($type_offre === "visite") {
                ?>

                            <div class="Detail_offre_horaire">
                                <h2>Horaire de la Visite</h2>
                                <p>Date :
                                    <span>
                                <?php
                                // Vérifiez si la date est définie et non nulle
                                if (isset($details_offre["date_visite"])) {
                                    // Formatez la date SQL (YYYY-MM-DD) en format lisible
                                    echo date("l, j F Y", strtotime($details_offre["date_visite"]));
                                } else {
                                    echo "Date non disponible";
                                }
                                ?>
                                    </span>
                                </p>
                                <p>Heure :
                                    <span>
                                <?php
                                // Vérifiez si l'heure est définie et non nulle
                                if (isset($details_offre["heure_visite"])) {
                                    // Formatez l'heure SQL (HH:MM:SS) en format lisible
                                    echo date("H\h i", strtotime($details_offre["heure_visite"]));
                                } else {
                                    echo "Heure non disponible";
                                }
                                ?>
                                    </span>
                                </p>
                            </div>

                <?php
            } else if ($type_offre === "activite") {
                ?>
                                <div class="Detail_offre_periode">
                                    <h2>Périodes d'ouverture</h2>
                                    <p>
                            <?php
                            // Vérifiez si les champs date_ouverture et date_fermeture existent et ne sont pas vides
                            if (!empty($details_offre["date_ouverture"]) && !empty($details_offre["date_fermeture"])) {
                                // Formatez les dates d'ouverture et de fermeture
                                $date_ouverture = date("j F Y", strtotime($details_offre["date_ouverture"]));
                                $date_fermeture = date("j F Y", strtotime($details_offre["date_fermeture"]));
                                echo "De <span>$date_ouverture</span> à <span>$date_fermeture</span>";
                            } else {
                                // Si les champs sont vides, afficher "Ouvert toute l'année"
                                echo "Ouvert toute l'année";
                            }
                            ?>
                                    </p>
                                </div>
                <?php
            }
            ?>



            <?php
            if (
                !empty($h_lundi["ouverture"]) ||
                !empty($h_mardi["ouverture"]) ||
                !empty($h_mercredi["ouverture"]) ||
                !empty($h_jeudi["ouverture"]) ||
                !empty($h_vendredi["ouverture"]) ||
                !empty($h_samedi["ouverture"]) ||
                !empty($h_dimanche["ouverture"])
            ) {
                ?>
                <div class="Detail_offre_ouverture_global_desktop">

                    <h2>Horaires</h2>
                    <ul class="hours_desktop_detail_offre">
                        <li><span>Lundi</span><?php echo afficherHoraire($h_lundi); ?></li>
                        <li><span>Mardi</span><?php echo afficherHoraire($h_mardi); ?></li>
                        <li><span>Mercredi</span><?php echo afficherHoraire($h_mercredi); ?></li>
                        <li><span>Jeudi</span><?php echo afficherHoraire($h_jeudi); ?></li>
                        <li><span>Vendredi</span><?php echo afficherHoraire($h_vendredi); ?></li>
                        <li><span>Samedi</span><?php echo afficherHoraire($h_samedi); ?></li>
                        <li><span>Dimanche</span><?php echo afficherHoraire($h_dimanche); ?></li>
                    </ul>

                </div>
                <?php
            }
            ?>





            <div class="detail_offre_localisation">
                <h2>Localisation</h2>
                <iframe class="map-frame"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig&q=<?php echo $latitude; ?>,<?php echo $longitude; ?>"
                    style="border:0;margin: auto 11em; width: 79vw; height:70vh" allowfullscreen="" loading="lazy">
                    border: 0;
                </iframe>
            </div>
            <?php
            // Récupérer la moyenne des notes
            $moyenne_note = $dbh->prepare('SELECT avg(note) FROM tripenarvor._avis WHERE code_offre = :code_offre and note<>0 and blacklister<>TRUE');
            $moyenne_note->bindValue(':code_offre', intval($code_offre), PDO::PARAM_INT);
            $moyenne_note->execute();
            $note_moyenne = $moyenne_note->fetchColumn();

            // Récupérer le nombre d'avis
            $nb_avis = $dbh->prepare('SELECT count(*) FROM tripenarvor._avis as avis_principals WHERE code_offre = :code_offre and blacklister<>TRUE and avis_principals.code_avis not in (select code_reponse FROM tripenarvor._reponse)');
            $nb_avis->bindValue(':code_offre', intval($code_offre), PDO::PARAM_INT);
            $nb_avis->execute();
            $nombre_d_avis = $nb_avis->fetchColumn();

            $appreciationGenerale = "";

            // Déterminer l'appréciation générale selon la note moyenne
            if ($note_moyenne <= 1) {
                $appreciationGenerale = "À éviter";
            } elseif ($note_moyenne <= 2) {
                $appreciationGenerale = "Peut mieux faire";
            } elseif ($note_moyenne <= 3) {
                $appreciationGenerale = "Correct";
            } elseif ($note_moyenne <= 4) {
                $appreciationGenerale = "Très Bien";
            } elseif ($note_moyenne <= 5) {
                $appreciationGenerale = "Exceptionnel";
            } else {
                $appreciationGenerale = "Valeur hors échelle";
            }

            // Fonction pour récupérer les réponses, y compris les sous-réponses (récursivité)
            function getResponses($dbh, $code_avis)
            {
                $stmt = $dbh->prepare('
                SELECT 
                    reponse.*, 
                    COALESCE(membre_reponse.prenom, \'Utilisateur\') AS prenom,
                    COALESCE(membre_reponse.nom, \'supprimé\') AS nom,
                    pro_reponse.raison_sociale AS raison_sociale_pro
                FROM tripenarvor._reponse
                INNER JOIN tripenarvor._avis AS reponse 
                    ON reponse.code_avis = tripenarvor._reponse.code_reponse
                LEFT JOIN tripenarvor._membre AS membre_reponse 
                    ON membre_reponse.code_compte = reponse.code_compte
                LEFT JOIN tripenarvor._professionnel AS pro_reponse 
                    ON pro_reponse.code_compte = reponse.code_compte
                WHERE tripenarvor._reponse.code_avis = :code_avis
            ');
                $stmt->bindValue(':code_avis', $code_avis, PDO::PARAM_INT);
                $stmt->execute();
                $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Récursivité : Ajouter les sous-réponses
                foreach ($reponses as &$reponse) {
                    $reponse['sous_reponses'] = getResponses($dbh, $reponse['code_avis']);
                }
                return $reponses;
            }


            // Fonction pour afficher les avis et les réponses récursivement
            function afficherAvis($avis, $niveau = 0)
            {
                global $dbh;
                global $details_offre;
                // Déterminer l'affichage selon le type d'utilisateur
                if (!empty($avis['raison_sociale_pro'])) {
                    // Si c'est un professionnel
                    $prenom = $avis['raison_sociale_pro'];
                    $nom = "";
                    $color = "--orange";
                } elseif (isset($_SESSION["membre"]["code_compte"]) && $avis['code_compte'] == $_SESSION["membre"]["code_compte"]) {
                    // Si l'utilisateur est celui qui a écrit l'avis ou la réponse
                    $prenom = "Moi";
                    $nom = "";
                    if ($avis["blacklister"]) {
                        $color = "--gris-fonce";
                    } else {
                        $color = "--vert-clair";
                    }
                } elseif (!empty($avis['prenom']) && !empty($avis['nom'])) {
                    // Si c'est un membre classique
                    $prenom = $avis['prenom'];
                    $nom = $avis['nom'];
                } else {
                    // Si l'utilisateur est supprimé
                    $prenom = "Utilisateur";
                    $nom = "supprimé";
                }

                // Texte de l'appréciation basé sur la note
                $appreciation = match ($avis["note"]) {
                    1 => "Insatisfaisant",
                    2 => "Passable",
                    3 => "Correct",
                    4 => "Excellent",
                    5 => "Parfait",
                    default => "Non noté",
                };

                // Calcul de la marge pour les sous-réponses
                $marge = $niveau * 5; // Indentation
                if (!$avis["blacklister"] || ($avis["blacklister"] && isset($_SESSION['membre']) && $avis["code_compte"] == $_SESSION['membre']['code_compte'])) {
                    ?>
                    <div class="avis" style="margin-left:<?php echo $marge; ?>vw">
                        <div class="avis-content" style="<?php if ($avis["blacklister"]) {
                            echo "opacity: 50%;";
                        } ?>">
                            <h3 class="avis">
                                <?php if ($niveau > 0): ?>
                                    <div class="note_prenom">
                                        Réponse |
                                        <span class="nom_avis"
                                            style="color:var(<?php echo $color; ?>)"><?php echo htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="note_prenom">
                                        <?php echo htmlspecialchars($avis['note']) . '.0 ' . $appreciation . " "; ?> |
                                        <span class="nom_avis"
                                            style="color:var(<?php echo $color; ?>)"><?php echo htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?>
                                        </span>
                                        <?php
                                        if ($avis["blacklister"]) {
                                            ?>
                                            <img src="images/icones/jeton.png" alt="jeton" class="jeton">
                                            <em>(Cet avis est blacklisté)</em>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>



                                <?php
                                if (isset($_SESSION['membre']['code_compte'])) {
                                    // Récupérer l'état du vote de l'utilisateur pour cet avis
                                    $codeAvis = $avis['code_avis']; // Assurez-vous que $avis contient bien le code de l'avis
                                    $codeCompte = $_SESSION['membre']['code_compte']; // L'utilisateur doit être connecté
                        
                                    $stmt = $dbh->prepare("SELECT pouce FROM tripenarvor._pouce WHERE code_avis = :code_avis AND code_compte = :code_compte");
                                    $stmt->execute([':code_avis' => $codeAvis, ':code_compte' => $codeCompte]);
                                    $voteState = $stmt->fetchColumn();

                                    // Si aucun vote n'existe, définir le vote par défaut à 0 (ni like ni dislike)
                                    if ($voteState === false) {
                                        $voteState = 0;
                                    }
                                }
                                ?>
                                <div class="signalement_repondre">
                                    <?php if (isset($_SESSION['membre']['code_compte']) && !$avis["blacklister"]) { ?>
                                        <div class="pouce pouce<?php echo $avis['code_avis']; ?>">
                                            <!-- Pouce positif -->
                                            <img id="positiveImage<?php echo $avis['code_avis']; ?>"
                                                src="<?php echo $voteState == 1 ? 'images/pouce_positif_couleur.png' : 'images/pouce_positif_blanc.png'; ?>"
                                                alt="Pouce positif"
                                                onclick="togglePositiveImage(<?php echo $avis['code_avis']; ?>)">
                                            <p id="positiveCount<?php echo $avis['code_avis']; ?>">
                                                <?php echo $avis['pouce_positif']; ?>
                                            </p>
                                        </div>

                                        <div class="pouce pouce<?php echo $avis['code_avis']; ?>">
                                            <!-- Pouce négatif -->
                                            <img id="negativeImage<?php echo $avis['code_avis']; ?>"
                                                src="<?php echo $voteState == -1 ? 'images/pouce_negatif_couleur.png' : 'images/pouce_negatif_blanc.png'; ?>"
                                                alt="Pouce négatif"
                                                onclick="toggleNegativeImage(<?php echo $avis['code_avis']; ?>)">
                                            <p id="negativeCount<?php echo $avis['code_avis']; ?>">
                                                <?php echo $avis['pouce_negatif']; ?>
                                            </p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="pouce pouce<?php echo $avis['code_avis']; ?>">
                                            <!-- Pouce positif -->
                                            <img id="positiveImage" src="images/pouce_positif_blanc.png" alt="Pouce positif"
                                                style="cursor:not-allowed;">
                                            <p id="positiveCount<?php echo $avis['code_avis']; ?>">
                                                <?php echo $avis['pouce_positif']; ?>
                                            </p>
                                        </div>

                                        <div class="pouce pouce<?php echo $avis['code_avis']; ?>">
                                            <!-- Pouce négatif -->
                                            <img id="negativeImage" src="images/pouce_negatif_blanc.png" alt="Pouce négatif"
                                                style="cursor:not-allowed;">
                                            <p id="negativeCount<?php echo $avis['code_avis']; ?>">
                                                <?php echo $avis['pouce_negatif']; ?>
                                            </p>
                                        </div>
                                    <?php } ?>

                                    <div class="menu_avis">
                                        <div class="menu-container" onclick="toggleMenu(event, this)">
                                            <?php if (!$avis["blacklister"]) { ?>
                                                <div class="context-menu">
                                                    <ul>
                                                        <?php
                                                        if (isset($_SESSION['membre']['code_compte']) && $avis['code_compte'] == $_SESSION['membre']['code_compte']) {
                                                            $avis['titre_offre'] = $details_offre['titre_offre'];
                                                            ?>
                                                            <li>
                                                                <form action="modif_avis_membre.php" method="POST">
                                                                    <input type="hidden" name="unAvis"
                                                                        value="<?php echo htmlspecialchars(serialize($avis)); ?>">
                                                                    <input id="btn-repondre-avis" type="submit" name="modifierAvis"
                                                                        value="Modifier l'avis">
                                                                </form>
                                                            </li>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <li>
                                                                <form action="poster_reponse_membre.php" method="POST">
                                                                    <input type="hidden" name="unAvis"
                                                                        value="<?php echo htmlspecialchars(serialize($avis)); ?>">
                                                                    <input id="btn-repondre-avis" type="submit" name="repondreAvis"
                                                                        value="Répondre à l'avis">
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <a href="signalement_membre.php?id_avis=<?php echo htmlspecialchars($avis['code_avis']); ?>"
                                                                    title="Signaler cet avis"
                                                                    style="text-decoration: none;margin-right: 2vw;color: black;font-size: 15px;font-weight: 400;">
                                                                    Signaler l'avis
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                            <img src="images/icones/ellipsis-vertical-solid.svg" alt="Menu" width="20"
                                                height="20">
                                        </div>
                                    </div>

                                    <script>
                                        function toggleMenu(event, element) {
                                            event.stopPropagation();
                                            closeAllMenus();
                                            var menu = element.querySelector('.context-menu');
                                            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
                                        }

                                        function closeAllMenus() {
                                            document.querySelectorAll('.context-menu').forEach(menu => {
                                                menu.style.display = 'none';
                                            });
                                        }

                                        document.addEventListener('click', function () {
                                            closeAllMenus();
                                        });
                                    </script>


                                    <!-- <span class="signalement_avis_offre">
                                        <a href="signalement_membre.php?id_avis=<?php echo htmlspecialchars($avis['code_avis']); ?>"
                                            title="Signaler cet avis"
                                            style="text-decoration: none; margin-right: 2vw; font-size: 21px;">🚩</a>
                                    </span>
                                    <form action="poster_reponse_membre.php" method="POST">
                                        <input type="hidden" name="unAvis"
                                            value="<?php echo htmlspecialchars(serialize($avis)); ?>">
                                        <input id="btn-repondre-avis" type="submit" name="repondreAvis" value="↵">
                                    </form> -->
                                </div>
                            </h3>
                            <p class="avis"><?php echo html_entity_decode($avis['txt_avis']); ?></p>
                        </div>
                    </div>
                    <?php
                    // Afficher les sous-réponses si elles existent
                    if (!empty($avis['sous_reponses'])) {
                        foreach ($avis['sous_reponses'] as $sous_reponse) {
                            afficherAvis($sous_reponse, $niveau + 1); // Indentation augmentée
                        }
                    }
                }
            }


            // Récupérer tous les avis principaux (sans réponses déjà existantes)
            $tous_les_avis = $dbh->prepare('SELECT * 
FROM tripenarvor._avis
LEFT JOIN tripenarvor.membre 
    ON tripenarvor._avis.code_compte = tripenarvor.membre.code_compte
WHERE code_offre = :code_offre
  AND (
      (code_avis NOT IN (SELECT code_reponse FROM tripenarvor._reponse) 
       AND tripenarvor.membre.code_compte IS NOT NULL)
      OR 
      (code_avis NOT IN (SELECT code_reponse FROM tripenarvor._reponse) 
       AND tripenarvor.membre.code_compte IS NULL)
  );
');
            $tous_les_avis->bindValue(':code_offre', intval($code_offre), PDO::PARAM_INT);
            $tous_les_avis->execute();
            $tous_les_avis = $tous_les_avis->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les réponses imbriquées pour chaque avis principal et les sous-réponses
            foreach ($tous_les_avis as &$avis) {
                // Récupération des réponses pour l'avis principal
                $avis['sous_reponses'] = getResponses($dbh, $avis['code_avis']);
            }

            // Affichage des avis et de leurs réponses (y compris les sous-réponses)
            ?>

            <?php
            // Le PHP est maintenant fermé et le HTML est structuré de manière lisible.
            ?>






        </div>
    </div>
    </div>


    <!-- Détails de l'offre sur MOBILE -->
    <div id="body_offre_mobile">
        <header class="header_tel">
            <a href="voir_offres.php"><img class="fleche_retour_tel" src="images/Bouton_retour.png"
                    alt="bouton retour"></a>
            <style>
                .fleche_retour_tel {
                    margin-left: -7em;
                    padding-right: 5em;
                    height: 3vh;
                    margin-top: 1vh;
                }
            </style>
            <h1>Détails</h1>
        </header>

        <!-- Carrousel d'images -->
        <div class="carousel">
            <div class="carousel-images">
                <?php
                foreach ($images_offre as $photo) {
                    ?>

                    <img src="<?php echo $photo[0]; ?>" alt="">

                    <?php
                }
                ?>
            </div>
            <div class="carousel-buttons">
                <button class="carousel-button prev" onclick="prevSlide()">&#10094;</button>
                <button class="carousel-button next" onclick="nextSlide()">&#10095;</button>
            </div>
        </div>
        <div class="titre_detail_offre_responsive">
            <h1 style="
    display: flex;
    margin-left: -4.4vw;
    margin-top: -1.4vh;
"><?php echo $details_offre["titre_offre"]; ?></h1>
            <a href="<?php echo $details_offre["site_web"]; ?>" class="description-link">
                <?php if (!empty($details_offre["site_web"])) { ?> <a href="<?php echo $details_offre["site_web"]; ?>"
                        class="description-link">
                        <h3>Site Web</h3>
                    </a> <?php } ?>
            </a>

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
            <?php echo $adresse_offre["ville"] . ", " . $adresse_offre["code_postal"]; ?></< /p>

        <section>

            <div class="detail_offre_resumer_titre">
                <article>
                    <h3>Résumé</h3>
            </div>
            <p class="detail_offre_resumer"><?php echo $details_offre["_resume"]; ?></p>
            </article>

            <div class="detail_offre_resumer_titre">
                <article>
                    <h3>Description</h3>
            </div>
            <p class="detail_offre_resumer"><?php echo $details_offre["_description"]; ?></p>
            </article>

            <br>

            <h2>Nos services</h2>
            <?php
            if ($type_offre === "restauration") {
                ?>
                <h3 style="margin-top: 1em;">Repas</h3>
                <p class="detail_offre_resumer"><?php echo $details_offre["repas"]; ?></p>

                <h3 style="margin-top: 1em;">Gamme de prix</h3>
                <p class="detail_offre_resumer"><?php echo $details_offre["gamme_prix"]; ?></p>
                <?php
            } else if ($type_offre === "parc_attractions") {
                ?>
                    <h3 style="margin-top: 1em;">Age requis</h3>
                    <p class="detail_offre_resumer"><?php echo $details_offre["age_requis"]; ?></p>

                    <h3 style="margin-top: 1em;">Nombre d'attractions</h3>
                    <p class="detail_offre_resumer"><?php echo $details_offre["nombre_attractions"]; ?></p>
                <?php
            } else if ($type_offre === "spectacle") {
                ?>
                        <h3 style="margin-top: 1em;">Capacité d'accueil</h3>
                        <p class="detail_offre_resumer"><?php echo $details_offre["capacite_accueil"]; ?></p>

                        <h3 style="margin-top: 1em;">Durée</h3>
                        <p class="detail_offre_resumer"><?php echo $details_offre["duree"]; ?></p>
                <?php
            } else if ($type_offre === "visite") {
                ?>
                            <h3 style="margin-top: 1em;">Visite Guidée</h3>
                            <p class="detail_offre_resumer">Oui</p>

                            <h3 style="margin-top: 1em;">Durée</h3>
                            <p class="detail_offre_resumer"><?php echo $details_offre["duree"]; ?></p>
                <?php
            } else if ($type_offre === "activite") {
                ?>
                                <h3 style="margin-top: 1em;">Durée</h3>
                                <p class="detail_offre_resumer"><?php echo $details_offre["duree"]; ?></p>

                                <h3 style="margin-top: 1em;">Age requis</h3>
                                <p class="detail_offre_resumer"><?php echo $details_offre["age_requis"]; ?></p>

                                <h3 style="margin-top: 1em;">Prestations incluses</h3>
                                <p class="detail_offre_resumer"><?php echo $details_offre["prestations_incluses"]; ?></p>

                                <h3 style="margin-top: 1em;">Prestations non-incluses</h3>
                                <p class="detail_offre_resumer"><?php echo $details_offre["prestations_non_incluses"]; ?></p>
                <?php
            }
            ?>

        </section>
        <article class="prix">
            <span>
                <h3>Tarif minimal</h3>
                <p class="tarif"><?php echo $details_offre["tarif"]; ?>€</p>
            </span>
            <!--<button id="bouton_publier">Publier un avis →</button>-->
        </article>
        <div class="detail_offre_resumer_titre">
            <h2>Accessibilité</h2>
            <p><?php echo $details_offre["accessibilite"]; ?></p>
        </div>

        <div class="global_service_detail_offre">
            <h3>Tags</h3>

            <div class="services">
                <?php
                foreach ($tags_offre as $tag) {
                    ?>
                    <div class="service">
                        <p><?php echo $tag[0]; ?></p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
        if (
            !empty($h_lundi["ouverture"]) ||
            !empty($h_mardi["ouverture"]) ||
            !empty($h_mercredi["ouverture"]) ||
            !empty($h_jeudi["ouverture"]) ||
            !empty($h_vendredi["ouverture"]) ||
            !empty($h_samedi["ouverture"]) ||
            !empty($h_dimanche["ouverture"])
        ) {
            ?>
            <div class="Detail_offre_ouverture_global">

                <h3>Horaires</h3>
                <ul class="hours">
                    <li><span>Lundi</span><?php echo afficherHoraire($h_lundi); ?></li>
                    <li><span>Mardi</span><?php echo afficherHoraire($h_mardi); ?></li>
                    <li><span>Mercredi</span><?php echo afficherHoraire($h_mercredi); ?></li>
                    <li><span>Jeudi</span><?php echo afficherHoraire($h_jeudi); ?></li>
                    <li><span>Vendredi</span><?php echo afficherHoraire($h_vendredi); ?></li>
                    <li><span>Samedi</span><?php echo afficherHoraire($h_samedi); ?></li>
                    <li><span>Dimanche</span><?php echo afficherHoraire($h_dimanche); ?></li>
                </ul>

            </div>





            <?php
        }
        ?>

        <div class="Detail_offre_ouverture_global">
            <h3>Localisation</h3>
            <iframe class="map-frame"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyASKQTHbmzXG5VZUcCMN3YQPYBVAgbHUig&q=<?php echo $latitude; ?>,<?php echo $longitude; ?>"
                style="border:0;margin: auto; width:85vw; height:50vh" allowfullscreen="" loading="lazy">
            </iframe>
        </div>

        <?php
        // Récupérer la moyenne des notes
        $moyenne_note = $dbh->prepare('SELECT avg(note) FROM tripenarvor._avis WHERE code_offre = :code_offre and note<>0 and blacklister<>TRUE');
        $moyenne_note->bindValue(':code_offre', intval($code_offre), PDO::PARAM_INT);
        $moyenne_note->execute();
        $note_moyenne = $moyenne_note->fetchColumn();

        // Récupérer le nombre d'avis
        $nb_avis = $dbh->prepare('SELECT count(*) FROM tripenarvor._avis WHERE code_offre = :code_offre and blacklister<>TRUE');
        $nb_avis->bindValue(':code_offre', intval($code_offre), PDO::PARAM_INT);
        $nb_avis->execute();
        $nombre_d_avis = $nb_avis->fetchColumn();

        $appreciationGenerale = "";

        // Déterminer l'appréciation générale selon la note moyenne
        if ($note_moyenne <= 1) {
            $appreciationGenerale = "À éviter";
        } elseif ($note_moyenne <= 2) {
            $appreciationGenerale = "Peut mieux faire";
        } elseif ($note_moyenne <= 3) {
            $appreciationGenerale = "Correct";
        } elseif ($note_moyenne <= 4) {
            $appreciationGenerale = "Très Bien";
        } elseif ($note_moyenne <= 5) {
            $appreciationGenerale = "Exceptionnel";
        } else {
            $appreciationGenerale = "Valeur hors échelle";
        }

        // Fonction pour récupérer les réponses, y compris les sous-réponses (récursivité)
        // Afficher les sous-réponses en premier si elles existent
        ?>

        <script>
            // Script pour la gestion des pouces
            function updateLikeDislike(action, codeAvis) {
                fetch("update_likes.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        action: action,
                        code_avis: codeAvis
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Mise à jour des images des pouces
                            const positiveImage = document.getElementById('positiveImage' + codeAvis);
                            const negativeImage = document.getElementById('negativeImage' + codeAvis);

                            if (data.current_vote === 1) {
                                positiveImage.src = 'images/pouce_positif_couleur.png';
                                negativeImage.src = 'images/pouce_negatif_blanc.png';
                            } else if (data.current_vote === -1) {
                                positiveImage.src = 'images/pouce_positif_blanc.png';
                                negativeImage.src = 'images/pouce_negatif_couleur.png';
                            } else {
                                positiveImage.src = 'images/pouce_positif_blanc.png';
                                negativeImage.src = 'images/pouce_negatif_blanc.png';
                            }

                            // Mise à jour des compteurs
                            document.getElementById('positiveCount' + codeAvis).textContent = data.pouce_positif;
                            document.getElementById('negativeCount' + codeAvis).textContent = data.pouce_negatif;
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Erreur réseau : ", error);
                    });
            }

            function togglePositiveImage(codeAvis) {
                const positiveImage = document.getElementById('positiveImage' + codeAvis);
                const action = positiveImage.src.includes('blanc') ? 'like' : 'unlike';
                updateLikeDislike(action, codeAvis);
            }

            function toggleNegativeImage(codeAvis) {
                const negativeImage = document.getElementById('negativeImage' + codeAvis);
                const action = negativeImage.src.includes('blanc') ? 'dislike' : 'undislike';
                updateLikeDislike(action, codeAvis);
            }
        </script>

    </div>
    <div class="avis-widget">
        <div class="avis-header">
            <h1 class="avis">
                <?php echo ($note_moyenne === null ? "Pas d'avis" : round($note_moyenne, 1) . "/5"); ?>
                <span class="avis-score">
                    <?php echo ($note_moyenne === null ? "" : $appreciationGenerale); ?>
                </span>
            </h1>
            <p class="avis"><?php echo $nombre_d_avis; ?> avis</p>
        </div>
        <div class="avis-list">
            <?php
            array_map('afficherAvis', $tous_les_avis);
            ?>
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

<form action="poster_un_avis.php" method="POST">
    <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
    <input id="btn-poster-avis" class="btn_poster_un_avis" type="submit" name="posterAvis" value="Poster un avis">
</form>
<style>
    .btn_poster_un_avis {
        display: none;
    }

    @media (max-width: 429px) {
        .btn_poster_un_avis {
            background-color: var(--vert-clair);
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            height: 50px;
            display: flex;
            margin-left: 6.5em;
            margin-top: -2em;
            margin-bottom: 7em;
            padding-left: 3em;
            padding-top: 0.7em;
            text-decoration: none;
            transition: transform 0.2s ease;
            /* Animation au survol */
            box-shadow: 0px 6px 19px rgba(0, 56, 255, 0.24);
        }

        input#btn-poster-avis {
            background-color: var(--vert-clair);
            color: white;
        }
    }
</style>



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

</html>
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
            }
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