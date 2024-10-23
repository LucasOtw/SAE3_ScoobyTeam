<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PACT</title>
    <link rel="stylesheet" href="creation_offre2.css">


</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="header-controls">
        <div>
            <img id="etapes" src="images/FilArianne2.png" alt="Étapes" width="80%" height="80%">
        </div>
    </div>


    <!-- Main Section -->
    <main class="main-creation-offre2">

        <div class="form-container2">
            <h1>Publier une offre</h1>

            <!-- Form Fields -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="checkFermeture()">
                <!-- Establishment Name & Type -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nom de l'activité *</legend>
                            <input type="text" id="nom_offre" name="nom_offre" placeholder="Nom de l'activité *">
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Type de l'offre</legend>
                            <input type="text" id="type_offre" name="type_offre" placeholder="Parc d'attractions" disabled>
                        </fieldset>
                    </div>
                </div>

                <!-- Email & Phone -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Email *</legend>
                            <input type="email" id="email" name="email" placeholder="Email *">
                        </fieldset>            
                    </div>
                    <div class="col">                     
                        <fieldset>
                            <legend>Téléphone *</legend>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *">
                        </fieldset>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Adresse Postale *</legend>
                            <input type="text" id="adresse" name="adresse" placeholder="Adresse Postale *">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Complément d'Adresse</legend>
                            <input type="text" id="complement_adresse" name="complement_adresse" placeholder="Complément d'Adresse ">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Ville *</legend>
                            <input type="text" id="ville" name="ville" placeholder="Ville *">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Code Postal *</legend>
                            <input type="text" id="code_postal" name="code_postal" placeholder="Code Postal *">
                        </fieldset>
                    </div>
                </div>

                <!-- Prix -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Tarif *</legend>
                            <input type="text" id="prix" name="prix" placeholder="Tarif *">
                        </fieldset>
                    </div>
                </div>

                <!-- Age requis -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Age minimum requis</legend>
                            <input type="number" id="age" name="age" placeholder="Age minimum requis">
                        </fieldset>
                    </div>  
                </div>

                <!-- Nb attractions -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nombre d'attractions</legend>
                            <input type="number" id="nb_attraction" name="nb_attractions" placeholder="Nombre d'attractions">
                        </fieldset>
                    </div>  
                </div>

                <!-- Tags -->
                <div class="row">
                    <div class="col">

                        <label for="tags">Tags</label>

                        <div class="dropdown-container">
                            <button type="button" class="dropdown-button">Sélectionner des tags</button>
                            <div class="dropdown-content">

                            <?php
                                    $dbh = new PDO("host=postgresdb;port=5432;dbname=db-scooby-team", "sae", "philly-Congo-bry4nt");

                                    foreach($dbh->query('SELECT nom_tag from tripenarvor._son_tag natural join tripenarvor._tags where restauration = true', PDO::FETCH_ASSOC) as $row)
                                    {
                                    ?>
                                <label class="tag">
                                    <input type="checkbox" name="tags" value="<?php echo $row; ?>">
                                    <?php echo ucfirst($row); ?>
                                </label>
                                    <?php
                                    }
                                ?>

                            </div>
                        </div>

                    </div>
                </div>


                <script>
                    document.querySelector('.dropdown-button').addEventListener('click', function() {
                        document.querySelector('.dropdown-content').classList.toggle('show');
                    });

                    // Close the dropdown if clicked outside
                    window.onclick = function(event) {
                        if (!event.target.matches('.dropdown-button')) {
                            var dropdowns = document.getElementsByClassName("dropdown-content");
                            for (var i = 0; i < dropdowns.length; i++) {
                                var openDropdown = dropdowns[i];
                                if (openDropdown.classList.contains('show')) {
                                    openDropdown.classList.remove('show');
                                }
                            }
                        }
                    };
                </script>



                <!-- Photos -->
                <div class="row">
                    <div class="col">
                        <label for="photos">Photos (facultatif)</label>
                        <input type="file" id="photos" name="photos[]" multiple>
                    </div>
                </div>


                <!-- Lien du site -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Ajouter le lien du site (facultatif)</legend>
                            <input type="url" id="lien" name="lien" placeholder="Ajouter le lien du site (facultatif)">
                        </fieldset>
                    </div>  
                </div>
                

                <!-- Résumé -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Résumé (facultatif)</legend>
                            <input type="text" id="resume" name="resume" placeholder="Résumé (facultatif)">
                        </fieldset>
                    </div>
                </div>

                <!-- Description -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Description (facultatif)</legend>
                            <input type="text" id="description" name="description" placeholder="Description (facultatif)">
                        </fieldset>
                    </div>
                </div>


                <!-- Accessibilite -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Accessibilité</legend>
                            <input type="text" id="accessibilite" name="accessibilite" placeholder="Accessibilité">
                        </fieldset>
                    </div>
                </div>


                 <!-- Offer Times -->
                <h2>Horaires</h2>
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

                <?php
                    $typeOffre = $dbh->prepare('select code_compte from tripenarvor.professionnel_prive');
                    $typeOffre->execute();
                ?>

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
                                    <label class="label-check" for="offre_gratuite">Offre Gratuite</label>
                                </div>
                            <?php
                            }
                            else 
                            {
                            ?>
                                <div>
                                    <input type="radio" id="offre_standard" name="offre" value="standard">
                                    <label class="label-check" for="offre_standard">Offre Standard</label>
                                </div>
                                <div>
                                    <input type="radio" id="offre_premium" name="offre" value="premium" checked>
                                    <label class="label-check" for="offre_premium">Offre Premium</label>
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
                            <label class="label-check" for="relief">Mettre mon offre "en Relief"</label>
                        </div>
                        <div>
                            <input type="radio" id="a_la_une" name="option" value="a_la_une" checked>
                            <label class="label-check" for="a_la_une">Mettre mon offre "À la Une"</label>
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
            

               

                <!-- Final Submit Button -->
                <button type="submit" id="button_valider">
                    Valider
                    <img src="images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>
            </form>

            <script>
                function checkFermeture() {
                    const checkboxL = document.getElementById('fermeCheckboxL');
                    const ouvertureInputL = document.getElementById('ouvertureL');
                    const fermetureInputL = document.getElementById('fermetureL');

                    const checkboxMa = document.getElementById('fermeCheckboxMa');
                    const ouvertureInputMa = document.getElementById('ouvertureMa');
                    const fermetureInputMa = document.getElementById('fermetureMa');

                    const checkboxMe = document.getElementById('fermeCheckboxMe');
                    const ouvertureInputMe = document.getElementById('ouvertureMe');
                    const fermetureInputMe = document.getElementById('fermetureMe');

                    const checkboxJ = document.getElementById('fermeCheckboxJ');
                    const ouvertureInputJ = document.getElementById('ouvertureJ');
                    const fermetureInputJ = document.getElementById('fermetureJ');

                    const checkboxV = document.getElementById('fermeCheckboxV');
                    const ouvertureInputV = document.getElementById('ouvertureV');
                    const fermetureInputV = document.getElementById('fermetureV');

                    const checkboxS = document.getElementById('fermeCheckboxS');
                    const ouvertureInputS = document.getElementById('ouvertureS');
                    const fermetureInputS = document.getElementById('fermetureS');

                    const checkboxD = document.getElementById('fermeCheckboxD');
                    const ouvertureInputD = document.getElementById('ouvertureD');
                    const fermetureInputD = document.getElementById('fermetureD');

                    if (checkboxL.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputL.value = "";
                        fermetureInputL.value = "";
                    }
                    else if (checkboxMa.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputMa.value = "";
                        fermetureInputMa.value = "";
                    }
                    else if (checkboxMe.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputMe.value = "";
                        fermetureInputMe.value = "";
                    }
                    else if (checkboxJ.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputJ.value = "";
                        fermetureInputJ.value = "";
                    }
                    else if (checkboxV.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputV.value = "";
                        fermetureInputV.value = "";
                    }
                    else if (checkboxS.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputS.value = "";
                        fermetureInputS.value = "";
                    }
                    else if (checkboxD.checked) {
                        // Si le bouton est activé (fermé), les valeurs d'ouverture et fermeture sont nulles
                        ouvertureInputD.value = "";
                        fermetureInputD.value = "";
                    }
                }
            </script>

            <?php

                $insert = $dbh ->prepare("insert into tripenarvor.offre_parc_attractions (titre_offre, date_publication, date_derniere_modif, _resume, _description, site_web, tarif, accessibilite, en_ligne, nb_blacklister, adresse_postale, complement_adresse, code_postal, ville, type_offre, nombre_attractions, age_requis)
                                            values (:titre_offre, GETDATE(), GETDATE(), :resume, :description, :site_web, :tarif, :accessibilite, false, 0, :adresse_postale, :complement_adresse, :code_postal, :ville, :type_offre, :nombre_attractions, :age_requis)");
                
                $insert->execute(["titre_offre" => $_POST["nom_offre"], 
                                    "resume" => $_POST["resume"], 
                                    "description" => $_POST["description"], 
                                    "site_web" => $_POST["lien"], 
                                    "tarif" => $_POST["prix"], 
                                    "accessibilite" => $_POST["accessibilite"], 
                                    "adresse_postale" => $_POST["adresse_postale"], 
                                    "complement_adresse" => $_POST["complement_adresse"], 
                                    "code_postal" => $_POST["code_postal"], 
                                    "ville" => $_POST["ville"], 
                                    "type_offre" => $_POST["offre"], 
                                    "nombre_attractions" => $_POST["nb_attractions"],
                                    "age_requis" => $_POST["age"]]);
                
                switch ($_POST["option"]) {
                    case 'en_relief':
                        $insert = $dbh -> prepare("insert into tripenarvor._option (nb_semaines, date_debut, date_fin, prix)
                                                    values (:nb_semaines, GETDATE(), DATEADD(WEEK, :nb_semaines, GETDATE()), 10.99)" );
                        $insert->execute(["nb_semaines"=>$_POST["duree_option"]]);

                        $select = $dbh -> prepare("select currval('tripenarvor._option_code_option_seq')");
                        $select->execute();

                        $update = $dbh -> prepare("update tripenarvor._offre
                                                    set option_en_relief = :code_option
                                                    where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                        $update->execute(["code_option"=>$select]);
                        break;
                    
                    case 'a_la_une':
                        $insert = $dbh -> prepare("insert into tripenarvor._option (nb_semaines, date_debut, date_fin, prix)
                                                    values (:nb_semaines, GETDATE(), DATEADD(WEEK, :nb_semaines, GETDATE()), 10.99)" );
                        $insert->execute(["nb_semaines"=>$_POST["duree_option"]]);

                        $select = $dbh -> prepare("select currval('tripenarvor._option_code_option_seq')");
                        $select->execute();

                        $update = $dbh -> prepare("update tripenarvor._offre
                                                    set option_a_la_une = :code_option
                                                    where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                        $update->execute(["code_option"=>$select]);
                        break;

                    default:
                        break;
                }



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureL"], "fermeture"=>$_POST["fermetureL"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set lundi = :lundi,
                                            where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["lundi"=>$select]);



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureMa"], "fermeture"=>$_POST["fermetureMa"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set mardi = :mardi,
                                                where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["mardi"=>$select]);



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureMe"], "fermeture"=>$_POST["fermetureMe"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set mercredi = :mercredi,
                                            where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["mercredi"=>$select]);



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureJ"], "fermeture"=>$_POST["fermetureJ"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set jeudi = :jeudi,
                                                where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["jeudi"=>$select]);



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureV"], "fermeture"=>$_POST["fermetureV"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set vendredi = :vendredi,
                                                where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["vendredi"=>$select]);



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureS"], "fermeture"=>$_POST["fermetureS"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set samedi = :samedi,
                                                where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["samedi"=>$select]);



                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureD"], "fermeture"=>$_POST["fermetureD"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor._offre
                                            set dimanche = :dimanche,
                                                where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["dimanche"=>$select]);



                
                if (isset($_FILES['photos'])) {
                    $files = $_FILES['photos'];

                    // Parcourir les fichiers téléchargés
                    for ($i = 0; $i < count($files['name']); $i++) {
                        $fileName = $files['name'][$i];
                        $fileTmpName = $files['tmp_name'][$i];
                        $fileError = $files['error'][$i];
                        $fileSize = $files['size'][$i];

                        // Vérifier s'il n'y a pas d'erreur lors du téléchargement
                        if ($fileError === 0) {
                            // Définir un répertoire de destination
                            $destination = 'imagesUser/' . $fileName;

                            // Déplacer le fichier vers le répertoire de destination
                            if (move_uploaded_file($fileTmpName, $destination)) {

                                echo "Le fichier $fileName a été téléchargé avec succès !<br>";

                                $insert = $dbh -> prepare("insert into tripenarvor._image (url_image) values (:url_image)");
                                $insert->execute(["url_image" => $fileName]);

                                $select = $dbh -> prepare("select currval('tripenarvor._image_code_image_seq')");
                                $select->execute();

                                $insert = $dbh -> prepare("insert into tripenarvor._son_image (code_image, code_offre) 
                                                            values (:code_image, (select currval('tripenarvor._offre_code_offre_seq')) )");
                                $insert->execute(["code_image" => $select]);

                            } else {
                                echo "Erreur lors du téléchargement du fichier $fileName.<br>";
                            }
                        } else {
                            echo "Erreur avec le fichier $fileName (code erreur : $fileError).<br>";
                        }
                    }
                } else {
                    echo "Aucun fichier sélectionné.";
                }


            ?>

        </div>

    </main>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Publier</a></li>
                    <li><a href="#">Historique</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Signaler un problème</a></li>
                    <li><a href="#">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
           
    </footer>
</body>
</html>
