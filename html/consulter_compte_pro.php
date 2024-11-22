<<<<<<< HEAD
<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}


// Connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";
$dbh = new PDO($dsn, $username, $password);

// Récupération de l'ID de l'utilisateur connecté
$codeCompte = $_SESSION['pro'];

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $raisonSociale = $_POST['raison-sociale'];
    $siren = $_POST['siren'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $complementAdresse = $_POST['complement-adresse'];
    $codePostal = $_POST['code-postal'];
    $ville = $_POST['ville'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $cgu = isset($_POST['cgu']) ? $_POST['cgu'] : '';

    // Validation des champs
    $errors = [];
    
    // Validation de la raison sociale
    if (empty($raisonSociale)) {
        $errors[] = "La raison sociale est obligatoire.";
    }
    
    // Validation du numéro de Siren (doit être composé de 9 chiffres)
    if (!empty($siren) && !preg_match('/^\d{9}$/', $siren)) {
        $errors[] = "Le numéro de Siren doit être composé de 9 chiffres.";
    }

    // Validation de l'email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est obligatoire et doit être valide.";
    }

    // Validation du téléphone (doit être composé de 10 chiffres)
    if (!empty($telephone) && !preg_match('/^\d{10}$/', $telephone)) {
        $errors[] = "Le numéro de téléphone doit être composé de 10 chiffres.";
    }

    // Validation de l'adresse postale
    if (empty($adresse)) {
        $errors[] = "L'adresse postale est obligatoire.";
    }

    // Validation du code postal (doit être composé de 5 chiffres)
    if (!empty($codePostal) && !preg_match('/^\d{5}$/', $codePostal)) {
        $errors[] = "Le code postal doit être composé de 5 chiffres.";
    }

    // Validation de la ville
    if (empty($ville)) {
        $errors[] = "La ville est obligatoire.";
    }

    // Validation du mot de passe
    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }

    // Validation de la confirmation du mot de passe
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Validation de l'acceptation des CGU
    if (empty($cgu)) {
        $errors[] = "Vous devez accepter les conditions générales d'utilisation.";
    }

    // Si des erreurs existent, on les affiche et on ne fait pas la mise à jour
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Mettre à jour la base de données avec les nouvelles informations
        try {
            // Mise à jour des informations dans les tables
            $stmt = $dbh->prepare("UPDATE tripenarvor._compte
                SET mail = :email, telephone = :telephone, password = :password
                WHERE code_compte = :code_compte");
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':telephone', $telephone);
            $stmt->bindValue(':password', $hashedPassword);
            $stmt->bindValue(':code_compte', $codeCompte);
            $stmt->execute();

            // Mise à jour de la raison sociale et du numéro Siren
            $stmt = $dbh->prepare("UPDATE tripenarvor._professionnel
                SET raison_sociale = :raison_sociale, num_siren = :num_siren
                WHERE code_compte = :code_compte");
            $stmt->bindValue(':raison_sociale', $raisonSociale);
            $stmt->bindValue(':num_siren', $siren);
            $stmt->bindValue(':code_compte', $codeCompte);
            $stmt->execute();

            // Mise à jour de l'adresse
            $stmt = $dbh->prepare("UPDATE tripenarvor._adresse
                SET adresse_postal = :adresse, complement_adresse = :complement_adresse,
                    code_postal = :code_postal, ville = :ville
                WHERE code_adresse = (SELECT code_adresse FROM tripenarvor._compte WHERE code_compte = :code_compte)");
            $stmt->bindValue(':adresse', $adresse);
            $stmt->bindValue(':complement_adresse', $complementAdresse);
            $stmt->bindValue(':code_postal', $codePostal);
            $stmt->bindValue(':ville', $ville);
            $stmt->bindValue(':code_compte', $codeCompte);
            $stmt->execute();

            echo "<p style='color:green;'>Vos informations ont été mises à jour avec succès.</p>";

        } catch (Exception $e) {
            echo "<p style='color:red;'>Erreur lors de la mise à jour : " . $e->getMessage() . "</p>";
        }
    }
}

// Récupérer les informations existantes pour l'affichage initial
$stmt = $dbh->prepare("SELECT 
    p.raison_sociale, p.num_siren, c.mail, c.telephone, a.adresse_postal, a.complement_adresse, a.code_postal, a.ville
FROM tripenarvor._compte c
JOIN tripenarvor._professionnel p ON c.code_compte = p.code_compte
JOIN tripenarvor._adresse a ON c.code_adresse = a.code_adresse
WHERE c.code_compte = :code_compte");
$stmt->bindValue(":code_compte", $codeCompte);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($userData) {
    // Affectation des valeurs récupérées
    $raisonSociale = $userData['raison_sociale'];
    $siren = $userData['num_siren'];
    $email = $userData['mail'];
    $telephone = $userData['telephone'];
    $adresse = $userData['adresse_postal'];
    $complementAdresse = $userData['complement_adresse'];
    $codePostal = $userData['code_postal'];
    $ville = $userData['ville'];
} else {
    echo "Aucun compte trouvé pour cet utilisateur.";
    exit;
}
?>

=======
>>>>>>> 28e7c2a35ae85adbcc6220fabb149cd40077eaf7
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mdp compte membre</title>
    <link rel="stylesheet" href="consulter_compte_pro.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PACT Logo">
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
            <img src="images/Rectangle_3.png" alt="Bannière" class="header-img">
        </div>

        <div class="profile-section">
            <img src="images/offres/BreizhShelter.jpg" alt="Photo de profil" class="profile-img">
            <h1>Ti Al Lannec</h1>
            <p>tiallannec@gmail.com | 07.98.76.54.12</p>
        </div>

        <div class="tabs">
            <div class="tab active">Informations</div>
            <div class="tab">Mot de passe et sécurité</div>
            <div class="tab">Offres</div>
            <div class="tab">Paiement</div>
        </div>
    </div>

    <form action="#" method="POST">
        <div class="crea_pro_raison_sociale_num_siren">
            <fieldset>
                <legend>Raison Sociale</legend>
                <input type="text" id="raison-sociale" name="raison-sociale" placeholder="Raison Sociale*" required>
            </fieldset>

            <fieldset>
                <legend>N° de Siren</legend>
                <input type="text" id="siren" name="siren" placeholder="N° de Siren*" required>
            </fieldset>
        </div>

        <div class="crea_pro_mail_tel">
            <fieldset>
                <legend>Email</legend>
                <input type="email" id="email" name="email" placeholder="Email*" required>
            </fieldset>

            <fieldset>
                <legend>Téléphone</legend>
                <input type="tel" id="telephone" name="telephone" placeholder="Téléphone*" required>
            </fieldset>

            <fieldset>
                <legend>Adresse Postale</legend>
                <input type="text" id="adresse" name="adresse" placeholder="Adresse postale*" required>
            </fieldset>

            <fieldset>
                <legend>Complément d'adresse</legend>
                <input type="text" id="comp_adresse" name="comp_adresse" placeholder="Complément d'adresse*" required>
            </fieldset>

            <fieldset>
                <legend>Ville</legend>
                <input type="text" id="ville" name="ville" placeholder="Ville*" required>
            </fieldset>
        </div>

        <div class="compte_membre_save_delete">
            <a href="deconnexion_compte_pro.php" class="submit-btn1">Déconnexion</a>
            <button type="submit" class="submit-btn3">Enregistrer</button>
        </div>
    </form>

    <footer class="footer_detail_avis">
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
