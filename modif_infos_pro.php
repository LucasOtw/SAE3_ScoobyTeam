

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mdp compte membre</title>
    <link rel="stylesheet" href="modif_compte_pro.css">
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
                <li><a href="#"class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="header">
            <img src="images/Rectangle_3.png" alt="Bannière" class="header-img">
        </div>

        <div class="profile-section">
            <img src="images/pppro.png" alt="Photo de profil" class="profile-img">
            <h1>Ti Al Lannec</h1>
            <p>tiallannec@gmail.com | 07.98.76.54.12</p>
        </div>

        <div class="tabs">
            <div class="tab active">Informations</div>
            <div class="tab">Mot de passe et sécurité</div>
            <div class="tab">Offres</div>
            <div class="tab">Paiement</div>
        </div>
    </div>
    <form action="#" method="POST">
    <div class="crea_pro_raison_sociale_num_siren">
        <fieldset>
            <legend>Raison Sociale</legend>
            <input type="text" id="raison-sociale" name="raison-sociale" placeholder="Raison Sociale" required>
        </fieldset>

        <fieldset>
            <legend>N° de Siren</legend>
            <input type="text" id="siren" name="siren" placeholder="N° de Siren" required>
        </fieldset>
    </div>

    <div class="crea_pro_mail_tel">
        <fieldset>
            <legend>Email</legend>
            <input type="email" id="email" name="email" placeholder="Email " required>
        </fieldset>

        <fieldset>
            <legend>Téléphone</legend>
            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone" required>
        </fieldset>
    </div>
    <div class="compte_membre_save_delete">
    <button type="submit" class="submit-btn1">Supprimer mon compte</button>
    <a href="deconnexion_compte_pro.php" class="submit-btn2">Déconnexion</a>
    <button type="submit" class="submit-btn3">Enregistrer</button>
</div>

</form>

    <footer class="footer_detail_avis">
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
