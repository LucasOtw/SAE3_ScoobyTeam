<?php

ob_start();
session_start();

      if (isset($_POST['EnvoiEtape3'])) {
          $jours = [
              'Lundi'    => ['ouvertureL', 'fermetureL'],
              'Mardi'    => ['ouvertureMa', 'fermetureMa'],
              'Mercredi' => ['ouvertureMe', 'fermetureMe'],
              'Jeudi'    => ['ouvertureJ', 'fermetureJ'],
              'Vendredi' => ['ouvertureV', 'fermetureV'],
              'Samedi'   => ['ouvertureS', 'fermetureS'],
              'Dimanche' => ['ouvertureD', 'fermetureD']
          ];
      
          $horaires_par_jour = [];
          $erreurs = []; // Assurez-vous d'initialiser le tableau
      
          foreach ($jours as $jour => [$ouverture, $fermeture]) {
              $horaire_ouverture = $_POST[$ouverture] ?? '';
              $horaire_fermeture = $_POST[$fermeture] ?? '';
      
              if (!empty($horaire_ouverture) || !empty($horaire_fermeture)) {
                  if (strtotime($horaire_ouverture) >= strtotime($horaire_fermeture)) {
                      $erreurs[] = "$jour : L'heure d'ouverture doit être plus ancienne que l'heure de fermeture !";
                  } else {
                      $horaires_par_jour[$jour] = [
                          'ouverture' => $horaire_ouverture,
                          'fermeture' => $horaire_fermeture
                      ];
                  }
              }
          }
      
          if (!empty($erreurs)) {
              foreach ($erreurs as $err) {
                  echo $err . "<br>";
              }
          } else {
              $_SESSION['crea_offre3'] = $horaires_par_jour;
              header('Location: ../etape_3_boost/creation_offre_restaurant_4.php');
              exit;
          }
      }
        
 /*       echo '<pre>';
        print_r($horaires_par_jour);
        echo '</pre>'; */

?>
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
                <li><a href="../mes_offres.php">Accueil</a></li>
                <li><a href="../creation_offre.php" class="active">Publier</a></li>
                <li><a href="../informations_personnelles_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
     <div class="fleche_retour">
        <div>
            <a href="../etape_2_restau/creation_offre_restaurant_2.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
        </div>
    </div>

    <div class="header-controls">
        <div>
            <img id="etapes" src="../images/fil_ariane3.png" alt="Étapes" width="80%" height="80%">
        </div>
    </div>


    <!-- Main Section -->
    <main class="main-creation-offre2">

        <div class="form-container2">
            <h1>Publier une offre</h1>

            <!-- Form Fields -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="checkFermeture()">
                 <!-- Offer Times -->
                 <h3>Horaires</h3>
                <div class="container">
                <!-- Lundi -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Lundi" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureL" name="ouvertureL" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureL" name="fermetureL" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxL'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Mardi -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Mardi" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureMa" name="ouvertureMA" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureMa" name="fermetureMa" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxMa'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Mercredi -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Mercredi" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureMe" name="ouvertureMe" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureMe" name="fermetureMe" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxMe'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Jeudi -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Jeudi" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureJ" name="ouvertureJ" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureJ" name="fermetureJ" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxJ'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Vendredi -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Vendredi" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureV" name="ouvertureV" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureV" name="fermetureV" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxV'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Samedi -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Samedi" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureS" name="ouvertureS" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureS" name="fermetureS" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxS'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Dimanche -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="Dimanche" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouvertureD" name="ouvertureD" placeholder="Ouverture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermetureD" name="fermetureD" placeholder="Fermeture">
                        </fieldset>
                    </div >
                    <div class="col">
                        <div class="ferme">
                            <span>Fermé</span>
                            <label class="switch" id='fermeCheckboxD'>
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>


                <button type="submit" id="button_valider" name="EnvoiEtape3">
                    Continuer <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
    </main>
</body>
</html>
