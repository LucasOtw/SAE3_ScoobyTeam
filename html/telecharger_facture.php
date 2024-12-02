<?php
session_start();

include('recupInfosCompte.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Télécharger une facture</title>
    <link rel="stylesheet" href="telecharger_facture.css">

</head>
<body>
<header>
    <div class="logo">
        <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </div>
    <nav>
        <ul>
            <li><a href="mes_offres.php">Accueil</a></li>
            <li><a href="connexion_pro.php" class="active">Publier</a></li>
            <li><a href="connexion_pro.pro">Mon Compte</a></li>
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
                <h1><?php echo $monComptePro['raison_sociale']; ?></h1>
                <p><?php echo $compte['mail'] . " | " . $compte['telephone']; ?></p>
            </div>
        </section>
    
    <style>
    </style>
</head>
<body>
    <div class="dropdown-container">
        <label for="offres">Mes offres :</label>
        <select id="offres" class="dropdown">
            <option value="" disabled selected>Choisissez une offre</option>
            <option value="offre1">Offre 1 - Titre de l'offre</option>
            <option value="offre2">Offre 2 - Titre de l'offre</option>
            <option value="offre3">Offre 3 - Titre de l'offre</option>
        </select>  




        
    </div>


    <?php
    // Définir le fuseau horaire (optionnel, pour s'assurer de la bonne heure)
    date_default_timezone_set('Europe/Paris');
    
    // Récupérer la date du jour au format souhaité
    $dateDuJour = date('d/m/Y');
    
    ?>

    
     <div class="facture-container" id="facture-container">
        <header class="facture-header">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo Entreprise">
            </div>
            <div class="facture-title">
                <h1>Facture</h1>
                <p><?php echo $dateDuJour?></p>
            </div>
        </header>

        <div class="facture-info">
            <div class="info client">
                <h3>Raison sociale : <?php echo $monComptePro['raison_sociale']; ?></h3>
                <p><strong>Adresse</strong> : <?php echo $_adresse['adresse_postal']; ?>, <?php echo $_adresse['ville']; ?>, <?php echo $_adresse['code_postal']; ?></p>
                <p><strong>Numéro de téléphone</strong> : <?php echo $compte['telephone']; ?></p>
                <p><strong>Email</strong> : <?php echo $compte['mail']; ?></p>
            </div>
        </div>

        <table class="facture-items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Prix unitaire</th>
                    <th>Unité</th>
                    <th>Quantité</th>
                    <th>Montant HT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Détail prestation ici</td>
                    <td>20€</td>
                    <td>heures</td>
                    <td>5</td>
                    <td>100€</td>
                </tr>
                <tr>
                    <td>Détail prestation ici</td>
                    <td>15€</td>
                    <td>heures</td>
                    <td>2</td>
                    <td>30€</td>
                </tr>
            </tbody>
        </table>

        <div class="facture-footer">
            <div class="info-facture">
                <p>Total HT: 130€</p>
                <p>TVA 20%: 26€</p>
                <p>Total TTC: 156€</p>
            </div>
            <div class="signature-container">
                <p><strong>Signature :</strong></p>
                <div class="signature-box"></div>
            </div>

        </div>

        
    </div>
    <!-- Bouton pour télécharger la facture en PDF -->
    <button id="download-btn">Télécharger en PDF</button>

    <!-- Bibliothèque html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script>
        // Génération du PDF
        document.getElementById('download-btn').addEventListener('click', () => {
            const element = document.getElementById('facture-container'); // Conteneur cible
            const options = {
                margin: 1,
                filename: 'facture.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(options).from(element).save(); // Convertir et télécharger
        });
    </script>

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
