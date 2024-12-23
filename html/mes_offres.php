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
                    <div class="offer-status">
                        <?php if (!$monOffre["en_ligne"]) { ?>
                            <span class="status-dot-offline"></span> Hors Ligne
                        <?php } else { ?>
                            <span class="status-dot-online"></span> En Ligne
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
                    Publier une offre
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
                    <li><a href="#">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mes_offres.php">Accueil</a></li>
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
