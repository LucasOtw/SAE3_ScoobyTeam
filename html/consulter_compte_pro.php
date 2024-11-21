<!DOCTYPE html>
<html lang="en">
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter compte pro</title>
    <link rel="stylesheet" href="consulter_compte_pro.css">
    <link rel="stylesheet" href="consulter_compte_pro.php">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
<header>
     <header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PACT Logo">
            <img src="images/logoBlanc.png" alt="PAVCT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#"class="active">Mon Compte</a></li>
                <li><a href="mes_offres.php" class="active">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="mes_offres.php?deco=true">Déconnexion</a></li>
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
    <main>
        <section class="profile">
            <div class="banner">
                <img src="images/Fond.png" alt="Bannière de profil">
            </div>
            <div class="profile-info">
                <img class="profile-picture" src="images/hotel.jpg" alt="Profil utilisateur">
                <h1><?php echo $monCompte['raison_sociale']; ?></h1>
                <p><?php echo $compte['mail'] ." | ". $compte['telephone']; ?></p>
            </div>
        </section>
    
        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
                <li><a href="mes_offres.php" class="active">Mes offres</a></li>
                <li><a href="#">Compte bancaire</a></li>
            </ul>
        </section> 

        <div class="tabs">
            <div class="tab active">Informations</div>
            <div class="tab">Mot de passe et sécurité</div>
            <div class="tab">Offres</div>
            <div class="tab">Paiement</div>
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
    <a href="deconnexion_compte_pro.php" class="submit-btn1">Déconnexion</a>
    <button type="submit" class="submit-btn3">Enregistrer</button>
</div>
</form>
    <footer class="footer_detail_avis">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PACT">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Publier</a></li>
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <li><a href="#">Historique</a></li>
                </ul>
            </div>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
           
    </footer>
</body>
</html>
