<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="ajout_bancaire.css">
    <style>
        .alert {
            display: none; /* Caché par défaut */
            padding: 10px;
            margin: 15px 0;
            background-color: #4CAF50; /* Couleur verte */
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="Images/logoBlanc.png" alt="PACT Logo">
    </div>
    <nav class="nav">
        <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Publier</a></li>
            <li><a href="#" class="active">Mon Compte</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <div class="header">
        <img src="Images/Fond.png" alt="Bannière" class="header-img">
    </div>

    <div class="profile-section">
        <img src="Images/pp.png" alt="Photo de profil" class="profile-img">
        <h1>Ti al Lannec</h1>
        <p>ti.al.lannec@gmail.com | 07.98.76.54.12</p>
    </div>
    <div class="tabs">
        <div class="tab">Mot de passe et sécurité</div>
        <div class="tab active">Compte Bancaire</div>
    </div>
</div>

<?php
// Détails de la connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=db-scooby-team;";
$username = "sae";
$password = "philly-Congo-bry4nt";

try {
    // Créer une instance PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Variables pour stocker les informations bancaires
    $iban = '';
    $bic = '';
    $nom = '';
    $message = ''; 

    // Préparer la requête pour récupérer les informations bancaires
    $query = "SELECT nom_compte, iban, bic FROM _compte_bancaire LIMIT 1";
    $stmt = $pdo->query($query);

    // Vérifier s'il y a des résultats
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nom = $row['nom_compte'];
        $iban = $row['iban'];
        $bic = $row['bic'];
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
            $update_query = "UPDATE _compte_bancaire SET nom_compte = :nom, iban = :iban, bic = :bic WHERE nom_compte = :nom";
            $stmt = $pdo->prepare($update_query);
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

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
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
    <h4>Modification des coordonnées bancaires</h4>
    <div class="form-image-container">
        <div class="form-sectione">
            <div class="IBAN">
                <fieldset>
                    <legend>IBAN</legend>
                    <input type="text" id="IBAN" name="IBAN" value="<?php echo $iban; ?>" placeholder="IBAN" required>
                </fieldset>
            </div>
            <div class="BIC">
                <fieldset>
                    <legend>BIC</legend>
                    <input type="text" id="BIC" name="BIC" value="<?php echo $bic; ?>" placeholder="BIC" required>
                </fieldset>
            </div>
            <div class="nom-du-proprietaire">
                <fieldset>
                    <legend>Nom</legend>
                    <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom" required>
                </fieldset>
            </div>
        </div>
    </div>

    <div class="checkbox">
        <input type="checkbox" id="cgu" name="cgu" required>
        <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
    </div>
    <div class="compte_membre_save_delete">
        <button type="submit" class="submit-btn2">Modifier les coordonnées</button>
    </div>
</form>

<footer class="footer">
    <div class="footer-links">
        <div class="logo">
            <img src="Images/logoBlanc.png" alt="Logo PACT">
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
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Publier</a></li>
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
            <a href="#"><img src="Images/Vector.png" alt="Facebook"></a>
            <a href="#"><img src="Images/Vector2.png" alt="Instagram"></a>
            <a href="#"><img src="Images/youtube.png" alt="YouTube"></a>
            <a href="#"><img src="Images/twitter.png" alt="Twitter"></a>
        </div>
    </div>
</footer>

<script>
    window.onload = function() {
        var alertBox = document.getElementById('alert');
        if (alertBox) {
            alertBox.style.display = 'block'; 
            setTimeout(function() {
                alertBox.style.display = 'none'; 
            }, 3000);
        }
    };
</script>
</body>
</html>
