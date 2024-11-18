<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mdp compte membre</title>
    <link rel="stylesheet" href="modif_mdp_pro.css">
</head>
<body>
<header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="connexion_pro.php"class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="header">
            <img src="images/Rectangle_3.png" alt="Bannière" class="header-img">
        </div>

        <div class="profile-section">
            <img src="images/pp.png" alt="Photo de profil" class="profile-img">
            <h1>Ti al Lannec</h1>
            <p>ti.al.lannec@gmail.com | 07.98.76.54.12</p>
        </div>

        <div class="tabs">
            <div class="tab">Informations</div>
            <div class="tab active">Mot de passe et sécurité</div>
            <div class="tab">Historique</div>
        </div>
    </div>
    <form action="#" method="POST">
    <fieldset>
        <legend>Entrez votre mot de passe actuel</legend>
        <input type="password" id="adresse" name="adresse" placeholder="Entrez votre mot de passe actuel" required>
    </fieldset>

    <fieldset>
        <legend>Definissez votre nouveau mot de passe</legend>
        <input type="password" id="code-postal" name="code-postal" placeholder="Definissez votre nouveau mot de passe" required>
    </fieldset>
    <fieldset>

        <legend>Re-tapez votre nouveau mot de passe</legend>
        <input type="password" id="code-postal" name="code-postal" placeholder="Re-tapez votre nouveau mot de passe" required>
    </fieldset>
    <div class="compte_membre_save_delete">
    <button type="submit" class="submit-btn1">Supprimer mon compte</button>
    <button type="submit" class="submit-btn2">Enregistrer</button>
</div>

</form>

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
