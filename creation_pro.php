<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire</title>
    <link rel="icon" type="image/png" href="images/logoPin.png" width="16px" height="32px">
    <link rel="stylesheet" href="creation_pro.css?">
</head>

<body>
    <div class="header-membre">
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
    </div>
    

    <div class="header-pro">
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
            <img src="images/logoNoir.png" alt="PACT Logo">
        </div>
        
    </header>
    </div>





    <main class="creation_compte_pro">
        <div class="creation_compte_pro_container">
            <div class="creation_compte_pro_form-section">
                <h1>S’inscrire</h1>
                <p>Créez un compte professionnel pour publier vos annonces, 
                    <br>toucher davantage de clients et faire croître votre activité facilement !</p>
                <form action="#" method="POST">
                    <div class="crea_pro_raison_sociale_num_siren">
                        <fieldset>
                            <legend>Raison sociale *</legend>
                            <input type="text" id="raison-sociale" name="raison-sociale" placeholder="Raison sociale *" required>
                        </fieldset>

                        <fieldset>
                            <legend>N° de Siren (Professionnel privé)</legend>
                            <input type="text" id="siren" name="siren" placeholder="N° de Siren (Professionnel privé)" maxlength=9>
                        </fieldset>
                    </div>
                    <div class="crea_pro_mail_tel">
                        <fieldset>
                            <legend>Email *</legend>
                            <input type="email" id="email" name="email" placeholder="Email *" required>
                        </fieldset>

                        <fieldset>
                            <legend>Téléphone *</legend>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *" required>
                        </fieldset>
                    </div>


                    <fieldset>
                        <legend>Adresse Postale *</legend>
                        <input type="text" id="adresse" name="adresse" placeholder="Adresse Postale *" required>
                    </fieldset>
                    <fieldset>
                        <legend>Complément d'adresse</legend>
                        <input type="text" id="complement-adresse" name="complement-adresse" placeholder="Complément d'adresse">
                    </fieldset>

                    <fieldset>
                        <legend>Code Postal *</legend>
                        <input type="text" id="code-postal" name="code-postal" placeholder="Code Postal *" required>
                    </fieldset>

                    <fieldset>
                        <legend>Ville *</legend>
                        <input type="text" id="ville" name="ville" placeholder="Ville *" required>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe *</legend>
                        <input type="password" id="password" name="password" placeholder="Mot de passe *" required>
                    </fieldset>

                    <fieldset>
                        <legend>Confirmer le mot de passe *</legend>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmer le mot de passe *" required>
                    </fieldset>

                    
                        <div class="checkbox">
                            <input type="checkbox" id="cgu" name="cgu" required>
                            <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                        </div>

                    <button type="submit" class="submit-btn">Créer mon compte <img src="images/flecheBlancheDroite.png" alt="fleche vers la droite"></button>
                </form>
                <div class="creation_compte_pro_other-links">
                    <p>Déjà un compte ? <a href="connexion_pro.php">Connexion</a></p>
                    <p>S’inscrire avec un compte <a href="creation_compte_membre.php">Membre</a></p>
                </div>
            </div>
            <div class="image-section">
                <img src="images/trebeurden.png" alt="Image de Trébeurden">
            </div>
        </div>
        <?php

        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        if(!empty($_POST)){
           // Récupération des variables du formulaire
            
            $raison_sociale = trim(isset($_POST['raison-sociale']) ? htmlspecialchars($_POST['raison-sociale']) : '');
            $siren = trim(isset($_POST['siren']) ? htmlspecialchars($_POST['siren']) : '');
            $email = trim(isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '');
            $telephone = trim(isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '');
            $adresse = trim(isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '');
            $complementAdresse = trim(isset($_POST['complement-adresse']) ? htmlspecialchars($_POST['complement-adresse']) : '');
            $codePostal = trim(isset($_POST['code-postal']) ? htmlspecialchars($_POST['code-postal']) : '');
            $ville = trim(isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : '');
            $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
            $confirmPassword = isset($_POST['confirm-password']) ? htmlspecialchars($_POST['confirm-password']) : '';
            $cgu = isset($_POST['cgu']) ? true : false; // Case à cocher
    
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    
            // Initialisation du tableau d'erreurs
            $erreurs = [];
    
            // 1. Raison sociale : Vérification basique (on peut ajouter des conditions spécifiques si nécessaire)
            if (empty($raison_sociale)) {
                $erreurs[] = "Le champ 'Raison sociale' est requis.";
            }

            // On vérifie si la raison sociale est bien unique (sinon, il y aura des insertions PUIS une erreur)

            $verifRaisonSociale = $dbh->prepare("SELECT 1 FROM tripenarvor._professionnel WHERE raison_sociale = :raison_sociale");
            $verifRaisonSociale->bindValue(":raison_sociale",$raison_sociale);
            $verifRaisonSociale->execute();

            $existeRaisonSociale = $verifRaisonSociale->fetch();

            if($existeRaisonSociale){
                $erreurs[] = "Une entreprise possède déjà cette raison sociale...";
            }
    
            // 2. N° de Siren : Vérifier si le Siren est valide (9 chiffres)
            if (!empty($siren) && !preg_match('/^[0-9]{9}$/', $siren)) {
                $erreurs[] = "Le numéro de Siren doit comporter 9 chiffres.";
            }

            // On vérifie si le numéro Siren est déjà présent dans ._professionnel_prive
            $verifNumeroSiren = $dbh->prepare("SELECT 1 FROM tripenarvor._professionnel_prive WHERE num_siren = :num_siren");
            $verifNumeroSiren->bindValue(":num_siren",$siren);
            $verifNumeroSiren->execute();

            $existeSiren = $verifNumeroSiren->fetch();
            if($existeSiren){
                $erreurs[] = "Ce numéro SIREN est déjà lié à un compte professionnel !";
            }
    
            // 3. Email : Vérifier si l'email est valide
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'adresse email est invalide.";
            }

            // On vérifie aussi l'unicité de l'adresse mail
            $verifAdresseMail = $dbh->prepare("SELECT 1 FROM tripenarvor._compte WHERE mail = :adresse_mail");
            $verifAdresseMail->bindValue(":adresse_mail",$email);
            $verifAdresseMail->execute();

            $existeAdresseMail = $verifAdresseMail->fetch();
            if($existeAdresseMail){
                $erreurs[] = "Cette adresse mail est déjà liée à un compte !";
            }
    
            // 4. Téléphone : Doit être un format valide de 10 chiffres
            if (empty($telephone) || !preg_match('/^[0-9]{10}$/', $telephone)) {
                $erreurs[] = "Le numéro de téléphone doit comporter 10 chiffres.";
            }
    
            // 5. Adresse : Vérifier si elle est non vide
            if (empty($adresse)) {
                $erreurs[] = "Le champ 'Adresse' est requis.";
            }
    
            // 6. Code Postal : Format à 5 chiffres ou 2A/2B pour la Corse
            if (empty($codePostal) || !preg_match('/^([0-9]{5}|2[A-B])$/', $codePostal)) {
                $erreurs[] = "Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B.";
            }
    
            // 7. Ville : Pas de chiffres, pas de caractères spéciaux
            if (empty($ville)) {
                $erreurs[] = "Le champ 'Ville' est requis.";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $ville)) {
                $erreurs[] = "Le nom de la ville ne doit contenir que des lettres, espaces, ou apostrophes.";
            }
    
            // 8. Mot de passe : Minimum 8 caractères, et correspondance avec le champ de confirmation
            if ($password !== $confirmPassword) {
                $erreurs[] = "Les mots de passe ne correspondent pas.";
            }
    
            // 9. Conditions générales d'utilisation (CGU) : Vérification que la case est cochée
            if (!$cgu) {
                $erreurs[] = "Vous devez accepter les conditions générales d'utilisation.";
            }
    
            // Vérifie s'il y a des erreurs
            if (empty($erreurs)) {
                // Pas d'erreurs, on peut procéder à l'enregistrement dans la base de données
                if(empty($complementAdresse) || !$complementAdresse){
                    $complementAdresse = "";
                }

                // On ajoute d'abord l'adresse

                $insererAdresse = $dbh->prepare("INSERT INTO tripenarvor._adresse(adresse_postal,complement_adresse,code_postal,ville) VALUES (:adresse_postal,:complement_adresse,:code_postal,:ville)");
                $insererAdresse->bindParam(':adresse_postal', $adresse);
                $insererAdresse->bindParam(':complement_adresse', $complementAdresse);
                $insererAdresse->bindParam(':code_postal', $codePostal);
                $insererAdresse->bindParam(':ville', $ville);

                if ($insererAdresse->execute()) {
                    // 2. Récupérer le code_adresse nouvellement créé
                    $codeAdresse = $dbh->lastInsertId();
                }

                // on ajoute maintenant le compte

                $creerCompte = $dbh->prepare("INSERT INTO tripenarvor._compte (telephone,mail,code_adresse,mdp) VALUES (:telephone,:mail,:code_adresse,:mdp)");
                
                $creerCompte->bindParam(':telephone', $telephone);
                $creerCompte->bindParam(':mail', $email);
                $creerCompte->bindParam(':mdp', $passwordHashed);
                $creerCompte->bindParam(':code_adresse',$codeAdresse);

                if ($creerCompte->execute()) {
                    // 2. Récupérer le code_adresse nouvellement créé
                    $codeCompte = $dbh->lastInsertId();
                }
                
                // on ajoute ce compte à la table _professionnel

                $insererPro = $dbh->prepare("INSERT INTO tripenarvor._professionnel (code_compte,raison_sociale) VALUES(:code_compte,:raison_sociale)");
                $insererPro->bindParam(":code_compte",$codeCompte);
                $insererPro->bindParam(":raison_sociale",$raison_sociale);
                $insererPro->execute();
                
                if (empty($siren)) {
                    // si il n'y a aucun numéro SIREN
                    $creerProPublique = $dbh->prepare("INSERT INTO tripenarvor._professionnel_publique VALUES (:code_compte)");
                    $creerProPublique->bindParam(":code_compte",$codeCompte);
                    $creerProPublique->execute();
                    
                }
                else {
                    $creerProPrive = $dbh->prepare("INSERT INTO tripenarvor._professionnel_prive VALUES (:code_compte,:num_siren)");
                    $creerProPrive->bindParam(":code_compte",$codeCompte);
                    $creerProPrive->bindParam(":num_siren",$siren);
                    $creerProPrive->execute();
                }

    
                $dbh->query("SELECT nextval('tripenarvor._compte_code_compte_seq');");
                
                // Appelle currval pour récupérer la dernière valeur
                $_SESSION["compte"] = ($dbh->query("SELECT currval('tripenarvor._compte_code_compte_seq');"))->fetchColumn();

                echo "Compte crée avec succès !";
    
    
            } else {
                // Affiche les erreurs
                foreach ($erreurs as $erreur) {
                    echo "<p>$erreur</p>";
                }
            }
        }

    ?>

    </main>
</body>

</html>
