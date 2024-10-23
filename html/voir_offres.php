<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les offres</title>
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
                <li><a href="voir_offres.php" class="active" >Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="connexion_membre" >Mon Compte</a></li>
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
                <li><a href="voir_offres.php" class="active" >Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="connexion_membre.php" >Mon Compte</a></li>
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



        <?php
            try {
                $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
                $username = "sae";
                $password = "philly-Congo-bry4nt";
                // Créer une instance PDO
                $dbh = new PDO($dsn, $username, $password);
                
                $stmt = $dbh->prepare('SELECT * FROM tripenarvor._offre');
                $stmt->execute();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $fImg = $dbh->prepare('select url_image from tripenarvor._son_image natural join tripenarvor._image where code_offre = :offre');
                    $fImg->execute(['offre' => $row["code_offre"]]);
                    ?>
                        <article class="offer">
                            <img src="images/<?php echo htmlspecialchars($fImg[0]); ?>.png" alt="<?php echo htmlspecialchars($row['titre_offre']); ?>">
                            <div class="offer-details">
                                <h2><?php echo htmlspecialchars($row['titre_offre']); ?></h2>
                                <p><?php echo htmlspecialchars($row['ville']); ?></p>
                                <span><?php echo date_format(date_create($row['date_publication']), 'd/m/Y'); ?></span>
                                <span>
                                    <!-- <img src="images/etoile.png" class="img-etoile">
                                    <p><?php /*echo round($row['note_moyenne'], 1); */?><span class="nb_avis">(355 avis)</span></p> -->
                                </span>
                                <button>Voir l'offre →</button>
                            </div>
                        </article>
        <?php
            }
            } catch (PDOException $e) {
                print "Erreur!: ". $e->getMessage(). "<br/>";
                die();
            }
            ?>

        </section>

    </main>
    <nav class="nav-bar">
    <a href="#"><img src="images/icones/House.png" alt="image de maison"></a>
    <a href="#"><img src="images/icones/recent.png" alt="image d'horloge"></a>
    <a href="#"><img src="images/icones/Croix.png" alt="image de PLUS"></a>
    <a href="#"><img src="images/icones/User.png" alt="image de Personne"></a>
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

</body>
</html>
