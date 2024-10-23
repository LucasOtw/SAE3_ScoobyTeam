<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="icon" type="image/png" href="images/logoPin.png" width="16px" height="32px">
    <link rel="stylesheet" href="creation_compte_membre.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
</head>

<body>
    <header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <header class="header-tel">
        <div class="logo-tel">
            <img src="images/LogoCouleur.png" alt="PACT Logo">
        </div>

    </header>

    <main class="creation_compte_membre">
        <h3 class="creation_compte_membre_bienvenue">Bienvenue !</h3>
        <div class="creation_compte_membre_container">
            <div class="creation_compte_membre_form-section">
                <h1>S’inscrire</h1>
                <p>Créer un compte pour membrefiter de l’expérience PACT</p>
                <form action="creation_compte_membre.php" method="POST">
                    <div class="crea_membre_raison_sociale_num_siren">
                        <fieldset>
                            <legend>Prénom *</legend>
                            <div class="input-icon">
                                <input type="text" id="prenom" name="prenom" placeholder="Prénom *" required>
                                <img src="images/icones/personne.png" alt="icones silhouette d'une personne" class="input-icon-img">
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Nom *</legend>
                            <div class="input-icon">
                                <input type="text" id="nom" name="nom" placeholder="Nom *" required>
                                <img src="images/icones/personne.png" alt="icones silhouette d'une personne" class="input-icon-img">
                            </div>
                        </fieldset>
                    </div>
                    <fieldset>
                        <legend>Pseudo *</legend>
                        <div class="input-icon">
                            <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo *" required>
                            <img src="images/icones/personne.png" alt="icones silhouette d'une personne" class="input-icon-img">
                        </div>
                    </fieldset>
                    <div class="crea_membre_mail_tel">
                        <fieldset>
                            <legend>Email *</legend>

                            <div class="input-icon">
                                <input type="email" id="email" name="email" placeholder="Email *" required>
                                <img src="images/icones/enveloppe.png" alt="icones de téléphone" class="input-icon-img">
                            </div>

                        </fieldset>

                        <fieldset>
                            <legend>Téléphone *</legend>
                            <div class="input-icon">
                                <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *" required>
                                <img src="images/icones/telephone.png" alt="icones de téléphone" class="input-icon-img">
                            </div>
                        </fieldset>
                    </div>


                    <fieldset>
                        <legend>Adresse Postale *</legend>
                        <div class="input-icon">
                            <input type="text" id="adresse" name="adresse" placeholder="Adresse Postale *" required>
                            <img src="images/icones/journal.png" alt="icones de journal" class="input-icon-img">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Code Postal *</legend>
                        <div class="input-icon">
                            <input type="text" id="code-postal" name="code-postal" placeholder="Code Postal *" required>
                            <img src="images/icones/pin.png" alt="icones de pin" class="input-icon-img">
                        </div>

                    </fieldset>
                    <fieldset>
                        <legend>Complément d'adresse</legend>
                        <div class="input-icon">
                            <input type="text" id="complement-adresse" name="complement-adresse" placeholder="Complément d'adresse">
                            <img src="images/icones/pin.png" alt="icones de pin" class="input-icon-img">
                        </div>

                    </fieldset>

                    <fieldset>
                        <legend>Ville *</legend>
                        <div class="input-icon">
                            <input type="text" id="ville" name="ville" placeholder="Ville *" required>
                            <img src="images/icones/batiment.png" alt="icones de batiment" class="input-icon-img">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe *</legend>
                        <div class="input-icon">
                            <input type="password" id="password" name="password" placeholder="Mot de passe *" required>
                            <img src="images/icones/cadenas.png" alt="icones de cadenas" class="input-icon-img">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Confirmer le mot de passe *</legend>
                        <div class="input-icon">
                            <img src="images/icones/cadenas.png" alt="icones de cadenas" class="input-icon-img">
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmer le mot de passe *" required>
                        </div>

                    </fieldset>


                    <div class="checkbox">
                        <input type="checkbox" id="cgu" name="cgu" required>
                        <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                    </div>
                    <div class="creation_compte_membre_deja_compte">
                        <a href="#">Déjà un compte ?</a>
                    </div>
                    <button type="submit" class="submit-btn">Créer mon compte <img src="images/flecheBlancheDroite.png" alt="fleche vers la droite"></button>
                </form>
                    
                <div class="creation_compte_membre_other-links">
                    <p>Déjà un compte ? <a href="connexion_membre.php" class="connexion_membre">Connexion</a></p>
                    <p>S’inscrire avec un compte <a href="creation_compte_pro" class="inscription_pro">Pro</a></p>
                </div>
            </div>
            <div class="image-section">
                <img src="images/trebeurden.png" alt="Image de Trébeurden">
            </div>
        </div>
    </main>
    <?php

        // Vérifie si le formulaire a été soumis    
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";  // Utilisateur PostgreSQL défini dans .env
        $password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env
    
        // Créer une instance PDO avec les bons paramètres
        $dbh = new PDO($dsn, $username, $password);

        // Récupération des champs

        if(!empty($_POST)){
            $prenom = trim(isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '');
            $nom = trim(isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '');
            $pseudo = trim(isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : '');
            $email = trim(isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '');
            $telephone = trim(isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '');
            $adresse = trim(isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '');
            $codePostal = trim(isset($_POST['code-postal']) ? htmlspecialchars($_POST['code-postal']) : '');
            $complementAdresse = trim(isset($_POST['complement-adresse']) ? htmlspecialchars($_POST['complement-adresse']) : '');
            $ville = trim(isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : '');
            $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
            $confirmPassword = isset($_POST['confirm-password']) ? htmlspecialchars($_POST['confirm-password']) : '';
            $cgu = isset($_POST['cgu']) ? true : false; // Case à cocher
            
    
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    
                
            // Initialisation du tableau d'erreurs
            $erreurs = [];
            // Vérifications approfondies des champs
            // 1. Prénom : Pas de chiffres, pas de caractères spéciaux
            if (empty($prenom)) {
                $erreurs[] = "Le champ 'Prénom' est requis.";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $prenom)) {
                $erreurs[] = "Le prénom ne doit contenir que des lettres, espaces, ou apostrophes.";
            }
            // 2. Nom : Pas de chiffres, pas de caractères spéciaux
            if (empty($nom)) {
                $erreurs[] = "Le champ 'Nom' est requis.";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $nom)) {
                $erreurs[] = "Le nom ne doit contenir que des lettres, espaces, ou apostrophes.";
            }
            // 3. Pseudo : Autoriser lettres, chiffres, mais pas de caractères spéciaux à part underscores
            if (empty($pseudo)) {
                $erreurs[] = "Le champ 'Pseudo' est requis.";
            }
            // 4. Email : Vérifier si l'email est valide
            if (empty($email) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                $erreurs[] = "L'adresse email est invalide.";
            }
            // 5. Téléphone : Doit être un format valide de 10 chiffres
            if (empty($telephone) || !preg_match('/^[0-9]{10}$/', $telephone)) {
                $erreurs[] = "Le numéro de téléphone doit comporter 10 chiffres.";
            }
            // 6. Adresse : Valider la longueur minimum et maximum si nécessaire
            if (empty($adresse)) {
                $erreurs[] = "Le champ 'Adresse' est requis.";
            }
            // 7. Code Postal : Format à 5 chiffres
            if (empty($codePostal) || !preg_match('/^([0-9]{5}|2[AB])$/', $codePostal)) {
                $erreurs[] = "Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B.";
            }
            // 8. Ville : Pas de chiffres, pas de caractères spéciaux
            if (empty($ville)) {
                $erreurs[] = "Le champ 'Ville' est requis.";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $ville)) {
                $erreurs[] = "Le nom de la ville ne doit contenir que des lettres, espaces, ou apostrophes.";
            }
            // 9. Mot de passe : Minimum 8 caractères, et correspondance avec le champ de confirmation
            if ($password !== $confirmPassword) {
                $erreurs[] = "Les mots de passe ne correspondent pas.";
            }
            // 10. Conditions générales d'utilisation (CGU) : Vérification que la case est cochée
            if (!$cgu) {
                $erreurs[] = "Vous devez accepter les conditions générales d'utilisation.";
            }

            // Vérifie s'il y a des erreurs
            if (empty($erreurs)) {
                echo "OUIIIIIIIIIIII !";
                // Pas d'erreurs, on peut procéder au traitement (inscription, enregistrement, etc.)

                // Le complément d'adresse ne peut pas être NULL mais peut être vide.
                // Si $complementAdresse est NULL, il faut le remplacer par une chaîne vide.

                if(empty($complementAdresse) || !$complementAdresse){
                    $complementAdresse = "";
                }
                
                $insert = $dbh->prepare("INSERT INTO tripenarvor._compte 
                    (telephone, mail, mdp, nom, prenom, pseudo, adresse_postal, complement_adresse, code_postal, ville)
                    VALUES (:telephone, :mail, :mdp, :nom, :prenom, :pseudo, :adresse_postal, :complement_adresse, :code_postal, :ville)");
                
                // Liez les valeurs aux paramètres
                $insert->bindParam(':telephone', $telephone);
                $insert->bindParam(':mail', $email);
                $insert->bindParam(':mdp', $passwordHashed);
                $insert->bindParam(':nom', $nom);
                $insert->bindParam(':prenom', $prenom);
                $insert->bindParam(':pseudo', $pseudo);
                $insert->bindParam(':adresse_postal', $adresse);
                $insert->bindParam(':complement_adresse', $complementAdresse);
                $insert->bindParam(':code_postal', $codePostal);
                $insert->bindParam(':ville', $ville);
                
                // Exécutez la requête
                var_dump($insert->execute());
    
               try {
                    // Appelle nextval pour initier la séquence
                    $dbh->query("SELECT nextval('tripenarvor._compte_code_compte_seq');");
                    
                    // Appelle currval pour récupérer la dernière valeur
                    $_SESSION["compte"] = ($dbh->query("SELECT currval('tripenarvor._compte_code_compte_seq');"))->fetchColumn();
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            } else {
                // Affiche les erreurs
                foreach ($erreurs as $erreur) {
                    echo "<p>$erreur</p>";
                }
                
            }
        }
    
    ?>   
</body>

</html>
