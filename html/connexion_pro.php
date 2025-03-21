<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="icon" type="image/png" href="images/logoPin_orange.png">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* Style d'erreurs */
        .erreur-formulaire-connexion-pro {
            display: none;
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Popup OTP */
        .popup-otp {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .popup-otp-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .popup-otp-content img {
            margin: 20px 0;
            width: 200px;
            height: 200px;
        }
    </style>
</head>

<body>
    <header class="header_pro">
        <div class="logo">
            <a href="voir_offres.php">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="#" class="active">Se connecter</a></li>
            </ul>
        </nav>
    </header>

    <main class="connexion_pro_main">
        <div class="connexion_pro_container">
            <div class="connexion_pro_form-container">
                <div class="connexion_pro_h2_p">
                    <h2>Se connecter</h2>
                    <p>Connectez-vous à votre compte professionnel pour gérer vos annonces, 
                    <br>suivre vos interactions et continuer à développer votre activité !</p>
                </div>
                <form action="#" method="POST">
                    <fieldset>
                        <legend>E-mail</legend>
                        <div class="connexion_pro_input-group">
                            <input type="email" name="email" id="email" placeholder="E-mail" required>
                            <p class="erreur-formulaire-connexion-pro erreur-user-inconnu">L'utilisateur n'existe pas</p>
                            <p class="erreur-formulaire-connexion-pro erreur-pro-inconnu">L'utilisateur n'est pas professionnel</p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_pro_input-group">
                            <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                            <p class="erreur-formulaire-connexion-pro erreur-mot-de-passe-incorect">Mot de passe incorrect</p>
                        </div>
                    </fieldset>

                    <button type="submit">Se connecter</button>
                    <div class="connexion_pro_additional-links">
                        <p><span class="pas_de_compte">Pas de compte ? <a href="creation_pro.php">Inscription</a></span></p>
                        <p class="compte_membre">Un compte <a href="connexion_membre.php">Membre</a> ?</p>
                    </div>
                </form>
            </div>
            <div class="connexion_pro_image-container">
                <img src="images/imageConnexionProEau.png" alt="Image de maison en pierre avec de l'eau">
            </div>
        </div>
    </main>

    <!-- POPUP OTP -->
    <div id="otpPopup" class="popup-otp">
        <div class="popup-otp-content">
            <h3>Validation OTP</h3>
            <p>Scannez ce QR Code avec votre application OTP</p>
            <img src="generate_qr.php" alt="QR Code OTP">
            <br>
            <button onclick="connectAnyway()">Se connecter quand même</button>
        </div>
    </div>

    <script>
        function showError(selector) {
            document.querySelector(selector).style.display = "block";
        }

        function showOTPPopup() {
            document.getElementById("otpPopup").style.display = "flex";
        }

        function connectAnyway() {
            window.location.href = "mes_offres.php";
        }
    </script>

    <?php
    require_once __DIR__ . '/../.security/config.php';

    try {
        $dbh = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        $stmt = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail = :mail");
        $stmt->bindParam(":mail", $email);
        $stmt->execute();
        $compte = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($compte) {
            $stmtPro = $dbh->prepare("SELECT 1 FROM tripenarvor._professionnel WHERE code_compte = :codeCompte");
            $stmtPro->bindParam(":codeCompte", $compte['code_compte']);
            $stmtPro->execute();
            $isPro = $stmtPro->fetch();

            if ($isPro) {
                if (password_verify($password, $compte['mdp'])) {
                    $_SESSION = [];
                    $_SESSION["pro"] = $compte;

                    // Affiche la popup OTP
                    echo "<script>showOTPPopup();</script>";
                    exit;
                } else {
                    echo "<script>showError('.erreur-mot-de-passe-incorect');</script>";
                }
            } else {
                echo "<script>showError('.erreur-pro-inconnu');</script>";
            }
        } else {
            echo "<script>showError('.erreur-user-inconnu');</script>";
        }
    }
    ?>
</body>
</html>
