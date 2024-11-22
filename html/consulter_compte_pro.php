<?php

ob_start();
session_start();

include("recupInfosCompte.php");


if(isset($_GET["deco"])){
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}
if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes offres</title>
    <link rel="stylesheet" href="mes_offres.css">
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

    <form action="#" method="POST">
        <div class="creation_compte_pro_form-section">
            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Raison Sociale</legend>
                    <input type="text" id="raison-sociale" name="raison-sociale" placeholder="Raison Sociale*" required>
                </fieldset>

                <fieldset>
                    <legend>N° de Siren</legend>
                    <input type="text" id="siren" name="siren" placeholder="N° de Siren*" required>
                </fieldset>
            </div>

            <div class="crea_pro_mail_tel">
                <fieldset>
                    <legend>Email</legend>
                    <input type="email" id="email" name="email" placeholder="Email*" required>
                </fieldset>

                <fieldset>
                    <legend>Téléphone</legend>
                    <input type="tel" id="telephone" name="telephone" placeholder="Téléphone*" required>
                </fieldset>

                <fieldset>
                    <legend>Adresse Postale</legend>
                    <input type="text" id="adresse" name="adresse" placeholder="Adresse postale*" required>
                </fieldset>

                <fieldset>
                    <legend>Complément d'adresse</legend>
                    <input type="text" id="comp_adresse" name="comp_adresse" placeholder="Complément d'adresse*" required>
                </fieldset>

                <fieldset>
                    <legend>Ville</legend>
                    <input type="text" id="ville" name="ville" placeholder="Ville*" required>
                </fieldset>
            </div>
            <div class="checkbox">
                                <input type="checkbox" id="cgu" name="cgu" required>
                                <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                            </div>

            <div class="compte_membre_save_delete">
                <a href="deconnexion_compte_pro.php" class="submit-btn1">Déconnexion</a>
                <button type="submit" class="submit-btn3">Enregistrer</button>
            </div>
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
