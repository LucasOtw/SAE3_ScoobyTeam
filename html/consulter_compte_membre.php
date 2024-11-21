<?php
session_start();

include("recupInfosCompte.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier compte membre</title>
    <link rel="stylesheet" href="consulter_compte_membre.css">
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
    <main class="main_consulter_compte_membre">

        <div class="profile">
            <div class="banner">
                <img src="images/Rectangle 3.png" alt="Bannière" class="header-img">
            </div>

            <div class="profile-section">
                <img src="images/Photo_de_Profil.png" alt="Photo de profil" class="profile-img">
                <h1>Juliette Martin</h1>
                <p>juliettemartin@gmail.com | 07.98.76.54.12</p>
            </div>

        </div>
            <section class="tabs">
                <ul>
                    <li><a href="informations_personnelles_pro.php" class="active">Informations personnelles</a></li>
                    <li><a href="mes_offres.php">Mot de passe et sécurité</a></li>
                    <li><a href="ajout_bancaire.php">Historique</a></li>
                </ul>
            </section>

           

        <form action="traitement_formulaire.php?" method="POST">

            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Nom *</legend>
                    <input type="text" id="nom" name="nom" value="<?php echo $monCompteMembre['nom']; ?>" placeholder="Nom *" required>
                </fieldset>

                <fieldset>
                    <legend>Prénom *</legend>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $monCompteMembre['prenom']; ?>" placeholder="Prénom *" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Pseudo *</legend>
                <input type="text" id="pseudo" name="pseudo" value="<?php echo $monCompteMembre['pseudo']; ?>" placeholder="Pseudo *" required>
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
                <button type="button" class="submit-btn3" onclick="window.location.href='deconnexion.php'">Déconnexion</button>
                <button type="submit" class="submit-btn2">Enregistrer</button>
            </div>

        </form>
        
    </main>

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
