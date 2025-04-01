<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obtenir de l'aide</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
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
                <li><a href="consulter_compte_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
      </header> 
        <h1 class="titre_version_mobile">Obtenir de l'aide</h1>
        
        <img src="images/faq.png" alt="Image pour la page des questions/aides" class="image-aide">
        <div class="faq-search-bar-container">
        <input type="text" class="faq-search-input" placeholder="Comment peut-on vous aider ?">
        <button class="faq-search-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </div>
<div class="faq-container">
    <a href="<?php echo $_SERVER['HTTP_REFERER']?>" style="position: absolute; top: 50px; left: 5vw; margin-top: 5vh;">
            <img src="images/Bouton_retour.png" alt="bouton retour">
    </a>
    <h1 class="faq-title">Questions fréquentes (FAQ)</h1>
    <ul class="faq-list">
        <li class="faq-item">
            <a href="consulter_compte_membre.php">
                <span>Comment modifier mes informations</span>
                <span class="faq-arrow">›</span>
            </a>
        </li>
        <li class="faq-item">
            <a href="signalement_membre.php">
                <span>Comment signaler un avis</span>
                <span class="faq-arrow">›</span>
            </a>
        </li>
        <li class="faq-item">
            <a href="modif_mdp_membre.php">
                <span>J’ai un problème avec mon mot de passe</span>
                <span class="faq-arrow">›</span>
            </a>
        </li>
        <li class="faq-item">
            <a href="cgu.php">
                <span>CGU</span>
                <span class="faq-arrow">›</span>
            </a>
        </li>
    </ul>

    <h2 class="faq-subtitle">Nous contacter</h2>
    <ul class="faq-list">
        <li class="faq-item">
            <a href="contacter_plateforme.php">
                <span>Nous envoyer un mail</span>
                <span class="faq-arrow">›</span>
            </a>
        </li>
        <li class="faq-item">
            <a href="creation_offre.php">
                <span>Comment créer une offre</span>
                <span class="faq-arrow">›</span>
            </a>
        </li>
    </ul>
</div>
    <footer>   
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
        <header class="header_tel">
            <a href="voir_offres.php"><img class="fleche_retour_tel" src="images/Bouton_retour.png"
                    alt="bouton retour"></a>
            <style>
                .fleche_retour_tel {
                    margin-left: -7em;
                    padding-right: 5em;
                    height: 3vh;
                    margin-top: 1vh;
                }
            </style>
        </header>
    </footer>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector(".faq-search-input");
        const faqItems = document.querySelectorAll(".faq-item");

        searchInput.addEventListener("input", function () {
            const filter = searchInput.value.toLowerCase();

            faqItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(filter)) {
                    item.style.display = ""; // Afficher l'élément
                } else {
                    item.style.display = "none"; // Masquer l'élément
                }
            });
        });
    });
</script>


</body>
</html>

