<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
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
                <li><a href="connexion_membre.php" class="active">Se connecter</a></li>
            </ul>
        </nav>
    </header>
    <header class="header-tel header_membre">
        <div class="logo-tel">
            <a href="connexion_membre.php">
                <img src="images/Bouton_retour.png" alt="bouton retour" class="bouton-retour-tel">
            </a>
            <img src="images/LogoCouleur.png" alt="PACT Logo">          
        </div>

    </header>

    <main class="creation_compte_membre">
        <div class="creation_compte_membre_container">
            <div class="creation_compte_membre_form-section">
                
                <h1>S’inscrire</h1>
                <p>
                    Créez un compte membre pour sauvegarder vos annonces favorites 
                    <br>partager vos avis sur les offres,  
                    <br>et vivre une expérience sur mesure !
                </p>
                <form action="creation_compte_membre.php" method="POST">
                    <div class="crea_membre_raison_sociale_num_siren">
                        <fieldset>
                            <legend>Prénom *</legend>
                            <div class="input-icon">
                                <input class="erreur-prenom-requis erreur-prenom-invalide" type="text" id="prenom" name="prenom" placeholder="Prénom *" required>
                                <img src="images/icones/personne.png" alt="icones silhouette d'une personne" class="input-icon-img">
                                <p class="erreur-formulaire-creation-membre erreur-prenom-requis"><img src="images/icon_informations.png" alt="icon i pour informations">>Ce champ est requis.</p>
                                <p class="erreur-formulaire-creation-membre erreur-prenom-invalide"><img src="images/icon_informations.png" alt="icon i pour informations">Le prénom ne doit contenir que des lettres, espaces, ou apostrophes.</p>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Nom *</legend>
                            <div class="input-icon">
                                <input class="erreur-nom-requis erreur-nom-invalide" type="text" id="nom" name="nom" placeholder="Nom *" required>
                                <img src="images/icones/personne.png" alt="icones silhouette d'une personne" class="input-icon-img">
                            <p class="erreur-formulaire-creation-membre erreur-nom-requis"><img src="images/icon_informations.png" alt="icon i pour informations">Le champ 'Nom' est requis.</p>
                            <p class="erreur-formulaire-creation-membre erreur-nom-invalide"><img src="images/icon_informations.png" alt="icon i pour informations">Le nom ne doit contenir que des lettres, espaces, ou apostrophes.</p>
                            </div>
                        </fieldset>
                    </div>
                    <fieldset>
                        <legend>Pseudo *</legend>
                        <div class="input-icon">
                            <input class="erreur-pseudo-requis" type="text" id="pseudo" name="pseudo" placeholder="Pseudo *" required>
                            <img src="images/icones/personne.png" alt="icones silhouette d'une personne" class="input-icon-img">
                        <p class="erreur-formulaire-creation-membre erreur-pseudo-requis"><img src="images/icon_informations.png" alt="icon i pour informations">Le champ 'Pseudo' est requis.</p>
                        </div>
                    </fieldset>
                    <div class="crea_membre_mail_tel">
                        <fieldset>
                            <legend>Email *</legend>

                            <div class="input-icon">
                                <input class="erreur-email-invalide erreur-email-existant" type="email" id="email" name="email" placeholder="Email *" required>
                                <img src="images/icones/enveloppe.png" alt="icones de téléphone" class="input-icon-img">
                            <p class="erreur-formulaire-creation-membre erreur-email-invalide"><img src="images/icon_informations.png" alt="icon i pour informations">L'adresse email est invalide.</p>
                            <p class="erreur-formulaire-creation-membre erreur-email-existant"><img src="images/icon_informations.png" alt="icon i pour informations">Cette adresse mail est déjà liée à un compte !</p>
                            </div>

                        </fieldset>

                        <fieldset>
                            <legend>Téléphone *</legend>
                            <div class="input-icon">
                                <input class="erreur-telephone-invalide" type="tel" id="telephone" name="telephone" placeholder="Téléphone *" required>
                                <img src="images/icones/telephone.png" alt="icones de téléphone" class="input-icon-img">
                            <p class="erreur-formulaire-creation-membre erreur-telephone-invalide"><img src="images/icon_informations.png" alt="icon i pour informations">Le numéro de téléphone doit comporter 10 chiffres.</p>
                            </div>
                        </fieldset>
                    </div>


                    <fieldset>
                        <legend>Adresse Postale *</legend>
                        <div class="input-icon">
                            <input class="erreur-adresse-requis" type="text" id="adresse" name="adresse" placeholder="Adresse Postale *" required>
                            <img src="images/icones/journal.png" alt="icones de journal" class="input-icon-img">
                        <p class="erreur-formulaire-creation-membre erreur-adresse-requis"><img src="images/icon_informations.png" alt="icon i pour informations">Le champ 'Adresse' est requis.</p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Code Postal *</legend>
                        <div class="input-icon">
                            <input class="erreur-code-postal-invalide" type="text" id="code-postal" name="code-postal" placeholder="Code Postal *" required>
                            <img src="images/icones/pin.png" alt="icones de pin" class="input-icon-img">
                        <p class="erreur-formulaire-creation-membre erreur-code-postal-invalide"><img src="images/icon_informations.png" alt="icon i pour informations">Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B.</p>
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
                            <input class="erreur-ville-requis erreur-ville-invalide erreur-ville-code-postal-incompatible" type="text" id="ville" name="ville" placeholder="Ville *" required>
                            <img src="images/icones/batiment.png" alt="icones de batiment" class="input-icon-img">
                        <p class="erreur-formulaire-creation-membre erreur-ville-requis"><img src="images/icon_informations.png" alt="icon i pour informations">Le champ 'Ville' est requis.</p>
                        <p class="erreur-formulaire-creation-membre erreur-ville-invalide"><img src="images/icon_informations.png" alt="icon i pour informations">Le nom de la ville ne doit contenir que des lettres, espaces, ou apostrophes.</p>
                        <p class="erreur-formulaire-creation-membre erreur-ville-code-postal-incompatible"><img src="images/icon_informations.png" alt="icon i pour informations">La ville ne correspond pas au code postal.</p>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe *</legend>
                        <div class="input-icon">
                            <input class="erreur-mots-de-passe-incompatibles" type="password" id="confirm-password" name="pwd" placeholder="Entrez un mot de passe *" required>
                            <img src="images/icones/cadenas.png" alt="icones de cadenas" class="input-icon-img">
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Confirmez le mot de passe *</legend>
                        <div class="input-icon">
                            <img src="images/icones/cadenas.png" alt="icones de cadenas" class="input-icon-img">
                            <input class="erreur-mots-de-passe-incompatibles" type="password" id="confirm-password" name="confirm-password" placeholder="Confirmez le mot de passe *" required>
                        <p class="erreur-formulaire-creation-membre erreur-mots-de-passe-incompatibles"><img src="images/icon_informations.png" alt="icon i pour informations">Les mots de passe ne correspondent pas.</p>
                        </div>

                    </fieldset>


                    <div class="checkbox">
                        <input type="checkbox" id="cgu" name="cgu" required>
                        <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                    </div>
                    <div class="creation_compte_membre_deja_compte">
                        <a href="connexion_membre.php" class="deja_compte">Déjà un compte ?</a>
                    </div>
                    <button type="submit" class="submit-btn">Créer mon compte</button>
                </form>
                    
                <div class="creation_compte_membre_other-links">
                    <p>Déjà un compte ? <a href="connexion_membre.php" class="connexion_membre">Connexion</a></p>
                    <p>S’inscrire avec un compte <a href="creation_pro.php" class="inscription_pro">Pro</a></p>
                </div>
            </div>
            <div class="image-section">
                <img src="images/trebeurden.png" alt="Image de Trébeurden">
            </div>
        </div>
    </main>
    <?php

        require_once __DIR__ . ("/../.security/config.php");
    
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
            $pwd = isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : '';
            $confirmPassword = isset($_POST['confirm-password']) ? htmlspecialchars($_POST['confirm-password']) : '';
            $cgu = isset($_POST['cgu']) ? true : false; // Case à cocher
            
    
            $passwordHashed = password_hash($pwd, PASSWORD_DEFAULT);
    
                
            // Initialisation du tableau d'erreurs
            $erreurs = [];
            $erreurs_a_afficher = [];
            // Vérifications approfondies des champs
            // 1. Prénom : Pas de chiffres, pas de caractères spéciaux
            if (empty($prenom)) {
                $erreurs[] = "Le champ 'Prénom' est requis.";
                $erreurs_a_afficher[] = "erreur-prenom-requis";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $prenom)) {
                $erreurs[] = "Le prénom ne doit contenir que des lettres, espaces, ou apostrophes.";
                $erreurs_a_afficher[] = "erreur-prenom-invalide";
            }
            // 2. Nom : Pas de chiffres, pas de caractères spéciaux
            if (empty($nom)) {
                $erreurs[] = "Le champ 'Nom' est requis.";
                $erreurs_a_afficher[] = "erreur-nom-requis";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $nom)) {
                $erreurs[] = "Le nom ne doit contenir que des lettres, espaces, ou apostrophes.";
                $erreurs_a_afficher[] = "erreur-nom-invalide";
            }
            // 3. Pseudo : Autoriser lettres, chiffres, mais pas de caractères spéciaux à part underscores
            if (empty($pseudo)) {
                $erreurs[] = "Le champ 'Pseudo' est requis.";
                $erreurs_a_afficher[] = "erreur-pseudo-requis";
            }
            // 4. Email : Vérifier si l'email est valide
            if (empty($email) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                $erreurs[] = "L'adresse email est invalide.";
                $erreurs_a_afficher[] = "erreur-email-invalide";
            } else {
                /* on vérifie l'unicité de l'adresse mail.
                Sinon, les insertions présentes AVANT une insertion utilisant l'adresse mail se déclencheront quand même !*/
    
                $verifAdresseMail = $dbh->prepare("SELECT 1 FROM tripenarvor._compte WHERE mail = :adresse_mail");
                $verifAdresseMail->bindValue(":adresse_mail",$email);
                $verifAdresseMail->execute();

                $existeAdresseMail = $verifAdresseMail->fetch(PDO::FETCH_ASSOC);
                if($existeAdresseMail){
                    $erreurs[] = "Cette adresse mail est déjà liée à un compte !";
                    $erreurs_a_afficher[] = "erreur-email-existant";
                }
            }
            
            // 5. Téléphone : Doit être un format valide de 10 chiffres
            if (empty($telephone) || !preg_match('/^[0-9]{10}$/', $telephone)) {
                $erreurs[] = "Le numéro de téléphone doit comporter 10 chiffres.";
                $erreurs_a_afficher[] = "erreur-telephone-invalide";
            }
            // 6. Adresse : Valider la longueur minimum et maximum si nécessaire
            if (empty($adresse)) {
                $erreurs[] = "Le champ 'Adresse' est requis.";
                $erreurs_a_afficher[] = "erreur-adresse-requis";
            }
            // 7. Code Postal : Format à 5 chiffres
            if (empty($codePostal) || !preg_match('/^([0-9]{5}|2[AB])$/', $codePostal)) {
                $erreurs[] = "Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B.";
                $erreurs_a_afficher[] = "erreur-code-postal-invalide";
            }
            // 8. Ville : Pas de chiffres, pas de caractères spéciaux
            if (empty($ville)) {
                $erreurs[] = "Le champ 'Ville' est requis.";
                $erreurs_a_afficher[] = "erreur-ville-requis";
            } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $ville)) {
                $erreurs[] = "Le nom de la ville ne doit contenir que des lettres, espaces, ou apostrophes.";
                $erreurs_a_afficher[] = "erreur-ville-invalide";
            }

            // 8.bis : Vérification du code postal

            if(!empty($ville) && !empty($codePostal)){
                $api_codePostal = 'http://api.zippopotam.us/fr/'.$codePostal;

                $api_codePostal = file_get_contents($api_codePostal);
                if($api_codePostal === FALSE){
                    $erreurs[] = "Erreur lors de l'accès à l'API";
                    exit();
                }
                
                $data = json_decode($api_codePostal,true);
                $isValid = false;
                
                if($data && isset($data['places'])){
                    foreach($data['places'] as $place){
                        if(stripos($place['place name'], $ville) === 0){
                            $isValid = true;
                            break;
                        }
                    }
                }
                if(!$isValid){
                    $erreurs[] = "La ville ne correspond pas au code postal";
                    $erreurs_a_afficher[] = "erreur-ville-code-postal-incompatible";
                }
            }
            
            // 9. Mot de passe : Minimum 8 caractères, et correspondance avec le champ de confirmation
            if (trim($pwd) !== trim($confirmPassword)) {
                $erreurs[] = "Les mots de passe ne correspondent pas.";
                $erreurs_a_afficher[] = "erreur-mots-de-passe-incompatibles";
            }
            // 10. Conditions générales d'utilisation (CGU) : Vérification que la case est cochée
            if (!$cgu) {
                $erreurs[] = "Vous devez accepter les conditions générales d'utilisation.";
                $erreurs_a_afficher[] = "erreur-cgu-non-accepte";
            }

            // Vérifie s'il y a des erreurs
            if (empty($erreurs)) {
                // Pas d'erreurs, on peut procéder au traitement (inscription, enregistrement, etc.)

                // Le complément d'adresse ne peut pas être NULL mais peut être vide.
                // Si $complementAdresse est NULL, il faut le remplacer par une chaîne vide.

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
                
                // Liez les valeurs aux paramètres
                $creerCompte->bindParam(':telephone', $telephone);
                $creerCompte->bindParam(':mail', $email);
                $creerCompte->bindParam(':mdp', $passwordHashed);
                $creerCompte->bindParam(':code_adresse',$codeAdresse);

                if ($creerCompte->execute()) {
                    // 2. Récupérer le code_adresse nouvellement créé
                    $codeCompte = $dbh->lastInsertId();
                }

                $creerMembre = $dbh->prepare("INSERT INTO tripenarvor._membre (code_compte, nom, prenom, pseudo) VALUES (:code_compte,:nom,:prenom,:pseudo)");

                $creerMembre->bindParam(':code_compte',$codeCompte);
                $creerMembre->bindParam(':nom', $nom);
                $creerMembre->bindParam(':prenom', $prenom);
                $creerMembre->bindParam(':pseudo', $pseudo);

                if($creerMembre->execute()){
                    ?>
                        <div class="creation-success">
                            <img src="images/verifier.png" alt="Succès">
                            <h2>Compte crée avec succès !</h2>
                        </div>
                    <?php
                    
                }
    
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
                // foreach ($erreurs as $erreur) {
                //     echo "<p>$erreur</p>";
                // }
                foreach ($erreurs_a_afficher as $champs) {
                    ?> 
                    <style>
                        <?php echo ".creation_compte_membre fieldset p.$champs"?>{
                            display : flex;
                            align-items: center;
                        }
                        <?php echo ".creation_compte_membre fieldset p.$champs img"?>{
                            width: 10px;
                            height: 10px;
                            margin-right: 10px;
                        }
                        <?php echo ".creation_compte_membre input.$champs"?>{
                            border: 1px solid red;
                        }
                    </style>
                    <?php
                }
            }
        }
    
    ?>   
</body>

</html>
