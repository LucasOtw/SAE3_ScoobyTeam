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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $tab_offre = [];

    // Récupération des champs obligatoires
    $nomOffre = htmlspecialchars($_POST['nom_offre'] ?? '');
    $adresse = htmlspecialchars($_POST['adresse'] ?? '');
    $ville = htmlspecialchars($_POST['ville'] ?? '');
    $codePostal = htmlspecialchars($_POST['code_postal'] ?? '');
    $resume = htmlspecialchars($_POST['resume'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $tarif = $_POST['prix'] ?? null;

    // Récupération des champs facultatifs
    $complementAdresse = htmlspecialchars($_POST['complement_adresse'] ?? '');
    $lien = htmlspecialchars($_POST['lien'] ?? '');
    $accessibilite = htmlspecialchars($_POST['accessibilite'] ?? '');

    // Récupération des fichiers (images)
    $photos = $_FILES['photos'] ?? null;
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
                <li><a href="../creation_offre.php" class="active">Publier</a></li>
                <li><a href="../consulter_compte_pro.php">Mon compte</a></li>
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
            <form action="#" method="post" enctype="multipart/form-data" onsubmit="checkFermeture()">
                <!-- Establishment Name & Type -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nom du spectacle *</legend>
                            <input type="text" id="nom_offre" name="nom_offre" placeholder="Nom du spectacle *" required>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Type de l'offre</legend>
                            <input type="text" id="type_offre" name="type_offre" placeholder="Spectacle" disabled>
                        </fieldset>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Adresse Postale *</legend>
                            <input type="text" id="adresse" name="adresse" placeholder="Adresse Postale *" required>
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
                            <input type="text" id="ville" name="ville" placeholder="Ville *" required>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Code Postal *</legend>
                            <input type="text" id="code_postal" name="code_postal" placeholder="Code Postal *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- Prix -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Tarif *</legend>
                            <input type="text" id="prix" name="prix" placeholder="Tarif *" required>
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
                        <input type="file" id="photos" name="photos[]" multiple required>
                    </div>
                </div>

                <!-- Capacite accueil -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Capacité d'accueil *</legend>
                            <input type="number" id="capacite_accueil" name="capacite_accueil" placeholder="Capacité d'accueil *" required>
                        </fieldset>
                    </div>  
                </div>

                <!-- Durée spectacle -->
                <div class="row">
                    <div class="col">
                        <fieldset class="duree">
                            <legend style="display:block;">Durée du spectacle</legend>
                            <input type="time" id="duree" name="duree" placeholder="Durée du spectacle" required>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset class="duree">
                            <legend style="display:block;">Date du spectacle</legend>
                            <input type="date" name="date" placeholder="Date du spectacle" required>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset class="duree">
                            <legend style="display: block;">Heure du spectacle</legend>
                            <input type="time" name="duree" placeholder="Heure du spectacle" required>
                        </fieldset>
                    </div>
                </div>
                

                <!-- Résumé -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Résumé</legend>
                            <input type="text" id="resume" name="resume" placeholder="Résumé" required>
                        </fieldset>
                    </div>
                </div>

                <!-- Description -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Description</legend>
                            <input type="text" id="description" name="description" placeholder="Description" required>
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

                <?php

                $mesTags = $dbh->prepare("SELECT * FROM tripenarvor._tags WHERE spectacle = true");
                $mesTags->execute();
                $mesTags = $mesTags->fetchAll(PDO::FETCH_ASSOC);

                ?>

                <!-- Tags -->
                <table>
                    <thead>
                        <tr>
                            <th>
                                Checkbox
                            </th>
                            <th>
                                Tag
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($mesTags as $monTag){
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="tags[]" value="<?php echo $monTag['code_tag'] ?>">
                                        <!-- On peut stocker chaque valeur pour chaque checkbox cochée dans un table "tags[]" -->
                                    </td>
                                    <td>
                                        <?php echo $monTag['nom_tag']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>


                <button type="submit" id="button_valider">
                    Continuer <img src="../images/fleche.png" alt="Fleche" width="25px" height="25px">
                </button>

            </form>
        </div>
    </main>
</body>
</html>
