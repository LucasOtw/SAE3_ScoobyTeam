<?php
ob_start();
session_start();

require_once __DIR__ . ("/../.security/config.php");

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

// On récupère les infos bancaires si elles existent

$getInfosBancaires = $dbh->prepare("SELECT * FROM tripenarvor._compte_bancaire
WHERE code_compte_bancaire = :code_compte_bc");
$getInfosBancaires->bindValue(":code_compte_bc",$monComptePro['code_compte_bancaire']);

try{
    $getInfosBancaires->execute();

    $infosBancaires = $getInfosBancaires->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e){
    die("Erreur à l'exécution : ".$e->getMessage());
}

echo "<pre>";
var_dump($offre);
echo "</pre>";


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

switch($type_offre){
    case "restauration":
        // on sélectionner la gamme de prix

        $reqGamme = $dbh->prepare("SELECT * FROM tripenarvor._offre_restauration WHERE code_offre = :code");
        $reqGamme->bindValue(":code",$offre['code_offre']);
        $reqGamme->execute();

        $infosOffre = $reqGamme->fetch(PDO::FETCH_ASSOC);
        $repas = explode(",",$infosOffre['repas']);
        $repas = array_map('trim',$repas);
        
        break;
    default:
        break;
}


/* RÉCUPÉRATION DES TAGS */


$req_tags = $dbh->prepare("SELECT * FROM tripenarvor._tags WHERE $type_offre = true");
$req_tags->execute();
$tags_offre = $req_tags->fetchAll(PDO::FETCH_ASSOC);

/* RÉCUPÉRATION DES TAGS DE L'OFFRE */

$req_tags_offre = $dbh->prepare("SELECT code_tag FROM tripenarvor._son_tag WHERE code_offre = :code_offre");
$req_tags_offre->bindValue(":code_offre",$offre['code_offre']);
$req_tags_offre->execute();

$mes_tags = $req_tags_offre->fetchAll(PDO::FETCH_ASSOC);

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

    // VERIFICATION DE L'URL

    if(isset($_POST['_site_modif']) && $_POST['_site_modif'] !== null){
        $url = $_POST['_site_modif'];

        if(!empty($url)){
            if(filter_var($url,FILTER_VALIDATE_URL)){
                
                /* $options = [
                    "http" => [
                        "method" => "HEAD", // HEAD au lieu de GET
                        "follow_location" => 1 // Suivre les redirections
                    ]
                ];
                $context = stream_context_create($options);
                $headers = @get_headers($url, 1, $context);
                
                
                $headers = @get_headers($url);
              
                if ($headers && strpos($headers[0], '200') !== false) {
                    // Circulez, tout va bien..
                } else {
                    $erreurs[] = "L'URL n'est pas accessible.";
                } */
            } else {
                $erreurs[] = "L'URL n'est pas valide.";
            }
        }
    }

    $tab_services = null;

    switch($type_offre){
        case "restauration":
            $prix = $_POST['prix'];
            $tarif = $_POST['_tarif'];
            echo "<h1>$tarif</h1>";
            echo $prix;
            switch($prix){
                case "€":
                    if($tarif < 0 || $tarif >= 25){
                        $erreurs[] = "Prix : {$prix} : le tarif doit être compris entre 0 et 25€ (exclus) !";
                    }
                    break;
                case "€€":
                    if($tarif < 25 || $tarif >= 40){
                        $erreurs[] = "Prix : {$prix} : le tarif doit être compris entre 25 et 40€ (exclus) !";
                    }
                    break;
                case "€€€":
                    if($tarif < 40){
                        $erreurs[] = "Prix : {$prix} : le tarif doit être supérieur ou égal à 40€ !";
                    }
                    break;
            }

            if(empty($erreurs)){
                $repas = isset($_POST['repas']) || !empty($_POST['repas']) || $_POST['repas'] !== null ? $_POST['repas'] : null;

                if(empty($repas) || $repas == null){
                    $erreurs[] = "Erreur : Une case n'a pas été cochée dans 'Services et Horaires' pour une offre de restauration.";
                    break;
                } else {
                    $chaine_repas = implode(", ",$repas);
                    $tab_services = array(
                       "gamme_prix" => $prix,
                       "repas" => $chaine_repas
                   );
                }
            }
        
            break;
        case "spectacle":
            $tab_services = array(
                "duree" => $_POST['_duree_modif'],
                "capacite_accueil" => $_POST['_capacite_acc_modif'],
                "date_spectacle" => $_POST['_date_modif'],
                "heure_spectacle" => $_POST['_heure_spect_modif']
            );
            break;
        case "visite":
            $date_visite = $_POST['_date_modif'];
    
            if (!empty($date_visite)) {
                // Reformater la date si elle n'est pas au bon format
                $date_visite = date('Y-m-d', strtotime($date_visite));
            } else {
                $date_visite = null;
            }
        
            $tab_services = array(
                "duree" => $_POST['_duree_modif'],
                "visite_guidee" => $_POST['_langues_modif'],
                "date_visite" => $date_visite,
                "heure_visite" => $_POST['_heure_visite_modif']
            );
            break;
        case "parc_attractions":
            $tab_services = array(
                "nombre_attractions" => $_POST['_nombre_attractions'],
                "age_requis" => $_POST['_age_requis']
            );
            break;
        case "activite":
            $tab_services = array(
                "duree" => $_POST['duree_modif'],
                "age_requis" => $_POST['_age_requis'],
                "prestations_incluses" => $_POST['prestations_incluses'],
                "prestations_non_incluses" => $_POST['prestations_non_incluses']
            );
            break;
    }

    // Si il n'y a pas d'erreurs...
    
    if(empty($erreurs)){

        if(!empty($_POST['tags'])){
            $valeurs_tags = array_column($mes_tags, 'code_tag');
            
            // Première boucle : suppression des tags non sélectionnés
            foreach($valeurs_tags as $un_tag){
                if(!in_array($un_tag, $_POST['tags'])){
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
            "accessibilite" => $_POST['_access_modif'],
            "site_web" => $_POST['_site_modif']
        );

        // si on modifie le prix, on doit le mettre à jour..
        
        if (isset($_POST['_tarif']) && $_POST['_tarif'] != null && !empty($_POST['_tarif'])) {
            $tab_offre["tarif"] = $_POST['_tarif'];
        }        

        // table "_adresse"
        
        $tab_adresse = array(
            "code_postal" => $_POST['_code_postal_modif'],
            "ville" => $_POST['_ville_modif'],
            "adresse_postal" => $_POST['_adresse_modif'],
            "complement_adresse" => $_POST['_comp_adresse_modif']
        );

        // GESTION DES INFOS GENERALES
    
        foreach($tab_offre as $att => $val){
            $requete = "UPDATE tripenarvor._offre SET $att = :value WHERE code_offre = :code_offre";
            $stmt = $dbh->prepare($requete);
    
            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_offre",$offre['code_offre']);
    
            try {
                $stmt->execute();

                if ($att == "titre_offre") {
                    // Modifier le nom du dossier

                    $ancien_dossier = str_replace(' ','',$offre['titre_offre']);
                    $nouveau_dossier = str_replace(' ','',$val);

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
                
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour du champ $att: " . $e->getMessage() . "<br>";
            }
        }

        // MODIFICATION DE L'ADRESSE
            
        foreach($tab_adresse as $att => $val){
            $requete = "UPDATE tripenarvor._adresse SET $att = :value WHERE code_adresse = :code_adresse";
            $stmt = $dbh->prepare($requete);

            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_adresse",$offre['code_adresse']);

            try {
                $stmt->execute();
            } catch (PDOException $e){
                echo "Erreur lors de la mise à jour du champ $att : " . $e->getMessage() . "<br>";
            }
        }

        // GESTION DES SERVICES

        $table_bdd_services = "tripenarvor._offre_".$type_offre;

        if($tab_services != null){
            foreach($tab_services as $att => $val){
                if($val !== null){
                    $requete = "UPDATE $table_bdd_services SET $att = :value WHERE code_offre = :code_offre";
                    $stmt = $dbh->prepare($requete);
        
                    $stmt->bindValue(":value",$val);
                    $stmt->bindValue(":code_offre",$offre['code_offre']);
                    $stmt->execute();
                }
            }
        }
        
        // GESTION DES NOUVELLES PHOTOS

        if(isset($_FILES['offre_nouv_images']) && !empty($_FILES['offre_nouv_images']['name'][0])){
            $photos = [];
            // Parcourir chaque photo envoyée
            foreach($_FILES['offre_nouv_images']['name'] as $key => $file_name){
                // Si le fichier n'est pas vide, on récupère les informations nécessaires
                if($_FILES['offre_nouv_images']['error'][$key] === 0){
                    $photos[] = [
                        'name' => $file_name,
                        'type' => $_FILES['offre_nouv_images']['type'][$key],
                        'tmp_name' => $_FILES['offre_nouv_images']['tmp_name'][$key],
                        'size' => $_FILES['offre_nouv_images']['size'][$key]
                    ];
                }
            }

            /* Le chemin est temporaire, on doit donc enregistrer directement les photos
            dans leur propre dossier */

            $n_doss = str_replace(' ','',$tab_offre['titre_offre']);
            $destination = "images/offres/{$n_doss}";

            if(!file_exists($destination)){
                mkdir($destination);
            }

            foreach($photos as $photo){
                $nom_temp = $photo['tmp_name'];
                $nom_photo = $photo['name'];

                // on construit le chemin de destination complet
                $chemin = $destination . '/' . $nom_photo;

                if(file_exists($nom_temp)){
                    // on déplace le fichier dans le dossier cible
                    if(move_uploaded_file($nom_temp,$chemin)){

                        // on vérifie son existence dans la bdd (le chemin devant être unique)

                        $checkUnique = $dbh->prepare("SELECT COUNT(*) FROM tripenarvor._image
                        WHERE url_image = :_url");
                        $checkUnique->bindValue(":_url",$chemin);
                        $checkUnique->execute();
                        
                        $isUnique = $checkUnique->fetchColumn();
                        
                        if($isUnique == 0){
                            // on met directement à jour la bdd
                            $ajout_photo = $dbh->prepare("INSERT INTO tripenarvor._image (url_image) VALUES(:url_image)");
                            $ajout_photo->bindValue(":url_image",$chemin);
                            $ajout_photo->execute();
                            $code_image = $dbh->lastInsertId();

                            $ajout_photo_offre = $dbh->prepare("INSERT INTO tripenarvor._son_image VALUES(:code_image,:code_offre)");
                            $ajout_photo_offre->bindValue(":code_image",$code_image);
                            $ajout_photo_offre->bindValue(":code_offre",$offre['code_offre']);
                            $ajout_photo_offre->execute();
                        }
                    } else {
                        echo "Erreur : Impossible de déplacer le fichier $nom_photo.<br>";
                    }
                } else {
                    echo "Erreur : Le fichier temporaire $nom_temp n'existe pas.<br>";
                }
            }
        }

        // GESTION DE LA SUPPRESSION DES PHOTOS

        if (isset($_POST['deleted_images']) && !empty($_POST['deleted_images'])) {
            // Récupérer les images envoyées dans le champ caché
            $deletedImages = json_decode($_POST['deleted_images'], true);
            
            // Tableau pour stocker les URLs modifiées
            $cleanedImages = [];
            
            // Parcourir les URLs et les modifier
            foreach ($deletedImages as $photoSrc) {
                // Utiliser une expression régulière pour obtenir tout ce qui suit "images"
                if (preg_match('/images\/.*/', $photoSrc, $matches)) {
                    // Ajouter la partie "images" et après à notre tableau
                    $cleanedImages[] = $matches[0];
                }
            }

            $codes_images = null;

            // PREMIERE BOUCLE
            // Sert à récupérer le code de chaque image
            
            foreach($cleanedImages as $image){
                $recup_codes_images = $dbh->prepare("SELECT code_image FROM tripenarvor._image WHERE url_image = :image");
                $recup_codes_images->bindValue(":image",trim($image));
                $recup_codes_images->execute();

                $codes_images = $recup_codes_images->fetchAll(PDO::FETCH_ASSOC);
                $codes_images = array_column($codes_images,'code_image');
            }

            // DEUXIEME BOUCLE
            // Sert à supprimer l'image dans _son_image
            foreach($codes_images as $code_image){
                $del_son_image = $dbh->prepare("DELETE FROM tripenarvor._son_image WHERE code_image = :code_image");
                $del_son_image->bindValue(":code_image",$code_image);
                $del_son_image->execute();
            }

            // TROISIEME BOUCLE
            // Sert à supprimer l'image dans _image et dans le dossier
            foreach($cleanedImages as $image){
                // le dossier existe forcément, l'image aussi.

                unlink($image);
                
                $del_image = $dbh->prepare("DELETE FROM tripenarvor._image WHERE url_image = :url");
                $del_image->bindValue(":url",$image);
                $del_image->execute();
            }
        }

        /* GESTION DE LA MISE A JOUR DE L'OFFRE ET DU PAIEMENT */

        if(isset($_POST['type_offre']) && !empty($_POST['type_offre']) && $_POST['type_offre']){
            $type_offre = trim($_POST['type_offre']);
        }

        if(isset($_POST['option_offre']) && !empty($_POST['option_offre']) && $_POST['option_offre']){
            $option_offre = trim($_POST['option_offre']);
        }

        $tabModifs = array();

        // Si le type de l'offre est différent du type précédent, on le met à jour

        if($type_offre !== trim($offre['nom_type'])){
            $tabModifs['nom_type'] = $type_offre;
        }

        // On vérifie maintenant si l'offre avait déjà une option.

        $checkIfOption = $dbh->prepare("SELECT * FROM tripenarvor._option
        WHERE code_offre = :code_offre");
        $checkIfOption->bindValue(":code_offre",$offre['code_offre']);
        
        try{
            $checkIfOption->execute();
            $isOption = $checkIfOption->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            die("Erreur à l'exécution (SELECT) : ". $e->getMessage());
        }

        if($isOption !== null){
            /*
            * Si il y avait une option précédemment, on a maintenant 2 cas :
            * 1 - On change d'option et / ou de durée pour l'option (UPDATE)
            * 2 - On supprime l'option (DELETE)
            */
        }

        // à la fin, tout roule, on peut donc mettre à jour la date de la dernière modification :)

        $today = date("Y-m-d");

        $update_modif_pub = $dbh->prepare("UPDATE tripenarvor._offre SET date_derniere_modif = :date WHERE code_offre = :code_offre");
        $update_modif_pub->bindValue(":date",$today);
        $update_modif_pub->bindValue(":code_offre",$offre['code_offre']);
        $update_modif_pub->execute();

        // header("location: detail_offre_pro.php");
        exit;
        
    } else {
        foreach($erreurs as $err){
            echo $err."<br>";
        }
    }
}

if($infos_offre !== null){
    $infos_offre = $infos_offre[0];
}
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Modifier mon offre</title>
    <link rel="stylesheet" href="styles.css?daz">
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
    <main>
        <h1 id="titre_modif_offre">Modifiez votre offre</h1>
        <form id="modif_offre" action="#" method="POST" enctype="multipart/form-data">
            <!-- Infos. Générales-->
            <section class="tabs">
                <ul>
                    <li><a href="#" class="active" data-tab="general">Informations générales</a></li>
                    <li><a href="#" data-tab="services">Services et horaires</a></li>
                    <li><a href="#" data-tab="photos">Photos</a></li>
                    <li><a href="#" data-tab="payment">Paiement</a></li>
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

                    <?php
                        if($type_offre !== "restauration"){
                            ?>
                            <label for="tarif">Tarif (*)</label>
                            <input type="number" id="tarif" name="_tarif" value="<?php echo $offre['tarif'] ?>" placeholder="00.00€" min="0" step="0.01" required>
                            <?php
                        }
                    ?>

                    <label for="url_site">Site Web</label>
                    <input type="text" id="url_site" data-sync="site_modif" value="<?php echo $offre['site_web']; ?>">

                    <input type="hidden" id="resume_modif" name="_resume_modif">
                    <input type="hidden" id="desc_modif" name="_desc_modif">
                    <input type="hidden" id="access_modif" name="_access_modif">
                    <input type="hidden" id="site_modif" name="_site_modif">
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
                                <th></th>
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
                            $prix_defaut = $infosOffre['gamme_prix'];
                            ?>
                            <div class="price-options">
                                <label for="prix">Prix</label>
                                <div class="radio-group">
                                    <div>
                                        <input type="radio" id="moins_25" name="prix" value="€" required <?php echo ($prix_defaut == "€") ? 'checked' : ''; ?>>
                                        <label class="label-check" for="moins_25">€ (menu à moins de 25€)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="entre_25_40" name="prix" value="€€" required <?php echo ($prix_defaut == "€€") ? 'checked' : ''; ?>>
                                        <label class="label-check" for="entre_25_40">€€ (entre 25€ et 40€)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="plus_40" name="prix" value="€€€" required <?php echo ($prix_defaut == "€€€") ? 'checked' : ''; ?>>
                                        <label class="label-check" for="plus_40">€€€ (au-delà de 40€)</label>
                                    </div>
                                </div>
                            </div>
    
                   <!-- Tarif -->
        
                            <div class="tarif-option">
                              <label for="tarif">
                                 Tarif
                              </label>
                              <input type="number" id="tarif" name="_tarif" value="<?php echo $offre['tarif']; ?>" placeholder="00.00€" min="0" step="0.01" required>
                           </div>
        
                        <!-- Meal Options -->
                            <div class="meal-options">
                                <label>Options de repas</label>
                                <div class="checkbox-group">
                                    <div>
                                        <input type="checkbox" id="petit_dejeuner" name="repas[]" value="Petit déjeuner" 
                                            <?php echo in_array("Petit déjeuner", $repas) ? 'checked' : ''; ?>>
                                        <label class="label-check" for="petit_dejeuner">Petit-Déjeuner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="brunch" name="repas[]" value="Brunch" 
                                            <?php echo in_array("Brunch", $repas) ? 'checked' : ''; ?>>
                                        <label class="label-check" for="brunch">Brunch</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="dejeuner" name="repas[]" value="Déjeuner" 
                                            <?php echo in_array("Déjeuner", $repas) ? 'checked' : ''; ?>>
                                        <label class="label-check" for="dejeuner">Déjeuner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="diner" name="repas[]" value="Dîner" 
                                            <?php echo in_array("Dîner", $repas) ? 'checked' : ''; ?>>
                                        <label class="label-check" for="diner">Dîner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="boissons" name="repas[]" value="Boissons" 
                                            <?php echo in_array("Boissons", $repas) ? 'checked' : ''; ?>>
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

                                    <label for="capacite_accueil">Capacité d'accueil (*)</label>
                                    <input type="number" id="capacite_accueil" data-sync="capacite_accueil_modif" value="<?php echo htmlspecialchars($infos_offre['capacite_accueil']) ?>" placeholder="Capacité d'accueil *" required>

                                    <input type="hidden" id="date_modif" name="_date_modif">
                                    <input type="hidden" id="duree_modif" name="_duree_modif">
                                    <input type="hidden" id="heure_spect_modif" name="_heure_spect_modif">
                                    <input type="hidden" id="capacite_accueil_modif" name="_capacite_acc_modif">
                                </fieldset>
                            <?php
                            break;
                        case "visite" :
                        ?>
                            <fieldset class="interieur_modif_offre_visite">
                                <label for="date">Date de la visite (*)</label>
                                <input type="date" id="date" data-sync="date_modif" value="<?php echo htmlspecialchars($infos_offre['date_visite']); ?>">

                                <label for="heure_visite">Heure de la visite (*)</label>
                                <input type="time" class="duree" id="duree" data-sync="heure_visite_modif" value="<?php echo htmlspecialchars($infos_offre['heure_visite']); ?>">

                                <label for="duree">Durée (*)</label>
                                <input type="time" class="duree" id="duree" data-sync="duree_modif" value="<?php echo htmlspecialchars($infos_offre['duree']); ?>">

                                <label for="langues">Langues</label>
                                <input type="text" id="langues" data-sync="langues_modif" value="<?php echo (isset($infos_offre['visite_guidee']) ? htmlspecialchars($infos_offre['visite_guidee']) : ""); ?>">

                                <input type="hidden" id="date_modif" name="_date_modif">
                                <input type="hidden" id="heure_visite_modif" name="_heure_visite_modif">
                                <input type="hidden" id="duree_modif" name="_duree_modif">
                                <input type="hidden" id="langues_modif" name="_langues_modif">
                                
                            </fieldset>
                        <?php
                            break;
                        case "parc_attractions":
                            ?>
                            <fieldset class="interieur_modif_offre_visite">
                                <legend>Âge requis (*)</legend>
                                <input type="number" id="age_requis" data-sync="age_requis" name="_age_requis" value="<?php echo htmlspecialchars($infos_offre['age_requis']); ?>"
                                min="0" max="18" oninput="validity.valid||(value='');">
                            </fieldset>
                            <fieldset class="interieur_modif_offre_visite">
                                <legend>Nombre d'attractions (*)</legend>
                                <input type="number" id="nombre_attractions" data-sync="nombre_attractions" name="_nombre_attractions" value="<?php echo htmlspecialchars($infos_offre['nombre_attractions']); ?>"
                                min="0" max="200" oninput="validity.valid||(value='');">
                            </fieldset>
                            <?php
                            break;
                        case "activite":
                            ?>
                            <fieldset class="interieur_modif_offre_spectacle">
                                <label for="duree">Durée (*)</label>
                                <input type="time" class="duree" id="duree" name="duree_modif" value="<?php echo htmlspecialchars($infos_offre['duree']); ?>">
                                <label for="age_requis">Âge requis (*)</label>
                                <input type="number" id="age_requis" data-sync="age_requis" name="_age_requis" value="<?php echo htmlspecialchars($infos_offre['age_requis']); ?>"
                                min="0" max="18" oninput="validity.valid||(value='');" required>
                            </fieldset>
                            <fieldset class="interieur_modif_offre_visite">
                                <label for="prestations_incluses">
                                    Prestations incluses (*)
                                </label>
                                <input type="text" id="prestations_incluses" name="prestations_incluses" data-sync="prestations_incluses"
                                value="<?php echo htmlspecialchars($infos_offre['prestations_incluses']); ?>" required minlength="1">

                                <label for="prestations_non_incluses">Prestations non incluses</label>
                                <input type="text" id="prestations_non_incluses" name="prestations_non_incluses" data-sync="prestations_non_incluses"
                                value="<?php echo ($infos_offre['prestations_non_incluses'] != null ? $infos_offre['prestations_non_incluses'] : ""); ?>">
                            </fieldset>
                            
                            <?php
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
            <h3>Ajouter de nouvelles photos</h3>
    <div class="drop-zone" id="drop-zone">
        <div class="drop-zone-icon">+</div>
        <div class="drop-zone-prompt">Glissez et déposez vos images ici</div>
        <div>ou</div>
        <label class="button_import_image" for="file-input">Choisir des fichiers</label>
        <input type="file" id="file-input" name="offre_nouv_images[]" accept="image/*" multiple class="file-input">
    </div>
    <div class="selected-files-container" id="selected-files">
        <!-- Les fichiers sélectionnés s'afficheront ici -->
    </div>
    <input type="hidden" name="deleted_images" id="deleted-images">

    <h3> vos photos actuelles</h3>
    <div class="photo-cards">
        <?php
        foreach($recup_photos as $photo){
        ?>
        <div class="photo-card">
            <div class="photo-image">
                <img src="<?php echo $photo; ?>" alt="Photo">
            </div>
            <button class="delete-photo-btn"></button>
        </div>
        <?php
        }
        ?>
    </div>
    </div>
    
   
        </div>
        <div class="tab-content" id="payment">
            <fieldset>
                <legend>Type</legend>
                <?php
                    if(isset($monComptePro['num_siren']) && $monComptePro['num_siren']){
                        ?>
                        <input type="radio" id="off_std" name="type_offre" value="Offre Standard"
                        <?php echo ($offre['nom_type'] == "Offre Standard") ? "checked" : "" ?>>
                        <label for="off_std">Offre Standard</label>
                        <input type="radio" id="off_pre" name="type_offre" value="Offre Premium"
                        <?php echo ($offre['nom_type'] == "Offre Premium") ? "checked" : "" ?>>
                        <label for="off_pre">Offre Premium</label>
                        <?php
                    } else {
                        ?>
                        <input type="radio" id="off_grt" name="type_offre" value="Offre Gratuite"
                        <?php echo ($offre['nom_type'] == "Offre Gratuite") ? "checked" : "" ?>>
                        <label for="off_grt">Offre Gratuite</label>
                        <?php
                    }
                ?>
            </fieldset>
            <fieldset>
                <legend>Option</legend>
                <input type="radio" id="no-opt" name="option_offre" value=""
                <?php echo ($offre['option_en_relief'] || $offre['option_a_la_une']) ? "checked" : "" ?>>
                <label for="no-opt">Aucune option</label>
                <input type="radio" id="opt_relief" name="option_offre" value="relief"
                <?php echo ($offre['option_en_relief']) ? "checked" : ""  ?>>
                <label for="opt_relief">Option "en Relief"</label>
                <input type="radio" id="opt_aLaUne" name="option_offre" value="aLaUne"
                <?php echo ($offre['option_a_la_une']) ? "checked" : "" ?>>
                <label for="opt_aLaUne">Option "à la Une"</label>
                <?php
                    if($offre['option_en_relief'] || $offre['option_a_la_une']){
                        // on récupère le nb de semaines

                        $getNbSemaines = $dbh->prepare("SELECT nb_semaines FROM tripenarvor._option
                        WHERE code_option = :code_option");

                        $nbSemaines = null;

                        if($offre['option_en_relief']){
                            $getNbSemaines->bindValue(":code_option",$offre['option_en_relief']);
                        } else if ($offre['option_a_la_une']){
                            $getNbSemaines->bindValue(":code_option",$offre['option_a_la_une']);
                        }
                        try{
                            $getNbSemaines->execute();

                            $nbSemaines = $getNbSemaines->fetchColumn();
                        } catch (PDOException $e){
                            die("Erreur d'exécution : ". $e->getMessage());
                        }

                        ?>
                        <?php
                    }
                ?>
                <fieldset id="champ-semaines" style="display: none;">
                    <legend>Durée</legend>
                    <input type="radio" id="sem1" name="nbSemaine" value="1"
                    <?php echo (isset($nbSemaines) && $nbSemaines && $nbSemaines == 1) ? "checked" : "" ?>>
                    <label for="sem1">1 semaine</label>
                    <input type="radio" id="sem2" name="nbSemaine" value="2"
                    <?php echo (isset($nbSemaines) && $nbSemaines && $nbSemaines == 2) ? "checked" : "" ?>>
                    <label for="sem2">2 semaines</label>
                    <input type="radio" id="sem3" name="nbSemaine" value="3"
                    <?php echo (isset($nbSemaines) && $nbSemaines && $nbSemaines == 3) ? "checked" : "" ?>>
                    <label for="sem3">3 semaines</label>
                    <input type="radio" id="sem4" name="nbSemaine" value="4"
                    <?php echo (isset($nbSemaines) && $nbSemaines && $nbSemaines == 4) ? "checked" : "" ?>>
                    <label for="sem4">4 semaines</label>
                </fieldset>
            </fieldset>
            <?php
            $affichePaiement = "none";
                if($infosBancaires){
                    $affichePaiement = "block";
                ?>
                <?php
            } ?>
            <fieldset id="champ-paiement" style="display: <?php echo $affichePaiement; ?>">
                <legend>Informations de paiement</legend>
                <label for="iban">IBAN*</label>
                <input id="iban" type="text" name="_IBAN" value="<?php echo (isset($infosBancaires) && $infosBancaires) ? trim($infosBancaires['iban']) : ""; ?>" placeholder="IBAN">

                <label for="bic">BIC*</label>
                <input id="bic" type="text" name="_BIC" value="<?php echo (isset($infosBancaires) && $infosBancaires) ? trim($infosBancaires['bic']) : ""; ?>" placeholder="BIC">

                <label for="nom_compte">Nom du compte</label>
                <input id="nom_compte" type="text" name="_nomCompte" value="<?php echo (isset($infosBancaires) && $infosBancaires) ? trim($infosBancaires['nom_compte']) : ""; ?>" placeholder="Nom du compte bancaire">

            </fieldset>
        </div>

            <div class="btn_modif_offre">
                <input type="submit" name="envoi_modif" value="Valider">
            </div>
        </form>
    </main>
    <footer class="footer footer_pro">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.php">Mentions Légales</a></li>
                    <li><a href="cgu.php">GGU</a></li>
                    <li><a href="cgv.php">CGV</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["pro"]) && !empty($_SESSION["pro"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_pro.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_pro.php">Se connecter</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Nous Connaitre</a></li>
                    <li><a href="obtenir_aide.php">Obtenir de l'aide</a></li>
                    <li><a href="contacter_plateforme.php">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <!--<li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>-->
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
            const deletedImagesField = document.getElementById('deleted-images');

            let deletedImages = [];
        
            // Ajouter un événement clic à chaque bouton
            deleteButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    // Empêcher l'envoi du formulaire
                    event.preventDefault();
        
                    // Récupérer l'élément parent (la carte de la photo)
                    const photoCard = button.closest('.photo-card');
        
                    // Cibler directement l'image à l'intérieur de .photo-image
                    const image = photoCard.querySelector('.photo-image img');
                    const photoSrc = image.src;

                    console.log(photoSrc);
        
                    // Vérifier si l'image est déjà marquée comme supprimée
                    if (image.classList.contains('supprimee')) {
                        // Enlever la classe et restaurer le texte du bouton
                        image.classList.remove('supprimee');
                        button.classList.remove('reverse'); // Enlever la classe "reverse" du bouton

                        console.log('e');
                        const indexPhoto = deletedImages.indexOf(photoSrc);

                        if(indexPhoto !== -1){
                            deletedImages.splice(indexPhoto,1);
                        }
                        
                    } else {
                        // Ajouter la classe pour griser l'image et changer le texte du bouton
                        image.classList.add('supprimee');
                        button.classList.add('reverse'); // Ajouter la classe "reverse" au bouton

                        deletedImages.push(photoSrc);
                    }
                    deletedImagesField.value = JSON.stringify(deletedImages);
                });
            });
        });
    </script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const selectedFilesContainer = document.getElementById('selected-files');

    // Empêcher le comportement par défaut du navigateur
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Ajouter des styles visuels pendant le glisser-déposer
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropZone.classList.add('active');
    }

    function unhighlight() {
        dropZone.classList.remove('active');
    }

    // Gérer le glisser-déposer des fichiers
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    // Gérer la sélection de fichiers via le bouton
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        // Mettre à jour l'interface utilisateur
        updateFileList(files);
        
        // Si vous avez besoin de prévisualiser les images
        [...files].forEach(previewFile);
    }

    function updateFileList(files) {
        selectedFilesContainer.innerHTML = '';
        [...files].forEach(file => {
            const fileElement = document.createElement('div');
            fileElement.className = 'selected-file';
            fileElement.innerHTML = `
                <span>${file.name}</span>
                <button type="button" class="remove-file">&times;</button>
            `;
            selectedFilesContainer.appendChild(fileElement);
        });

        // Ajouter des écouteurs d'événements pour supprimer les fichiers
        document.querySelectorAll('.remove-file').forEach((button, index) => {
            button.addEventListener('click', () => {
                removeFile(index);
            });
        });
    }

    function removeFile(index) {
        // Créer un nouveau FileList sans le fichier à supprimer
        // Note: FileList est un objet en lecture seule, nous devons donc trouver une solution de contournement
        const dt = new DataTransfer();
        const files = fileInput.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        fileInput.files = dt.files;
        updateFileList(fileInput.files);
    }

    function previewFile(file) {
        // Si vous souhaitez ajouter une prévisualisation des images
        if (file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Vous pouvez ajouter du code ici pour afficher une prévisualisation de l'image
            };
            reader.readAsDataURL(file);
        }
    }


});
</script>
<script>
    document.addEventListener('DOMContentLoaded',function(){
        var isPaiement = <?php echo json_encode($infosBancaires); ?>;
        const champOffGrat = document.getElementById('off_grt');

        const choix_noOpt = document.getElementById('no-opt');
        const choix_optRelief = document.getElementById('opt_relief');
        const choix_optAlaUne = document.getElementById('opt_aLaUne');
        var choixOpt = "";
        
        const champSemaines = document.getElementById('champ-semaines');
        console.log(champSemaines);
        const inputSemaines = champSemaines.querySelectorAll("input[type=radio]");
        const sem1 = inputSemaines[0];
        const champPaiement = document.getElementById('champ-paiement');

        const btnsPaiement = champPaiement.querySelectorAll('input[type=text]');

        console.log(btnsPaiement);

        if(champOffGrat == null){
            champPaiement.style.display = "block";
        }

        choix_noOpt.addEventListener('click', function(){
            choixOpt = "";
            champSemaines.style.display = "none";
            if(champOffGrat !== null){
                champPaiement.style.display = "none";
            }
            sem1.checked = false;
            btnsPaiement.forEach(input => {
                input.required = false;
            })
        });

        choix_optRelief.addEventListener('click',function(){
            if(choixOpt !== choix_optRelief.value){
                choixOpt = choix_optRelief.value;
                console.log(choixOpt);
                champSemaines.style.display = "block";
                sem1.checked = true;
                champPaiement.style.display = "block";
            }

            btnsPaiement.forEach(input => {
                input.required = true;
            });
        });

        choix_optAlaUne.addEventListener('click', function(){
            if(choixOpt !== choix_optAlaUne.value){
                choixOpt = choix_optAlaUne.value;
                console.log(choixOpt);
                champSemaines.style.display = "block";
                sem1.checked = true;
                champPaiement.style.display = "block";
            }

            btnsPaiement.forEach(input => {
                input.required = true;
                console.log(input);
            });
        });
    });
</script>
</body>
</html>