<?php
session_start();

include('recupInfosCompte.php');

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

if(!isset($_SESSION['pro'])){
  header('location: connexion_pro.php');
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Télécharger une facture</title>
    <link rel="stylesheet" href="telecharger_facture.css">

</head>
<body>
<header>
    <div class="logo">
     <a href="mes_offres.php">
           <img src="images/logo_blanc_pro.png" alt="PACT Logo">
     </a>

    </div>
    <nav>
        <ul>
            <li><a href="mes_offres.php">Accueil</a></li>
            <li><a href="creation_offre.php">Publier</a></li>
            <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
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
        border-radius: 15px;
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
            transform: translateY(2px);
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
            margin-bottom: 30px;
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
            margin-top: 2.65em;
        }
        
        .thank-you p {
            margin: 5px 0;
        }

       .dropdown-container {
            margin: 20px 0;
            font-family: Arial, sans-serif;
        } 
        
        .btn-valider {
            background-color: var(--orange);
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            border-radius: 12px;
            box-shadow: 0px 6px 19px rgba(0, 56, 255, 0.24);
            height: 4.3vh;
            box-shadow: 0px 6px 19px rgba(0, 56, 255, 0.24);
            transition: transform 0.2s ease;
        }
        
        .btn-valider:hover {
            transform: translateY(-2px); /* Effet d'élévation au survol */
        }

        .btn-valider:active {
            transform: translateY(2px); /* Effet d'élévation au survol */
        }

        .dropdown-container {
          margin: 20px 0;
          font-family: Arial, sans-serif;
          display: flex;
          flex-direction: column;
          align-items: center; /* Centre horizontalement le contenu */
      }
      
      .dropdown-container label {
          margin-bottom: 3em;
          font-size: 16px;
          color: #333;
          text-align: center;
      }
      
      .dropdown-button-wrapper {
          display: flex; /* Active le mode flexbox */
          gap: 10px; /* Espacement entre les éléments */
          align-items: center; /* Aligne verticalement */
          justify-content: center; /* Centre les éléments horizontalement */
          margin-top: 2em;
      }
     .offer-name {
          color: #333;
          text-align: center;
      }
      .offer-name strong {
          color: #333;
      }











     

    </style>
</head>
<body>
    <div class="dropdown-container">
    <form method="POST" action="">
        <div class="dropdown-button-wrapper">
            <select id="offres" name="offre" class="dropdown">
                <option value="" disabled selected>Choisissez une offre</option>
                <?php
                    $offres_pro = $dbh->prepare("SELECT * FROM tripenarvor._offre WHERE professionnel = :code_compte");
                    $offres_pro->bindParam(':code_compte', $compte['code_compte']);
                    $offres_pro->execute();
                    $offres = $offres_pro->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($offres as $offre) {
                        ?>
                        <option value="<?php echo $offre['code_offre'];?>"> <?php echo $offre['titre_offre'];?></option>
                    <?php } ?>
            </select>
            <button type="submit" class="btn-valider">Valider</button>
        </div>
    </form>
</div>



        
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
         $offreSelectionnee = isset($_POST['offre']) ? $_POST['offre'] : 1;
         $date_publication = '';
         $nom_type = '';
         $prix_par_jour = ''; // Prix par jour défini
         $montant_ht = 0;
         $titre_offre = '';

         $en_relief = '';
         $a_la_une = '';
         
         $montant_ht_total = 0;
         
         $query = "select * from tripenarvor._offre natural join tripenarvor._type_offre WHERE code_offre = :offreSelectionnee";
         $stmt = $pdo->prepare($query);
         $stmt->bindParam(':offreSelectionnee', $offreSelectionnee, PDO::PARAM_INT);
         $stmt->execute();
         
         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             $date_publication = $row['date_publication'];
             $nom_type = $row['nom_type'];
             $prix_par_jour = ($row['prix'] / (1 + 0.2));
             $titre_offre = $row['titre_offre'];      
          
             $date_pub = new DateTime($date_publication);
             $date_actuelle = new DateTime();
             $interval = $date_pub->diff($date_actuelle);
             $jours_ecoules = $interval->days;
         
             $montant_ht = $jours_ecoules * $prix_par_jour;
         }
         ?>

         <?
          $nb_semaines_relief = '';
          $query_nb_semaine = "select * from tripenarvor._offre natural join tripenarvor._option WHERE code_offre = :offreSelectionnee";

          $stmt_nb_semaine = $pdo->prepare($query_nb_semaine);
          $stmt_nb_semaine->bindParam(':offreSelectionnee', $offreSelectionnee, PDO::PARAM_INT);
          $stmt_nb_semaine->execute();

          if ($row = $stmt_nb_semaine->fetch(PDO::FETCH_ASSOC)) {

             $en_relief = $row['option_en_relief'];
             $a_la_une = $row['option_a_la_une'];
             if ($en_relief === null ) { $nb_semaines_relief = 0 ;} else { $nb_semaines_relief = $row['nb_semaines']; }
             if ($a_la_une === null ) { $nb_semaines_une = 0 ;} else { $nb_semaines_une = $row['nb_semaines']; }
             
         }

?>
          <div class="offer-name">
               <p><strong>Nom de l'offre :</strong> <?php echo $titre_offre;?></p>
          </div>
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
                     <td><?php echo number_format($prix_par_jour, 2, ',', ' ')?>€</td>
                     <td><?php echo $date_publication ?></td>
                     <td><?php echo number_format($montant_ht,2, ',', ' ') ?>€</td>
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
                    <td><?php if ($en_relief !== null ) { echo "8,34€"; } else { echo "Pas sélectionné"; }?></td>
                    <td><?php if ($en_relief !== null ) { echo "$nb_semaines_relief"; } else { echo "0"; }?></td>
                    <td><?php if ($en_relief !== null ) { echo number_format(8.34 * $nb_semaines_relief, 2, ',', ' ') . "€"; } else { echo "0,00€"; }?></td>
                </tr>
            
                <tr>
                    <td>À la Une</td>
                    <td><?php if ($a_la_une !== null ) { echo "16,68€"; } else { echo "Pas sélectionné"; }?></td>
                    <td><?php if ($a_la_une !== null ) { echo "$nb_semaines_une"; } else { echo "0"; }?></td>
                    <td><?php if ($a_la_une !== null ) { echo number_format(16.86 * $nb_semaines_une, 2, ',', ' ') . "€"; } else { echo "0,00€"; }?></td>
                </tr>
            </tbody>
        </table>
      <?php $montant_ht_total = $montant_ht + 16.86 * $nb_semaines_une + 8.34 * $nb_semaines_relief;?>
        <div class="facture-footer">
            <div class="info-facture">
                <p>Total HT: <?php echo number_format($montant_ht_total, 2, ',', ' ') ; ?>€</p>
                <p>TVA 20%: <?php echo number_format($montant_ht_total*0.20, 2, ',', ' '); ?>€</p>
                <p>Total TTC: <?php echo number_format($montant_ht_total*1.20, 2, ',', ' '); ?>€</p>
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
         
            <div class="signature-container" style="margin-left: 30em;margin-top: -6em;">
                <p><strong>L'équipe PACT :</strong></p>
                <img src="images/signature.png" style="width: 15em;">

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

<!-- Bouton pour imprimer la facture -->
<button id="print-btn">Imprimer la facture</button>

<!-- Bibliothèque html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    // Fonction d'impression
    document.getElementById('print-btn').addEventListener('click', () => {
        const element = document.getElementById('facture-container'); // Conteneur cible

        // Générer une capture en JPEG
        html2canvas(element).then((canvas) => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0); // Convertir en image JPEG

            // Créer une fenêtre d'impression temporaire
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Impression de la facture</title></head><body>');
            printWindow.document.write('<img src="' + imgData + '" style="width: 100%;"/>'); // Ajouter l'image JPEG
            printWindow.document.write('</body></html>');
            printWindow.document.close(); // Nécessaire pour certains navigateurs
            printWindow.focus(); // Mettre la fenêtre au premier plan
            printWindow.print(); // Lancer l'impression
            printWindow.close(); // Fermer la fenêtre après impression
        });
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
                <li><a href="creation_offre.php">Publier</a></li>
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
