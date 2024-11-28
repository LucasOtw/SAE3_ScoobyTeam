<?php 
    ob_start(); // bufferisation, ça devrait marcher ?
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="consulter_mes_avis.css">
</head>
<body>
<div class="header-membre">
    <header class="header-pc">
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <!-- Static example: User is connected -->
                <li>
                    <a href="consulter_compte_membre.php" class="active">Mon compte</a>
                </li>
                <!-- Uncomment this to simulate user not connected -->
                <!--
                <li>
                    <a href="connexion_membre.php">Se connecter</a>
                </li>
                -->
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

        <div class="profile-info">
            <img src="images/icones/icone_compte.png" alt="Photo de profil" class="profile-img">
            <h1>John Doe (johnny)</h1> <!-- Static example -->
            <p>john.doe@example.com | 06 12 34 56 78</p> <!-- Static example -->
        </div>
    </div>
    <!-- POUR TEL -->
    <div class="edit-profil">
        <a href="compte_membre_tel.php">
            <img src="images/Bouton_retour.png" alt="bouton retour">
        </a>
        <h1>Editer le profil</h1>
    </div>                
    <section class="tabs">
        <ul>
            <li><a href="#">Informations personnelles</a></li>
            <li><a href="modif_mdp_membre.php">Mot de passe et sécurité</a></li>
            <li><a href="consulter_mes_avis" class="active">Historique de mes avis</a></li>
            <!-- Uncomment if needed -->
            <!-- <li><a href="historique_membre.php">Historique</a></li> -->
        </ul>
    </section>
    <div class="avis-widget">
    <div class="avis-list">
        <div class="avis">
            <div class="avis-content">
                <h3 class="avis" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        5.0 Excellent | <span class="nom_avis">John Doe &nbsp;</span>| &nbsp;<span class="nom_visite">Je suis Tigre</span>
                    </span>
                    <button class="delete-btn" title="Supprimer cet avis">
                        <img src="images/trash.svg" alt="Supprimer" class="delete-icon">
                    </button>
                </h3>
                <p class="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
            </div>
        </div>

        <div class="avis">
            <div class="avis-content">
                <h3 class="avis" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        4.2 Génial | <span class="nom_avis">John Doe &nbsp;</span>| &nbsp;<span class="nom_visite">Breizh Shelter</span>
                    </span>
                    <button class="delete-btn" title="Supprimer cet avis">
                        <img src="images/trash.svg" alt="Supprimer" class="delete-icon">
                    </button>
                </h3>
                <p class="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
            </div>
        </div>
        <div class="avis">
            <div class="avis-content">
                <h3 class="avis" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        4.2 Génial | <span class="nom_avis">John Doe &nbsp;</span>| &nbsp;<span class="nom_visite">Breizh Shelter</span>
                    </span>
                    <button class="delete-btn" title="Supprimer cet avis">
                        <img src="images/trash.svg" alt="Supprimer" class="delete-icon">
                    </button>
                </h3>
                <p class="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
            </div>
        </div>
        <div class="avis">
            <div class="avis-content">
                <h3 class="avis" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        4.2 Génial | <span class="nom_avis">John Doe &nbsp;</span>| &nbsp;<span class="nom_visite">Breizh Shelter</span>
                    </span>
                    <button class="delete-btn" title="Supprimer cet avis">
                        <img src="images/trash.svg" alt="Supprimer" class="delete-icon">
                    </button>
                </h3>
                <p class="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
            </div>
        </div>
        <div class="avis">
            <div class="avis-content">
                <h3 class="avis" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        4.2 Génial | <span class="nom_avis">John Doe &nbsp;</span>| &nbsp;<span class="nom_visite">Breizh Shelter</span>
                    </span>
                    <button class="delete-btn" title="Supprimer cet avis">
                        <img src="images/trash.svg" alt="Supprimer" class="delete-icon">
                    </button>
                </h3>
                <p class="avis">Super, un séjour enrichissant, un personnel réactif. Je recommande. À noter les gens sont serviables, à l'écoute. Le cadre est relativement tranquille avec un panorama magnifique.</p>
            </div>
        </div>
    </div>
</div>

        
</main>

</body>
</html>

