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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab_offre = [];
    // Récupération des champs obligatoires
    $nomOffre = htmlspecialchars($_POST['nom_offre'] ?? '');
    $adresse = htmlspecialchars($_POST['adresse'] ?? '');
    $ville = htmlspecialchars($_POST['ville'] ?? '');
    $codePostal = htmlspecialchars($_POST['code_postal'] ?? '');
    $resume = htmlspecialchars($_POST['resume'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $tarif = htmlspecialchars($_POST['prix'] ?? '');
    $age_mini = htmlspecialchars($_POST['age'] ?? '');

    // Récupération des champs facultatifs
    $complementAdresse = htmlspecialchars($_POST['complement_adresse'] ?? '');
    $lien = htmlspecialchars($_POST['lien'] ?? '');
    $accessibilite = htmlspecialchars($_POST['accessibilite'] ?? '');
    $nb_attractions = htmlspecialchars($_POST['nb_attractions'] ?? '');

    // Récupération des fichiers (photos)
    $photos = $_FILES['photos'] ?? null;

    $erreurs = [];

    // Validation des données
    if (empty($nomOffre) || empty($adresse) || empty($ville) || empty($codePostal) || empty($resume) || empty($description) || empty($tarif) || empty($age_mini)) {
        echo "Tous les champs obligatoires doivent être remplis.";
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
        } else {
            /* on ne peut pas insérer les données pour le moment...
            Donc on les STOCKE ! :D*/
            
            $tab_offre['titre_offre'] = $nomOffre;
            $tab_offre['adresse'] = $adresse;
            $tab_offre['ville'] = $ville;
            $tab_offre['codePostal'] = $codePostal;
            $tab_offre['resume'] = $resume;
            $tab_offre['description'] = $description;
            $tab_offre['prix'] = $tarif;
            $tab_offre['age_requis'] = $age_mini;
            
            // Récupération des champs facultatifs
            $tab_offre['complementAdresse'] = $complementAdresse;
            $tab_offre['lien'] = $lien;
            $tab_offre['accessibilite'] = $accessibilite;
            $tab_offre['nb_attractions'] = $nb_attractions;
            
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
            }

            $mesTags = [];
            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag) {
                    $mesTags[] = htmlspecialchars($tag);
                }
            }
            $tab_offre["tags"] = $mesTags;

            $_SESSION['crea_offre'] = $tab_offre;
            header('location: ../etape_2_horaires/creation_offre_attraction_2.php');
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
    <title>Offre Attraction</title>
    <link rel="stylesheet" href="../styles.css">


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
            <h1>Publiez une offre</h1>

            <!-- Form Fields -->
            <form action="" method="post" enctype="multipart/form-data" onsubmit="checkFermeture()">
                <!-- Establishment Name & Type -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nom du Parc *</legend>
                            <input type="text" id="nom_offre" name="nom_offre" placeholder="Nom du Parc *" required>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend style="display:block;">Type de l'offre</legend>
                            <input type="text" id="type_offre" name="type_offre" placeholder="Parc d'attractions" disabled required>
                        </fieldset>
                    </div>
                </div>

                <!-- Email & Phone -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Email *</legend>
                            <input type="email" id="email" name="email" placeholder="Email *" required>
                        </fieldset>            
                    </div>
                    <div class="col">                     
                        <fieldset>
                            <legend>Téléphone *</legend>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *" required>
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
                            <input type="number" id="prix" name="prix" placeholder="Tarif *" min="0" required oninput="validity.valid||(value='');">
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
                        <label for="photos">Photos (au moins une)</label>
                        <input type="file" id="photos" name="photos[]" multiple required>
                    </div>
                </div>

                <!-- Age requis -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Age minimum requis *</legend>
                            <input type="number" id="age" name="age" placeholder="Age minimum requis *" min="0" max="18" required oninput="validity.valid||(value='');">
                        </fieldset>
                    </div>  
                </div>

                <!-- Nb attractions -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nombre d'attractions</legend>
                            <input type="number" id="nb_attraction" name="nb_attractions" placeholder="Nombre d'attractions" required>
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

                $mesTags = $dbh->prepare("SELECT * FROM tripenarvor._tags WHERE parc_attractions = true");
                $mesTags->execute();
                $mesTags = $mesTags->fetchAll(PDO::FETCH_ASSOC);

                ?>

                <!-- Tags -->
                <table>
                    <thead>
                        <tr>
                            <th>
                                Tags
                            </th>
                            <th>
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
