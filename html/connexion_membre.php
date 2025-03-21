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
    /* Style pour la Popup */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4); /* Couleur de fond semi-transparente */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Contenu de la Popup */
    .modal-content {
        background-color: #fefefe;
        padding: 20px;
        border: 1px solid #888;
        width: 40%;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        max-width: 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Le titre de la popup */
    .modal-content h3 {
        font-size: 18px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    /* Le bouton de fermeture de la popup */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }

    /* QR Code */
    .modal-content img {
        margin-bottom: 20px;
        width: 150px;
        height: 150px;
    }

    /* Bouton Envoyer quand même */
    #submitFormBtn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    #submitFormBtn:hover {
        background-color: #45a049;
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
                    
                    <div class="connexion_membre_btn_connecter_pas_de_compte">
                        <button class="se_connecter" type="button" id="connectButton">Se connecter</button>
                        
                        <hr>
                        <div class="connexion_membre_liens_connexion_inscription">
                            <p><span class="pas_de_compte">Pas de compte ?<a href="creation_compte_membre.php">Inscription</a></p>
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

    <!-- Modal Popup QR Code -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Scanne ce QR Code avec Google Authenticator</h3>
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=otpauth://totp/Monsite:example@example.com?secret=JBSWY3DPEHPK3PXP&issuer=MonSite&algorithm=SHA1&digits=6" alt="QR Code OTP">
            <button id="submitFormBtn">Envoyer quand même</button>
        </div>
    </div>

    <script>
        // Récupère le bouton et la modale
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("connectButton");
        var submitBtn = document.getElementById("submitFormBtn");
        var span = document.getElementsByClassName("close")[0];
        var form = document.getElementById("connexionForm");

        modal.style.display = "none";

        // Ouvre la modale lorsque le bouton est cliqué
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Ferme la modale lorsque l'utilisateur clique sur (x)
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Ferme la modale si l'utilisateur clique à l'extérieur de la modale
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Soumettre le formulaire lorsque "Envoyer quand même" est cliqué
        submitBtn.onclick = function() {
            form.submit();
        }
    </script>
</body>

</html>
