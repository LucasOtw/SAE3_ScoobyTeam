<?php

ob_start();
session_start();

include_once('../recupInfosCompte.php');

if(!isset($monComptePro['num_siren'])){
    // si le professionnel est publique, il n'a rien à faire là
    header('location: ../etape_4_creation/creation_offre_spectacle_4.php');
    exit;
}

// on récupère les prix de type_offre

$getPrixOffre = $dbh->prepare("SELECT * FROM tripenarvor._type_offre");
$getPrixOffre->execute();

$prixOffre = $getPrixOffre->fetchAll(PDO::FETCH_ASSOC);

const PRIX_RELIEF = 10.00;
const PRIX_A_LA_UNE = 20.00;

if(isset($_POST['envoiForm4'])){
    // si le formulaire est envoyé..
    foreach($_POST as $cle => $post){
        if($cle !== "envoiForm4"){
            $_SESSION["crea_offre3"][$cle] = $post;
        }
    }
    if(isset($_SESSION["crea_offre3"]) && !empty($_SESSION["crea_offre3"])){
        header('location: ../etape_4_creation/creation_offre_spectacle_4.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre - Spectacle</title>
    <link rel="stylesheet" href="../creation_offre2.css">


</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="../../images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="../mes_offres.php">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="../consulter_compte_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
     <div class="fleche_retour">
        <div>
            <a href="../etape_2_horaires/creation_offre_restaurant_3.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
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
                            if(!isset($monComptePro['num_siren'])){
                                // si il y a un numéro SIREN, c'est un compte privé
                                ?>
                                <div>
                                    <input type="radio" id="offre_gratuite" name="offre" value="gratuite" required>
                                    <label class="label-check" for="offre_gratuite">Offre Gratuite <sup>1</sup></label>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div>
                                    <input type="radio" id="offre_premium" name="offre" value="standard" required>
                                    <label class="label-check" for="offre_standard">Offre Standard <sup>2</sup> (<?php echo $prixOffre[0]['prix'] ?>€ / jour)</label>
                                </div>
                                <div>
                                    <input type="radio" id="offre_premium" name="offre" value="premium" required>
                                    <label class="label-check" for="offre_premium">Offre Premium <sup>3</sup> (<?php echo $prixOffre[1]['prix'] ?>€ / jour)</label>
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
                            <input type="radio" id="no_boost" name="option" value="aucune">
                            <label class="label-check" for="no_boost">Ne pas booster mon offre</label>
                        </div>
                        <div>
                            <input type="radio" id="en_relief" name="option" value="en_relief">
                            <label class="label-check" for="relief">Offre "en Relief" <sup>4</sup> (<?php echo PRIX_RELIEF; ?>€/semaine)</label>
                        </div>
                        <div>
                            <input type="radio" id="a_la_une" name="option" value="a_la_une" checked>
                            <label class="label-check" for="a_la_une">Offre "À la Une" <sup>5</sup> (<?php echo PRIX_A_LA_UNE; ?>€/semaine)</label>
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

                <button type="submit" name="envoiForm4" id="button_valider">
                Valider <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
        <div class="notes">
            <p>
                <sup>1</sup> Offre Gratuite : Accessible aux professionnels publics ou associatifs, sans coût.
            </p>
            <br>
            <p>
                <sup>2</sup> Offre Standard (payante) : Promotion sur la plateforme avec un coût mensuel, possibilité de personnaliser l'offre via des options payantes. 
                <br>
                &nbsp;&nbsp;&nbsp;1.67€ HT, soit 2€ TTC/jour de publication.
            </p>
            <br>
            <p>
                <sup>3</sup> Offre Premium (payante) : Inclut tous les avantages de l'Offre Standard, avec en plus un droit de veto permettant de "blacklister" jusqu'à 3 avis sur 12 mois.
                <br>
                &nbsp;&nbsp;&nbsp;3.34€ HT, soit 4€ TTC/jour de publication.
            </p>
            <br>
            <p>
                <sup>4</sup> "En Relief" : Met en avant l'offre dans les listes.
                <br>
                &nbsp;&nbsp;&nbsp;8.34€ HT, soit 10€ TTC/semaine.
            </p>
            <br>
            <p>
                <sup>5</sup> "À la Une" : Offre une visibilité maximale en tête de la page d'accueil et dans les listes.
                <br>
                &nbsp;&nbsp;&nbsp;16.68€ HT, soit 20€ TTC/semaine.
            </p>
        </div>
    </main>
</body>
</html>
