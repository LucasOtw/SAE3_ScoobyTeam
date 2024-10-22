<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premières coordonnées bancaires</title>
    <link rel="stylesheet" href="style_bancaire.css">
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
// Connexion à la base de données PostgreSQL
$host = "localhost"; // ou l'adresse de votre serveur
$port = "5432"; // Port par défaut de PostgreSQL
$dbname = "votre_base_de_donnees";
$user = "votre_nom_utilisateur";
$password = "votre_mot_de_passe";

// Créer la connexion
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Vérifier la connexion
if (!$conn) {
    die("Échec de la connexion: " . pg_last_error());
}

// Initialiser les valeurs par défaut
$iban = '';
$bic = '';
$nom = '';

// Récupérer les données de la base de données
$result = pg_query($conn, "SELECT nom, iban, bic FROM tripenarvor.professionnel_prive WHERE code_compte = 1"); // Ajustez la condition selon votre logique

if ($result) {
    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $nom = $row['nom'];
        $iban = $row['iban'];
        $bic = $row['bic'];
    }
}

// Insertion initiale des données (si nécessaire)
$insertQuery = "INSERT INTO tripenarvor.professionnel_prive (telephone, mail, adresse_postal, complement_adresse, code_postal, ville, mdp, raison_sociale, num_siren)
VALUES ('0123456789', 'pro@gmail.com', '123 Rue de la Liberté', 'Apt 2', '29870', 'Paris', 'password456', 'Entreprise Privée', '123456789')";
pg_query($conn, $insertQuery);

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $iban = htmlspecialchars($_POST['IBAN']);
    $bic = htmlspecialchars($_POST['BIC']);
    $nom = htmlspecialchars($_POST['nom']);
    $cgu = isset($_POST['cgu']) ? true : false;

    if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
        // Préparer la requête de mise à jour
        $updateQuery = "UPDATE tripenarvor.professionnel_prive
                        SET iban = $1, bic = $2, nom_compte = $3, code_adresse = (SELECT code_adresse FROM tripenarvor._compte WHERE code_compte = 1)
                        WHERE code_compte = 1";
        
        $result = pg_prepare($conn, "update_query", $updateQuery);
        $result = pg_execute($conn, "update_query", array($iban, $bic, $nom));

        if ($result) {
            echo "Les informations bancaires ont été mises à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour des informations: " . pg_last_error($conn);
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

<footer class="footer_detail_avis">
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
