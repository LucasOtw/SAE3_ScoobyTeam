<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premières coordonnées bancaires</title>
    <link rel="stylesheet" href="ajout_bancaire.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="Images/logoBlanc.png" alt="PACT Logo">
    </div>
    <nav>
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

$host = 'votre_hôte';
$port = '5432'; 
$dbname = 'votre_base_de_données';
$user = 'votre_utilisateur';
$password = 'votre_mot_de_passe';


$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

$iban = '';
$bic = '';
$nom = '';


$query = "SELECT nom_compte, iban, bic FROM _compte_bancaire WHERE code_compte_bancaire = $_SESSION"; 
$result = pg_query_params($conn, $query, array($_SESSION['compte_bancaire_id'])); 
if ($result) {
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $nom = $row['nom_compte'];
        $iban = $row['iban'];
        $bic = $row['bic'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iban = htmlspecialchars($_POST['IBAN']);
    $bic = htmlspecialchars($_POST['BIC']);
    $nom = htmlspecialchars($_POST['nom']);
    $cgu = isset($_POST['cgu']) ? true : false;

    if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
        
        $updateQuery = "UPDATE _compte_bancaire SET nom_compte = $1, iban = $2, bic = $3 WHERE code_compte_bancaire = $4"; 
        $result = pg_query_params($conn, $updateQuery, array($nom, $iban, $bic, $_SESSION['compte_bancaire_id']));
        
        if ($result) {
            echo "Les informations bancaires ont été modifiées avec succès.";
        } else {
            echo "Impossible de modifier les informations. Veuillez réessayer.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}


pg_close($conn);
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
</body>
</html>
