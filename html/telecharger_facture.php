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
            margin-top: 1.5em;
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
            margin-bottom: 3em;
            margin-top: 2em;
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
        background-color:  var(--orange);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease;
        }
    
        #download-btn:hover {
            background-color:  var(--orange);
            transform: translateY(-2px);
        }
        
        #download-btn:active {
            background-color:  var(--orange);
            transform: translateY(1px);
        }
        .dropdown-container {
            margin-top: 2em;
            text-align: center;
            margin-bottom: 2em;
        }
        
        .dropdown {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .info-facture {
        width: max-content; /* Adapte la largeur au contenu */
        margin-left: auto; /* Repousse le conteneur vers la droite */
        margin-right: 20px; /* Ajoute un espace entre le conteneur et le bord droit */
        padding: 10px;
        }
        
        .info-facture p {
            margin: 5px 0;
            font-size: 14px;
            color: #333; /* Texte un peu plus foncé pour le contraste */
        }

        .signature-container {
        margin-top: 20px;
        text-align: left;
        margin-left: 20px;
        }
        
        .signature-container p {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .signature-box {
            width: 250px;
            height: 80px;
            border: 2px dashed #9b9b9b;
            border-radius: 5px; /* Coins légèrement arrondis */
            background-color: #f9f9f9; /* Fond léger pour contraster */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999; /* Texte indicatif en gris */
            font-size: 14px;
            font-style: italic;
        }

        .payment-info {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            padding: 10px;
        }
        
        .payment-info p {
            margin: 5px 0;
            line-height: 1.5;
        }
        
        .thank-you {
            margin-top: 30px;
            text-align: center;
            font-size: 16px;
            font-style: italic;
            color: #007bff;
            margin-top: 10em;
        }
        
        .thank-you p {
            margin: 5px 0;
        }







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
        <?php
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";
         try {
            // Créer une instance PDO
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }

            $iban = '';
            $bic = '';
            $nom = '';
            $query = "SELECT nom_compte, iban, bic FROM tripenarvor._compte_bancaire LIMIT 1";
            $stmt = $pdo->query($query);

            // Vérifier s'il y a des résultats
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nom = $row['nom_compte'];
                $iban = $row['iban'];
                $bic = $row['bic'];
            }
        ?>



         <?php 
                
                $date_publication = '';
                $nom_type = '';
                $query = "SELECT date_publication, nom_type FROM tripenarvor._offre where code_offre = :code_offre";
                $stmt = $pdo->query($query);
                 $recupInfo->bindParam(':code_offre', 1/*$code_offre*/); // A GERER
                // Vérifier s'il y a des résultats
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $date_publication = $row['date_publication'];
                    $nom_type = $row['nom_type'];
                }
                    ?>
        <table class="facture-items">
            <thead>
                <tr>
                    <th>Type d'offre</th>
                    <th>Prix par jour</th>
                    <th>Date de mise en ligne</th>
                    <th>Montant HT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $nom_type ?></td>
                    <td>5€</td>
                    <td><?php echo $date_publication ?></td>
                    <td>100€</td>
                </tr>
            </tbody>
        </table>
        
        <table class="facture-items">
            <thead>
                <tr>
                    <th>Boost</th>
                    <th>Prix par semaine</th>
                    <th>Nombre de semaines</th>
                    <th>Montant HT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>En relief</td>
                    <td>10€</td>
                    <td>3</td>
                    <td>100€</td>
                </tr>
            </tbody>
        </table>

        <div class="facture-footer">
            <div class="info-facture">
                <p>Total HT: 130€</p>
                <p>TVA 20%: 26€</p>
                <p>Total TTC: 156€</p>
            </div>
            <div class="payment-info">
                <p><strong>Mode de paiement :</strong> Virement bancaire</p>
                <p><strong>IBAN :</strong> <?php echo $iban?></p>
                <p><strong>BIC :</strong> <?php echo $bic?></p>
            </div>

            <div class="signature-container">
                <p><strong>Signature :</strong></p>
                <div class="signature-box"></div>
            </div>

            <div class="thank-you">
                <p><strong>Merci pour votre confiance !</strong></p>
                <p>Nous espérons vous revoir bientôt.</p>
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
