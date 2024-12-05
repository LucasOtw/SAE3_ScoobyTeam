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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $tab_offre = [];

    // Récupération des champs obligatoires
    $nomOffre = htmlspecialchars($_POST['nom_offre'] ?? '');
    $adresse = htmlspecialchars($_POST['adresse'] ?? '');
    $ville = htmlspecialchars($_POST['ville'] ?? '');
    $codePostal = htmlspecialchars($_POST['code_postal'] ?? '');
    $resume = htmlspecialchars($_POST['resume'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    // tarif
    $date_visite = $_POST['date_visite'];
    $tarif = $_POST['_tarif'];
    $duree = $_POST['duree'];
    $heure_visite = $_POST['heure_visite'];

    // Récupération des champs facultatifs
    $complementAdresse = htmlspecialchars($_POST['complement_adresse'] ?? '');
    $lien = htmlspecialchars($_POST['lien'] ?? '');
    $accessibilite = htmlspecialchars($_POST['accessibilite'] ?? '');

    // Récupération des fichiers (images)
    $photos = $_FILES['photos'] ?? null;

    $erreurs = [];

    if(empty($nomOffre) || empty($adresse) || empty($ville) || empty($codePostal) || empty($tarif) || empty($duree) || empty($resume) || empty($description)){
        $erreurs[] = "Tous les champs obligatoires doivent être remplis.";
    } else {
        // si tous les champs obligatoires sont remplis, on procède aux vérifications
        if (!preg_match('/^([0-9]{5}|2[AB])$/', $codePostal)) {
            $erreurs[] = "Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B.";
        }
        if(!empty($ville) && !empty($codePostal)){
            $api_codePostal = 'http://api.zippopotam.us/fr/'.trim($codePostal);

            $api_codePostal = file_get_contents($api_codePostal);
            if($api_codePostal === FALSE){
                $erreurs[] = "Erreur lors de l'accès à l'API";
                exit();
            }
            
            $data = json_decode($api_codePostal,true);
            $isValid = false;
            
            if($data && isset($data['places'])){
                foreach($data['places'] as $place){
                    if(stripos($place['place name'], $ville) === 0){
                        $isValid = true;
                        break;
                    }
                }
            }
            if(!$isValid){
                $erreurs[] = "La ville ne correspond pas au code postal";
                $erreurs_a_afficher[] = "erreur-ville-code-postal-incompatible";
            }
        }
        if(!empty($erreurs)){
            foreach($erreurs as $erreur){
                echo $erreur;
            }
            echo "HA";
        } else {
            // si il n'y a aucune erreur, on peut stocker les données.
            echo "<pre>";
            foreach($_POST as $d){
                print_r($d);
            }
            echo "</pre>";
            $tab_offre['titre_offre'] = $nomOffre;
            $tab_offre['adresse'] = $adresse;
            $tab_offre['ville'] = $ville;
            $tab_offre['codePostal'] = $codePostal;
            $tab_offre['resume'] = $resume;
            $tab_offre['description'] = $description;
            $tab_offre['tarif'] = $tarif;
            $tab_offre['date_visite'] = $date_visite;

            if(empty($duree)){
                $duree = null;
            } else {
                $tab_offre['duree'] = $duree;
            }

            if(empty($heure_visite)){
                $heure_visite = null;
            } else {
                $tab_offre['heure_visite'] = $heure_visite;   
            }

            // Récupération des champs facultatifs
            $tab_offre['complementAdresse'] = $complementAdresse;
            $tab_offre['lien'] = $lien;
            $tab_offre['accessibilite'] = $accessibilite;
            $tab_offre['langues'] = $_POST['langues'];

            // Récupération des fichiers (photos) - si plusieurs fichiers sont envoyés
            if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
                $photos = [];
                // Parcourir chaque photo envoyée
                foreach ($_FILES['photos']['name'] as $key => $file_name) {
                    // Si le fichier n'est pas vide, on récupère les informations nécessaires
                    if ($_FILES['photos']['error'][$key] === 0) {
                        $photos[] = [
                            'name' => $file_name,
                            'type' => $_FILES['photos']['type'][$key],
                            'tmp_name' => $_FILES['photos']['tmp_name'][$key],
                            'size' => $_FILES['photos']['size'][$key]
                        ];
                    }
                }
            
                /* on n'a plus besoin de rajouter les photos dans la session
                puisque le chemin temporaire n'est disponible que lors de l'envoi du formulaire */

                $nom_dossier_images = $tab_offre['titre_offre'];
                $nom_dossier_images = str_replace(' ','',$nom_dossier_images);
                $destination = "../images/offres/".$nom_dossier_images;

                if(!file_exists($destination)){
                    mkdir($destination, 0777, true); // crée le dossier si il n'existe pas.
                }

                foreach($photos as $photo){
                    $nom_temp = $photo['tmp_name'];
                    $nom_photo = $photo['name'];

                    // on construit le chemin de destination complet
                    $chemin = $destination . '/' . $nom_photo;

                    if (file_exists($nom_temp)) {
                        // Déplacer le fichier dans le dossier cible
                        if (move_uploaded_file($nom_temp, $chemin)) {
                            echo "Le fichier $nom_photo a été déplacé avec succès.<br>";
                        } else {
                            echo "Erreur : Impossible de déplacer le fichier $nom_photo.<br>";
                        }
                    } else {
                        echo "Erreur : Le fichier temporaire $nom_temp n'existe pas.<br>";
                    }
                }
            } else {
                $erreurs[] = "Veuillez choisir au moins une image !";
            }
            $_SESSION['crea_offre'] = $tab_offre;
            var_dump($_SESSION['crea_offre']);
            header('location: ../etape_2_horaires/creation_offre_visite_2.php');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre Visite</title>
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
                            <legend>Nom de la visite *</legend>
                            <input type="text" id="nom_offre" name="nom_offre" placeholder="Nom de la visite *" required>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Type de l'offre</legend>
                                <input type="text" id="type_offre" name="type_offre" placeholder="Visite" disabled>
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
                            <legend>Tarif (€) *</legend>
                            <input type="number" id="prix" name="_tarif" placeholder="Tarif *" min="0" step="0.01" required>
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
                        <label for="photos">Photos (au moins 1 image)</label>
                        <input type="file" id="photos" name="photos[]" multiple>
                    </div>
                </div>

                <!-- Durée de la visite -->
                <div class="row">
                    <div class="col">
                        <fieldset class="duree">
                            <legend style="display:block;">Durée de la visite *</legend>
                            <input type="time" id="duree" name="duree" placeholder="Durée de la visite">
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Date de la visite</legend>
                            <input type="date" name="date_visite">
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset class="duree">
                            <legend style="display:block;">Heure de la visite</legend>
                            <input type="time" id="duree" name="heure_visite" placeholder="Heure de la visite">
                        </fieldset>
                    </div>
                </div>

                <!-- Visite guidée ? -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Langues (Visite guidée)</legend>
                            <input 
                                type="text" 
                                name="langues" 
                                placeholder="Français, Anglais, ..." 
                                pattern="^\s*([A-Za-zÀ-ÿ]+(\s+[A-Za-zÀ-ÿ]+)?)(\s*,\s*([A-Za-zÀ-ÿ]+(\s+[A-Za-zÀ-ÿ]+)?))*\s*$" 
                                title="Entrez une liste de langues séparées par des virgules, ex. Français, Anglais"
                            >
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

                $mesTags = $dbh->prepare("SELECT * FROM tripenarvor._tags WHERE visite = true");
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
