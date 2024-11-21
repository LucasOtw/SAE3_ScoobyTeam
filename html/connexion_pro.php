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
    <link rel="stylesheet" href="connexion_pro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
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
                <li><a href="connexion_pro.php" class="active">Mon Compte</a></li>
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
                            <p class="erreur-formulaire-connexion-pro erreur-user-inconnu"><img src="images/icon_informations.png" alt="icon i pour informations">L'utilisateur n'existe pas</p>
                            <p class="erreur-formulaire-connexion-pro erreur-pro-inconnu"><img src="images/icon_informations.png" alt="icon i pour informations">L'utilisateur n'existe pas en tant que professionnel </p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_pro_input-group">
                            <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                            <p class="erreur-formulaire-connexion-pro erreur-mot-de-passe-incorect"><img src="images/icon_informations.png" alt="icon i pour informations">Mot de passe incorrect</p>
                        </div>
                    </fieldset>

                    <button type="submit">Se connecter</button>
                    <div class="connexion_pro_additional-links">
                        <p><span class="pas_de_compte">Pas de compte ?<a href="creation_pro.php">Inscription</a></span></p>
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
    // Connexion à la base de données
    $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
    $username = "sae";
    $password = "philly-Congo-bry4nt";

    try {
        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
    // Vérifier si le formulaire a été soumis
    if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)){
        $email = trim(htmlspecialchars($_POST['email']));
        $password = trim(htmlspecialchars($_POST['password']));

        // on vérifie que l'adresse mail soit reliée à un compte

        $codeCompte = $dbh->prepare("SELECT code_compte FROM tripenarvor._compte WHERE mail = :mail");
        $codeCompte->bindParam(":mail",$email);
        $codeCompte->execute();
        $codeCompte = $codeCompte->fetch(PDO::FETCH_ASSOC);

        if ($codeCompte) {
            // si un compte est trouvé, on vérifie maintenant qu'il soit professionnel
            // var_dump($codeCompte[0]);  // Commenté ou supprimé pour éviter la sortie avant header
            $estPro = $dbh->prepare("SELECT 1 FROM tripenarvor._professionnel WHERE code_compte = :codeCompte");
            $estPro->bindParam(":codeCompte", $codeCompte[0]);
            $estPro->execute();
            $estPro = $estPro->fetch();
        
            if ($estPro) {
                // si le compte est professionnel, alors on vérifie son mot de passe
                $mdpPro = $dbh->prepare("SELECT mdp FROM tripenarvor._compte WHERE code_compte = :codeCompte");
                $mdpPro->bindParam("codeCompte", $codeCompte[0]);
                $mdpPro->execute();
                $mdpPro = $mdpPro->fetch();
                echo "MDP : ".$password."";
                echo "MDP2 : ".$mdpPro[0]."";
                var_dump(password_verify($password,$mdpPro[0]));
                if (password_verify(trim($password), trim($mdpPro[0]))) {
                    // si le mot de passe est correct
                    echo "TA MERE";
                    $_SESSION["pro"] = $codeCompte; // Stocke le code_compte dans la session
                    header('Location: mes_offres.php'); // Redirection
                    exit; // Assure que le script s'arrête après la redirection
                } else /* Mot de passe Invalide */{
                    ?> 
                        <style>
                            <?php echo ".connexion_pro_main fieldset p.erreur-mot-de-passe-incorect"?>{
                                display : flex;
                                align-items: center;
                            }
                            <?php echo ".connexion_pro_main fieldset p.erreur-mot-de-passe-incorect img"?>{
                                width: 10px;
                                height: 10px;
                                margin-right: 10px;
                            }
                            <?php echo ".connexion_pro_main input.erreur-mot-de-passe-incorect"?>{
                                border: 1px solid red;
                            }
                        </style>
                        <?php
                }
            } else /* Mail Pas Pro */ {
                ?> 
                        <style>
                            <?php echo ".connexion_pro fieldset p.erreur-pro-inconnu"?>{
                                display : flex;
                                align-items: center;
                            }
                            <?php echo ".connexion_pro fieldset p.erreur-pro-inconnu img"?>{
                                width: 10px;
                                height: 10px;
                                margin-right: 10px;
                            }
                            <?php echo ".connexion_pro input.erreur-pro-inconnu"?>{
                                border: 1px solid red;
                            }
                        </style>
                        <?php
            }
        } else /* Mail Inconnu */{
            ?> 
                        <style>
                            <?php echo ".connexion_membre_main fieldset p.erreur-user-inconnu"?>{
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
</body>
</html>
