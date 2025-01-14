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
                                                                where professionnel = :code_compte ;');
                        $toutes_les_notifs->bindValue(":code_compte", $monComptePro["code_compte"]);
                        $toutes_les_notifs->execute();
                        $notifs = $toutes_les_notifs->fetchAll(PDO::FETCH_ASSOC);
            
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
                                $compte = $infoCompte->fetch(PDO::FETCH_ASSOC);
                
                                $infoOffre = $dbh->prepare('select * from tripenarvor._offre where code_offre = :code_offre;');
                                $infoOffre->bindValue(':code_offre',$notif["code_offre"]);
                                $infoOffre->execute();
                                $offre = $infoOffre->fetch(PDO::FETCH_ASSOC);
                                
                                ?>
                                
                                <li class="notif"
                                    data-consult="<?php echo $notif["consulter_notif"]; ?>" >
                                    <img src="<?php echo $compte_pp; ?>" alt="photo de profil" class="profile-img">
                                    <div class="notification-content">
                                        <strong><?php echo $compte["prenom"].' '.$compte["nom"]; ?></strong>
                                        <span class="notification-location"> | <?php echo $notif["titre_offre"]; ?></span>
                                        <p><?php echo $notif["txt_avis"]; ?></p>
                                        <span class="notification-time"><?php echo tempsEcouleDepuisPublication($offre);?></span>
                                        <span class="new-notif-dot"></span>
                                    </div>
                                </li>
                                <?php
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
                    <input id="btn-voir-offre" class="button-text add-btn" type="submit" name="vueDetails" value="+">
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
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="creation_offre.php">Publier</a></li>
                    <li><a href="consulter_compte_pro.php"</a>Mon Compte</li>
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
                    notifItems.forEach(notif => {
                        console.log("Notification trouvée");
                        const consulter = notif.getAttribute('data-consult') ? 1 : 0;
                        console.log("Consultée : ", consulter);
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
