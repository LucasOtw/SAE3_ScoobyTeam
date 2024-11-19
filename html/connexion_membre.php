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

        /* CONNEXION (V2) */

        if(!empty($_POST)){
            echo "bonjour";
            $email = trim(isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : '');
            $password = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';

            // on cherche l'utilisateur dans la base de données

            $existeUser = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail='$email'");
            $existeUser->execute();
            $estUtilisateur = $existeUser->fetch(PDO::FETCH_ASSOC);

            if($estUtilisateur !== false){
                // si l'utilisateur existe, on doit vérifier que c'est un membre
                $verifMembre = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
                $verifMembre->bindValue(":code_compte",$estUtilisateur['code_compte']);
                $verifMembre->execute();

                $estMembre = $verifMembre->fetch(PDO::FETCH_ASSOC);

                if($estMembre !== false){
                    // si c'est un membre...
                    // on vérifie le mot de passe
                    $verifMDP = $dbh->prepare("SELECT mdp FROM tripenarvor._compte WHERE mail = :mail");
                    $verifMDP->bindValue(":mail",$estUtilisateur['mail']);
                    $verifMDP->execute();

                    $vraiMDP = $verifMDP->fetch();

                    if (password_verify($password, $vraiMDP[0])) {
                        // Si le mdp correspond au mdp haché...
                        // On peut connecter l'utilisateur !
                    
                        $_SESSION['membre'] = $estUtilisateur;
                        session_regenerate_id(true);
                        header("location: voir_offres.php");
                    } else {
                        $erreurs[] = "Adresse mail ou mot de passe incorrect";
                    }

                } else {
                    $erreurs[] = "Ce compte n'existe pas";
                }
            } else {
                $erreurs[] = "Ce compte n'existe pas";
            }
        }
        
    ?>
</body>

</html>
