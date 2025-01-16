<?php
ob_start(); // Démarre la mise en tampon de sortie
session_start();

if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
   exit;
}

if(isset($_SESSION['crea_offre'])){
   unset($_SESSION['crea_offre']);
   unset($_SESSION['crea_offre2']);
   unset($_SESSION['crea_offre3']);
   unset($_SESSION['crea_offre4']);
   unset($_SESSION['ajoutOption']);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création offre</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
     <header class="header">
        <div class="logo">
           <a href="mes_offres.php">
                  <img src="images/logo_blanc_pro.png" alt="PACT Logo">
            </a>
      
        </div>
        <nav class="nav">
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="consulter_compte_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal centré -->
    <main class="main-creation-offre">


        <div class="form-container">
            <h1>Type d'offre</h1>
            <p>Tout d'abord, veuillez choisir le type de votre offre pour personnaliser votre expérience.</p>

    
            <form method="post">              
                <label for="offre">Choisissez le type de votre offre</label>
                <div class="type_offre_select_button">
                    <select id="offre" name="offreChoisie" data-placeholder="Sélectionnez..." required>
                        <option value="" hidden selected>Sélectionnez...</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="spectacle">Spectacle</option>
                        <option value="visite">Visite</option>
                    </select>
                    <button type="submit" class="button_continuer">Continuez
                        <img src="images/fleche.png" alt="Fleche" width="25px" height="25px">
                    </button>
                </div>
            </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $offreChoisie = $_POST["offreChoisie"];
                
                // Vérifie que l'utilisateur a sélectionné une option valide
                if ($offreChoisie !== "default") {
                    header("Location: https://scooby-team.ventsdouest.dev/etape_1_form/creation_offre_".$offreChoisie."_1.php");
                    exit();
                } else {
                    echo "<script>alert('Veuillez choisir une offre.');</script>";
                }
            }
        ?>
        </div>
    </main>
</body>
</html>

<?php
ob_end_flush(); // Termine et envoie la sortie tamponnée
?>
