<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="ajout_bancaire.css">
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="images/logoBlanc.png" alt="PACT Logo">
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
        <img src="images/Fond.png" alt="Bannière" class="header-img">
    </div>

    <div class="profile-section">
        <img src="images/pp.png" alt="Photo de profil" class="profile-img">

        <?php
        // Variables pour stocker les informations de profil
        $raisonSociale = '';
        $userEmail = '';
        $userPhone = '';
        $codeCompte = '';
        $codeCompteBancaire = '';
        
        // Connexion à la base de données
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparer la requête pour récupérer les informations de profil
            $query = "SELECT raison_sociale, telephone, mail, code_compte, code_compte_bancaire FROM tripenarvor._professionnel NATURAL JOIN tripenarvor._compte";
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            // Vérifier s'il y a des résultats
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $raisonSociale = $row['raison_sociale'];
                $userEmail = $row['mail'];
                $userPhone = $row['telephone'];
                $codeCompte = $row['code_compte'];
                $codeCompteBancaire = $row['code_compte_bancaire'];
            }

        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }

        // Fermer la connexion
        $pdo = null;
        ?>

        <h1><?php echo $raisonSociale; ?></h1>
        <p><?php echo $userEmail; ?> | <?php echo $userPhone; ?></p>
    </div>
    <div class="tabs">
        <div class="tab">Mot de passe et sécurité</div>
        <div class="tab active">Compte Bancaire</div>
    </div>
</div>

<?php
$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iban = htmlspecialchars($_POST['IBAN']);
    $bic = htmlspecialchars($_POST['BIC']);
    $nom = htmlspecialchars($_POST['nom']);
    $cgu = isset($_POST['cgu']) ? true : false;

    // Vérifier que tous les champs sont remplis
    if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
        // Mettre à jour les informations bancaires dans la base de données
        try {
            $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $update_query = "UPDATE tripenarvor._compte_bancaire SET nom_compte = :nom, iban = :iban, bic = :bic WHERE code_compte_bancaire = :code_compte_bancaire";
            $stmt = $pdo->prepare($update_query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':iban', $iban);
            $stmt->bindParam(':bic', $bic);
            $stmt->bindParam(':code_compte_bancaire', $codeCompteBancaire);

            if ($stmt->execute()) {
                $message = "Les informations bancaires ont été modifiées avec succès."; 
            } else {
                $message = "Impossible de modifier les informations. Veuillez réessayer.";
            }
        } catch (PDOException $e) {
            $message = "Erreur de mise à jour : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!-- Affichage des messages -->
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form action="#" method="POST">
    <h4>Modification des coordonnées bancaires</h4>
    <div class="form-image-container">
        <div class="form-sectione">
            <div class="IBAN">
                <fieldset>
                    <legend>IBAN</legend>
                    <input type="text" id="IBAN" name="IBAN" value="<?php echo isset($iban) ? $iban : ''; ?>" placeholder="IBAN" required>
                </fieldset>
            </div>
            <div class="BIC">
                <fieldset>
                    <legend>BIC</legend>
                    <input type="text" id="BIC" name="BIC" value="<?php echo isset($bic) ? $bic : ''; ?>" placeholder="BIC" required>
                </fieldset>
            </div>
            <div class="nom-du-proprietaire">
                <fieldset>
                    <legend>Nom</legend>
                    <input type="text" id="nom" name="nom" value="<?php echo isset($nom) ? $nom : ''; ?>" placeholder="Nom" required>
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
            <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
            <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
            <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
            <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
        </div>
    </div>
</footer>

</body>
</html>
