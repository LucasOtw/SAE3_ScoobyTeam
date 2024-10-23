<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création offre</title>
    <link rel="stylesheet" href="creation_offre1.css">
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
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="header-controls">
        <div>
            <img id="etapes" src="images/FilArianne1.png" alt="Étapes" width="80%" height="80%">
        </div>
    </div>
    <!-- Contenu principal centré -->
    <main class="main-creation-offre">


        <div class="form-container">
            <h1>Type d'offre</h1>
            <p>Tout d'abord, veuillez choisir le type de votre offre pour personnaliser votre expérience.</p>

    
            <form method="post">              
                <label for="offre">Choisissez le type de votre offre</label>
                <div class="type_offre_select_button">
                    <select id="offre" name="offreChoisie">
                        <option value="default">Sélectionner...</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="spectacle">Spectacle</option>
                        <option value="visite">Visite</option>
                        <option value="attraction">Parc d'attraction</option>
                        <option value="activite">Activité</option>
                    </select>
                    <button type="submit" class="button_continuer">Continuer
                        <img src="images/fleche.png" alt="Fleche" width="25px" height="25px">
                    </button>
                </div>
            </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $offreChoisie = $_POST["offreChoisie"];
                
                // Vérifie que l'utilisateur a sélectionné une option valide
                if ($offreChoisie !== "default") {
                    header("Location: creation_offre_$offreChoisie.php");
                    exit();
                } else {
                    echo "<script>alert('Veuillez choisir une offre.');</script>";
                }
            }
        ?>
        </div>
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
           
    </footer>
</body>
</html>
