<?php

ob_start();
session_start();

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

            // Générer un nom unique pour le fichier
            $fileName = pathinfo($file['name'], PATHINFO_FILENAME) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            echo $fileName;
            $filePath = $uploadDir . $fileName;

            // Déplacer le fichier temporaire vers le dossier cible
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Générer l'URL de l'image
                $photoUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $filePath;

                // Vérifier si l'utilisateur existe déjà
                $query = $dbh->prepare('SELECT COUNT(*) FROM tripenarvor._sa_pp WHERE code_compte = :user_id');
                $query->bindValue(':user_id', $compte['code_compte'], PDO::PARAM_INT);
                $query->execute();
                $exists = $query->fetchColumn() > 0;

                if ($exists) {
                    // Mise à jour de l'URL de la photo de profil
                    $updateQuery = $dbh->prepare('UPDATE tripenarvor._sa_pp SET url_image = :profile_photo_url WHERE code_compte = :user_id');
                    $updateQuery->bindValue(':profile_photo_url', $photoUrl, PDO::PARAM_STR);
                    $updateQuery->bindValue(':user_id', $compte['code_compte'], PDO::PARAM_INT);
                    $updateQuery->execute();

                    echo "Photo de profil mise à jour avec succès !";
                } else {
                    // Insérer une nouvelle entrée
                    $insertQuery = $dbh->prepare('INSERT INTO tripenarvor._sa_pp (url_image, code_compte) VALUES (:profile_photo_url, :user_id)');
                    $insertQuery->bindValue(':user_id', $compte['code_compte'], PDO::PARAM_INT);
                    $insertQuery->bindValue(':profile_photo_url', $photoUrl, PDO::PARAM_STR);
                    $insertQuery->execute();

                    echo "Utilisateur créé et URL de la photo enregistrée !";
                }
            } else {
                echo "Erreur lors du déplacement du fichier.";
            }
        } else {
            echo "Erreur lors de l'upload du fichier.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="compte_membre_tel.css">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="pp.js"></script>

</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="logo">
                <img src="images/logoNoirVert.png" alt="PACT Logo">
            </div>
        </header>

        <!-- Profile Section -->
        <section class="profile">
    <div class="profile-img-container">
        <img class="profile-img" src="<?php echo $compte_pp; ?>" alt="Photo de profil">
            <form action="#" method="POST" enctype="multipart/form-data">
                <label for="upload-photo" class="upload-photo-button">
                    <span class="iconify" data-icon="mdi:camera" data-inline="false"></span>
                </label>
                <input type="file" id="upload-photo" name="profile-photo" accept="image/*" hidden required>
                <button type="submit" name="changePhoto">Upload</button>
            </form>
    </div>
    <h1 class="profile-name">Juliette Martin</h1>
     <p class="profile-contact">juliemartin@gmail.com | 07.98.76.54.12</p>
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


        <!-- Actions Section -->
        <main class="actions">
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
            <a href="consulter_mes_avis.php">
            <button class="action-btn">
                <img src="images/Vector_12.png" alt="History Icon"> Historique
            </button>
            </a>
            <!--Il faudra gérer la deconnexion sur bouton"-->
            <button class="action-btn">
                <img src="images/Vector_14.png" alt="Logout Icon"> <a href="connexion_membre.php"&deco="true">Déconnexion</a>
            </button>
        </main>
    </div>
    <nav class="nav-bar">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
                <?php
                if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
                    echo "compte_membre_tel.php";
                } else {
                    echo "connexion_membre.php";
                }
                ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
</body>
</html>
