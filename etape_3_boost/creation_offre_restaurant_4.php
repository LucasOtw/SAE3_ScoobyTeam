<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre Spectacle</title>
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
            <a href="creation_offre1.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
        </div>
    </div>

    <div class="header-controls">
        <div>
            <img id="etapes" src="../images/fil_ariane4.png" alt="Étapes" width="80%" height="80%">
        </div>
    </div>


    <!-- Main Section -->
    <main class="main-creation-offre2">

        <div class="form-container2">
            <h1>Publier une offre</h1>

            <!-- Form Fields -->
                <form action="" method="post" enctype="multipart/form-data" onsubmit="checkFermeture()">
                  <!-- Offre Options -->
                <div class="offre-options">
                    <label>Choix de l'offre</label>
                    <div class="radio-group">
                        <?php
                            if ($_SESSION["compte"] != $typeOffre)
                            {
                        ?>
                                <div>
                                    <input type="radio" id="offre_gratuite" name="offre" value="gratuite">
                                    <label class="label-check" for="offre_gratuite">Offre Gratuite <sup>1</sup></label>
                                </div>
                            <?php
                            }
                            else 
                            {
                            ?>
                                <div>
                                    <input type="radio" id="offre_standard" name="offre" value="standard">
                                    <label class="label-check" for="offre_standard">Offre Standard <sup>2</sup> (3.99€)</label>
                                </div>
                                <div>
                                    <input type="radio" id="offre_premium" name="offre" value="premium" checked>
                                    <label class="label-check" for="offre_premium">Offre Premium <sup>3</sup> (5.99€)</label>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>

                <!-- Boost Options -->
                <div class="boost-options">
                    <label>Options de boost (lorsque l'offre sera en ligne)</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" id="no_boost" name="option" value="">
                            <label class="label-check" for="no_boost">Ne pas booster mon offre</label>
                        </div>
                        <div>
                            <input type="radio" id="en_relief" name="option" value="en_relief">
                            <label class="label-check" for="relief">Mettre mon offre "en Relief" <sup>4</sup> (2.99€/semaine)</label>
                        </div>
                        <div>
                            <input type="radio" id="a_la_une" name="option" value="a_la_une" checked>
                            <label class="label-check" for="a_la_une">Mettre mon offre "À la Une" <sup>5</sup> (3.99€/semaine)</label>
                        </div>
                    </div>
                </div>

                <!-- Duration Options -->
                <div class="duration-options">
                    <label>Durée du boost</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" id="1_semaine" name="duree_option" value="1">
                            <label class="label-check" for="1_semaine">1 semaine</label>
                        </div>
                        <div>
                            <input type="radio" id="2_semaines" name="duree_option" value="2">
                            <label class="label-check" for="2_semaines">2 semaines</label>
                        </div>
                        <div>
                            <input type="radio" id="3_semaines" name="duree_option" value="3" checked>
                            <label class="label-check" for="3_semaines">3 semaines</label>
                        </div>
                        <div>
                            <input type="radio" id="4_semaines" name="duree_option" value="4">
                            <label class="label-check" for="4_semaines">4 semaines</label>
                        </div>
                    </div>
                </div>


                <button type="submit" id="button_valider">
                Valider <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
        <div class="notes">
            <p>
                <sup>1</sup> Offre Gratuite : Accessible aux professionnels publics ou associatifs, sans coût.
            </p>
            <p>
                <sup>2</sup> Offre Standard (payante) : Promotion sur la plateforme avec un coût mensuel, possibilité de personnaliser l'offre via des options payantes.
            </p>
            <p>
                <sup>3</sup> Offre Premium (payante) : Inclut tous les avantages de l'Offre Standard, avec en plus un droit de veto permettant de "blacklister" jusqu'à 3 avis sur 12 mois.
            </p>
            <p>
                <sup>4</sup> "En Relief" : Met en avant l'offre dans les listes.
            </p>
            <p>
                <sup>5</sup> "À la Une" : Offre une visibilité maximale en tête de la page d'accueil et dans les listes.
            </p>
        </div>
    </main>
</body>
</html>