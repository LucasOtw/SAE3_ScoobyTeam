
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PAVCT</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        
        <nav>
            <ul>
                <li><a href="#" class="active">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <header class="header-tel">
        <div class="logo-tel">
            <img src="images/LogoCouleur.png" alt="PACT Logo">
        </div>
        
    </header>
    <main class="toute_les_offres_main">
        <header>
            <h2>Les offres</h2>
            <!-- <span id="filter">
                <button><img src="images/tri.png">Filtrer</button>
            </span> -->
        </header>
        <section id="offers-list">
            <article class="offer">
                <img src="images/offre1.png" alt="Image de l'offre Golf de St-Samson">
                <div class="offer-details">
                    <h2>Golf de St-Samson</h2>
                    <p>Plœmeur Bodou</p>
                    <span>1 mois</span>
                    <span>
                        <img src="images/etoile.png" class="img-etoile">
                        <p>4.6 (355 avis)</p>
                    </span>
                    <button>Voir l'offre →</button>
                </div>
            </article>

            <article class="offer">
                <img src="images/offre2.png" alt="Image de l'offre Armor'Park">
                <div class="offer-details">
                    <h2>Armor'Park</h2>
                    <p>Lannion</p>
                    <span>3 mois</span>
                    <span>
                        <img src="images/etoile.png" class="img-etoile">
                        <p>4 (50 avis)</p>
                    </span>
                    <button>Voir l'offre →</button>
                </div>
            </article>

            <article class="offer">
                <img src="images/offre3.png" alt="Image de l'offre L'Eclipse">
                <div class="offer-details">
                    <h2>L'Eclipse</h2>
                    <p>Lannion</p>
                    <span>6 mois</span>
                    <span>
                        <img src="images/etoile.png" class="img-etoile">
                        <p>3.8 (300 avis)</p>
                    </span>
                    <button>Voir l'offre →</button>
                </div>
            </article>
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
