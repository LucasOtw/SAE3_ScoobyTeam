<?php
ob_start();
session_start();

if(isset($_SESSION['membre'])){
    session_destroy();
    exit;
}

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
                <form id="connexionForm" action="#" method="POST">
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
                    <span id="error-msg"></span>

                    <button type="submit" id="connectButton" >Se connecter</button>
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

    <div class="modal-overlay" id="modal-overlay"></div>

    <div id="modal-otp" class="otp-confirm-content">
        <form id="envoiCode" action="#" method="POST">
            <p class="texte-boite-perso">Veuillez renseigner le code à 6 chiffres présent dans l'application :</p>
            <input type="text" name="code_otp" id="otpCode" placeholder="Code à 6 chiffres" maxlength="6">
            <input style="margin-top: 63px; margin-left: 170px; background-color: var(--orange);" type="submit" id="submit-code" value="Envoyer le code">
            <p id="errorMsg" style="color: red; display: none; margin-top: 16px;">Le code doit contenir exactement 6 chiffres.</p>
            <!--<button>Se connecter quand même</button>-->
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function (){
            var form = document.getElementById("connexionForm");
            var connectBtn = document.getElementById("connectButton");
            var champOTP = document.getElementById("otpCode");
            var errorMsg = document.getElementById("errorMsg");
            var formOTP = document.getElementById("envoiCode");
            var btnOTP = document.getElementById("submit-code");

            var btnEnvoiQuentin = formOTP ? formOTP.querySelector('button') : null;

            var modalOTP = document.getElementById('modal-otp');

            let codeCompte;

            modalOTP.style.display = "none";

            // GESTION DE L'ENVOI DU FORMULAIRE DE CONNEXION

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                let email = document.getElementById('email');
                let password = document.getElementById('password');

                let emailValue = email.value.trim();
                let passwordValue = password.value.trim();

                error = document.getElementById('error-msg');

                fetch("connexion/script_connexion_pro.php",{
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        mail: emailValue,
                        pwd: passwordValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        error.innerHTML = "";

                        if(!data.otp){
                            window.location.href = "mes_offres.php";
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

                if (champOTP.value.length < 6){
                    console.log("Code OTP trop court !");
                } else {
                    fetch("verification_codeOTP.php",{
                        method: "POST",
                        headers: {
                            'Content-Type' : 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            codeOTP: champOTP.value,
                            code_compte: codeCompte
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success){
                            errorMsg.innerHTML = "";
                            errorMsg.innerHTML = data.message;
                            errorMsg.style.color = "green";
                            errorMsg.style.display = "block";
                            setTimeout(() => {
                                window.location.href = "mes_offres.php";
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

            btnEnvoiQuentin.addEventListener('click', function(){
                form.submit();
            });

        });

        function showPopup(){
            document.getElementById('modal-overlay').style.display = 'block';
            document.getElementById('modal-otp').style.display = 'block';
        }

        function hidePopup(){
            document.getElementById('modal-overlay').style.display = 'none';
            document.getElementById('modal-otp').style.display = 'none';
        }
    </script>
</body>
</html>
