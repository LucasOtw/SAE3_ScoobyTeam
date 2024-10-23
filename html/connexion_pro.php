<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="icon" type="image/png" href="images/logoPin.png" width="16px" height="32px">
    <link rel="stylesheet" href="connexion_pro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
</head>

<body>
    <header class="header_pro">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="creation_offre1.php">Publier</a></li>
                <li><a href="#" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <main class="connexion_pro_main">
        <div class="connexion_pro_container">
            <div class="connexion_pro_form-container">
                <div class="connexion_pro_h2_p">
                    <h2>Se connecter</h2>
                    <p>Se connecter pour accéder à vos favoris</p>
                </div>
                <form action="#">
                    <fieldset>
                        <legend>E-mail</legend>
                        <div class="connexion_pro_input-group">
                            <input type="email" id="email" placeholder="E-mail" required>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_pro_input-group">
                            <input type="password" id="password" placeholder="Mot de passe" required>
                        </div>
                    </fieldset>
                <!--
                    <div class="connexion_pro_remember-group">
                        <div>
                            <input type="checkbox" id="remember" checked>
                            <label class="connexion_pro_lab_enreg" for="remember">Enregistrer</label>
                        </div>
                        <a href="#">Mot de passe oublié ?</a>
                    </div>
                -->
                    <button type="submit">Se connecter</button>
                    <div class="connexion_pro_additional-links">
                        <p><span class="pas_de_compte">Pas de compte ?<a href="creation_pro.php">Inscription</a></p>

                        <p class="compte_membre"><a href="connexion_membre.php">Un compte Membre&nbsp?</a></p>
                    </div>
                </form>

            </div>
            <div class="connexion_pro_image-container">
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

        $checkTables = $dbh->prepare('SHOW TABLES');
        $checkTables->execute();
        var_dump($checkTables->fetchAll());

        $email = trim(isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '');
        $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
        
        $mailDansBdd = $dbh -> prepare("select code_compte from _professionnel NATURAL JOIN _compte where mail='$email';");
        $mailDansBdd -> execute();

        $mdpDansBdd = $dbh -> prepare("select mdp from _professionnel NATURAL JOIN _compte where mail='$email';");
        $mailDansBdd -> execute();
        
        $passwordHashedFromDB = password_hash($mdpDansBdd, PASSWORD_DEFAULT);

        if ($mailDansBdd != NULL){
            if (password_verify($password, $passwordHashedFromDB)) {
                // Le mot de passe est correct
                // Connexion
                session_start();
                $_SESSION["compte"] = $mailDansBdd;
                // redirection
                header('Location: modif_infos_pro.php');
                exit();
            } else {
                // Le mot de passe est incorrect
                ?>
                <p>
                <?php
                    echo "Le mot de passe est incorrect";
                ?>
            </p>
            <?php
            }
        } else {
            // Mail inconnu
            ?>
            <p>
                <?php
                    echo "Le mail est inconnu";
                ?>
            </p>
            <?php
        }

        
    ?>
</body>

</html>
