<?php
ob_start();
session_start();
require_once __DIR__ . ("/../.security/config.php");
$dbh = new PDO($dsn, $username, $password);

if (!empty($_POST)) {
    $email = trim(isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : '');
    $password = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';

    $stmt = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
        $stmt->bindParam(':code_compte', $user['code_compte']);
        $stmt->execute();
        $membre = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($membre) {
            if (password_verify($password, $user['mdp'])) {
                $_SESSION["membre"] = $user;
                header('location: voir_offres.php');
                exit();
            } else {
                $erreur = 'mot_de_passe';
            }
        } else {
            $erreur = 'membre';
        }
    } else {
        $erreur = 'utilisateur';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se connecter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        .erreur-message {
            display: flex;
            align-items: center;
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .erreur-message img {
            width: 14px;
            height: 14px;
            margin-right: 8px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            border-radius: 10px;
            text-align: center;
            position: relative;
        }
        .modal-content img {
            max-width: 100%;
            margin-bottom: 20px;
        }
        .modal-content button {
            margin-top: 10px;
        }
        .close {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Connexion Membre</h2>

    <form action="connexion_membre.php" method="POST" id="connexionForm">
        <label for="email">E-mail</label><br>
        <input type="email" id="email" name="mail" required>
        <?php if (isset($erreur) && $erreur == 'utilisateur'): ?>
            <div class="erreur-message"><img src="images/icon_informations.png">Utilisateur inconnu</div>
        <?php elseif (isset($erreur) && $erreur == 'membre'): ?>
            <div class="erreur-message"><img src="images/icon_informations.png">L'utilisateur n'est pas un membre</div>
        <?php endif; ?>

        <br><br>

        <label for="password">Mot de passe</label><br>
        <input type="password" id="password" name="pwd" required>
        <?php if (isset($erreur) && $erreur == 'mot_de_passe'): ?>
            <div class="erreur-message"><img src="images/icon_informations.png">Mot de passe incorrect</div>
        <?php endif; ?>

        <br><br>

        <!-- Bouton qui ouvre la modale -->
        <button type="button" id="openModalBtn">Se connecter</button>
    </form>

    <!-- Modale -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Vérification OTP</h3>
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=otpauth://totp/Monsite:example@example.com?secret=JBSWY3DPEHPK3PXP&issuer=MonSite&algorithm=SHA1&digits=6" alt="QR Code OTP">
            <p>1. Scannez le QR Code avec votre app d'authentification</p>
            <p>2. Récupérez le code généré</p>
            <p>3. Cliquez ci-dessous pour continuer</p>
            <!-- Bouton réel de soumission -->
            <button type="submit" id="submitFormBtn">Se connecter quand même</button>
        </div>
    </div>

    <script>
        window.onload = function () {
            const modal = document.getElementById("myModal");
            const openBtn = document.getElementById("openModalBtn");
            const closeBtn = document.getElementsByClassName("close")[0];
            const form = document.getElementById("connexionForm");
            const submitBtn = document.getElementById("submitFormBtn");

            openBtn.onclick = function () {
                modal.style.display = "block";
            }

            closeBtn.onclick = function () {
                modal.style.display = "none";
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            submitBtn.onclick = function () {
                form.submit();
            }
        }
    </script>
</body>
</html>
