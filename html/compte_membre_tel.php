<?php

ob_start();
session_start();

if(!isset($_SESSION['membre'])){
    header('location: voir_offres.php');
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

include("recupInfosCompte.php");

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
    header('location: compte_membre_tel.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="pp.js"></script>

</head>
<body>
    <!-- Header Section -->
    <div class="header-membre">
        <header class="header-tel">
        <svg
            width="428"
            height="202"
            viewBox="0 0 428 202"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 126.87L0 0H428V134.241C374.61 176.076 300.465 202 218.5 202C131.823 202 53.891 173.01 0 126.87Z"
                fill="#E8E8E8"
            ></path>
        </svg>
            <div  class="logo-tel">
                <a href="voir_offres.php">
                    <img src="images/LogoCouleur.png" alt="PACT Logo">
                </a>
            </div>
        </header>
    </div>

    <main class="main_compte_membre_tel">
    <!-- Profile Section -->
        <section class="profile">
            <div class="profile-img-container">
                <img class="profile-img" src="<?php echo $compte_pp; ?>" alt="Photo de profil">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <label for="upload-photo" class="upload-photo-button">
                        <span class="iconify" data-icon="mdi:camera" data-inline="false"></span>
                    </label>
                    <input type="file" id="upload-photo" name="profile-photo" accept="image/*" hidden required>
                    <button type="submit" class="modif_photo" name="changePhoto">Enregistrer</button>
                </form>
            </div>
        
            <h1 class="profile-name"><?php echo $monCompteMembre['prenom']." ".$monCompteMembre['nom'] ?></h1>
            <p class="profile-contact"><?php echo $compte['mail'] ?> | <?php echo $compte['telephone'] ?></p>
        </section>


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


</script>
        
        <a href="consulter_compte_membre.php">
            <button class="action-btn">
                <img src="images/Vector_10.png" alt="Edit Icon"> Éditer les informations
            </button>
        </a>
        <a href="modif_mdp_membre.php">
            <button class="action-btn">
                <img src="images/Vector_11.png" alt="Password Icon"> Modifier mon mot de passe
            </button>
        </a>
        <a href="contacter_plateforme.php">
            <button class="action-btn">
                <img src="images/icones/phone.png" alt="Password Icon"> Contacter la plateforme
            </button>
        </a>
        <a href="obtenir_aide.php">
            <button class="action-btn">
                <img src="images/icones/help.png" alt="Password Icon"> Aide
            </button>
        </a>
        <a href="consulter_mes_avis.php">
            <button class="action-btn">
                <img src="images/Vector_12.png" alt="History Icon"> Historique
            </button>
        </a>
        <a href="?deco=true">
            <button class="action-btn">
                <img src="images/Vector_14.png" alt="Logout Icon"> Déconnexion
            </button>
        </a>

       <button type="button" name="suppr-compte" class="btn-suppr-compte" id="btn-suppr-compte" style="margin-bottom: 12vh;"> 
            <img src="images/trash.svg" alt="Trash Icon" style="width: 20px; height: 20px; margin-right: 10px;"> Supprimer mon compte
        </button>

    </main>
    <nav class="nav-bar">
    <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
    <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
    <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
    <a href="
        <?php
            if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                echo "compte_membre_tel.php";
            } else {
                echo "connexion_membre.php";
            }
        ?>">
        <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
    <script>
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
                        window.location.href = '/compte_membre_tel.php?deco=true'; // Redirection côté client
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
</body>
</html>
