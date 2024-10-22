<?php

// Connexion à la BDD

$dbh = new PDO('mysql:host=postgresql;port=5432;dbname=sae', 'sae', 'field-biDe-v3ndr4-bahut');

// Récupération des offres déjà consultées

$reqOffresCons = $bdd->query("SELECT * FROM tripenarvor._consulte WHERE code_compte = ".$_SESSION["compte"]." AND consulte = 1");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier compte membre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="header">
            <img src="images/Rectangle 3.png" alt="Bannière" class="header-img">
        </div>

        <div class="profile-section">
            <img src="images/Photo_de_Profil.png" alt="Photo de profil" class="profile-img">
            <h1>Juliette Martin</h1>
            <p>juliettemartin@gmail.com | 07.98.76.54.12</p>
        </div>

        <div class="tabs">
            <div class="tab ">Informations</div>
            <div class="tab">Mot de passe et sécurité</div>
            <div class="tab active">Historique</div>
        </div>
    </div>
    <div class="reviews-container">
        <?php
        
            if(count($reqOffresCons) > 0){
                // si l'utilisateur a déjà des offres consultées
                foreach($reqOffresCons as $offres_cons){
                    // on récupère l'image associée à l'offre
                    $image_offre = $bdd->query('SELECT url_image FROM _image WHERE code_image =
                                                (SELECT code_image FROM son_image WHERE code_offre = '.$offres_cons["code_offre"].';');
                    $infos_offre = $bdd->query('SELECT * FROM _offre WHERE code_offre = '.$offres_cons["code_offre"].';');
                    
                    ?>
                    <div class="card">

                    </div>
                    <?php
                }
            }

        ?>
        <div class="card">
            <img src="images/golf.png" alt="Golf de St-Samson">
            <div class="content">
                <h4>Golf de St-Samson</h4>
                <p>Pleumeur Bodou</p>
                <p class="rating">⭐ 4.6 (305 avis)</p>
            </div>
        </div>

        <div class="card">
            <img src="images/Armorypark.png" alt="AmzerPark">
            <div class="content">
                <h4>AmzerPark</h4>
                <p>Lannion</p>
                <p class="rating">⭐ 4.0 (60 avis)</p>
            </div>
        </div>

        <div class="card">
            <img src="images/eclipse.png" alt="L'Éclipse">
            <div class="content">
                <h4>L'Éclipse</h4>
                <p>Lannion</p>
                <p class="rating">⭐ 3.8 (500 avis)</p>
            </div>
        </div>

        <div class="card">
            <img src="images/plage.png" alt="Plage de Trestrou">
            <div class="content">
                <h4>Plage de Trestrou</h4>
                <p>Trébeurden</p>
                <p class="rating">⭐ 5.0 (14 avis)</p>
            </div>
        </div>

        <div class="card">
            <img src="images/equitation.png" alt="Ruin Équitation">
            <div class="content">
                <h4>Ruin Équitation</h4>
                <p>Pleumeur Bodou</p>
                <p class="rating">⭐ 4.7 (240 avis)</p>
            </div>
        </div>

        <div class="card">
            <img src="images/chateau.png" alt="Château Kerlabou">
            <div class="content">
                <h4>Château Kerlabou</h4>
                <p>Pleumeur Bodou</p>
                <p class="rating">⭐ 3.9 (345 avis)</p>
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
                <img src="images/Boiteauxlettres.png" alt="Boîte aux lettres">
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
</body>
</html>