<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="icon" type="image/png" href="images/logoPin.png" width="16px" height="32px">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="header_footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="header-membre">
    <header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        
        <nav>
            <ul>
                <li><a href="#" >Accueil</a></li>
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
    </div>
    

    <div class="header-pro">
    <header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        
        <nav>
            <ul>
                <li><a href="#" >Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#" class="active">Mon Compte</a></li>
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
                <p>Créer un compte pour profiter de l’expérience PACT</p>
                <form action="#" method="POST">
                    <div class="crea_pro_raison_sociale_num_siren">
                        <fieldset>
                            <legend>Raison sociale *</legend>
                            <input type="text" id="raison-sociale" name="raison-sociale" placeholder="Raison sociale *" required>
                        </fieldset>

                        <fieldset>
                            <legend>N° de Siren *</legend>
                            <input type="text" id="siren" name="siren" placeholder="N° de Siren *" required>
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
                    <p>S’inscrire avec un compte <a href="#">Membre</a></p>
                </div>
            </div>
            <div class="image-section">
                <img src="images/trebeurden.png" alt="Image de Trébeurden">
            </div>
        </div>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des variables du formulaire
    $raison_sociale = $_POST['raison-sociale'];
    $siren = $_POST['siren'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $codePostal = $_POST['code-postal'];
    $ville = $_POST['ville'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $cgu = isset($_POST['cgu']) ? 1 : 0;  // Checkbox pour CGU

    // Initialisation du tableau d'erreurs
    $erreurs = [];

    // 1. Raison sociale : Vérification basique (on peut ajouter des conditions spécifiques si nécessaire)
    if (empty($raison_sociale)) {
        $erreurs[] = "Le champ 'Raison sociale' est requis.";
    }

    // 2. N° de Siren : Vérifier si le Siren est valide (9 chiffres)
    if (empty($siren) || !preg_match('/^[0-9]{9}$/', $siren)) {
        $erreurs[] = "Le numéro de Siren doit comporter 9 chiffres.";
    }

    // 3. Email : Vérifier si l'email est valide
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
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
        try {
            // Préparation de la requête d'insertion
            $insert = $membre->prepare("INSERT INTO membre(raison_sociale, siren, email, telephone, adresse_postal, code_postal, ville, mdp) 
                                        VALUES ($raison_sociale, $siren, $email, $telephone, $adresse, $code_postal, $ville, $mdp)");

            // Exécution de la requête avec les valeurs du formulaire
            $insert->execute([
                ':raison_sociale' => $raison_sociale,
                ':siren' => $siren,
                ':email' => $email,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':code_postal' => $codePostal,
                ':ville' => $ville,
                ':mdp' => password_hash($password, PASSWORD_DEFAULT) // Hachage du mot de passe
            ]);

            echo "Le formulaire est valide. Compte créé avec succès.";
        } catch (Exception $e) {
            echo "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage();
        }
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