<?php
session_start();
?>

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
  <header class="header-tel">
        <svg
            width="428"
            height="202"
            viewBox="0 0 428 202"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 126.87L0 0H428V134.241C374.61 176.076 300.465 202 218.5 202C131.823 202 53.891 173.01 0 126.87Z"
                fill="var(--orange)"
            ></path>
        </svg>
            <div  class="logo-tel">
                <a href="voir_offres.php">
                    <img src="images/LogoBlanc.png" alt="PACT Logo" style="margin-top: 2em;">
                </a>
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
    
    </main>

    <nav class="nav-bar" style=" background-color: var(--orange);">
      <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
      <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
      <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
      <a href="
        <?php
            if(isset($_SESSION["membre"])){
                echo 'compte_membre_tel.php';
            } else {
                echo 'connexion_membre.php';
            }
        ?>">
      <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
  </div>
</body>
</html>
