<?php

ob_start();
session_start();

require_once __DIR__ . ("/../.security/config.php");

$dbh = new PDO($dsn, $username, $password);

if (!empty($_POST)) {
    $email = trim(isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : '');
    $password = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';

    // on cherche dans la base de données si le compte existe.
    $existeUser = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail='$email'");
    $existeUser->execute();
    $existeUser = $existeUser->fetch(PDO::FETCH_ASSOC);

    if ($existeUser) {
        // si l'utilisateur existe, on vérifie d'abord si il est membre.
        // Car même si l'adresse mail et le mdp sont corrects, si le compte n'est pas lié à un membre, ça ne sert à rien de continuer les vérifications
        $existeMembre = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
        $existeMembre->bindParam(':code_compte', $existeUser['code_compte']);
        $existeMembre->execute();

        $existeMembre = $existeMembre->fetch(PDO::FETCH_ASSOC);
        if ($existeMembre) {
            // Si le membre existe, on vérifie le mot de passe
            $checkPWD = $dbh->prepare("SELECT mdp FROM tripenarvor._compte WHERE code_compte = :code_compte");
            $checkPWD->bindParam(':code_compte', $existeUser['code_compte']);
            $checkPWD->execute();

            $pwd_compte = $checkPWD->fetch();

            if (password_verify($password, $pwd_compte[0])) {
                // les mots de passe correspondent
                // l'utilisateur peut être connecté
                header('location: voir_offres.php');
                $_SESSION["membre"] = $existeUser;
            } else /* MDP Invalide */ {
                ?>
                <style>
                    <?php echo "div.connexion_membre_form-container form fieldset p.erreur-mot-de-passe-incorect" ?>
                        {
                        display: flex;
                        align-items: center;
                    }

                    <?php echo ".connexion_membre_main fieldset p.erreur-mot-de-passe-incorect img" ?>
                        {
                        width: 10px;
                        height: 10px;
                        margin-right: 10px;
                    }

                    <?php echo ".connexion_membre_main input.erreur-mot-de-passe-incorect" ?>
                        {
                        border: 1px solid red;
                    }
                </style>
                <?php
            }
        } else /* Utilisateur Membre Inexistant */ {
            ?>
            <style>
                <?php echo "div.connexion_membre_form-container form fieldset p.erreur-membre-inconnu" ?>
                    {
                    display: flex;
                    align-items: center;
                }

                <?php echo ".connexion_membre_main fieldset p.erreur-membre-inconnu img" ?>
                    {
                    width: 10px;
                    height: 10px;
                    margin-right: 10px;
                }

                <?php echo ".connexion_membre_main input.erreur-membre-inconnu" ?>
                    {
                    border: 1px solid red;
                }
            </style>
            <?php
        }
    } else /* Utilisateur Inexistant */ {
        ?>
        <style>
            <?php echo "div.connexion_membre_form-container form fieldset p.erreur-user-inconnu" ?>
                {
                display: flex;
                align-items: center;
            }

            <?php echo ".connexion_membre_main fieldset p.erreur-user-inconnu img" ?>
                {
                width: 10px;
                height: 10px;
                margin-right: 10px;
            }

            <?php echo ".connexion_membre_main input.erreur-user-inconnu" ?>
                {
                border: 1px solid red;
            }
        </style>
        <?php
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="styles.css">


</head>

<body>
    <header class="header-pc header_membre">
        <div class="logo-pc">
            <a href="voir_offres.php">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="#" class="active">Se connecter</a></li>
            </ul>
        </nav>
    </header>
    <header class="header-tel header_membre">
        <div class="logo-tel">.
            <a href="voir_offres.php">
                <img src="images/Bouton_retour.png" alt="bouton retour" class="bouton-retour-tel">
            </a>
            <img src="images/LogoCouleur.png" alt="PACT Logo">
        </div>
    </header>
    <h3 class="connexion_membre_ravi">Ravi de vous revoir !</h3>

    <main class="connexion_membre_main">
        <div class="connexion_membre_container">
            <div class="connexion_membre_form-container">
                <div class="connexion_membre_h2_p">
                    <h2>Se connecter</h2>
                    <p>Sauvegardez vos annonces favorites, donnez votre avis sur les offres <br>profitez d'une
                        expérience personnalisée.</p>
                </div>
                <form action="connexion_membre.php" method="POST" id="connexionForm">
                    <fieldset>
                        <legend>E-mail</legend>
                        <div class="connexion_membre_input-group">
                            <input class="erreur-user-inconnu erreur-membre-inconnu" type="email" id="email" name="mail"
                                placeholder="E-mail" required>
                            <p class="erreur-formulaire-connexion-membre erreur-user-inconnu"><img
                                    src="images/icon_informations.png" alt="icon i pour informations">L'utilisateur
                                n'existe pas</p>
                            <p class="erreur-formulaire-connexion-membre erreur-membre-inconnu"><img
                                    src="images/icon_informations.png" alt="icon i pour informations">L'utilisateur
                                n'existe pas en tant que membre </p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_membre_input-group">
                            <input class="erreur-mot-de-passe-incorect" type="password" id="password" name="pwd"
                                placeholder="Mot de passe" required>
                            <p class="erreur-formulaire-connexion-membre erreur-mot-de-passe-incorect"><img
                                    src="images/icon_informations.png" alt="icon i pour informations">Mot de passe
                                incorrect</p>
                        </div>
                    </fieldset>
                    <span id="error-msg"></span>

                    <div class="connexion_membre_btn_connecter_pas_de_compte">
                        <button class="se_connecter" type="submit" id="connectButton">Se connecter</button>

                        <hr>
                        <div class="connexion_membre_liens_connexion_inscription">
                            <p><span class="pas_de_compte">Pas de compte ?<a
                                        href="creation_compte_membre.php">Inscription</a></p>
                            <p><span class="connexion_compte_pro">Un compte<a href="connexion_pro.php">Pro </a>?</p>
                        </div>
                    </div>
                </form>

            </div>
            <div class="connexion_membre_image-container">
                <img src="images/imageConnexionProEau.png" alt="Image de maison en pierre avec de l'eau">
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="modal-overlay"></div>

    <div id="modal-otp" class="otp-confirm-content">
        <form id="envoiCode" action="#" method="POST">
            <p class="texte-boite-perso">Veuillez renseigner le code à 6 chiffres présent dans votre application d'authentification :</p>
            <input type="text" name="code_otp" id="otpCode" placeholder="Code à 6 chiffres" maxlength="6">
            <input style=" margin-top: 20px;
    margin-left: 170px;" type="submit" id="submit-code" value="Envoyer le code">
            <p id="errorMsg" style="color: red; display: none;margin-top: 16px;">Le code doit contenir exactement 6 chiffres.</p>
            <!--<button>Se connecter quand même</button>-->
        </form>
    </div>


    <script>

        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById("connexionForm");
            var connectBtn = document.getElementById("connectButton");
            var champOTP = document.getElementById('otpCode');
            var errorMsg = document.getElementById('errorMsg');
            var formOTP = document.getElementById('envoiCode');
            var btnOTP = document.getElementById('submit-code');

            var btnEnvoiQuentin = formOTP ? formOTP.querySelector('button') : null;

            var modalOTP = document.getElementById('modal-otp');

            let codeCompte;
            let nbEssais = 3;

            modalOTP.style.display = "none";

            // GESTION DE L'ENVOI DU FORMULAIRE DE CONNEXION

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                let email = document.getElementById('email');
                let password = document.getElementById('password');

                let emailValue = email.value.trim();
                let passwordValue = password.value.trim();

                error = document.getElementById('error-msg');

                fetch("connexion/script_connexion_membre.php", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ mail: emailValue, pwd: passwordValue })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {

                            error.innerHTML = "";

                            if (!data.otp) {
                                window.location.href = "voir_offres.php";
                            } else {
                                showPopup();
                                codeCompte = data.code_compte;
                            }
                        } else {
                            error.innerHTML = data.message;
                        }
                    })
            });

            // GESTION DE L'ENVOI DU FORMULAIRE OTP

            btnOTP.disabled = true;

            if (champOTP) {
                champOTP.addEventListener("input", function () {
                    this.value = this.value.replace(/\D/g, "").slice(0, 6);
                    errorMsg.style.display = (this.value.length === 6) ? "none" : "block";
                    btnOTP.disabled = (this.value.length === 6) ? false : true;
                });
            }

            formOTP.addEventListener('submit', (e) => {
                e.preventDefault();

                if (champOTP.value.length < 6) {
                    console.log("Code OTP trop court !");
                } else {
                    fetch("verification_codeOTP.php", {
                        method: "POST",
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            codeOTP: champOTP.value,
                            code_compte: codeCompte
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                errorMsg.innerHTML = "";
                                errorMsg.innerHTML = data.message;
                                errorMsg.style.color = "green";
                                errorMsg.style.display = "block";
                                setTimeout(() => {
                                    window.location.href = "voir_offres.php";
                                }, 1500);
                            } else {
                                errorMsg.innerHTML = "";
                                errorMsg.innerHTML = data.message;
                                errorMsg.style.color = "red";
                                errorMsg.style.display = "block";
                            }
                        })
                }
            });

            btnEnvoiQuentin.addEventListener('click', function () {
                form.submit();
            });

        });

        // Pour afficher la popup et l'overlay
        function showPopup() {
            document.getElementById('modal-overlay').style.display = 'block';
            document.getElementById('modal-otp').style.display = 'block';
        }

        // Pour masquer la popup et l'overlay
        function hidePopup() {
            document.getElementById('modal-overlay').style.display = 'none';
            document.getElementById('modal-otp').style.display = 'none';
        }

    </script>

</body>

</html>
