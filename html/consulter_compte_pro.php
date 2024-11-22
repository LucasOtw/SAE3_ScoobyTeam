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
    p.raison_sociale,num_siren, c.mail, c.telephone, a.adresse_postal, a.complement_adresse, a.code_postal, a.ville
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon compte</title>
    <link rel="stylesheet" href="creation_pro.css?">
</head>
<body>
    <div class="header-membre">
        <!-- Votre contenu de header ici -->
    </div>

    <main class="creation_compte_pro">
        <div class="creation_compte_pro_container">
            <div class="creation_compte_pro_form-section">
                <h1>Modifier mon compte</h1>
                <p>Modifiez vos informations professionnelles !</p>
                <form action="#" method="POST">
                    <div class="crea_pro_raison_sociale_num_siren">
                        <fieldset>
                            <legend>Raison sociale *</legend>
                            <input type="text" id="raison-sociale" name="raison-sociale" value="<?php echo htmlspecialchars($raisonSociale); ?>" placeholder="Raison sociale *" required>
                        </fieldset>
                        <fieldset>
                            <legend>N° de Siren (Professionnel privé)</legend>
                            <input type="text" id="siren" name="siren" value="<?php echo htmlspecialchars($siren); ?>" placeholder="N° de Siren (Professionnel privé)" maxlength="9">
                        </fieldset>
                    </div>

                    <div class="crea_pro_mail_tel">
                        <fieldset>
                            <legend>Email *</legend>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email *" required>
                        </fieldset>
                        <fieldset>
                            <legend>Téléphone *</legend>
                            <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>" placeholder="Téléphone *" required>
                        </fieldset>
                    </div>

                    <fieldset>
                        <legend>Adresse Postale *</legend>
                        <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>" placeholder="Adresse Postale *" required>
                    </fieldset>
                    <fieldset>
                        <legend>Complément d'adresse</legend>
                        <input type="text" id="complement-adresse" name="complement-adresse" value="<?php echo htmlspecialchars($complementAdresse); ?>" placeholder="Complément d'adresse">
                    </fieldset>
                    <fieldset>
                        <legend>Code Postal *</legend>
                        <input type="text" id="code-postal" name="code-postal" value="<?php echo htmlspecialchars($codePostal); ?>" placeholder="Code Postal *" required>
                    </fieldset>
                    <fieldset>
                        <legend>Ville *</legend>
                        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($ville); ?>" placeholder="Ville *" required>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe *</legend>
                        <input type="password" id="password" name="password" placeholder="Mot de passe *" required>
                    </fieldset>

                    <fieldset>
                        <legend>Confirmer le mot de passe *</legend>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmer le mot de passe *" required>
                    </fieldset>

                    <div class="checkbox">
                        <input type="checkbox" id="cgu" name="cgu" required>
                        <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                    </div>

                    <button type="submit" class="submit-btn">Mettre à jour mon compte</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
