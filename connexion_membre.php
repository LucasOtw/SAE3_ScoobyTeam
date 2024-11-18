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
    <link rel="icon" type="image/png" href="images/logoPin.png" width="16px" height="32px">
    <link rel="stylesheet" href="connexion_membre.css">
</head>

<body>
    <header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        
        <nav>
            <ul>
                <li><a href="voir_offres.php" >Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="connexion_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <header class="header-tel">
        <div class="logo-tel">
            <img src="images/LogoCouleur.png" alt="PACT Logo">
        </div>
        
    </header>
        <h3 class="connexion_membre_ravi">Ravi de vous revoir !</h3>
    <main class="connexion_membre_main">
        <div class="connexion_membre_container">
            <div class="connexion_membre_form-container">
                <div class="connexion_membre_h2_p">
                    <h2>Se connecter</h2>
                    <p>Se connecter pour accéder à vos favoris</p>
                </div>
                <form action="connexion_membre.php" method="POST">
                    <fieldset>
                        <legend>E-mail</legend>
                        <div class="connexion_membre_input-group">
                            <input type="email" id="email" name="mail" placeholder="E-mail" required>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_membre_input-group">
                            <input type="password" id="password" name="pwd" placeholder="Mot de passe" required>
                        </div>
                    </fieldset>
                    
                    <!--
                    <div class="connexion_membre_remember-group">
                        <div>
                            <input type="submit" value="Enregistrer">
                        </div>
                        <a href="#">Mot de passe oublié ?</a>
                    </div>
                
                -->
                    <div class="connexion_membre_btn_connecter_pas_de_compte">
                        <button type="submit">Se connecter</button>
                        <hr>
                        <p><span class="pas_de_compte">Pas de compte ?<a href="creation_compte_membre.php">Inscription</a></p>
                    </div>
                    
                </form>

            </div>
            <div class="connexion_membre_image-container">
                <img src="images/imageConnexionProEau.png" alt="Image de maison en pierre avec de l'eau">
            </div>
        </div>
    </main>
    <?php
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        if(!empty($_POST)){
            $email = trim(isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : '');
            $password = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';
    
            // on cherche dans la base de données si le compte existe.
    
            $existeUser = $dbh->prepare("SELECT code_compte FROM tripenarvor._compte WHERE mail='$email'");
            $existeUser->execute();

            if($existeUser){
                // si l'utilisateur existe, on vérifie d'abord si il est membre.
                $existeUser = $existeUser->fetch();
                // Car même si l'adresse mail et le mdp sont corrects, si le compte n'est pas lié à un membre, ça ne sert à rien de continuer les vérifications
                $existeMembre = $dbh->prepare("SELECT 1 FROM tripenarvor._membre WHERE code_compte = :code_compte");
                $existeMembre->bindParam(':code_compte',$existeUser[0]);
                $existeMembre->execute();
                if($existeMembre){
                    // Si le membre existe, on vérifie le mot de passe
                    $checkPWD = $dbh->prepare("SELECT mdp FROM tripenarvor._compte WHERE code_compte = :code_compte");
                    $checkPWD->bindParam(':code_compte',$existeUser[0]);
                    $checkPWD->execute();

                    $pwd_compte = $checkPWD->fetch();

                    if(password_verify($password,$pwd_compte[0])){
                        // les mots de passe correspondent
                        // l'utilisateur peut être connecté
                        header('location: voir_offres.php');
                        $_SESSION["compte"] = $existeUser[0];
                    }
                }
            }
        }
        
    ?>
</body>

</html>
