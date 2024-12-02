<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=429, initial-scale=1.0">
  <title>Créer compte pro</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="incitation_container">
    <header class="incitation_header">
      <div class="incitation_logo-container">
        <img src="images/logo_blanc_pro.png">
      </div>
      <div class="incitation_skip-container">
        <a href="voir_offres.php"><img src="images/croix_fermer.png" class="skip"></a>
      </div>
    </header>
    
<main class="incitation_content">
    <div class="incitation_image-container">
        <img src="images/cadeau.png" alt="Icône cadeau" class="image">
    </div>
      <h2 class="incitation_main-title">Envie de publier une offre ?</h2>
      <p class="incitation_subtitle">Passez à la vitesse supérieure avec un compte Professionnel</p>
      <div class="incitation_image-container">
        <img src="images/features.png" class="features-image">
      </div>
    </main>

    <nav class="nav-bar">
      <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
      <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
      <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
      <a href="
        <?php
            if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                echo 'consulter_compte_membre.php';
            } else {
                echo 'connexion_membre.php';
            }
        ?>">
      <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
  </div>
</body>
</html>
