<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajout coordonnées bancaires</title>
    <link rel="stylesheet" href="ajout_bancaire.css">
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
<div class="container">
    <div class="header">
        <img src="images/Fond.png" alt="Bannière" class="header-img">
    </div>

    <div class="profile-section">
        <img src="images/pp.png" alt="Photo de profil" class="profile-img">
        <h1>Ti al Lannec</h1>
        <p>ti.al.lannec@gmail.com | 07.98.76.54.12</p>
    </div>
    <div class="tabs">
        <div class="tab"><a href="modif_mdp_pro.php"  >Mot de passe et sécurité</a></div>
        <div class="tab active">Compte Bancaire</div>
    </div>
</div>

<?php
// Détails de la connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

try {
    // Créer une instance PDO
    $pdo = new PDO($dsn, $username, $password);

    // Définir le mode d'erreur PDO à Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Variables pour stocker les informations bancaires
    $iban = '';
    $bic = '';
    $nom = '';

    // Préparer la requête pour récupérer les informations bancaires
    $query = "SELECT nom_compte, iban, bic FROM tripenarvor._compte_bancaire WHERE code_compte_bancaire = :compte_bancaire_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':compte_bancaire_id', $_SESSION['compte_bancaire_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier s'il y a des résultats
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
            $updateQuery = "UPDATE tripenarvor._compte_bancaire SET nom_compte = :nom, iban = :iban, bic = :bic WHERE code_compte_bancaire = :compte_bancaire_id";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':iban', $iban, PDO::PARAM_STR);
            $stmt->bindParam(':bic', $bic, PDO::PARAM_STR);
            $stmt->bindParam(':compte_bancaire_id', $_SESSION['compte_bancaire_id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Les informations bancaires ont été modifiées avec succès.";
            } else {
                echo "Impossible de modifier les informations. Veuillez réessayer.";
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}

// Fermer la connexion
$pdo = null;
?>

<form action="#" method="POST">
    <h4>Modification des coordonnées bancaires</h4>
    <div class="form-image-container">
        <div class="form-section">
            <div class="IBAN">
                <fieldset>
                    <legend>IBAN</legend>
                    <input type="text" id="IBAN" name="IBAN" value="<?php echo $iban; ?>" placeholder="IBAN (obligatoire)" required>
                </fieldset>
            </div>
            <div class="BIC">
                <fieldset>
                    <legend>BIC</legend>
                    <input type="text" id="BIC" name="BIC" value="<?php echo $bic; ?>" placeholder="Veuillez entrer votre BIC" required>
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
        <button type="submit" class="submit-btn2">Ajouter vos coordonnées</button>
    </div>
</form>

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
