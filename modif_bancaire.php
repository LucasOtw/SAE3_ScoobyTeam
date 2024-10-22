<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="style_bancaire.css">
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
        <!-- <div class="tab">Informations</div> -->
        <div class="tab">Mot de passe et sécurité</div>
        <!-- <div class="tab">Mes Offres</div> -->
        <div class="tab active">Compte Bancaire</div>
    </div>
</div>

<?php
// Définir le chemin du fichier
$file = 'coordonnees_bancaires.txt';

// Initialiser les valeurs par défaut
$iban = '';
$bic = '';
$nom = '';
$message = ''; // Pour stocker le message d'alerte

// Vérifier si le fichier existe déjà et récupérer les données
if (file_exists($file)) {
    $fileContents = file($file, FILE_IGNORE_NEW_LINES);
    if (count($fileContents) >= 3) {
        $nom = explode(": ", $fileContents[0])[1];
        $iban = explode(": ", $fileContents[1])[1];
        $bic = explode(": ", $fileContents[2])[1];
    }
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $iban = htmlspecialchars($_POST['IBAN']);
    $bic = htmlspecialchars($_POST['BIC']);
    $nom = htmlspecialchars($_POST['nom']);
    $cgu = isset($_POST['cgu']) ? true : false;

    // Vérifier que tous les champs sont bien remplis
    if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
        // Créer le contenu à écrire dans le fichier
        $data = "Nom: $nom\nIBAN: $iban\nBIC: $bic\n---\n";

        // Ouvrir le fichier en mode "write" pour remplacer les données existantes
        $fileHandle = fopen($file, 'w');

        if ($fileHandle) {
            // Écrire les nouvelles données dans le fichier
            fwrite($fileHandle, $data);

            // Fermer le fichier
            fclose($fileHandle);

            $message = "Les informations bancaires ont été modifiées avec succès."; // Message de succès
        } else {
            $message = "Impossible de modifier les informations. Veuillez réessayer.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!-- Affichage du message d'alerte -->
<?php if ($message): ?>
    <div class="alert" id="alert">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form action="#" method="POST">
    <h4>Modification des coordonnées bancaires</h4>
    <div class="form-image-container">
        <div class="form-section">
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
                    <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom"required>
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

<script>
    // Afficher le message d'alerte et le masquer après 3 secondes
    window.onload = function() {
        var alertBox = document.getElementById('alert');
        if (alertBox) {
            alertBox.style.display = 'block'; // Affiche le message
            setTimeout(function() {
                alertBox.style.display = 'none'; // Masque le message après 3 secondes
            }, 3000);
        }
    };
</script>
</body>
</html>
