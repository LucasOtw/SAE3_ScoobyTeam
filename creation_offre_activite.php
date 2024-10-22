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
            <form action="" method="post" enctype="multipart/form-data">
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
                            <input type="text" id="type_offre" name="type_offre" placeholder="Activité" disabled>
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

                <!-- Tags -->
                <div class="row">
                    <div class="col">

                        <label for="tags">Tags</label>

                        <div class="dropdown-container">
                            <button type="button" class="dropdown-button">Sélectionner des tags</button>
                            <div class="dropdown-content">

                            <?php
                                    $dbh = new PDO("host=postgresdb;port=5432;dbname=db-scooby-team", "sae", "philly-Congo-bry4nt");

                                    foreach($dbh->query('SELECT nom_tag from tripenarvor._son_tag natural join tripenarvor._tags where activite = true', PDO::FETCH_ASSOC) as $row)
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

                <!-- Age requis -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Age minimum requis</legend>
                            <input type="number" id="age" name="age" placeholder="Age minimum requis">
                        </fieldset>
                    </div>  
                </div>

                <!-- Durée de l'activité -->
                <div class="row">
                <div class="col">
                        <fieldset class="duree">
                            <legend style="display:block;">Durée de l'activité</legend>
                            <input type="time" id="duree" name="duree" placeholder="Durée de l'activité">
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


                <!-- Prestations incluses -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Prestations incluses</legend>
                            <input type="text" id="prestations_incluses" name="prestations_incluses" placeholder="Prestations incluses">
                        </fieldset>
                    </div>
                </div>


                <!-- Prestations non-incluses -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Prestations incluses</legend>
                            <input type="text" id="prestations_non_incluses" name="prestations_non_incluses" placeholder="Prestations non-incluses">
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
                            <input type="radio" id="no_boost" name="boost" value="no_boost">
                            <label class="label-check" for="no_boost">Ne pas booster mon offre</label>
                        </div>
                        <div>
                            <input type="radio" id="relief" name="boost" value="relief">
                            <label class="label-check" for="relief">Mettre mon offre "en Relief"</label>
                        </div>
                        <div>
                            <input type="radio" id="a_la_une" name="boost" value="a_la_une" checked>
                            <label class="label-check" for="a_la_une">Mettre mon offre "À la Une"</label>
                        </div>
                    </div>
                </div>

                <!-- Duration Options -->
                <div class="duration-options">
                    <label>Durée du boost</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" id="1_semaine" name="duree" value="1_semaine">
                            <label class="label-check" for="1_semaine">1 semaine</label>
                        </div>
                        <div>
                            <input type="radio" id="2_semaines" name="duree" value="2_semaines">
                            <label class="label-check" for="2_semaines">2 semaines</label>
                        </div>
                        <div>
                            <input type="radio" id="3_semaines" name="duree" value="3_semaines" checked>
                            <label class="label-check" for="3_semaines">3 semaines</label>
                        </div>
                        <div>
                            <input type="radio" id="4_semaines" name="duree" value="4_semaines">
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
                function checkStatus(id) {
                    const checkbox = document.getElementById(id);
                    
                    if (checkbox.checked) {
                        return true;
                    } else {
                        return false;
                    }
                }
             </script>

            <?php
                $insert = $dbh ->prepare("insert into tripenarvor.offre_activite (titre_offre, date_publication, date_derniere_modif, _resume, _description, site_web, tarif, accessibilite, en_ligne, nb_blacklister, adresse_postale, complement_adresse, code_postal, ville, type_offre, duree, age_requis, prestations_incluses, prestations_non_incluses)
                                            values (:titre_offre, GETDATE(), GETDATE(), :resume, :description, :site_web, :tarif, :accessibilite, false, 0, :adresse_postale, :complement_adresse, :code_postal, :ville, :type_offre, :duree, :age_requis, :prestations_incluses, :prestations_non_incluses)");
                
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
                                    "duree" => $_POST["duree"], 
                                    "prestations_incluses" => $_POST["prestations_incluses"], 
                                    "prestations_non_incluses" => $_POST["prestations_non_incluses"]]);
                

                $insert = $dbh -> prepare("insert into tripenarvor._horaire(ouverture, fermeture) values (:ouverture, :fermeture)");
                $insert->execute(["ouverture"=>$_POST["ouvetureL"], "fermeture"=>$_POST["fermetureL"]]);

                $select = $dbh -> prepare("select currval('tripenarvor._horaire_code_horaire_seq')");
                $select->execute();

                $update = $dbh -> prepare("update tripenarvor.offre_activite
                                            set lundi = :lundi,
                                                professionnel = :code_compte,
                                                code_adresse = (select code_adresse from tripenarvor._adresse where code_offre = (select currval('tripenarvor._offre_code_offre_seq')))
                                            where code_offre = (select currval('tripenarvor._offre_code_offre_seq'))");
                $update->execute(["lundi"=>$select, "code_compte"=>$_SESSION["compte"]]);
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
