<?php
    ob_start(); // bufferisation, ça devrait marcher ?
    session_start();

include("recupInfosCompte.php");

if(!isset($_SESSION['membre'])){
   header('location: connexion_membre.php');
   exit;
}

if(isset($_GET['deco'])){
    if($_GET['deco'] == true){
        session_unset();
        session_destroy();
        header('location: voir_offres.php');
        exit;
    }
}

// Vérification si le bouton de génération est cliqué
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_api_key'])) {
    $prefix = 'M'; // Préfixe pour générer la clé
    $stmt = $dbh->prepare('update tripenarvor._membre set api_key = tripenarvor.generate_api_key(:prefix) where code_compte = :code_compte');
    $stmt->bindParam(':prefix', $prefix, PDO::PARAM_STR);
    $stmt->bindParam(':code_compte', $_SESSION['membre']['code_compte']);
    $stmt->execute();

    // Récupérer la nouvelle clé générée
}

if (isset($_POST['modif_infos'])){
    $erreur = [];
    // Récupérer les valeurs initiales (par exemple, depuis la base de données)
   $valeursInitiales = [
       'nom' => $monCompteMembre['nom'],
       'prenom' => $monCompteMembre['prenom'],
       'pseudo' => $monCompteMembre['pseudo'],
       'mail' => $compte['mail'],
       'telephone' => $compte['telephone'],
       'adresse_postal' => $_adresse['adresse_postal'],
       'code_postal' => $_adresse['code_postal'],
       'ville' => $_adresse['ville'],
   ];
   
   // Champs modifiés
   $champsModifies = [];
   
   // Parcourir les données soumises
   foreach ($_POST as $champ => $valeur) {
       if (isset($valeursInitiales[$champ]) && $valeursInitiales[$champ] !== $valeur) {
           $champsModifies[$champ] = $valeur;
       }
   }
   
   // Mettre à jour seulement les champs modifiés
   if (!empty($champsModifies)) {
         $champs_valides_membre = ['nom', 'prenom', 'pseudo'];
         $champs_valides_compte = ['mail', 'telephone'];
         $champs_valides_adresse = ['adresse_postal', 'code_postal', 'ville'];
         
         if (empty($compte['code_compte']) || empty($_adresse['code_adresse'])) {
             echo "Erreur : Informations de compte ou d'adresse manquantes.";
             return;
         }
         
         foreach ($champsModifies as $champ => $valeur) {
             $valeurNettoye = trim(preg_replace('/\s+/', '', $valeur)); // Supprime les espaces en trop
         
             if (in_array($champ, $champs_valides_membre)) {
                 // Mise à jour pour _membre
                 $query = $dbh->prepare("UPDATE tripenarvor._membre SET $champ = :valeur WHERE code_compte = :code_compte");
                 $query->execute(['valeur' => $valeurNettoye, 'code_compte' => $compte['code_compte']]);
                 if ($query->rowCount() > 0) {
                     $_SESSION['membre'][$champ] = $valeurNettoye;
                 }
             } elseif (in_array($champ, $champs_valides_compte)) {
                 // Mise à jour pour _compte
                 switch($champ){
                     case "telephone":
                         if(strlen($valeurNettoye) < 10 || strlen($valeurNettoye) > 10){
                             $erreur[] = "Le numéro de téléphone doit être composé de 10 numéros !";
                             header('location: consulter_compte_membre.php');
                             break;
                         }
                         break;
                     case "mail":
                         if(!filter_var($valeurNettoye, FILTER_VALIDATE_EMAIL)){
                             $erreur[] = "L'adresse mail est invalide !";
                             header('location: consulter_compte_membre.php');
                             break;
                         }
                         break;
                 }
                 if(empty($erreur)){
                    $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
                    $query->execute(['valeur' => $valeurNettoye, 'code_compte' => $compte['code_compte']]);

                    if ($query->rowCount() > 0) {
                        $_SESSION['membre'][$champ] = $valeurNettoye;
                    }
                 }
             } elseif (in_array($champ, $champs_valides_adresse)) {
                   if (empty($_adresse['code_adresse'])) {
                       echo "Erreur : code_adresse introuvable.";
                       return;
                   }
               
                   // Validation spécifique par champ
                   $valeurNettoye = trim($valeur);
                   if ($champ == 'code_postal')
                   {
                      $valeurNettoye = intval($valeur);
                   }
                   if ($champ === 'code_postal' && !preg_match('/^\d{5}$/', $valeur)) {
                       echo "Erreur : code_postal invalide.";
                       return;
                   }
                   if ($champ === 'adresse_postal' && empty($valeurNettoye)) {
                       echo "Erreur : adresse_postal est vide.";
                       return;
                   }

                   print_r($valeurNettoye);
               
                   // Mise à jour dans la base de données
                   $query = $dbh->prepare("UPDATE tripenarvor._adresse SET $champ = :valeur WHERE code_adresse = :code_adresse");
                   $query->execute([
                       'valeur' => $valeurNettoye,
                       'code_adresse' => $_adresse['code_adresse']
                   ]);

                   if ($query->rowCount() > 0) {
                      print_r("La donnée a été modifiée avec succès dans la base de données.");
                  } else {
                      print_r("Aucune modification effectuée. Les données sont peut-être déjà identiques.");
                  }
               
                   if ($query->rowCount() > 0) {
                       $_SESSION['membre'][$champ] = $valeurNettoye;
                   } else {
                       echo "Aucune mise à jour effectuée pour $champ.";
                   }
             } else {
                 echo "Champ non valide : $champ";
             }
         }
         
         // Récupérer les informations mises à jour
         include("recupInfosCompte.php");

   } else {
       echo "Aucune modification détectée.";
   }
    header('location: consulter_compte_membre.php');
    exit;
}

// GESTION DE LA PHOTO

if (isset($_POST['changePhoto'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile-photo'])) {
        $file = $_FILES['profile-photo'];

        // Vérifier qu'il n'y a pas d'erreurs d'upload
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Vérification du type MIME
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                echo "Type de fichier non autorisé.";
                exit;
            }

            // Dossier où enregistrer l'image
            $uploadDir = 'images/profile/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Créer le dossier s'il n'existe pas
            }

            function getUniqueFileName($directory, $fileName) {
                // Récupérer le nom sans l'extension
                $name = pathinfo($fileName, PATHINFO_FILENAME);
                // Récupérer l'extension
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            
                // Construire le chemin complet du fichier
                $newFileName = $fileName; // Par défaut, c'est le nom original
                $counter = 1;
            
                // Vérifier si le fichier existe dans le dossier
                while (file_exists($directory . '/' . $newFileName)) {
                    // Ajouter un suffixe au nom
                    $newFileName = $name . '_' . $counter . '.' . $extension;
                    $counter++;
                }
            
                return $newFileName;
            }

            // Générer un nom unique pour le fichier
            $fileName = pathinfo($file['name'], PATHINFO_FILENAME) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $fichierImage = getUniqueFileName($uploadDir,$fileName);
            $filePath = $uploadDir . $fileName;

            // Déplacer le fichier temporaire vers le dossier cible
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Générer l'URL de l'image
                $photoUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $filePath;
            
                // Vérifier si l'utilisateur existe déjà
                $query = $dbh->prepare('SELECT url_image FROM tripenarvor._sa_pp WHERE code_compte = :user_id');
                $query->bindValue(':user_id', $compte['code_compte'], PDO::PARAM_INT);
                $query->execute();
                $existingPhoto = $query->fetch(PDO::FETCH_ASSOC);
            
                if ($existingPhoto) {
                    // Récupérer l'ancienne URL et supprimer le fichier associé
                    $oldPhotoPath = parse_url($existingPhoto['url_image'], PHP_URL_PATH);
                    $oldPhotoPath = $_SERVER['DOCUMENT_ROOT'] . $oldPhotoPath; // Convertir l'URL en chemin absolu
            
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath); // Supprimer l'ancien fichier
                    }
            
                    // Mise à jour de l'URL de la photo de profil
                    $updateQuery = $dbh->prepare('UPDATE tripenarvor._sa_pp SET url_image = :profile_photo_url WHERE code_compte = :user_id');
                    $updateQuery->bindValue(':profile_photo_url', $photoUrl, PDO::PARAM_STR);
                    $updateQuery->bindValue(':user_id', $compte['code_compte'], PDO::PARAM_INT);
                    $updateQuery->execute();
            
                } else {
                    // Insérer une nouvelle entrée
                    $insertQuery = $dbh->prepare('INSERT INTO tripenarvor._sa_pp (url_image, code_compte) VALUES (:profile_photo_url, :user_id)');
                    $insertQuery->bindValue(':user_id', $compte['code_compte'], PDO::PARAM_INT);
                    $insertQuery->bindValue(':profile_photo_url', $photoUrl, PDO::PARAM_STR);
                    $insertQuery->execute();
                }
            } else {
                echo "Erreur lors du déplacement du fichier.";
            }
        } else {
            echo "Erreur lors de l'upload du fichier.";
        }
    }
    header('location: consulter_compte_membre.php');
    exit;
}

$url_photo = parse_url($compte_pp);
$url_JSON = json_encode($url_photo);
$path_photo = $url_photo['path'];

// TELECHARGEMENT DES DONNEES (FORMAT JSON)
if (isset($_POST['dwl-data'])) {
    $url_photo = parse_url($compte_pp);
    $path_photo = $url_photo['path'];

    // Récupération des avis
    $avis = $dbh->prepare("SELECT o.titre_offre, a.txt_avis, a.note FROM tripenarvor._avis a 
                           JOIN tripenarvor._offre o ON a.code_offre = o.code_offre
                           WHERE a.code_compte = :code_compte");
    $avis->bindValue(":code_compte", $compte['code_compte']);
    $avis->execute();
    $result = $avis->fetchAll(PDO::FETCH_ASSOC);

    $tab_avis = [];
    foreach ($result as $res) {
        if (!array_key_exists($res['titre_offre'], $tab_avis)) {
            $tab_avis[$res['titre_offre']] = [];
        }
        $content = html_entity_decode($res['txt_avis'], ENT_QUOTES, 'UTF-8');
        $tab_avis[$res['titre_offre']][] = [
            'content' => $content,
            'note' => $res['note']
        ];
    }

    // Démarrer la génération de l'archive ZIP
    $zip = new ZipArchive();
    $zipFile = tempnam(sys_get_temp_dir(), 'zip'); 

    if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
        // Création du JSON
        $data = [
            'Nom' => $monCompteMembre['nom'],
            'Prenom' => $monCompteMembre['prenom'],
            'Pseudo' => $monCompteMembre['pseudo'],
            'Email' => $compte['mail'],
            'Téléphone' => $compte['telephone'],
            'Liste_Avis' => $tab_avis
        ];
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Ajouter le fichier JSON dans l'archive
        $zip->addFromString("donnees.json", $jsonData);

        // Ajouter l'image (si elle existe)
        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($path_photo, '/');

        if (file_exists($absolutePath)) {
            $zip->addFile($absolutePath, "profil.jpg");
        } else {
            error_log("⚠️ L'image n'existe pas : " . $absolutePath);
        }

        // Fermer l'archive ZIP
        $zip->close();

        // Définir les headers pour forcer le téléchargement
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="mon_archive.zip"');
        header('Content-Length: ' . filesize($zipFile));

        ob_end_clean();
        flush();

        // Lire et envoyer le fichier ZIP au client
        readfile($zipFile);

        // Supprimer le fichier temporaire après téléchargement
        unlink($zipFile);
    } else {
        // Erreur lors de la création du fichier ZIP
        echo "Erreur lors de la création du fichier ZIP.";
    }

    

/*     $absolutePath = $_SERVER['DOCUMENT_ROOT'].trim($path_photo);

    if(file_exists($absolutePath)){
        $nom_photo = basename($compte_pp);

        $fileExtension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
            case 'webp':
                header('Content-Type: image/webp');
                break;
            case 'bmp':
                header('Content-Type: image/bmp');
                break;
            default:
                // Si le type n'est pas reconnu, on peut renvoyer une erreur ou gérer un type générique
                header('Content-Type: application/octet-stream');
                break;
        }
        header('Content-Disposition: attachment; filename="' . $nom_photo . '"');
        header('Content-Length: ' . filesize($path_photo));
        readfile($path_photo);
    } */
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualiser Profil</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<body>
    <?php
        if(isset($erreur)){
            foreach($erreur as $err){
                echo $erreur."<br>";
            }
        }
    ?>
    <div class="header_membre">
        <header class="header-pc">
            <div class="logo">
                 <a href="voir_offres.php">
                    <img src="images/logoBlanc.png" alt="PACT Logo">
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                        if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                        ?>
                        <li>
                            <a href="consulter_compte_membre.php" class="active">Mon compte</a>
                        </li>
                            <?php
                        } else {
                            ?>
                        <li>
                            <a href="connexion_membre.php">Se connecter</a>
                        </li>
                        <?php
                        }
                    ?>
                </ul>
            </nav>
        </header>
    </div>
    <main class="main_consulter_compte_membre">
<!-- POUR PC/TABLETTE -->
        <div class="profile">
            <div class="banner">
                <img src="images/Rectangle 3.png" alt="Bannière" class="header-img">
            </div>

            <div class="profile-img-container">
                 <div class="profile-img-icone">
                     <img class="profile-img" src="<?php echo $compte_pp; ?>" alt="Photo de profil">
                     <form action="#" method="POST" enctype="multipart/form-data">
                         <label for="upload-photo" class="upload-photo-button">
                             <span class="iconify" data-icon="mdi:camera" data-inline="false"></span>
                         </label>
                         <input type="file" id="upload-photo" name="profile-photo" accept="image/*" hidden required>
                         <button type="submit" class="modif_photo" name="changePhoto">Enregistrer</button>
                     </form>
                 </div>
            </div>
            
            <h1><?php echo $monCompteMembre['prenom']." ".$monCompteMembre['nom']." (".$monCompteMembre['pseudo'].")"; ?></h1>
            <p><?php echo $compte['mail']; ?> | <?php echo trim(preg_replace('/(\d{2})/', '$1 ', $compte['telephone'])); ?></p>
        </div>

        <style>
    @media (max-width: 429px) {
        .profile{
            display:none;
        }
    }
</style>

<!-- POUR TEL -->
        <style>
    .edit-profil {
        display: none;
    }
</style>

<div class="edit-profil">
    <a href="compte_membre_tel.php">
        <img src="images/Bouton_retour.png" alt="bouton retour">
    </a>
    <h1 class ="editer_profil_tel">Editer le profil</h1>
</div>      
            <section class="tabs">
                <ul>
                    <li><a href="#" class="active">Informations personnelles</a></li>
                    <li><a href="modif_mdp_membre.php">Mot de passe et sécurité</a></li>
                    <li><a href="consulter_mes_avis.php">Historique</a></li>
                </ul>
            </section>

        <form action="consulter_compte_membre.php" method="POST" class="compteForm">

            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Nom *</legend>
                    <input type="text" id="nom" name="nom" value="<?php echo $monCompteMembre['nom']; ?>" placeholder="Nom *" required>
                </fieldset>

                <fieldset>
                    <legend>Prénom *</legend>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $monCompteMembre['prenom']; ?>" placeholder="Prénom *" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Pseudo *</legend>
                <input type="text" id="pseudo" name="pseudo" value="<?php echo $monCompteMembre['pseudo']; ?>" placeholder="Pseudo *" required>
            </fieldset>

            <div class="crea_pro_mail_tel">
                <fieldset>
                    <legend>Email *</legend>
                    <input type="email" id="email" name="mail" value="<?php echo $compte['mail']; ?>" placeholder="Email *" required>
                </fieldset>

                <fieldset>
                    <legend>Téléphone *</legend>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo trim(preg_replace('/(\d{2})/', '$1 ', $compte['telephone'])) ?>" placeholder="Téléphone *" maxlength="14" required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Adresse Postale *</legend>
                <input type="text" id="adresse_postal" name="adresse_postal" value="<?php echo $_adresse['adresse_postal']; ?>" placeholder="Adresse Postale *" required maxlength="38">
            </fieldset>
           
            <div class="crea_pro_mail_tel">
               <fieldset>
                   <legend>Code Postal *</legend>
                   <input type="text" id="code_postal" name="code_postal" value="<?php echo $_adresse['code_postal']; ?>" placeholder="Code Postal *" required>
               </fieldset>
   
               <fieldset>
                   <legend>Ville *</legend>
                   <input type="text" id="ville" name="ville" value="<?php echo $_adresse['ville']; ?>" placeholder="Ville *" required>
               </fieldset>
            </div>
            
            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="cgu.php">Conditions générales d’utilisation (CGU)</a></label>
            </div>
             <div class="compte_membre_actions">
            <!-- Section pour la déconnexion et l'enregistrement -->
            <div class="compte_membre_save_delete">
                <div class="supprimer-download">
                    <!-- Bouton "Supprimer le compte" et bouton pour "Mes données" -->
                    <button type="button" name="suppr-compte" class="btn-suppr-compte" id="btn-suppr-compte">Supprimer le compte</button>
                </div>
                <div class="donnees_deco_enreg">
                    <button type="button" name="mes-donnees" class="submit-btn4" id="btn-mes-donnees">Mes données</button>
                    <a href="?deco=true" class="submit-btn1">Déconnexion</a>
                    <button type="submit" name="modif_infos" class="submit-btn2" id="btn-enreg">Enregistrer</button>
                </div>
                
        
            <!-- Message de succès (initialement masqué) -->
        <div id="mail-success" class="creation-success" style="display: none;">
            <img src="images/verifier.png" alt="Succès">
            <h2>Le mail a été envoyé avec succès !</h2>
        </div>
        
        <!-- Popup qui s'affichera -->
        <div class="custom-confirm" id="popup-mes-donnees">
            <div class="custom-confirm-content">
                <p style="margin-bottom: 122px;">Que voulez-vous faire ?</p>
                <button id="confirm-donnees" class="btn-confirm">Télécharger</button>
                <button id="cancel-donnees" class="btn-cancel">Mail</button>
                <button id="close-popup" class="btn-close">Fermer</button>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Récupère les éléments du DOM
                const btnMesDonnees = document.getElementById("btn-mes-donnees");
                const popupMesDonnees = document.getElementById("popup-mes-donnees");
                const btnConfirmDonnees = document.getElementById("confirm-donnees");
                const btnCancelDonnees = document.getElementById("cancel-donnees");
                const btnClosePopup = document.getElementById("close-popup");

                const realPopup = popupMesDonnees.querySelector("div");
        
                // Récupérer le message de succès après que le DOM ait fini de se charger
                const successMessage = document.getElementById('mail-success');
        
                // Afficher la popup lorsque l'utilisateur clique sur le bouton "Mes données"
                btnMesDonnees.addEventListener("click", () => {
                    popupMesDonnees.style.display = "block";
                    realPopup.style.display = "block";
                });
        
                // Masquer la popup lorsque l'utilisateur clique sur "Fermer"
                btnClosePopup.addEventListener("click", () => {
                    popupMesDonnees.style.display = "none";
                    realPopup.style.display = "none";
                });
        
                // Télécharger les données lorsque l'utilisateur clique sur "Télécharger"
                btnConfirmDonnees.addEventListener("click", () => {
                    // Rediriger vers la page PHP de téléchargement
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = ""; // La même page pour exécuter le code de téléchargement
        
                    // Ajout d'un champ caché pour identifier l'action
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "dwl-data";
                    input.value = "true"; // Déclencher le téléchargement
                    form.appendChild(input);
        
                    document.body.appendChild(form);
                    form.submit(); // Soumet le formulaire
        
                    // Fermer la popup
                    popupMesDonnees.style.display = "none";
                });
        
                // Afficher le message de succès lorsque l'utilisateur clique sur "Mail"
                btnCancelDonnees.addEventListener("click", () => {
                    // Rediriger vers la page envoyer_email2.php
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = "envoyer_email2.php"; // Page qui envoie le mail
        
                    // Ajout d'un champ caché pour identifier l'action (optionnel)
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "send-email";
                    input.value = "true"; // Action spécifique pour envoyer un e-mail
                    form.appendChild(input);
        
                    document.body.appendChild(form);
                    form.submit(); // Soumet le formulaire
        
                    // Fermer la popup
                    popupMesDonnees.style.display = "none";
        
                    // Afficher le message de succès après un délai
                    setTimeout(() => {
                        // S'assurer que l'élément existe avant d'essayer de le manipuler
                        if (successMessage) {
                            successMessage.style.display = "block"; // Affiche le message de succès
        
                            // Facultatif : cacher le message après quelques secondes
                            setTimeout(() => {
                                successMessage.style.display = "none"; // Cache le message après 5 secondes
                            }, 5000); // Le message disparaît après 5 secondes
                        }
                    }, 1000); // Le message de succès apparaît après 1 seconde
                });
            });
        </script>


        </form>
       
      
  </div>
    <div id="mail-success" class="creation-success" style="display:none;">
        <img src="images/verifier.png" alt="Succès">
        <h2>Le mail a été envoyé avec succès !</h2>
    </div>
    <?php if (isset($_GET['mail_sent']) && $_GET['mail_sent'] == 'true'): ?>
        <div id="mail-success" class="creation-success">
            <img src="images/verifier.png" alt="Succès">
            <h2>Le mail a été envoyé avec succès !</h2>
        </div>
    <?php endif; ?>


    </main>

    
    <nav class="nav-bar_consulter_compte_membre">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="consulter_mes_avis.php"><img  src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="incitation.php"><img  src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
            <?php
                if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                    echo "consulter_compte_membre.php";
                } else {
                    echo "connexion_membre.php";
                }
            ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>

    
    <footer class="footer footer_membre">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
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
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["membre"]) && !empty($_SESSION["membre"])) {
                        ?>
                        <li>
                            <a href="compte_membre_tel.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_membre.php">Se connecter</a>
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

        <div class="footer-bottom">x
            <div class="social-icons">
                <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </footer>
    <script>
        // Désactiver le bouton par défaut
        const form = document.getElementById('compteForm');
        const saveButton = document.getElementById('btn-enreg');
        const inputs = form.querySelectorAll('input');
    
        // Sauvegarde des valeurs initiales
        const initialValues = {};
        inputs.forEach(input => {
            initialValues[input.name] = input.value;
        });
    
        // Vérifier si un champ a changé
        form.addEventListener('input', () => {
            let hasChanged = false;
            inputs.forEach(input => {
                if (input.value !== initialValues[input.name]) {
                    hasChanged = true;
                }
            });
            saveButton.disabled = !hasChanged; // Activer ou désactiver le bouton
        });
    </script>
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('upload-photo');
            const profileImg = document.querySelector('.profile-img');
        
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        profileImg.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(file);
                } else {
                    profileImg.src = '';
                }
            });
        });

        ////////////////////////////////////////////////////////////////////////////////////
        ///                            Supprimer compte                                  ///
        ////////////////////////////////////////////////////////////////////////////////////

        document.getElementById('btn-suppr-compte').addEventListener('click', function () {
            const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce compte ?");
            if (confirmation) {
                const compteId = <?php echo json_encode($compte['code_compte']); ?>;
        
                fetch('/supprimer_compte.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: compteId })
                })
                .then(response => response.json()) // Parse la réponse JSON
                .then(data => {
                    if (data.success) {
                        alert('Compte supprimé avec succès.');
                        window.location.href = '/consulter_compte_membre.php?deco=true'; // Redirection côté client
                    } else {
                        alert(data.message || 'Erreur lors de la suppression du compte.');
                    }
                })
                .catch(error => {
                    console.error('Erreur réseau ou serveur :', error);
                    alert('Impossible de supprimer le compte.');
                });
            }
        });
    
    </script>
    <script>
    // Lorsque le message est envoyé avec succès, afficher le message de succès
    function afficherMessageSucces() {
        // Trouver l'élément qui contient le message de succès
        const successMessage = document.getElementById('creation-success');
        // Afficher le message
        successMessage.style.display = 'block';
    
        // Facultatif: cacher après quelques secondes
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 5000); // Le message disparaît après 5 secondes
    }
    
    // Appel de la fonction pour afficher le message après l'envoi réussi du mail
    // Assurez-vous de l'appeler dans le callback de votre envoi PHP ou dans un appel de réussite de votre processus JavaScript.
    afficherMessageSucces();
    </script>



            
    
</body>
</html>
