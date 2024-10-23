<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = htmlspecialchars($_POST['mdpAct']);
    $newPassword = htmlspecialchars($_POST['newMdp']);
    $confirmNewPassword = htmlspecialchars($_POST['confNewMdp']);

    try {
        // Connexion à la base de données PostgreSQL via PDO
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=db-scooby-team;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Utilisateur actuel (récupérer email depuis la session)
        $email = $_SESSION['email']; 

        // Requête pour récupérer le mot de passe, raison_sociale et téléphone de l'utilisateur
        $stmt = $pdo->prepare("SELECT mdp, raison_sociale, telephone FROM professionnel WHERE mail = :mail");
        $stmt->bindParam(':mail', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Stocker les informations utilisateur dans la session
            $_SESSION['raison_sociale'] = $user['raison_sociale'];
            $_SESSION['telephone'] = $user['telephone'];

            // Comparer le mot de passe actuel
            if (password_verify($currentPassword, $user['mdp'])) {
                // Vérifier que les nouveaux mots de passe correspondent
                if ($newPassword === $confirmNewPassword) {
                    // Hacher le nouveau mot de passe
                    $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Mettre à jour le mot de passe dans la base de données
                    $updateStmt = $pdo->prepare("UPDATE professionnel SET mdp = :mdp WHERE mail = :mail");
                    $updateStmt->bindParam(':mdp', $newPasswordHashed);
                    $updateStmt->bindParam(':mail', $email);

                    if ($updateStmt->execute()) {
                        echo "Mot de passe mis à jour avec succès.";
                    } else {
                        echo "Erreur lors de la mise à jour du mot de passe.";
                    }
                } else {
                    echo "Les nouveaux mots de passe ne correspondent pas.";
                }
            } else {
                echo "Le mot de passe actuel est incorrect.";
            }
        } else {
            echo "Utilisateur non trouvé.";
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mdp compte membre</title>
    <link rel="stylesheet" href="modif_mdp_pro.css">
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
                <li><a href="#"class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="header">
            <img src="images/Rectangle_3.png" alt="Bannière" class="header-img">
        </div>

        <div class="profile-section">
            <img src="images/Photo_de_Profil.png" alt="Photo de profil" class="profile-img">
            <h1><?php echo htmlspecialchars($_SESSION['raison_sociale']); ?></h1>
        <p><?php echo htmlspecialchars($_SESSION['email']); ?> | <?php echo htmlspecialchars($_SESSION['telephone']); ?></p>
        </div>

        <div class="tabs">
            <div class="tab">Informations</div>
            <div class="tab active">Mot de passe et sécurité</div>
            <div class="tab">Offres</div>
            <div class="tab">Paiement</div>
        </div>
    </div>
    <form action="#" method="POST">
    <fieldset>
        <legend>Entrez votre mot de passe actuel</legend>
        <input type="password" id="mdpAct" name="mdpAct" placeholder="Entrez votre mot de passe actuel" required>
    </fieldset>

    <fieldset>
        <legend>Definissez votre nouveau mot de passe</legend>
        <input type="password" id="newMdp" name="newMdp" placeholder="Definissez votre nouveau mot de passe" required>
    </fieldset>
    <fieldset>

        <legend>Re-tapez votre nouveau mot de passe</legend>
        <input type="password" id="confNewMdp" name="confNewMdp" placeholder="Re-tapez votre nouveau mot de passe" required>
    </fieldset>
    <div class="compte_membre_save_delete">
    <button type="submit" class="submit-btn1">Supprimer mon compte</button>
    <button type="submit" class="submit-btn2">Déconnexion</button>
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
