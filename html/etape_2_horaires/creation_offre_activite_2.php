<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre Activité</title>
    <link rel="stylesheet" href="../creation_offre2.css">


</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="../../images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="../voir_offres.php">Accueil</a></li>
                <li><a href="../creation_offre.php" class="active">Publier</a></li>
                <li><a href="../informations_personnelles_pro.php">Mon Compte</a></li>
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
            <img id="etapes" src="../images/fil_ariane2.png" alt="Étapes" width="80%" height="80%">
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

                <button type="submit" id="button_valider">
                    Continuer <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
    </main>
</body>
</html>
