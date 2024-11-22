<?php
ob_start();
session_start();

// var_dump($_SESSION['crea_offre']);
// pour afficher les infos (meilleur résultat avec <pre> !)

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre Restaurant</title>
    <link rel="stylesheet" href="../creation_offre2.css">


</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="../../images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="informations_personnelles_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
     <div class="fleche_retour">
        <div>
            <a href="../etape_1_form/creation_offre_restaurant_1.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
        </div>
    </div>

    <div class="header-controls">
        <div>
            <img id="etapes" src="../images/fil_ariane2.png" alt="Étapes" width="80%" height="80%">
        </div>
    </div>


    <!-- Main Section -->
    <main class="main-creation-offre2">

        <div class="form-container2">
            <h1>Publier une offre</h1>

            <!-- Form Fields -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="checkFermeture()">
                <!-- Price Options -->
                <div class="price-options">
                    <label for="prix">Prix</label>
                        <div class="radio-group">
                            <div>
                                <input type="radio" id="moins_25" name="prix" value="€" required>
                                <label class="label-check" for="moins_25">€ (menu à moins de 25€)</label>
                            </div>
                            <div>
                                <input class="label-check" type="radio" id="entre_25_40" name="prix" value="€€" required>
                                <label class="label-check" for="entre_25_40">€€ (entre 25€ et 40€)</label>
                            </div>
                            <div>
                                <input type="radio" id="plus_40" name="prix" value="€€€" required>
                                <label class="label-check" for="plus_40">€€€ (au-delà de 40€)</label>
                            </div>
                        </div>
                </div>

                <!-- Meal Options -->
                <div class="meal-options">
                    <label>Options de repas</label>
                    <div class="checkbox-group">
                        <div>
                            <input type="checkbox" id="petit_dejeuner" name="repas[]" value="petit_dejeuner">
                            <label class="label-check" for="petit_dejeuner">Petit-Déjeuner</label>
                        </div>
                        <div>
                            <input type="checkbox" id="brunch" name="repas[]" value="brunch">
                            <label class="label-check" for="brunch">Brunch</label>
                        </div>
                        <div>
                            <input type="checkbox" id="dejeuner" name="repas[]" value="dejeuner" checked>
                            <label class="label-check" for="dejeuner">Déjeuner</label>
                        </div>
                        <div>
                            <input type="checkbox" id="diner" name="repas[]" value="diner" checked>
                            <label class="label-check" for="diner">Dîner</label>
                        </div>
                        <div>
                            <input type="checkbox" id="boissons" name="repas[]" value="boissons">
                            <label class="label-check" for="boissons">Boissons</label>
                        </div>
                    </div>
                </div>

                <button type="submit" id="button_valider" name="envoiFormEtape2">
                    Continuer <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
    </main>
    <?php

    if(isset($_POST['envoiFormeEtape2']){
       
    }

    ?>
</body>
</html>
