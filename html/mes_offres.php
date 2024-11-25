<?php

ob_start();
session_start();

include("recupInfosCompte.php");


if(isset($_GET["deco"])){
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}
if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
   exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes offres</title>
    <link rel="stylesheet" href="mes_offres.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
     <header class="header">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav class="nav">
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="profile">
            <div class="banner">
                <img src="images/Fond.png" alt="Bannière de profil">
            </div>
            <div class="profile-info">
                <img class="profile-picture" src="images/hotel.jpg" alt="Profil utilisateur">
                <h1><?php echo $monComptePro['raison_sociale']; ?></h1>
                <p><?php echo $compte['mail'] ." | ". $compte['telephone']; ?></p>
            </div>
        </section>
    
        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
                <li><a href="mes_offres.php" class="active">Mes offres</a></li>
                <li><a href="modif_bancaire.php">Compte bancaire</a></li>
            </ul>
        </section>
        <div class="button-container">
            <button class="button-Hors-Ligne">Hors - Ligne</button>
            <button class="button-Ligne">En Ligne</button>
        </div>
        <section class="offers">
        <?php
            foreach($mesOffres as $monOffre){
                ?>
                <div class="offer-card">
                    <div class="offer-image">
                        <img src="<?php echo $monOffre['url_images'][0]; ?>" alt="Offre">
                        <div class="offer-status">
                            <span class="status-dot"></span> Hors Ligne
                        </div>
                    </div>
                    <div class="offer-info">
                        <h3><?php echo $monOffre['titre_offre']; ?></h3>
                        <p class="category"><?php echo $monOffre['_resume']; ?></p>
                        <p class="update"><span class="update-icon">⟳</span> Update <?php echo strtolower(tempsEcouleDepuisUpdate($monOffre)) ?></p>
                        <p class="last-update">Publiée <?php echo strtolower(tempsEcouleDepuisPublication($monOffre)) ?></p>
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
<!--
        <div class="offer-card">
            <div class="offer-image">
                <img src="images/hotel.jpg" alt="Offre Ti Al Lannec">
                <div class="offer-rating">
                    <span class="star">★</span>
                    <span class="rating">5.0</span>
                </div>
                <div class="offer-status">
                    <span class="status-dot"></span> Hors Ligne
                </div>
            </div>
            <div class="offer-info">
                <h3>Ti Al Lannec</h3>
                <p class="category">Restaurant Gastronomique</p>
                <p class="update"><span class="update-icon">⟳</span> Update 2j</p>
                <p class="last-update">Mis à jour il y a 2 semaines</p>
                <p class="offer-type">Offre Standard</p>
                <p class="price">40-500€</p>
            </div>
            <button class="add-btn">+</button>
        </div> -->
            <a href="creation_offre.php" class="button-text">
                <button class="image-button">
                    Publier une offre
                </button>
            </a>
            
        </section> 

    </main>
    
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
                <img src="images/Boiteauxlettres_pro.png" alt="Boîte aux lettres">
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
