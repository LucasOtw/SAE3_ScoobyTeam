<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter compte pro</title>
    <link rel="stylesheet" href="consulter_compte_pro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
     <header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PAVCT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php" class="active">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="mes_offres.php?deco=true">Déconnexion</a></li>
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
                <h1>Entreprise privée</h1>
                <p>0123456789</p>
            </div>
        </section>
    
        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
                <li><a href="mes_offres.php" class="active">Mes offres</a></li>
                <li><a href="#">Compte bancaire</a></li>
            </ul>
        </section> 

        <form action="consulter_compte_pro.php" method="POST" id="compteForm">

            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Raison sociale</legend>
                    <input type="text" id="r_sociale" name="raison_sociale" placeholder="Raison sociale *" required>
                </fieldset>

                <fieldset>
                    <legend>N° de Siren (Professionnel privé)</legend>
                    <input type="text" id="num_siren" name="numero_siren"  placeholder="N° de Siren (Professionnel privé)" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Email *</legend>
                <input type="text" id="mail" name="email" placeholder="Email *" required>
            </fieldset>

            <div class="crea_pro_mail_tel">
                <fieldset>
                    <legend>Email *</legend>
                    <input type="email" id="email" name="email" value="<?php echo $mesInfos['mail']; ?>" placeholder="Email *" required>
                </fieldset>

                <fieldset>
                    <legend>Téléphone *</legend>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo trim(preg_replace('/(\d{2})/', '$1 ', $mesInfos['telephone'])) ?>" placeholder="Téléphone *" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Adresse Postale *</legend>
                <input type="text" id="adresse" name="adresse" value="<?php echo $_adresse['adresse_postal']; ?>" placeholder="Adresse Postale *" required>
            </fieldset>

            <fieldset>
                <legend>Code Postal *</legend>
                <input type="text" id="code-postal" name="code-postal" value="<?php echo $_adresse['code_postal']; ?>" placeholder="Code Postal *" required>
            </fieldset>

            <fieldset>
                <legend>Ville *</legend>
                <input type="text" id="ville" name="ville" value="<?php echo $_adresse['ville']; ?>" placeholder="Ville *" required>
            </fieldset>

            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
            </div>
            
            <div class="compte_membre_save_delete">
                <button type="submit" name="modif_infos" class="submit-btn2" id="btn-enreg">Enregistrer</button>
            </div>

        </form>
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
