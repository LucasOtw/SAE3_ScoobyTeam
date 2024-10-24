<?php session_start();

if(isset($_GET["deco"])){
    header('location: connexion_membre.php');
    session_destroy();
};

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Offres PACT</title>
    <link rel="stylesheet" href="voir_offres.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
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
                    if(isset($_SESSION["compte"]) || !empty($_SESSION["compte"])){
                        ?>
                        <li><a href="#">/!\ EN COURS /!\</a></li>
                        <li><a href="voir_offres.php?deco=true">Se déconnecter</a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="connexion_membre.php">Se connecter</a></li>
                        <?php
                    } ?>
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
        <header>
            <h2>Les offres</h2>
            <!-- <span id="filter">
                <button><img src="images/tri.png">Filtrer</button>
            </span> -->
        </header>

        <section id="offers-list">

        <article class="offer">
                <img src="images/offre2.png" alt="Image de l'offre Armor'Park">
                <div class="offer-details">
                    <h2>Armor'Park</h2>
                    <p>Lannion</p>
                    <span>3 mois</span>
                    <span>
                        <!-- <img src="images/etoile.png" class="img-etoile">
                        <p>4 <span class="nb_avis">(50 avis)</span></p> -->
                    </span>
                    <button>Voir l'offre →</button>
                </div>
            </article>

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
            $infosOffre = $dbh->query('SELECT code_offre,titre_offre,code_adresse FROM tripenarvor._offre');
            $infosOffre = $infosOffre->fetchAll();

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
                
                // Utiliser fetchAll pour récupérer toutes les images sous forme de tableau
                $images = $imagesOffre->fetchAll(PDO::FETCH_ASSOC);
            
                if (!empty($images)) {
                    // Récupérer la première image si disponible
                    $offre_image = $images[0]['code_image']; 
                } else {
                    $offre_image = ""; // Pas d'image trouvée
                }

                ?>
                <article class="offer">
                    <img src=<?php echo $offre_image ?> alt="aucune image">
                    <div class="offer-details">
                        <h2><?php echo $offre["titre_offre"] ?></h2>
                        <p><?php echo $villeOffre["ville"] ?></p>
                        <span>Durée inconnue</span>
                        <button>Voir l'offre →</button>
                    </div>
                </article>
                <?php
            }

        ?>
        <article class="offer">
                <img src="images/offre2.png" alt="Image de l'offre Armor'Park">
                <div class="offer-details">
                    <h2>Armor'Park</h2>
                    <p>Lannion</p>
                    <span>3 mois</span>
                    <span>
                        <!-- <img src="images/etoile.png" class="img-etoile">
                        <p>4 <span class="nb_avis">(50 avis)</span></p> -->
                    </span>
                    <button>Voir l'offre →</button>
                </div>
            </article>
        </section>

    </main>
    <nav class="nav-bar">
    <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
    <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
    <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
    <a href="connexion_membre.php"><img src="images/icones/User icon.png" alt="image de Personne"></a>
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
