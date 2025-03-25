
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header-pc header_membre">
    <div class="logo-pc">
        <a href="voir_offres.php">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="voir_offres.php">Accueil</a></li>
            <li><a href="connexion_pro.php">Publier</a></li>
            <li><a href="consulter_compte_membre.php">Mon Compte</a></li>
        </ul>
    </nav>
</header>

<main class="error-container">
    <h1 class="error-title">Oups ! Page introuvable</h1>
    <p class="error-subtitle">La page que vous recherchez n'existe pas ou a été déplacée.</p>
    <img src="images/404.png" alt="Erreur 404" class="error-image">
    <h2 class="error-text">Que souhaitez-vous faire ?</h2>
    <ul class="error-list">
        <li class="error-item-gold">
            <a href="javascript:history.back()">
                <span>Revenir à la page précédente</span>
                <span class="error-arrow">›</span>
            </a>
        </li>
        <li class="error-item">
            <a href="voir_offres.php">
                <span>Retourner à l'accueil</span>
                <span class="error-arrow">›</span>
            </a>
        </li>
        <li class="error-item">
            <a href="obtenir_aide.php">
                <span>Obtenir de l'aide</span>
                <span class="error-arrow">›</span>
            </a>
        </li>
        <li class="error-item">
            <a href="contacter_plateforme.php">
                <span>Nous contacter</span>
                <span class="error-arrow">›</span>
            </a>
        </li>
        <li class="error-item">
            <a href="connexion_membre.php">
                <span>Se connecter</span>
                <span class="error-arrow">›</span>
            </a>
        </li>
    </ul>
</main>




<footer class="footer footer_membre">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.php">Mentions Légales</a></li>
                    <li><a href="cgu.php">GGU</a></li>
                    <li><a href="cgv.php">CGV</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["membre"]) && !empty($_SESSION["membre"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_membre.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_membre.php">Se connecter</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Nous Connaitre</a></li>
                    <li><a href="obtenir_aide.php">Obtenir de l'aide</a></li>
                    <li><a href="contacter_plateforme.php">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <!--<li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>-->
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
<script>
    function goBack() {
        if (document.referrer) {
            window.location.href = document.referrer;
        } else {
            window.location.href = "index.php"; // Redirige vers l'accueil si aucune page précédente n'est trouvée
        }
    }
</script>

