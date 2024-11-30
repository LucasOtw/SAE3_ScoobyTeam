<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Télécharger une facture</title>
    <link rel="stylesheet" href="telecharger_facture.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="script.js"></script>

</head>
<body>
<header>
    <div class="logo">
        <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </div>
    <nav>
        <ul>
            <li><a href="mes_offres.php">Accueil</a></li>
            <li><a href="connexion_pro.php">Publier</a></li>
            <li><a href="connexion_pro.pro" class="active">Mon Compte</a></li>
        </ul>
    </nav>
</header>
<main class="main-telecharger-facture">
        <section class="profile">
            <div class="banner">
                <img src="images/Fond.png" alt="Bannière de profil">
            </div>
            <div class="profile-info">
                <img class="profile-picture" src="images/hotel.jpg" alt="Profil utilisateur">
                <h1><?php echo $monCompte['raison_sociale']; ?></h1>
                <p><?php echo $compte['mail'] . " | " . $compte['telephone']; ?></p>
            </div>
        </section>

        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
                <li><a href="mes_offres.php">Mes offres</a></li>
                <li><a href="#" class="active">Compte bancaire</a></li>
            </ul>
        </section>

    <div class="facture-container">
    <h1>Facture #12345</h1>
    <div class="facture-details">
        <p><strong>Date :</strong> 30 Novembre 2024</p>
        <p><strong>Client :</strong> Jean Dupont</p>
        <p><strong>Adresse :</strong> 123 Rue Exemple, Paris, France</p>
    </div>
    
    <table class="facture-items">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Service A</td>
                <td>2</td>
                <td>€50</td>
                <td>€100</td>
            </tr>
            <tr>
                <td>Service B</td>
                <td>1</td>
                <td>€150</td>
                <td>€150</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                <td>€250</td>
            </tr>
        </tbody>
    </table>
    
    <div class="facture-footer">
        <button id="download-pdf">Télécharger la facture en PDF</button>
    </div>
</div>


    


</main>
<footer class="footer">
    <div class="footer-links">
        <div class="logo">
            <img src="images/logoBlanc.png" alt="Logo PACT">
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
</body>
</html>
