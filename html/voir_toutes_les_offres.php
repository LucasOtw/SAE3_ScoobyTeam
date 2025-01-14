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


$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);


if ($donneesSession && isset($donneesSession["code_compte"])) {
    $code_compte = $donneesSession["code_compte"];
    
    $compte = $dbh->prepare('SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte');
    $compte->bindValue(":code_compte", $code_compte);
    $compte->execute();

    $resultat = $compte->fetch(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="voir_offres.css">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="scroll.js"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

</head>
<body>
    </div>
    <!-- Code pour le pop-up --> 
    <div id="customPopup">
        <img src="images/robot_popup.png" width="50" height="70" class="customImage">
        <p><a href="creation_compte_membre.php" class="txt_popup">Créez votre compte</a> en quelques clics et accédez à un monde de possibilités ! </p>
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
                        if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
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


        <div class="titres-offres">
            <h2 class="titre-les-offres">Toutes les offres</h2>
        </div>
        
        <section id="offers-list">
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
        
            foreach($infosOffre as $offre){

                
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


                $horaireOffre = $dbh->prepare('SELECT ouverture, fermeture FROM tripenarvor._horaire WHERE code_horaire = (SELECT '.$dateFr.' FROM tripenarvor._offre WHERE code_offre = :code_offre);');
                $horaireOffre->bindParam(":code_offre", $offre["code_offre"]);
                $horaireOffre->execute();

                $horaire = ($horaireOffre->fetch(PDO::FETCH_ASSOC));


                if ($type_offre == 'parc_attractions' || $type_offre == 'restauration' || $type_offre == 'activite')
                {
                    $periodeOffre = $dbh->prepare('SELECT date_ouverture, date_fermeture FROM tripenarvor._offre_'.$type_offre.' WHERE code_offre = :code_offre;');
                    $periodeOffre->bindParam(":code_offre", $offre["code_offre"]);
                    $periodeOffre->execute();
    
                    $periode = ($periodeOffre->fetch(PDO::FETCH_ASSOC));
                } else {
                    $periode = "";
                }
                
                
                if (!empty($horaire))
                {
                    
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
                

                if ($type_offre == 'visite' || $type_offre == 'spectacle')
                {
                    $eventOffre = $dbh->prepare('SELECT date_'.$type_offre.', heure_'.$type_offre.' FROM tripenarvor._offre_'.$type_offre.' WHERE code_offre = :code_offre;');
                    $eventOffre->bindParam(":code_offre", $offre["code_offre"]);
                    $eventOffre->execute();
    
                    $event = ($eventOffre->fetch(PDO::FETCH_ASSOC));
                } else {
                    $event = "";
                }
                

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
                    /* echo $villeOffre["ville"]; */
                ?>
                    <article class="offer <?php if (!empty($offre['option_en_relief']) || !empty($offre['option_a_la_une']) ){echo "en_relief";} ?>" 
                                data-category=<?php echo $type_offre;?> 
                                data-price="<?php echo $offre["tarif"];?>" 
                                data-rate=<?php echo $offre["note_moyenne"]; ?>
                                 location="<?php echo $villeOffre["ville"]; ?>"
                                data-status=<?php echo $dataStatusEng; ?> 
                                data-event=<?php if(!empty($event)) { echo $event['date_'.$type_offre]; } else { echo ""; } ?> 
                                data-period-o=<?php if(!empty($periode)) { echo $periode['date_ouverture']; } else { echo ""; } ?>
                                data-period-c=<?php if(!empty($periode)) { echo $periode['date_fermeture']; } else { echo ""; } ?> >
                        
                                <img src="<?php echo "./".$offre_image['url_image']; ?>" alt="aucune image">
                        
                                <div class="offer-details">
                                    <h2><?php echo $offre["titre_offre"]; ?></h2>
                                    
                                    <p>
                                        <span class="iconify" data-icon="mdi:map-marker" style="color: #BDC426; font-size: 1.2em; margin-right: 5px; margin-bottom: -4px;"></span>
                                        <?php echo $villeOffre["ville"]; ?>
                                    </p>
                                    
                                    <span><?php echo tempsEcouleDepuisPublication($offre); ?></span>
                                    
                                    <p>
                                        <?php 
                                        if (!empty($offre["note_moyenne"])) { 
                                            echo '⭐ '.$offre["note_moyenne"]; 
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
                                    
                                    <?php if (($type_offre == "visite" || $type_offre == "spectacle") && !empty($event['date_'.$type_offre])) { ?> 
                                        <p><?php echo $event['date_'.$type_offre].' à '.$event['heure_'.$type_offre]; ?></p> 
                                    <?php } ?>
                                            
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
    <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
    <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
    <a href="
        <?php
            if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
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
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <li><a href="connexion_memebre.php">Se Connecter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="contacter_plateforme.php">Signaler un problème</a></li>
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

</body>
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


