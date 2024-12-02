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
    
    <style>
        /* Container principal */
        .facture-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #d1d1d1;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }
        
        /* Header de la facture */
        .facture-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .facture-header .logo img {
            max-height: 50px;
        }
        
        .facture-title h1 {
            font-size: 24px;
            color: #fff;
            margin: 0;
        }
        
        .facture-title p {
            font-size: 16px;
            color: #fff;
        }
        
        /* Informations entreprise et client */
        .facture-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .facture-info .info {
            width: 48%;
        }
        
        .facture-info .info h3 {
            font-size: 18px;
            color: #333;
            margin: 0 0 10px 0;
        }
        
        .facture-info .info p {
            font-size: 14px;
            color: #555;
            margin: 3px 0;
        }
        
        /* Table des items */
        .facture-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .facture-items th, .facture-items td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .facture-items th {
            background-color: var(--orange);
            color: white;
        }
        
        .facture-items td {
            background-color: #f9f9f9;
        }
        
        /* Footer de la facture */
        .facture-footer {
            margin-top: 20px;
        }
        
        .facture-footer p {
            font-size: 16px;
            color: #333;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            width: 50%;
            margin-bottom: 20px;
        }
        #download-btn {
        display: block;
        width: 200px;
        margin: 20px auto;
        padding: 15px;
        font-size: 18px;
        color: white;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease;
        }
    
        #download-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        
        #download-btn:active {
            background-color: #003f7f;
            transform: translateY(1px);
        }


    </style>
</head>
<body>
     <div class="facture-container" id="facture-container">
        <header class="facture-header">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo Entreprise">
            </div>
            <div class="facture-title">
                <h1>Facture N°12345</h1>
                <p>Le 30 Novembre 2024</p>
            </div>
        </header>

        <div class="facture-info">
            <div class="info entreprise">
                <h3>Nom de l'entreprise</h3>
                <p>Adresse</p>
                <p>Ville et Code Postal</p>
                <p>Numéro de téléphone</p>
                <p>Email</p>
            </div>
            <div class="info client">
                <h3>Nom du client</h3>
                <p>Adresse</p>
                <p>Numéro de téléphone</p>
                <p>Email</p>
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
            <p>Total HT: 130€</p>
            <p>TVA 20%: 26€</p>
            <p>Total TTC: 156€</p>

            <div class="signature">
                <p>Date d'échéance: 30 Décembre 2024</p>
                <p>Signature :</p>
                <div class="signature-line"></div>
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
