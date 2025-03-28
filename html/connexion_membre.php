<?php

ob_start();
session_start();

require_once __DIR__ . ("/../.security/config.php");

$dbh = new PDO($dsn, $username, $password);

if(!empty($_POST)){
    $email = trim(isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : '');
    $password = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';

    // on cherche dans la base de données si le compte existe.
    $existeUser = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail='$email'");
    $existeUser->execute();
    $existeUser = $existeUser->fetch(PDO::FETCH_ASSOC);

    if($existeUser){
        // si l'utilisateur existe, on vérifie d'abord si il est membre.
        // Car même si l'adresse mail et le mdp sont corrects, si le compte n'est pas lié à un membre, ça ne sert à rien de continuer les vérifications
        $existeMembre = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
        $existeMembre->bindParam(':code_compte', $existeUser['code_compte']);
        $existeMembre->execute();

        $existeMembre = $existeMembre->fetch(PDO::FETCH_ASSOC);
        if($existeMembre){
            // Si le membre existe, on vérifie le mot de passe
            $checkPWD = $dbh->prepare("SELECT mdp FROM tripenarvor._compte WHERE code_compte = :code_compte");
            $checkPWD->bindParam(':code_compte', $existeUser['code_compte']);
            $checkPWD->execute();

            $pwd_compte = $checkPWD->fetch();

            if(password_verify($password, $pwd_compte[0])){
                // les mots de passe correspondent
                // l'utilisateur peut être connecté
                header('location: voir_offres.php');
                $_SESSION["membre"] = $existeUser;
            } else /* MDP Invalide */ {
                ?> 
                <style>
                    <?php echo "div.connexion_membre_form-container form fieldset p.erreur-mot-de-passe-incorect"?>{
                        display : flex;
                        align-items: center;
                    }
                    <?php echo ".connexion_membre_main fieldset p.erreur-mot-de-passe-incorect img"?>{
                        width: 10px;
                        height: 10px;
                        margin-right: 10px;
                    }
                    <?php echo ".connexion_membre_main input.erreur-mot-de-passe-incorect"?>{
                        border: 1px solid red;
                    }
                </style>
                <?php
            }
        } else /* Utilisateur Membre Inexistant */ {
            ?> 
            <style>
                <?php echo "div.connexion_membre_form-container form fieldset p.erreur-membre-inconnu"?>{
                    display : flex;
                    align-items: center;
                }
                <?php echo ".connexion_membre_main fieldset p.erreur-membre-inconnu img"?>{
                    width: 10px;
                    height: 10px;
                    margin-right: 10px;
                }
                <?php echo ".connexion_membre_main input.erreur-membre-inconnu"?>{
                    border: 1px solid red;
                }
            </style>
            <?php
        }
    } else /* Utilisateur Inexistant */ {
        ?> 
            <style>
                <?php echo "div.connexion_membre_form-container form fieldset p.erreur-user-inconnu"?>{
                    display : flex;
                    align-items: center;
                }
                <?php echo ".connexion_membre_main fieldset p.erreur-user-inconnu img"?>{
                    width: 10px;
                    height: 10px;
                    margin-right: 10px;
                }
                <?php echo ".connexion_membre_main input.erreur-user-inconnu"?>{
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
    <style>
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px); /* Pour Safari */
            z-index: 998; /* Juste en dessous de la popup */
            display: none;
        }
    </style>
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
                <li><a href="voir_offres.php" >Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="#" class="active">Se connecter</a></li>
            </ul>
        </nav>
    </header>
    <header class="header-tel header_membre">
        <div class="logo-tel">
            <img src="images/LogoCouleur.png" alt="PACT Logo">
        </div>
         <a class="btn_plus_tard" href="voir_offres.php">Plus tard</a>
    </header>
        <h3 class="connexion_membre_ravi">Ravi de vous revoir !</h3>
        
    <main class="connexion_membre_main">
        <div class="connexion_membre_container">
            <div class="connexion_membre_form-container">
                <div class="connexion_membre_h2_p">
                    <h2>Se connecter</h2>
                    <p>Sauvegardez vos annonces favorites, donnez votre avis sur les offres <br>profitez d'une expérience personnalisée.</p>
                </div>
                <form action="connexion_membre.php" method="POST" id="connexionForm">
                    <fieldset>
                        <legend>E-mail</legend>
                        <div class="connexion_membre_input-group">
                            <input class="erreur-user-inconnu erreur-membre-inconnu" type="email" id="email" name="mail" placeholder="E-mail" required>
                            <p class="erreur-formulaire-connexion-membre erreur-user-inconnu"><img src="images/icon_informations.png" alt="icon i pour informations">L'utilisateur n'existe pas</p>
                            <p class="erreur-formulaire-connexion-membre erreur-membre-inconnu"><img src="images/icon_informations.png" alt="icon i pour informations">L'utilisateur n'existe pas en tant que membre </p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_membre_input-group">
                            <input class="erreur-mot-de-passe-incorect" type="password" id="password" name="pwd" placeholder="Mot de passe" required>
                            <p class="erreur-formulaire-connexion-membre erreur-mot-de-passe-incorect"><img src="images/icon_informations.png" alt="icon i pour informations">Mot de passe incorrect</p>
                        </div>
                    </fieldset>
                    <span id="error-msg"></span>
                    
                    <div class="connexion_membre_btn_connecter_pas_de_compte">
                        <button class="se_connecter" type="submit" id="connectButton">Se connecter</button>
                        
                        <hr>
                        <div class="connexion_membre_liens_connexion_inscription">
                            <p><span class="pas_de_compte">Pas de compte ?<a href="creation_compte_membre.php">Inscription</a></p>
                            <p><span class="connexion_compte_pro">Un compte<a href="connexion_pro.php">Pro </a>?</p>
                        </div>
                    </div>
                </form>
            
</div>
            </div>
            <div class="connexion_membre_image-container">
                <img src="images/imageConnexionProEau.png" alt="Image de maison en pierre avec de l'eau">
            </div>
        </div>
    </main>

<div id="modal-otp" class="otp-confirm-content">
    <form id="envoiCode" action="#" method="POST">
        <p class="texte-boite-perso">Code à 6 chiffres :</p>
        <input type="text" name="code_otp" id="otpCode" placeholder="Code à 6 chiffres" maxlength="6">
        <input type="submit" value="Envoyer le code">
        <p id="errorMsg" style="color: red; display: none;">Le code doit contenir exactement 6 chiffres.</p>
        <button>Se connecter quand même</button>
        <p id="countdown"></p>
    </form>
</div>

<div id="modal-backdrop" class="modal-backdrop"></div>

    <script>

// Pour afficher la popup avec l'overlay flou
function showPopup() {
    document.getElementById('modal-backdrop').style.display = 'block';
    document.getElementById('modal-otp').style.display = 'block';
}

// Pour masquer la popup et l'overlay
function hidePopup() {
    document.getElementById('modal-backdrop').style.display = 'none';
    document.getElementById('modal-otp').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {

    var form = document.getElementById("connexionForm");
    var connectBtn = document.getElementById("connectButton");
    var submitBtn = document.getElementById("submitFormBtn");
    var champOTP = document.getElementById('otpCode');
    var errorMsg = document.getElementById('errorMsg');
    var formOTP = document.getElementById('envoiCode');
    var btnEnvoiQuentin = formOTP ? formOTP.querySelector('button') : null;

    var modalOTP = document.getElementById('modal-otp');

    let email = document.getElementById('email');
    let password = document.getElementById('password');

    let emailValue_otp;

    let storedBlocked = JSON.parse(localStorage.getItem("essais_user")) || {};
    let lockTime = JSON.parse(localStorage.getItem("user_lock")) || {};

    let now = Date.now();


    let codeCompte;
    let nbEssais;

    modalOTP.style.display = "none";

    // GESTION DE L'ENVOI DU FORMULAIRE DE CONNEXION

    form.addEventListener('submit',(e) => {
        e.preventDefault();

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
            if(data.success){

                error.innerHTML = "";

                console.log("Authentification validée !");
                console.log(data.message);

                emailValue_otp = emailValue;

                if(!data.otp){
                    window.location.href = "voir_offres.php";
                } else {
                    checkLockTime(emailValue_otp);

                    if(!storedBlocked.hasOwnProperty(emailValue_otp)){
                        storedBlocked[emailValue_otp] = 3;
                    }

                    modalOTP.style.display = "block";
                    codeCompte = data.code_compte;
                    }
            } else {
                console.log("Authentification refusée !");
                console.log(data.message);
                error.innerHTML = data.message;
            }
        })
    });

    // GESTION DE L'ENVOI DU FORMULAIRE OTP

    if (champOTP) {
        champOTP.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, "").slice(0, 6);
            errorMsg.style.display = (this.value.length === 6) ? "none" : "block";
        });
    }

    formOTP.addEventListener('submit', (e) => {
        e.preventDefault();

        if (champOTP.value.length < 6) {
            console.log("Code OTP trop court !");
        } else {
            console.log(storedBlocked);
            if(storedBlocked[emailValue_otp] >= 1){
                fetch("verification_codeOTP.php",{
                    method: "POST",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        codeOTP : champOTP.value,
                        code_compte : codeCompte,
                        nbEssais : storedBlocked[emailValue_otp]
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        console.log(data.message);
                        window.location.href = "voir_offres.php";
                    } else {
                        if(storedBlocked[emailValue_otp] >= 0){
                            storedBlocked[emailValue_otp]--;
                            console.log(storedBlocked);
                            localStorage.setItem("essais_user",JSON.stringify(storedBlocked));
                        }
                        console.log(storedBlocked[emailValue_otp]);
                        console.log(data.message);
                    }
                })
            } else {
                // let blockDuration = 5 * 60 * 1000 // 5 minutes
                let blockDuration = 30 * 1000;
                lockTime[emailValue_otp] = now + blockDuration;
                localStorage.setItem("user_lock",JSON.stringify(lockTime));
            }
        }
    });
    btnEnvoiQuentin.addEventListener('click', function(){
        form.submit();
    });

    function checkLockTime(emailValue) {
        let now = Date.now();
        let lockTime = JSON.parse(localStorage.getItem("user_lock")) || {}; // assure que `user_lock` est un objet
        let remainingTime = Math.ceil((lockTime[emailValue] - now) / 1000);

        console.log(remainingTime);

        if (lockTime[emailValue] && now < lockTime[emailValue]) {
            champOTP.disabled = true; // Désactive le champ OTP si l'utilisateur est verrouillé
        } else if (now >= lockTime[emailValue]) {
            champOTP.disabled = false; // Réactive le champ OTP si le verrouillage est expiré
            let storedBlock = JSON.parse(localStorage.getItem("essais_user")) || {}; // Assure que `essais_user` est un objet
            console.log(storedBlock);
            delete storedBlock[emailValue]; // Supprime l'utilisateur de `essais_user` après déverrouillage
            localStorage.setItem("essais_user", JSON.stringify(storedBlock)); // Sauvegarde les données mises à jour
        }

        // Utilisation de setTimeout avec une fonction de rappel pour éviter la récursion infinie
        setTimeout(function() {
            checkLockTime(emailValue);
        }, 1000);
    }



 /*    if (!form || !connectBtn) {
        console.error("Éléments du formulaire non trouvés !");
        return;
    }

    if (btnEnvoiQuentin) {
        btnEnvoiQuentin.addEventListener('click', function(e) {
            e.preventDefault();
            form.submit();
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        let email = document.getElementById('email');
        let password = document.getElementById('password');

        if (!email || !password) {
            console.error("Champs e-mail ou mot de passe introuvables !");
            return;
        }

        let emailValue = email.value.trim();
        let passwordValue = password.value.trim();

        fetch("connexion/script_connexion_membre.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ mail: emailValue, pwd: passwordValue })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
            } else {
                console.log(data.message);
            }
        })
        .catch(error => {
            console.error("Erreur AJAX :", error);
        });
    });

    if (champOTP) {
        champOTP.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, "").slice(0, 6);
            errorMsg.style.display = (this.value.length === 6) ? "none" : "block";
        });
    }

    if (formOTP) {
        formOTP.addEventListener('submit', (e) => {
            e.preventDefault();
            if (champOTP.value.length < 6) {
                console.log("Code OTP trop court !");
            } else {
                fetch("verification_codeOTP.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ codeOTP: champOTP.value })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.success ? "Code valide !" : "Code invalide !");
                })
                .catch(error => {
                    console.error("Erreur :", error);
                });
            }
        });
    } */

});

    
</script>

</body>

</html>