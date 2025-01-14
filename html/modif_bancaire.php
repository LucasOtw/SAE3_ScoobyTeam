<?php

ob_start();
session_start();

include("recupInfosCompte.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Coordonnées bancaires</title>
    <link rel="stylesheet" href="styles.css">
    
</head>

<body>
    <header class="header_pro">
        <div class="logo">
            <a href="mes_offres.php">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
                <li>
    <a href="#" class="notification-icon" id="notification-btn">
        <img src="images/notif.png" alt="cloche notification" class="nouvelle-image" style="margin-top: -5px;">
        <span class="notification-badge" style="display:none"></span>
    </a>
    <div id="notification-popup">
        <ul>
            <li>
                <img src="images/user2.png" alt="photo de profil" class="profile-img">
                <div class="notification-content">
                    <strong>Quentin Uguen</strong>
                    <span class="notification-location"> | Abbaye de Monfort</span>
                    <p>Déplorable, environnement moche</p>
                    <span class="notification-time">1 mois</span>
                    <span class="new-notif-dot"></span>
                </div>
            </li>
        </ul>
    </div>
</li>
    </header>
    <main class="main-modif-bancaire">
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

        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
                <li><a href="mes_offres.php">Mes offres</a></li>
                <li><a href="#" class="active">Compte bancaire</a></li>
                <li><a href="modif_mdp_pro.php">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_reponses_pro.php">Mes réponses</a></li>
            </ul>
        </section>
        </div>

        <?php
        // Détails de la connexion à la base de données
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
            // Variables pour stocker les informations bancaires
            $iban = '';
            $bic = '';
            $nom = '';
            $message = '';

            if ($monComptePro["code_compte_bancaire"] != null)
            {
                // Préparer la requête pour récupérer les informations bancaires
                $query = "SELECT nom_compte, iban, bic FROM tripenarvor._compte_bancaire where code_compte_bancaire = :code_compte_bancaire";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':code_compte_bancaire', $monComptePro["code_compte_bancaire"]);
                $stmt->execute();
    
                // Vérifier s'il y a des résultats
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nom = $row['nom_compte'];
                    $iban = $row['iban'];
                    $bic = $row['bic'];
                }
            }

            // Si le formulaire est soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $iban = htmlspecialchars($_POST['IBAN']);
                $bic = htmlspecialchars($_POST['BIC']);
                $nom = htmlspecialchars($_POST['nom']);
                $cgu = isset($_POST['cgu']) ? true : false;

                // Vérifier que tous les champs sont remplis
                if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
                    // Mettre à jour les informations bancaires dans la base de données
                    $update_query = "UPDATE tripenarvor._compte_bancaire SET nom_compte = :nom, iban = :iban, bic = :bic WHERE code_compte_bancaire = :code_compte_bancaire";
                    $stmt = $pdo->prepare($update_query);
                    $stmt->bindParam(':code_compte_bancaire', $monComptePro["code_compte_bancaire"]);
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':iban', $iban);
                    $stmt->bindParam(':bic', $bic);

                    if ($stmt->execute()) {
                        $message = "Les informations bancaires ont été modifiées avec succès.";
                    } else {
                        $message = "Impossible de modifier les informations. Veuillez réessayer.";
                    }
                } else {
                    $message = "Veuillez remplir tous les champs.";
                }
            }

        // Fermer la connexion
        $pdo = null;
        ?>

        <?php if ($message): ?>
            <div class="alert" id="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="#" method="POST">
            <h3>Modifiez vos coordonnées bancaires</h3>
            <div class="form-image-container">
                <div class="form-section">
                    <div class="IBAN">
                        <fieldset>
                            <legend>IBAN *</legend>
                            <input type="text" id="IBAN" name="IBAN" value="<?php echo $iban; ?>"
                                placeholder="IBAN *" required>
                        </fieldset>
                    </div>
                    <div class="BIC">
                        <fieldset>
                            <legend>BIC *</legend>
                            <input type="text" id="BIC" name="BIC" value="<?php echo $bic; ?>"
                                placeholder="BIC *" required>
                        </fieldset>
                    </div>
                    <div class="nom-du-proprietaire">
                        <fieldset>
                            <legend>Nom *</legend>
                            <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom *"
                                required>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
            </div>
            <div class="compte_membre_save_delete">
                <button type="submit" class="submit-btn2">Modifiez vos coordonnées</button>
            </div>
        </form> 
    </main>
    <footer class="footer footer_pro">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PACT">
            </div>
             <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <li><a href="connexion_memebre.php">Se Connecter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="contacter_plateforme.php">Signaler un problème</a></li>
                    <li><a href="contacter_plateforme.php">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <!--<li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>-->
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

    <script>
        window.onload = function () {
            var alertBox = document.getElementById('alert');
            if (alertBox) {
                alertBox.style.display = 'block';
                setTimeout(function () {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const notificationBtn = document.getElementById('notification-btn');
    const notificationPopup = document.getElementById('notification-popup');

    notificationBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche le comportement par défaut de l'ancre
        notificationPopup.classList.toggle('hidden');
    });

    // Fermer le pop-up si on clique en dehors
    document.addEventListener('click', (e) => {
        if (!notificationPopup.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationPopup.classList.add('hidden');
        }
    });
});

    </script>
</body>


</html>
