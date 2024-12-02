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
                <img src="images/logoNoir.png" alt="PACT Logo">
            </div>
        </header>

        <!-- Profile Section -->
        <section class="profile">
    <div class="profile-img-container">
        <img class="profile-img" src="" alt="Photo de profil">
        <form action="/upload" method="POST" enctype="multipart/form-data">
            <label for="upload-photo" class="upload-photo-button">
                <span class="iconify" data-icon="mdi:camera" data-inline="false"></span>
            </label>
            <input type="file" id="upload-photo" name="profile-photo" accept="image/*" hidden>
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
