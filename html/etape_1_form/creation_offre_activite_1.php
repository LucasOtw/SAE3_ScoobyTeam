<?php
ob_start();
session_start();

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);

if(!isset($_SESSION['pro'])){
    header('location: connexion_pro.php');
    exit;
}

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}

?>

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
                <li><a href="../mes_offres.php" class="active">Accueil</a></li>
                <li><a href="../creation_offre.php">Publier</a></li>
                <?php
                    if(isset($_SESSION["pro"]) || !empty($_SESSION["pro"])){
                       ?>
                       <li>
                           <a href="../consulter_compte_pro.php">Mon compte</a>
                       </li>
                        <li>
                            <a href="../connexion_pro.php?deco=true">Se déconnecter</a>
                        </li>
                        <?php
                    } else {
                        ?>
                       <li>
                           <a href="../connexion_pro.php">Se connecter</a>
                       </li>
                       <?php
                    }
                ?>
            </ul>
        </nav>
    </header>
     <div class="fleche_retour">
        <div>
            <a href="../creation_offre.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
        </div>
    </div>

    <div class="header-controls">
        <div>
            <img id="etapes" src="../images/fil_ariane1.png" alt="Étapes" width="80%" height="80%">
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
                            <input type="text" id="type_offre" name="type_offre" placeholder="Activité" disabled>
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

                <!-- Lien du site -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Ajouter le lien du site (facultatif)</legend>
                            <input type="url" id="lien" name="lien" placeholder="Ajouter le lien du site (facultatif)">
                        </fieldset>
                    </div>  
                </div>

                <!-- Photos -->
                <div class="row">
                    <div class="col">
                        <label for="photos">Photos (facultatif)</label>
                        <input type="file" id="photos" name="photos[]" multiple>
                    </div>
                </div>

                <!-- Age requis -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Age minimum requis *</legend>
                            <input type="number" id="age" name="age" placeholder="Age minimum requis *">
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
                            <legend>Accessibilité (facultatif)</legend>
                            <input type="text" id="accessibilite" name="accessibilite" placeholder="Accessibilité (facultatif)">
                        </fieldset>
                    </div>
                </div>


                <!-- Prestations incluses -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Prestations incluses (facultatif)</legend>
                            <input type="text" id="prestations_incluses" name="prestations_incluses" placeholder="Prestations incluses (facultatif)">
                        </fieldset>
                    </div>
                </div>


                <!-- Prestations non-incluses -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Prestations incluses (facultatif)</legend>
                            <input type="text" id="prestations_non_incluses" name="prestations_non_incluses" placeholder="Prestations non-incluses (facultatif)">
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

                <button type="submit" id="button_valider">
                    Continuer <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
    </main>
</body>
</html>
