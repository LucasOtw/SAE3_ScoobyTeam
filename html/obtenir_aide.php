<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obtenir de l'aide</title>
    <link rel="stylesheet" href="obtenir_aide.css">
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

        <header class="header-tel header_membre">
            <div class="logo-tel">
                <a href="voir_offres.php"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
            </div>
        </header>
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
        <h1 class="faq-title">Questions fréquentes (FAQ)</h1>
        <ul class="faq-list">
            <li class="faq-item">
                <span>Comment modifier mes informations</span>
                <span class="faq-arrow">›</span>
            </li>
            <li class="faq-item">
                <span>Comment signaler un avis</span>
                <span class="faq-arrow">›</span>
            </li>
            <li class="faq-item">
                <span>J’ai un problème avec mon mot de passe</span>
                <span class="faq-arrow">›</span>
            </li>
        </ul>

        <h2 class="faq-subtitle">Nous contacter</h2>
        <ul class="faq-list">
            <li class="faq-item">
                <span>Nous envoyer un mail</span>
                <span class="faq-arrow">›</span>
            </li>
            <li class="faq-item">
                <span>Comment accéder à l’offre en ligne</span>
                <span class="faq-arrow">›</span>
            </li>
        </ul>
    </div>
</body>
</html>

