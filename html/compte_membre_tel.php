<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="compte_membre_tel.css">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="logo">
                <img src="images/LogoNoirVert.png" alt="PACT Logo">
            </div>
        </header>

        <!-- Profile Section -->
        <section class="profile">
            <img class="profile-img" src="images/pp.png" alt="Photo de profil">
            <h1 class="profile-name">Juliette Martin</h1>
            <p class="profile-contact">juliemartin@gmail.com | 07.98.76.54.12</p>
        </section>

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
                <img src="images/Vector_14.png" alt="Logout Icon"> Déconnexion
            </button>
        </main>
    </div>
</body>
</html>
