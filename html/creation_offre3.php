<?php

ob_start();
session_start();

include("recupInfosCompte.php");

if(!isset($_SESSION['pro'])){
    // si on tente d'accéder à la page sans être connecté à un compte pro, on
    header('location: connexion_pro.php');
    exit;
}
if(!isset($_POST['valider']) && !isset($_POST['valider_plus_tard'])){
    if(isset($_SESSION['crea_offre']) && isset($_SESSION['crea_offre2']) && isset($_SESSION['crea_offre3']) && isset($_SESSION['crea_offre4'])){
        echo "DOBBY HAS NO MASTER YOU SON OF A BITCH !";
    } else {
        header('location: creation_offre.php');
        exit;
    }
}

// on vérifie si le pro a un compte bancaire
if($monComptePro['code_compte_bancaire']){
    echo "test";
} else {
    echo "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAARGH !!";
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="creation_offre3.css">
</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre1.php" class="active">Publier</a></li>
                <li><a href="connexion_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="fleche_retour">
        <div>
            <a href="etape_3_boost/creation_offre_restaurant_4.php"><img src="images/Bouton_retour.png" alt="retour"></a>
        </div>
    </div>

    <main class="main-creation-offre3">

        <h1>Publier une offre</h1>
        <h2>Ajouter une nouvelle carte</h2>

        <div class="form_carte">
            <form action="#" method="POST">
                <!-- Numero -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>IBAN *</legend>
                            <input type="text" id="IBAN" name="IBAN" value="test" placeholder="IBAN *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- BIC -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>BIC *</legend>
                            <input type="text" id="BIC" name="BIC" value="test" placeholder="BIC *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- Nom -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nom du compte *</legend>
                            <input type="text" id="nom" name="nom" value="test" placeholder="Nom du compte *" required>
                        </fieldset>
                    </div>
                </div>

                <div class="checkbox">
                    <input type="checkbox" id="cgu" name="cgu" required>
                    <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                </div>

                <div class="boutons">
                    <button type="submit" name="valider" class="btn-primary">Valider</button>
                    <button class="btn-secondary" name="valider_plus_tard">Je l'ajouterai plus tard
                        <img src="images/fleche_droite.png" alt="fleche_droite">
                    </button>
                </div>
            </form>

            <div class="carte">
                <img src="images/carte_bancaire.png" alt="carte">
            </div>
        </div>

        <p class="terms">En publiant votre offre, vous acceptez les conditions générales d'utilisation (CGU).</p>

    </main>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
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
                    <li><a href="creation_offre1.php">Publier</a></li>
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
