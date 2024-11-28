<?php
    if (headers_sent($file, $line)) {
        die("Les en-têtes ont déjà été envoyés dans le fichier $file à la ligne $line.");
    }
    ob_start();
    session_start();


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
            $retour = "il y a ".$jours." jour(s)";
        } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent){
            $semaines = floor($jours / 7);
            $retour = "il y a ".$semaines." semaine(s)";
        } elseif ($mois < 12){
            $retour = "il y a ".$mois." mois";
        } else {
            $retour = "il y a ".$annees." an(s)";
        }

        return $retour;
    }

    function tempsEcouleDepuisDerniereModif($offre){
            // date d'aujourd'hui
            $date_actuelle = new DateTime();
            // conversion de la date de publication en objet DateTime
            $date_modif_offre = new DateTime($offre['date_derniere_modif']);
            // calcul de la différence en jours
            $diff = $date_modif_offre->diff($date_actuelle);
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
                $retour = "il y a ".$jours." jour(s)";
            } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent){
                $semaines = floor($jours / 7);
                $retour = "il y a ".$semaines." semaine(s)";
            } elseif ($mois < 12){
                $retour = "il y a ".$mois." mois";
            } else {
                $retour = "il y a ".$annees." an(s)";
            }

            return $retour;
    }

    function afficherHoraire($jour)
    {
        if (!empty($jour["ouverture"]))
        {
            $dateTimeO = DateTime::createFromFormat('H:i:s', $jour["ouverture"]);
            $dateTimeF = DateTime::createFromFormat('H:i:s', $jour["fermeture"]);

            $horaire = ": ".$dateTimeO->format('H')."h".$dateTimeO->format('i')." - ".$dateTimeF->format('H')."h".$dateTimeF->format('i');
        }
        else
        {
            $horaire = ": Fermé";
        }
        return $horaire;
    }

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

        // si le formulaire est bien récupéré
        $details_offre = unserialize($_POST["uneOffre"]);// on récupère son contenu

        $code_offre = $details_offre["code_offre"]; // on récupère le code de l'offre envoyé


        if(!empty($details_offre))
        { // si l'offre existe

                // Une offre a forcément au moins une image. 
                // On récupère l'image (ou les images) associée(s)

            $stmt = $dbh->prepare('SELECT url_image FROM tripenarvor._son_image natural join tripenarvor._image WHERE code_offre = :code_offre;');
            $stmt->execute([':code_offre' => $code_offre]);
            $images_offre = $stmt->fetchAll(PDO::FETCH_NUM);

            $tags_offre = $dbh->query('SELECT nom_tag FROM tripenarvor._tags WHERE code_tag = (SELECT code_tag FROM tripenarvor._son_tag WHERE code_offre = '.$code_offre.')');
            $tags_offre = $tags_offre->fetch(PDO::FETCH_NUM);

            if (!empty($details_offre["lundi"]))
            {
                $h_lundi = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["lundi"].";");
                $h_lundi = $h_lundi->fetch(PDO::FETCH_ASSOC);
            } else { $h_lundi = null; }
            if (!empty($details_offre["mardi"]))
            {
                $h_mardi = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["mardi"].";");
                $h_mardi = $h_mardi->fetch(PDO::FETCH_ASSOC);
            } else { $h_mardi = null; }
            if (!empty($details_offre["mercredi"]))
            {
                $h_mercredi = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["mercredi"].";");
                $h_mercredi = $h_mercredi->fetch(PDO::FETCH_ASSOC);
            } else { $h_mercredi = null; }
            if (!empty($details_offre["jeudi"]))
            {
                $h_jeudi = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["jeudi"].";");
                $h_jeudi = $h_jeudi->fetch(PDO::FETCH_ASSOC);
            } else { $h_jeudi = null; }
            if (!empty($details_offre["vendredi"]))
            {
                $h_vendredi = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["vendredi"].";");
                $h_vendredi = $h_vendredi->fetch(PDO::FETCH_ASSOC);
            } else { $h_vendredi = null; }
            if (!empty($details_offre["samedi"]))
            {
                $h_samedi = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["samedi"].";");
                $h_samedi = $h_samedi->fetch(PDO::FETCH_ASSOC);
            } else { $h_samedi = null; }
            if (!empty($details_offre["dimanche"]))
            {
                $h_dimanche = $dbh->query('select * from tripenarvor._horaire where code_horaire = '.$details_offre["dimanche"].";");
                $h_dimanche = $h_dimanche->fetch(PDO::FETCH_ASSOC);
            } else { $h_dimanche = null; }

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

            echo "<pre>";
            var_dump($details_offre);
            echo "</pre>";

            $option_en_relief = $dbh->prepare('SELECT * FROM tripenarvor._option WHERE code_option = :option_en_relief');
            $option_en_relief->bindValue(":option_en_relief",$details_offre["option_en_relief"]);
            $option_en_relief->execute();
            $option_en_relief = $option_en_relief->fetch(PDO::FETCH_ASSOC);

            $option_a_la_une = $dbh->prepare('SELECT * FROM tripenarvor._option WHERE code_option = :option_a_la_une');
            $option_a_la_une->bindValue(":option_a_la_une",$details_offre["option_a_la_une"]);
            $option_a_la_une->execute();
            $option_a_la_une = $option_a_la_une->fetch(PDO::FETCH_ASSOC);

            echo "<pre>";
            var_dump($option_a_la_une);
            echo '</pre>';

            // On récupère aussi l'adresse indiquée, ainsi que les horaires (si non nulles)

            $adresse_offre = $dbh->prepare('SELECT * FROM tripenarvor._adresse WHERE code_adresse = :code_adresse');
            $adresse_offre->bindValue(":code_adresse",$details_offre["code_adresse"]);
            $adresse_offre->execute();
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
    <link rel="stylesheet" href="detail_offre_pro.css?">
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
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
            </ul>
            </nav>
        </header>
        
        <div class="detail_offre_hotel-detail">

            <div class="detail_offre_hotel-header">

                <div class="detail_offre_hotel-info">
                    <h1><?php echo $details_offre["titre_offre"];?></h1>

                    <p><?php echo $adresse_offre["ville"].", ".$adresse_offre["code_postal"];?></p>

                    <p><i class="fas fa-clock"></i> Publié <?php echo tempsEcouleDepuisPublication($details_offre);?></p>

                    <p class="update"><span class="update-icon">⟳</span> Dernière modification <?php echo tempsEcouleDepuisDerniereModif($details_offre);?></p>


                    <div>
                        <p id="offer-state">L'offre est actuellement : <span id="offer-status">
                            <?php echo $details_offre['en_ligne'] ? "En Ligne" : "Hors Ligne"; ?>
                        </span></p>
                    </div>
                    

                     
               

                    <!-- <div class="detail_offre_rating">
                        ⭐ 5.0 (255 avis)
                    </div> -->
                </div>

                <div class="detail_offre_price-button">
                <div class="detail_offre_pro_info-icon-container">
                <span class="info-icon">I</span>
                <div class="tooltip">
                <div class="detail_offre_option">
                <?php
                    if ($option_a_la_une !== false)
                    {
                ?>
                        <h3>Option à la une</h3>
                        <p>Début de l'option : <?php echo $option_a_la_une["date_debut"];?></p>
                        <p>Fin de l'option : <?php echo $option_a_la_une["date_fin"];?></p>
                        <p>Duree de l'option : <?php echo $option_a_la_une["nb_semaines"];?></p>
                        <p>Prix de l'option : <?php echo $option_a_la_une["prix"];?></p>
                <?php
                    }
                    if ($option_en_relief !== false)
                    {
                ?>
                        <h3>Option en relief</h3>
                        <p>Début de l'option : <?php echo $option_en_relief["date_debut"];?></p>
                        <p>Fin de l'option : <?php echo $option_en_relief["date_fin"];?></p>
                        <p>Duree de l'option : <?php echo $option_en_relief["nb_semaines"];?></p>
                        <p>Prix de l'option : <?php echo $option_en_relief["prix"];?></p>
                <?php
                    }
                ?>
            </div>

                </div>
                </div>
                    <!--toogle button pour mise en ligne/ hors ligne -->
                    
                    <div class="slider-container">
                        <div class="slider" onclick="toggleSlider()">
                            <div class="slider-circle"></div>
                        </div>
                    </div>

                    <script src="toggle-button.js"></script>
                    
                    <p class="detail_offre_price"><?php echo $details_offre["tarif"];?>€</p>
                    <div class="detail_offre_pro_button">                        
                    <a href="<?php echo $details_offre["site_web"]; ?>"><button class="visit-button_detailoffre">Voir le site ➔</button></a>
                    <form id="add-btn" action="modifier_offre.php" method="POST">
                        <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                        <input id="btn-voir-offre" class="button-text add-btn" type="submit" name="vueDetails" value="Modifier votre offre">
                    </form>


                     

<span id="offerStatus"></span>


               

                        
                    </div>
                </div>

            </div>

            <div class="a-la-une-wrapper">

                <button class="card-scroll-btn card-scroll-btn-left" onclick="scrollcontentLeft()">&#8249;</button>
                <section class="a-la-une">
                <?php
                foreach ($images_offre as $photo)
                {
                ?>

                    <article class="card-a-la-une">
                        <div class="image-background-card-a-la-une">
                            <img src="<?php echo $photo[0];?>" alt="">
                        </div>
                    </article>

                <?php
                }
                ?> 
                </section>

                <button class="card-scroll-btn card-scroll-btn-right" onclick="scrollcontentRight()">&#8250;</button>

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
                            <div class="info-box" id="infoBox" style="max-height: 0; padding: 0; overflow: hidden; width: 25.5em; transition: max-height 0.3s ease, padding 0.3s ease;">

                                <?php
                                if ($type_offre === "restauration")
                                {
                                ?>
                                    <h3 style="margin-top: 1em;">Repas</h3>
                                    <p><?php echo $details_offre["repas"];?></p>

                                    <h3 style="margin-top: 1em;">Gamme de prix</h3>
                                    <p><?php echo $details_offre["gamme_prix"];?></p>
                                <?php
                                }
                                else if ($type_offre === "parc_attractions")
                                {
                                ?>
                                    <h3 style="margin-top: 1em;">Age requis</h3>
                                    <p><?php echo $details_offre["age_requis"];?></p>

                                    <h3 style="margin-top: 1em;">Nombre d'attractions</h3>
                                    <p><?php echo $details_offre["nombre_attractions"];?></p>
                                <?php
                                }
                                else if ($type_offre === "spectacle")
                                {
                                ?>
                                    <h3 style="margin-top: 1em;">Capacité d'acceuil</h3>
                                    <p><?php echo $details_offre["capacite_acceuil"];?></p>

                                    <h3 style="margin-top: 1em;">Durée</h3>
                                    <p><?php echo $details_offre["duree"];?></p>
                                <?php
                                }
                                else if ($type_offre === "visite")
                                {
                                ?>
                                    <h3 style="margin-top: 1em;">Visite Guidée</h3>
                                    <p>Oui</p>

                                    <h3 style="margin-top: 1em;">Durée</h3>
                                    <p><?php echo $details_offre["duree"];?></p>
                                <?php
                                }
                                else if  ($type_offre === "activite")
                                {
                                ?>
                                    <h3 style="margin-top: 1em;">Durée</h3>
                                    <p><?php echo $details_offre["duree"];?></p>

                                    <h3 style="margin-top: 1em;">Age requis</h3>
                                    <p><?php echo $details_offre["age_requis"];?></p>

                                    <h3 style="margin-top: 1em;">Prestations incluses</h3>
                                    <p><?php echo $details_offre["prestations_incluses"];?></p>

                                    <h3 style="margin-top: 1em;">Prestations non-incluses</h3>
                                    <p><?php echo $details_offre["prestations_non_incluses"];?></p>
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
                foreach ($tags_offre as $tag)
                {
                ?>
                    <div class="detail_offre_icon">
                        <p><?php echo $tag;?></p>
                    </div>
                <?php
                }
                ?> 



            </div>
        </div>

        <?php
        if ($type_offre === "restauration")
        {
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
        else if ($type_offre === "parc_attractions")
        {
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
        else if ($type_offre === "spectacle")
        {
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
        }
        else if ($type_offre === "visite")
        {
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
        }
        else if  ($type_offre === "activite")
        {
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
        if (!empty($h_lundi["ouverture"]) || 
           !empty($h_mardi["ouverture"]) ||
           !empty($h_mercredi["ouverture"]) ||
           !empty($h_jeudi["ouverture"]) ||
           !empty($h_vendredi["ouverture"]) ||
           !empty($h_samedi["ouverture"]) ||
           !empty($h_dimanche["ouverture"]))
        {
        ?>
            <div class="Detail_offre_ouverture_global_desktop">

                <h2>Horaires</h2>
                <ul class="hours_desktop_detail_offre">
                    <li><span>Lundi</span><?php echo afficherHoraire($h_lundi);?></li>
                    <li><span>Mardi</span><?php echo afficherHoraire($h_mardi);?></li>
                    <li><span>Mercredi</span><?php echo afficherHoraire($h_mercredi);?></li>
                    <li><span>Jeudi</span><?php echo afficherHoraire($h_jeudi);?></li>
                    <li><span>Vendredi</span><?php echo afficherHoraire($h_vendredi);?></li>
                    <li><span>Samedi</span><?php echo afficherHoraire($h_samedi);?></li>
                    <li><span>Dimanche</span><?php echo afficherHoraire($h_dimanche);?></li>
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

        <div class="avis-widget">
              <div class="avis-header">
                <h1 class ="avis">5.0 <span class="avis-score">Très bien</span></h1>
                <p class="avis">255 avis vérifiés</p>
              </div>
              <div class="avis-list">
                <div class="avis">
                  <div class="avis-content">
                    <h3 class="avis">5.0 Excellent | <span class="nom_avis">Maël Sellier</span></h3>
                    <p class ="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
                  </div>
                </div>
                <div class="avis">
                  <div class="avis-content">
                    <h3 class="avis">4.9 Parfait | <span class="nom_avis">Juliette Martin</span></h3>
                    <p class ="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande.</p>
                  </div>
                </div>
                <div class="avis">
                  <div class="avis-content">
                    <h3 class="avis">4.2 Génial | <span class="nom_avis">Antoine Prieur</span></h3>
                    <p class ="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
                  </div>
                </div>
                <div class="avis">
                  <div class="avis-content">
                    <h3 class="avis">3.8 Bien | <span class="nom_avis">Tim Cook</span></h3>
                    <p class ="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
                  </div>
                </div>
                <div class="avis">
                  <div class="avis-content">
                    <h3 class="avis">4.0 Très bien | <span class="nom_avis">Johnny Ives</span></h3>
                    <p class ="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
                  </div>
                </div>
              </div>
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
                    <img src="images/Boiteauxlettres_pro.png" alt="Boîte aux lettres">
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
