
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modifier la photo de profil</title>
    <link rel="stylesheet" href="modif_pp_membre.css">
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
            <div class="tab active">Informations</div>
            <div class="tab">Mot de passe et sécurité</div>
            <div class="tab">Historique</div>
        </div>
    </div>
    <!-- Section pour changer la photo de profil -->
    <div class="photo-selection">
        <h2>Changer la photo de profil</h2>
        <table class="photo-table">
        <tr>
            <td>
                <img src="images/photo1.png" alt="Photo 1" class="selectable-img">
            </td>
            <td>
                <img src="images/photo2.png" alt="Photo 2" class="selectable-img">
            </td>
            <td>
                <img src="images/photo3.png" alt="Photo 3" class="selectable-img">
            </td>
        </tr>
        <tr>
            <td>
                <img src="images/photo4.png" alt="Photo 4" class="selectable-img">
            </td>
            <td>
                <img src="images/photo5.png" alt="Photo 5" class="selectable-img">
            </td>
            <td>
                <img src="images/photo6.png" alt="Photo 6" class="selectable-img">
            </td>
            <td>
                <!-- Image pour choisir une photo depuis l'explorateur -->
                <label for="file-upload">
                    <img src="images/upload_placeholder.png" alt="Télécharger une photo" class="selectable-img">
                </label>
                <!-- Champ de fichier caché -->
                <input type="file" id="file-upload" name="profile-photo" style="display: none;" accept="image/*">
            </td>
        </tr>

        </table>
    </div>
    <form action="traitement_formulaire.php?id=<?php echo $user_id; ?>" method="POST">
    

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