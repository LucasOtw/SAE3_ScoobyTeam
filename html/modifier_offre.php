<?php
ob_start();
session_start();

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

// Créer une instance PDO avec les bons paramètres
$dbh = new PDO($dsn, $username, $password);

include("recupInfosCompte.php");

if (isset($_GET["deco"])) {
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}
if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}

// Définir le filtre par défaut
$filter = "all";
if (isset($_GET["filter"])) {
    $filter = $_GET["filter"];
}

if(isset($_SESSION['aCreeUneOffre'])){
    unset($_SESSION['aCreeUneOffre']);
}

if (isset($_POST['envoiOffre'])) {
    $_SESSION['modif_offre'] = unserialize($_POST['uneOffre']);
    $offre = $_SESSION['modif_offre'];
}

$offre = $_SESSION['modif_offre'];


/* TABLEAU DES JOURS */


$jours = [
    'lundi',
    'mardi',
    'mercredi',
    'jeudi',
    'vendredi',
    'samedi',
    'dimanche'
];


/* RÉCUPÉRATION DES PHOTOS */

$req_recup_photos = $dbh->prepare("SELECT url_image FROM tripenarvor._image WHERE code_Image IN
(
SELECT code_Image FROM tripenarvor._son_image WHERE code_offre = :code_offre
)");
$req_recup_photos->bindValue(":code_offre",$offre['code_offre']);
$req_recup_photos->execute();

$recup_photos = $req_recup_photos->fetchAll(PDO::FETCH_COLUMN);

echo "<pre>";
var_dump($recup_photos);
echo "</pre>";

/* RÉCUPÉRATION DES HORAIRES */


// Récupération des codes horaires pour chaque jour
$req_codes = $dbh->prepare("SELECT lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche
                            FROM tripenarvor._offre
                            WHERE code_offre = :code_offre");
$req_codes->bindValue(":code_offre", $offre['code_offre']);
$req_codes->execute();
$codes_horaires = $req_codes->fetch(PDO::FETCH_ASSOC); // Utiliser fetch() pour obtenir une seule ligne

// Maintenant, on veut les heures d'ouverture et de fermeture pour chaque code
foreach($codes_horaires as $jour => $code){
    // Si le code est null, on continue
    if ($code === null) {
        continue;
    } else {
        // Requête pour récupérer les horaires d'ouverture et de fermeture
        $req_horaire = $dbh->prepare("SELECT ouverture, fermeture FROM tripenarvor._horaire
                                      WHERE code_horaire = :code");
        $req_horaire->bindValue(":code", $code);
        $req_horaire->execute();
        
        $tab_horaire = $req_horaire->fetch(PDO::FETCH_ASSOC); // Utiliser fetch() ici aussi pour obtenir une seule ligne

        // Vérifier si des horaires ont été trouvés pour ce code
        if ($tab_horaire) {
            // Ajouter les horaires à $codes_horaires
            $codes_horaires[$jour] = [
                'code_horaire' => $code,
                'ouverture' => $tab_horaire['ouverture'],
                'fermeture' => $tab_horaire['fermeture']
            ];
        }
    }
}

// Debug : Afficher les horaires récupérés

echo "<pre>";
var_dump($codes_horaires);
echo "</pre>";


/* RÉCUPÉRATION DU "TYPE DE L'OFFRE" */

$tables = [
    '_offre_activite',
    '_offre_parc_attractions',
    '_offre_restauration',
    '_offre_spectacle',
    '_offre_visite'
];

$infos_offre = null;
$type_offre = null;

foreach($tables as $table){
    // on cherche les infos de l'offre dans chaque table, SI elle est présente
    
    $requete = "SELECT t.* FROM tripenarvor.$table t JOIN tripenarvor._offre o
    ON o.code_offre = t.code_offre
    WHERE t.code_offre = :code_offre";

    $stmt = $dbh->prepare($requete);
    $stmt->bindValue(":code_offre",$offre['code_offre']);
    $stmt->execute();

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($res)){
        $infos_offre = $res;
        $type_offre = str_replace("_offre_",'',$table);
        break;
    }
}

echo "<h1>".$type_offre."</h1>";



/* RÉCUPÉRATION DES TAGS */



$req_tags = $dbh->prepare("SELECT * FROM tripenarvor._tags WHERE $type_offre = true");
$req_tags->execute();
$tags_offre = $req_tags->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($tags_offre);
echo "</pre>";

/* RÉCUPÉRATION DES TAGS DE L'OFFRE */

$req_tags_offre = $dbh->prepare("SELECT code_tag FROM tripenarvor._son_tag WHERE code_offre = :code_offre");
$req_tags_offre->bindValue(":code_offre",$offre['code_offre']);
$req_tags_offre->execute();

$mes_tags = $req_tags_offre->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($mes_tags);
echo "</pre>";


// Si on envoie le script de modification...

if (isset($_POST['envoi_modif'])){

    $erreurs = [];

    if(empty($_POST['_titre_modif']) || empty($_POST['_resume_modif']) || empty($_POST['_desc_modif'])){
        $erreurs[] = "Des champs obligatoires ne sont pas remplis !";
    }

    /* VÉRIFICATION DU CODE POSTAL */

    if(!empty($_POST['_ville_modif']) && !empty($_POST['_code_postal_modif'])){

        $ville = $_POST['_ville_modif'];
        $codePostal = $_POST['_code_postal_modif'];
        
        $api_codePostal = 'http://api.zippopotam.us/fr/'.$codePostal;
    
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
        }
    }

    /* VERIFICATION DES HORAIRES */

    foreach ($_POST['horaires'] as $jour => $horaire) {
        $code_horaire_existant = isset($codes_horaires[$jour]) ? $codes_horaires[$jour]['code_horaire'] : null;
    
        // DEBUG : Vérifie chaque étape
        error_log("Traitement du jour : $jour | Ouverture : {$horaire['ouverture']} | Fermeture : {$horaire['fermeture']}");
    
        if (!empty($horaire['ouverture']) && !empty($horaire['fermeture'])) {
            if ($horaire['ouverture'] > $horaire['fermeture']) {
                $erreurs[] = "Erreur : L'heure d'ouverture ne peut pas être après l'heure de fermeture pour : {$jour}";
                break; // Sort de la boucle pour éviter un problème
            }
    
            if ($code_horaire_existant !== null) {
                // Mise à jour
                if ($horaire['ouverture'] != $codes_horaires[$jour]['ouverture']) {
                    $req_update = $dbh->prepare("UPDATE tripenarvor._horaire SET ouverture = :ouverture WHERE code_horaire = :code_horaire");
                    $req_update->bindValue(':ouverture', $horaire['ouverture']);
                    $req_update->bindValue(':code_horaire', $code_horaire_existant);
                    $req_update->execute();
                }
                if ($horaire['fermeture'] != $codes_horaires[$jour]['fermeture']) {
                    $req_update = $dbh->prepare("UPDATE tripenarvor._horaire SET fermeture = :fermeture WHERE code_horaire = :code_horaire");
                    $req_update->bindValue(':fermeture', $horaire['fermeture']);
                    $req_update->bindValue(':code_horaire', $code_horaire_existant);
                    $req_update->execute();
                }
            } else {
                // Insertion
                $req_insert = $dbh->prepare("INSERT INTO tripenarvor._horaire (ouverture, fermeture) VALUES (:ouverture, :fermeture) RETURNING code_horaire");
                $req_insert->bindValue(':ouverture', $horaire['ouverture']);
                $req_insert->bindValue(':fermeture', $horaire['fermeture']);
                $req_insert->execute();
                $nouveau_code_horaire = $req_insert->fetchColumn();
    
                $req_update_offre = $dbh->prepare("UPDATE tripenarvor._offre SET {$jour} = :code_horaire WHERE code_offre = :code_offre");
                $req_update_offre->bindValue(':code_horaire', $nouveau_code_horaire);
                $req_update_offre->bindValue(':code_offre', $offre['code_offre']);
                $req_update_offre->execute();
            }
        } elseif (empty($horaire['ouverture']) && empty($horaire['fermeture'])) {
            if ($code_horaire_existant !== null) {
                $req_delete = $dbh->prepare("DELETE FROM tripenarvor._horaire WHERE code_horaire = :code_horaire");
                $req_delete->bindValue(':code_horaire', $code_horaire_existant);
                $req_delete->execute();
    
                $req_update_offre = $dbh->prepare("UPDATE tripenarvor._offre SET {$jour} = NULL WHERE code_offre = :code_offre");
                $req_update_offre->bindValue(':code_offre', $offre['code_offre']);
                $req_update_offre->execute();
            }
        } else {
            $erreurs[] = "Erreur : Vous devez spécifier les horaires d'ouverture et de fermeture pour : {$jour}";
            break; // Quitte la boucle pour éviter une boucle infinie
        }
    }

    // Si il n'y a pas d'erreurs...
    
    if(empty($erreurs)){

        echo "<pre>";
        var_dump($_POST['horaires']);
        echo "</pre>";

        if(!empty($_POST['tags'])){
            $valeurs_tags = array_column($mes_tags, 'code_tag');
            var_dump($valeurs_tags);
            
            // Première boucle : suppression des tags non sélectionnés
            foreach($valeurs_tags as $un_tag){
                if(in_array($un_tag, $_POST['tags'])){
                    echo $un_tag."<br>";
                } else {
                    // Si un tag de la table n'est pas dans le tableau $_POST['tags'], on le supprime
                    $req_del_tag = $dbh->prepare("DELETE FROM tripenarvor._son_tag WHERE code_tag = :code_tag AND code_offre = :code_offre");
                    $req_del_tag->bindValue(":code_tag", $un_tag);
                    $req_del_tag->bindValue(":code_offre", $offre['code_offre']);
                    $req_del_tag->execute();
                }
            }
            
            // Deuxième boucle : ajout des nouveaux tags non présents dans la table
            foreach($_POST['tags'] as $tab_tag){
                // Si un tag de $_POST['tags'] n'est pas dans les tags existants
                if(!in_array($tab_tag, $valeurs_tags)){
                    $req_add_tag = $dbh->prepare("INSERT INTO tripenarvor._son_tag (code_tag, code_offre) VALUES (:code_tag, :code_offre)");
                    $req_add_tag->bindValue(":code_tag", $tab_tag);
                    $req_add_tag->bindValue(":code_offre", $offre['code_offre']);
                    $req_add_tag->execute();
                }
            }
        } else {
            // si c'est vide, alors on supprime tous les tags pour l'offre
            $req_del_all = $dbh->prepare("DELETE FROM tripenarvor._son_tag WHERE code_offre = :code_offre");
            $req_del_all->bindValue(":code_offre",$offre['code_offre']);
            $req_del_all->execute();
        }
        
        // table "_offre"

        $tab_offre = array(
            "titre_offre" => $_POST['_titre_modif'],
            "_resume" => $_POST['_resume_modif'],
            "_description" => $_POST['_desc_modif'],
            "accessibilite" => $_POST['_access_modif']
        );
        

        // table "_adresse"
        
        $tab_adresse = array(
            "code_postal" => $_POST['_code_postal_modif'],
            "ville" => $_POST['_ville_modif'],
            "adresse_postal" => $_POST['_adresse_modif'],
            "complement_adresse" => $_POST['_comp_adresse_modif']
        );
        
    
        foreach($tab_offre as $att => $val){
            $requete = "UPDATE tripenarvor._offre SET $att = :value WHERE code_offre = :code_offre";
            $stmt = $dbh->prepare($requete);
            print_r($requete);
    
            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_offre",$offre['code_offre']);
    
            try {
                $stmt->execute();

                if ($att == "titre_offre") {
                    // Modifier le nom du dossier

                    $ancien_dossier = str_replace(' ','',$offre['titre_offre']);
                    $nouveau_dossier = $val;

                    $ancien_chemin = "images/offres/{$ancien_dossier}";
                    $nouveau_chemin = "images/offres/{$nouveau_dossier}";

                    if(is_dir($ancien_chemin)){
                        if(!file_exists($nouveau_chemin)){
                            rename($ancien_chemin,$nouveau_chemin);
                        }
                    }

                    // Modification dans la bdd pour toutes les images.
                    foreach($recup_photos as $image){
                        $nom_image = basename($image);
                        $img_ancien = $ancien_chemin . "/" . $nom_image;
                        $img_nouveau = $nouveau_chemin . "/" . $nom_image;
                        
                        $req_update_image = $dbh->prepare("UPDATE tripenarvor._image SET url_image = :nouveau WHERE url_image = :ancien");
                        $req_update_image->bindValue(":nouveau",$img_nouveau);
                        $req_update_image->bindValue(":ancien",$img_ancien);
                        $req_update_image->execute();
                    }
                }
                
                echo "Champ $att mis à jour avec succès.<br>";
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour du champ $att: " . $e->getMessage() . "<br>";
            }
        }
        foreach($tab_adresse as $att => $val){
            $requete = "UPDATE tripenarvor._adresse SET $att = :value WHERE code_adresse = :code_adresse";
            $stmt = $dbh->prepare($requete);

            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_adresse",$offre['code_adresse']);

            try {
                $stmt->execute();
                echo "Champ $att mis à jour avec succès. <br>";
            } catch (PDOException $e){
                echo "Erreur lors de la mise à jour du champ $att : " . $e->getMessage() . "<br>";
            }
        }
    } else {
        foreach($erreurs as $err){
            echo $err."<br>";
        }
    }
}

echo "<pre>";
var_dump($offre);
echo "</pre>";

if($infos_offre !== null){
    echo "<pre>";
    var_dump($infos_offre);
    $infos_offre = $infos_offre[0];
    echo "</pre>";
}
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Modifier mon offre</title>
    <link rel="stylesheet" href="styles.css?da">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header-pc header_pro">
    <div class="logo">
    <a href="mes_offres.php">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </a>
    </div>
    <nav class="nav">
        <ul>
            <li><a href="mes_offres.php" class="active">Accueil</a></li>
            <li><a href="creation_offre.php">Publier</a></li>
            <li><a href="consulter_compte_pro.php">Mon Compte</a></li>
        </ul>
    </nav>
</header>
<body>
    <main>
        <h1 id="titre_modif_offre">Modifiez votre offre</h1>
        <form id="modif_offre" action="#" method="POST">
            <!-- Infos. Générales-->
            <section class="tabs">
                <ul>
                    <li><a href="#" class="active" data-tab="general">Informations générales</a></li>
                    <li><a href="#" data-tab="services">Services et horaires</a></li>
                    <li><a href="#" data-tab="photos">Photos</a></li>
                </ul>
            </section>
            <div class="tab-content active" id="general">
                <fieldset>
                    <legend>Titre</legend>
                    
                    <h1 id="titre" contenteditable="true" data-sync="titre_modif"><?php echo $offre['titre_offre']; ?></h1>
                    <input type="hidden" id="titre_modif" name="_titre_modif">
                </fieldset>
                
                <fieldset>
                    <legend>Infos Générales</legend>
                    
                    <label for="resume">Résumé (*)</label>
                    <span contenteditable="true" id="resume" data-sync="resume_modif"><?php echo $offre['_resume']; ?></span>
                    <label for="description">Description (*)</label>
                    <span contenteditable="true" id="description" data-sync="desc_modif"><?php echo $offre['_description'] ?></span>
                    <label for="accessibilite">Accessibilité</label>
                    <span contenteditable="true" placeholder="Accessibilité" id="accessibilite" data-sync="access_modif"><?php echo $offre['accessibilite'] ?? ""; ?></span>

                    <input type="hidden" id="resume_modif" name="_resume_modif">
                    <input type="hidden" id="desc_modif" name="_desc_modif">
                    <input type="hidden" id="access_modif" name="_access_modif">
                </fieldset>
                
                <fieldset>
                    <legend>Adresse</legend>
                    
                    <label for="code_postal">Code Postal</label>
                    <input id="code_postal" type="text" data-sync="code_postal_modif" value="<?php echo $offre['code_postal']; ?>" maxlength="5" required>
                    <label for="ville">Ville</label>
                    <input id="ville" type="text" data-sync="ville_modif" value="<?php echo $offre['ville']; ?>" maxlength="30" required>
                    <label for="adresse">Adresse Postale</label>
                    <input type="text" id="adresse" data-sync="adresse_modif" value="<?php echo $offre['adresse_postal'] ?>" maxlength="64" required>
                    <label for="complement_adresse">Complément d'Adresse</label>
                    <input type="text" id="complement_adresse" data-sync="comp_adresse_modif" placeholder="Complément d'adresse" value="<?php echo $offre['complement_adresse']; ?>" maxlength="64">

                    <input type="hidden" id="code_postal_modif" name="_code_postal_modif">
                    <input type="hidden" id="ville_modif" name="_ville_modif">
                    <input type="hidden" id="adresse_modif" name="_adresse_modif">
                    <input type="hidden" id="comp_adresse_modif" name="_comp_adresse_modif">
                </fieldset>
                
                <fieldset>
                    <legend>Tags</legend>

                    <table>
                        <thead>
                            <tr>
                                <th>Tags</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Extraire seulement les valeurs de 'code_tag' de $mes_tags
                                $mes_tags_values = array_column($mes_tags, 'code_tag');
                            
                                foreach($tags_offre as $tag){
                                    // On vérifie si le code_tag est déjà détenu par l'offre
                                    $isChecked = in_array($tag['code_tag'], $mes_tags_values);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="tags[]" value="<?php echo $tag['code_tag']; ?>"<?php echo $isChecked ? ' checked' : ''; ?>>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($tag['nom_tag']) ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="tab-content" id="services">
                <fieldset class="interieur_modif_offre_services">
                    <legend> Services </legend>
                    <?php

                    switch($type_offre){
                        case "restauration":
                            ?>
                            <div class="price-options">
                                <label for="prix">Prix</label>
                                <div class="radio-group">
                                    <div>
                                        <input type="radio" id="moins_25" name="prix" value="€" required>
                                        <label class="label-check" for="moins_25">€ (menu à moins de 25€)</label>
                                    </div>
                                    <div>
                                        <input class="label-check" type="radio" id="entre_25_40" name="prix" value="€€" required>
                                        <label class="label-check" for="entre_25_40">€€ (entre 25€ et 40€)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="plus_40" name="prix" value="€€€" required>
                                        <label class="label-check" for="plus_40">€€€ (au-delà de 40€)</label>
                                    </div>
                                </div>
                            </div>
    
                   <!-- Tarif -->
        
                            <div class="tarif-option">
                              <label for="tarif">
                                 Tarif
                              </label>
                              <input type="number" id="tarif" name="_tarif" placeholder="00.00€" min="0" step="0.01" required>
                           </div>
        
                        <!-- Meal Options -->
                            <div class="meal-options">
                                <label>Options de repas</label>
                                <div class="checkbox-group">
                                    <div>
                                        <input type="checkbox" id="petit_dejeuner" name="repas[]" value="Petit déjeuner">
                                        <label class="label-check" for="petit_dejeuner">Petit-Déjeuner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="brunch" name="repas[]" value="Brunch">
                                        <label class="label-check" for="brunch">Brunch</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="dejeuner" name="repas[]" value="Déjeuner">
                                        <label class="label-check" for="dejeuner">Déjeuner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="diner" name="repas[]" value="Dîner">
                                        <label class="label-check" for="diner">Dîner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="boissons" name="repas[]" value="Boissons">
                                        <label class="label-check" for="boissons">Boissons</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                        case "spectacle" :
                            ?>
                                <fieldset class="interieur_modif_offre_spectacle">
                                    <label for="date">Date du spectacle (*)</label>
                                    <input type="date" id="date" data-sync="date_modif" value="<?php echo htmlspecialchars($infos_offre['date_spectacle']) ?>" required>

                                    <label for="duree">Durée (*)</label>
                                    <input type="time" class="duree" id="duree" data-sync="duree_modif" value="<?php echo htmlspecialchars($infos_offre['duree']) ?>" required>

                                    <label for="heure_spectacle">Heure du spectacle (*)</label>
                                    <input type="time" class="duree" id="heure_spectacle" data-sync="heure_spect_modif" value="<?php echo htmlspecialchars($infos_offre['heure_spectacle']) ?>" required>
                                </fieldset>
                            <?php
                            break;
                        case "visite" :
                        ?>
                            <fieldset class="interieur_modif_offre_visite>
                                <label for="date">Date de la visite (*)</label>
                                <input type="date" id="date" data-sync="date_modif" value="<?php echo htmlspecialchars($infos_offre['date_visite']); ?>">

                                <label for="heure_visite">Heure de la visite (*)</label>
                                <input type="time" class="duree" id="duree" data-sync="heure_visite_modif" value="<?php echo htmlspecialchars($infos_offre['heure_visite']); ?>">

                                <label for="duree">Durée (*)</label>
                                <input type="time" class="duree" id="duree" data-sync="duree_modif" value="<?php echo htmlspecialchars($infos_offre['duree']); ?>">

                                <label for="langues">Langues</label>
                                <input type="text" id="langues" data-sync="langues_modif" value="<?php echo htmlspecialchars($infos_offre['visite_guidee']); ?>">
                                
                            </fieldset>
                        <?php
                            break;
                    }

                    ?>
                </fieldset>
              <fieldset>
                <legend>Horaires</legend>
                <?php
                    foreach($jours as $jour){
                ?>
                <div class="horaires-row">
                    <div class="col">
                        <input type="text" id="jour" name="horaires[<?php echo $jour; ?>]" placeholder="<?php echo ucfirst($jour); ?>" disabled>
                    </div>

                    <div class="col">
                        <fieldset class="interieur_modif_offre">
                            <legend>Ouverture</legend>
                            <input type="time" id="ouverture" name="horaires[<?php echo $jour; ?>][ouverture]" placeholder="Ouverture"
                            <?php if(isset($codes_horaires[$jour]['ouverture']) && $codes_horaires[$jour]['ouverture'] !== null){
                                ?>
                                value="<?php echo htmlspecialchars($codes_horaires[$jour]['ouverture']); ?>"
                                <?php
                            } ?>>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset class="interieur_modif_offre">
                            <legend>Fermeture</legend>
                            <input type="time" id="fermeture" name="horaires[<?php echo $jour; ?>][fermeture]" placeholder="Fermeture"
                            <?php if(isset($codes_horaires[$jour]['fermeture']) && $codes_horaires[$jour]['fermeture'] !== null){
                                ?>
                                value="<?php echo htmlspecialchars($codes_horaires[$jour]['fermeture']); ?>"
                                <?php
                            } ?>>
                        </fieldset>
                    </div>
                </div>
                <?php
                    }
                ?>
            </fieldset>



            </div>
            <div class="tab-content" id="photos">
                <div class="photo-cards">
                    <?php
                    foreach($recup_photos as $photo){
                    ?>
    
                    <div class="photo-card">
                        <div class="photo-image">
                            <img src="<?php echo $photo; ?>" alt="Photo">
                        </div>
                        <button class="delete-photo-btn">Supprimer</button>
                    </div>
                <?php
                }
                ?>
                    <!-- Carte pour ajouter une photo -->
                    <div id="photo-card" class="add-photo-card">
                        <div id="add-photo">
                            <span>+</span>
                            <p>Ajouter une photo</p>
                        </div>
                        <!-- Champ fichier caché -->
                    </div>
                    <input type="file" id="file-input" accept="image/*">
                </div>
            </div>
        </div>

            <div class="btn_modif_offre">
                <input type="submit" name="envoi_modif" value="Modifier">
            </div>
        </form>
    </main>
    <footer class="footer footer_pro">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
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
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
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
            </div>
        </div>
           
    </footer>
    <script defer>
        const form = document.getElementById('modif_offre');
        
        // Au lieu de gérer chaque champ individuellement, récupère tous les éléments avec `data-sync`
        form.addEventListener('submit', (event) => {
            const elementsToSync = document.querySelectorAll('[data-sync]');
            elementsToSync.forEach((element) => {
                // Récupère l'id du champ de destination
                const targetId = element.getAttribute('data-sync');
                const target = document.getElementById(targetId);
        
                if (target) {
                    // Utilise innerHTML pour les champs éditables ou value pour les inputs
                    target.value = element.contentEditable === "true" ? element.innerHTML : element.value;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.tabs a');
            const sections = document.querySelectorAll('.tab-content');
    
            tabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
    
                    // Supprime la classe active de tous les onglets
                    tabs.forEach(t => t.classList.remove('active'));
    
                    // Ajoute la classe active à l'onglet cliqué
                    tab.classList.add('active');
    
                    // Récupère l'ID de la section associée
                    const tabId = tab.getAttribute('data-tab');
    
                    // Masque toutes les sections
                    sections.forEach(section => section.classList.remove('active'));
    
                    // Affiche la section correspondante
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
    <script defer>
        document.addEventListener('DOMContentLoaded', () => {

            // IMAGES GÉNÉRALES

            // Sélectionner tous les boutons "Supprimer"
            const deleteButtons = document.querySelectorAll('.delete-photo-btn');
        
            // Ajouter un événement clic à chaque bouton
            deleteButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    // Empêcher l'envoi du formulaire
                    event.preventDefault();
        
                    // Récupérer l'élément parent (la carte de la photo)
                    const photoCard = button.closest('.photo-card');
        
                    // Cibler directement l'image à l'intérieur de .photo-image
                    const image = photoCard.querySelector('.photo-image img');
        
                    // Vérifier si l'image est déjà marquée comme supprimée
                    if (image.classList.contains('supprimee')) {
                        // Enlever la classe et restaurer le texte du bouton
                        image.classList.remove('supprimee');
                        button.textContent = 'Supprimer';
                        button.classList.remove('reverse'); // Enlever la classe "reverse" du bouton
                    } else {
                        // Ajouter la classe pour griser l'image et changer le texte du bouton
                        image.classList.add('supprimee');
                        button.textContent = 'Ajouter';
                        button.classList.add('reverse'); // Ajouter la classe "reverse" au bouton
                    }
                });
            });
        });
    </script>

</body>
</html>
