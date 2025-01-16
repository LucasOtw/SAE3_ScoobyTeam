<?php
ob_start();
session_start();

include("recupInfosCompte.php");

if (isset($_GET["deco"])) {
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}
if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}

// Définir le filtre par défaut
$filter = "all";
if (isset($_GET["filter"])) {
    $filter = $_GET["filter"];
}

if(isset($_SESSION['aCreeUneOffre'])){
    unset($_SESSION['aCreeUneOffre']);
}

function tempsEcouleDepuisNotif($avis)
{
    // date d'aujourd'hui
    $date_actuelle = new DateTime();
    // conversion de la date de publication en objet DateTime
    $date_ajout_avis = new DateTime($avis['date_notif']);
    $date_ajout_avis = new DateTime($date_ajout_avis->format('Y-m-d'));
    // calcul de la différence en jours
    $diff = $date_ajout_avis->diff($date_actuelle);
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
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Mes offres</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header-pc header_pro">
    <div class="logo">
    <a href="mes_offres.php">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </a>
    </div>
    <nav class="nav">
        <ul>
            <li><a href="mes_offres.php" class="active">Accueil</a></li>
            <li><a href="creation_offre.php">Publier</a></li>
            <li><a href="consulter_compte_pro.php">Mon Compte</a></li>
            <li>
                <a href="#" class="notification-icon" id="notification-btn">
                    <img src="images/notif.png" alt="cloche notification" class="nouvelle-image" style="margin-top: -5px;">
                    <span id="notification-badge" style="display:none"></span>
                </a>
                <div id="notification-popup">
                    <ul>
                        <?php
                        ///////////////////////////////////////////////////////////////////////////////
                        ///                            Contenu notif                                ///
                        ///////////////////////////////////////////////////////////////////////////////
                        
                        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
                        $username = "sae";
                        $password = "philly-Congo-bry4nt";
                        
                        // Créer une instance PDO
                        $dbh = new PDO($dsn, $username, $password);
            
                        $toutes_les_notifs = $dbh->prepare('select * from tripenarvor._notification 
                                                                natural join tripenarvor._avis 
                                                                natural join tripenarvor._offre 
                                                                where professionnel = :code_compte 
                                                                order by date_notif desc;');
                        $toutes_les_notifs->bindValue(":code_compte", $monComptePro["code_compte"]);
                        $toutes_les_notifs->execute();
                        $notifs = $toutes_les_notifs->fetchAll(PDO::FETCH_ASSOC);

                        $nb_notif=0;
            
                        // echo "<pre>";
                        // var_dump($notifs);
                        // echo "</pre>";

                        if (empty($notifs))
                        {
                            echo "<p> Aucun avis n'a été posté sur vos offres </p>";
                        }
                        else
                        {
                            foreach ($notifs as $index => $notif)
                            {
                                if (!$notif["consulter_notif"])
                                {
                                    echo '<script language="javascript">
                                            const notificationBadge = document.getElementById("notification-badge");
                                            notificationBadge.style.removeProperty("display");
                                          </script>';
                                }
                                
                                $checkPP = $dbh->prepare("SELECT url_image FROM tripenarvor._sa_pp WHERE code_compte = :code_compte");
                                $checkPP->bindValue(":code_compte",$notif['code_compte']);
                                $checkPP->execute();
                                
                                $photo_profil = $checkPP->fetch(PDO::FETCH_ASSOC);
                                if($photo_profil){
                                  $compte_pp = $photo_profil['url_image'];
                                } else {
                                  $compte_pp = "images/icones/icone_compte.png";
                                }
                
                                $infoCompte = $dbh->prepare('select * from tripenarvor._compte natural join tripenarvor._membre where code_compte= :code_compte;');
                                $infoCompte->bindValue(':code_compte',$notif['code_compte']);
                                $infoCompte->execute();
                                $compte_m = $infoCompte->fetch(PDO::FETCH_ASSOC);

                                if (!empty($compte_m['prenom']) && !empty($compte_m['nom'])) {
                                    // Si c'est un membre classique
                                    $prenom = $compte_m['prenom'];
                                    $nom = $compte_m['nom'];
                                } else {
                                    // Si l'utilisateur est supprimé
                                    $prenom = "Utilisateur";
                                    $nom = "supprimé";
                                }

                                if ($nb_notif <5)
                                {
                                ?>
                                
                                    <li class="notif"
                                        data-consult="<?php echo $notif["consulter_notif"]; ?>"
                                        data-avis="<?php echo $notif["code_avis"]; ?>" >
                                        <img src="<?php echo $compte_pp; ?>" alt="photo de profil" class="profile-img">
                                        <div class="notification-content">
                                            <strong><?php echo $prenom.' '.$nom; ?></strong>
                                            <span class="notification-location"> | <?php echo $notif["titre_offre"]; ?></span>
                                            <p><?php echo $notif["txt_avis"]; ?></p>
                                            <span class="notification-time"><?php echo tempsEcouleDepuisNotif($notif);?></span>
                                            <span class="new-notif-dot" style="display:none"></span>
                                        </div>
                                    </li>
                        
                                <?php
                                }
                                $nb_notif++;
                            }
                        }                        
                        ?>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</header>
<main class="main_mes_offres">
    <section class="profile">
        <div class="banner">
            <img src="images/Fond.png" alt="Bannière de profil">
        </div>
        <div class="profile-info">
            <img class="profile-picture" src="images/hotel.jpg" alt="Profil utilisateur">
            <h1><?php echo $monComptePro['raison_sociale']; ?></h1>
            <p><?php echo $compte['mail'] . " | " . $compte['telephone']; ?></p>
        </div>
    </section>

    <section class="tabs">
        <ul>
            <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
            <li><a href="mes_offres.php" class="active">Mes offres</a></li>
            <?php if ( isset($monComptePro['raison_sociale'])) { ?>
                <li><a href="modif_bancaire.php">Compte bancaire</a></li>
            <?php } ?>
            <li><a href="modif_mdp_pro.php">Mot de passe et sécurité</a></li>
            <li><a href="consulter_mes_reponses_pro.php">Mes réponses</a></li>
        </ul>
    </section>
    <section>
        <h2 class="vos_offres">Vos offres</h2>
        <div class="button-container">
            <!-- Les boutons déclenchent un filtre via des paramètres dans l'URL -->
            <a href="mes_offres.php?filter=offline" class="button-HorsLigne <?php echo $filter === 'offline' ? 'button-Active' : ''; ?>">Hors - Ligne</a>
            <a href="mes_offres.php?filter=online" class="button-Ligne <?php echo $filter === 'online' ? 'button-Active' : ''; ?>">En Ligne</a>
            <a href="mes_offres.php?filter=all" class="button-toutes <?php echo $filter === 'all' ? 'button-Active' : ''; ?>">Toutes</a>

            <a href="telecharger_facture.php" class="button-factures">
                Voir mes factures
            <span class="arrow">▶</span>
    </a>
        </div>
    </section>
    <section class="offers">
        <?php
        foreach ($mesOffres as $monOffre) {
            // Appliquer le filtre
            if (
                ($filter === "online" && !$monOffre["en_ligne"]) ||
                ($filter === "offline" && $monOffre["en_ligne"])
            ) {
                continue;
            }
            ?>
            <div class="offer-card">
                <div class="offer-image">
                    <img src="<?php echo $monOffre['url_images'][0]; ?>" alt="Offre">
                    <div
                        <?php if (!$monOffre["en_ligne"]) { 
                            ?> <div class="offer-status status-offline"><p>Hors Ligne</p>
                        <?php } 
                            else {
                            ?> <div class="offer-status status-online"><p>En Ligne</p>
                        <?php } ?>
                    </div>
                </div>
                <div class="offer-info">
                    <h3><?php echo $monOffre['titre_offre']; ?></h3>
                    <p class="category"><?php echo $monOffre['_resume']; ?></p>
                    <p class="update"><span class="update-icon">⟳</span> Update <?php echo strtolower(tempsEcouleDepuisUpdate($monOffre)) ?></p>
                    <p class="last-update"><?php if ($monOffre['en_ligne']) { ?>Publiée <?php echo strtolower(tempsEcouleDepuisPublication($monOffre)) ?> <?php } else { echo "N'est pas publiée" ; } ?></p>
                    <p class="offer-type"><?php echo $monOffre['nom_type']; ?></p>
                    <p class="price"><?php echo $monOffre['tarif']; ?>€</p>
                </div>
                <form id="add-btn" action="detail_offre_pro.php" method="POST">
                    <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($monOffre)); ?>">
                    <input id="btn-voir-offre" class="button-text add-btn_pro" type="submit" name="vueDetails" value="+">
                </form>
            </div>
            <?php
        }
        ?>
        <a href="creation_offre.php" class="button-text">
                <button class="image-button">
                    Publiez une offre
                    <img src="images/croix.png">
                </button>
        </a>
        
    </section>
    
</main>
<footer class="footer footer_pro">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
                    <li><a href="cgu.html">GGU</a></li>
                    <li><a href="cgv.html">CGV</a></li>
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
    <script>
       document.addEventListener('DOMContentLoaded', () => {
            const notificationBtn = document.getElementById('notification-btn');
            const notificationPopup = document.getElementById('notification-popup');
            const notificationBadge = document.getElementById('notification-badge');
        
            // Ajouter la classe hidden pour masquer le pop-up au démarrage
            notificationPopup.classList.add('hidden');

            // S'assurer que notifItems contient des éléments
            const gererNotifications = () => {
                const notifItems = document.querySelectorAll(".notif");
        
                if (notifItems.length > 0) {
                    console.log("Notification trouvée");
                    notifItems.forEach(notif => {
                        const consulter = notif.getAttribute('data-consult') ? 1 : 0;
                        const newNotifDot = notif.querySelector('.new-notif-dot');
                        const avis = notif.getAttribute('data-avis');
                        
                        console.log("///Consultée : ", consulter);
                        console.log("///Avis : ", avis);

                        if (consulter == 0)
                        {
                            newNotifDot.style.removeProperty('display');
                            
                            // Utilisation de fetch pour envoyer les données
                            fetch("https://scooby-team.ventsdouest.dev/update_notif_status.php", {
                                method: "POST",  // Méthode POST
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded",  // Spécifie le type de contenu
                                },
                                body: new URLSearchParams({
                                    code_avis : avis
                                })
                            })
                                
                            .then(response => {
                                if (response.ok) {
                                    console.log("L'état de la notif a été mis à jour avec succès.");
                                    notif.setAttribute('data-consult', "1");
                                } else {
                                    console.error("Erreur lors de la mise à jour de l'état de la notif : " + response.status);
                                }
                            })
                            .catch(error => {
                                console.error("Erreur de réseau ou autre :", error);
                            });
                            
                        }
                        else
                        {
                            newNotifDot.style.display = 'none';
                        }
                    });
                } else {
                    console.log("Aucune notification trouvée.");
                }
            };
        
            notificationBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le comportement par défaut de l'ancre
                notificationPopup.classList.toggle('hidden');

                if (!notificationPopup.classList.contains('hidden')) {
                    // Appeler la fonction pour traiter les notifications lorsque le popup est visible
                    gererNotifications();
                }
                
                notificationBadge.style.display = "none";
            });
        
            document.addEventListener('click', (e) => {
                if (!notificationPopup.contains(e.target) && !notificationBtn.contains(e.target)) {
                    notificationPopup.classList.add('hidden');
                }
            });
           
        });

    </script>
</body>
</html>
