<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="compte_membre_tel.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="pp.js"></script>

</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="logo">
                <img src="images/LogoNoirVert.png" alt="PACT Logo">
            </div>
        </header>

        <!-- Profile Section -->
        <section class="profile">
            <div class="profile-img-container">
                 <img class="profile-img" src="images/pp.png" alt="Photo de profil">
                    <form action="/upload" method="POST" enctype="multipart/form-data">
                         <label for="upload-photo" class="upload-photo-button">
                         <span class="iconify" data-icon="mdi:camera" data-inline="false"></span>
                         </label>
                        <input type="file" id="upload-photo" name="profile-photo" accept="image/*" hidden>
                    </form>
            </div>
        <h1 class="profile-name">Juliette Martin</h1>
     <p class="profile-contact">juliemartin@gmail.com | 07.98.76.54.12</p>
</section>


        <!-- Actions Section -->
        <main class="actions">
        <a href="consulter_compte_membre.php">
            
            <button class="action-btn">
                <img src="images/Vector_10.png" alt="Edit Icon"> Éditer les informations
            </button>
            </a>
            <a href="modif_mdp_membre.php">
            <button class="action-btn">
                <img src="images/Vector_11.png" alt="Password Icon"> Modifier mon mot de passe
            </button>
            </a>
            <a href="consulter_mes_avis.php">
            <button class="action-btn">
                <img src="images/Vector_12.png" alt="History Icon"> Historique
            </button>
            </a>
            <!--Il faudra gérer la deconnexion sur bouton"-->
            <button class="action-btn">
                <img src="images/Vector_14.png" alt="Logout Icon"> Déconnexion
            </button>
        </main>
    </div>
    <nav class="nav-bar">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="r
                <?php
                if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
                    echo "compte_membre_tel.php.php";
                } else {
                    echo "connexion_membre.php";
                }
                ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
</body>
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
</html>
