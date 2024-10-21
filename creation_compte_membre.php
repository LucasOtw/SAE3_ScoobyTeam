<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="icon" type="image/png" href="images/logoPin.png" width="16px" height="32px">
    <link rel="stylesheet" href="style.css">
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
    <?php/*
    // Vérifie si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des champs
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

        // echo $prenom
        //     . " / " . $nom
        //     . " / " . $pseudo
        //     . " / " . $email
        //     . " / " . $telephone
        //     . " / " . $adresse
        //     . " / " . $codePostal
        //     . " / " . $complementAdresse
        //     . " / " . $ville
        //     . " / " . $password
        //     . " / " . $confirmPassword
        //     . " / " . $cgu;

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
        if (empty($codePostal) || !preg_match('/^[0-9]{5}|2[AB]$/', $codePostal)) {
            $erreurs[] = "Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B";
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
                        // Pas d'erreurs, on peut procéder au traitement (inscription, enregistrement, etc.)
                        echo "Le formulaire est valide. Compte créé avec succès.";
                        $insert = $membre -> prepare("insert into tripenarvor.membre(telephone, mail, mdp, nom, prenom, pseudo, adresse_postal, complement_adresse, code_postal, ville)
                                                        values ($telephone, $email, $password, $nom, $prenom, $pseudo, $adresse, $complementAdresse, $codePostal, $ville)");
                        $insert->execute();
                        $membre = null;
                    } else {
                        // Affiche les erreurs
                        foreach ($erreurs as $erreur) {
                            ?>
                            <style>

                            </style>
                            <?php
                            
                        }
                    }
                }
    */?>
    <?php
    // Vérifie si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des champs
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

        // Initialisation du tableau d'erreurs
        $erreurs = [];
        $errorFields = []; // Array to keep track of error fields

        // Vérifications approfondies des champs

        // 1. Prénom
        if (empty($prenom)) {
            $erreurs[] = "Le champ 'Prénom' est requis.";
            $errorFields['prenom'] = true;
        } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $prenom)) {
            $erreurs[] = "Le prénom ne doit contenir que des lettres, espaces, ou apostrophes.";
            $errorFields['prenom'] = true;
        }

        // 2. Nom
        if (empty($nom)) {
            $erreurs[] = "Le champ 'Nom' est requis.";
            $errorFields['nom'] = true;
        } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $nom)) {
            $erreurs[] = "Le nom ne doit contenir que des lettres, espaces, ou apostrophes.";
            $errorFields['nom'] = true;
        }

        // 3. Pseudo
        if (empty($pseudo)) {
            $erreurs[] = "Le champ 'Pseudo' est requis.";
            $errorFields['pseudo'] = true;
        }

        // 4. Email
        if (empty($email) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            $erreurs[] = "L'adresse email est invalide.";
            $errorFields['email'] = true;
        }

        // 5. Téléphone
        if (empty($telephone) || !preg_match('/^[0-9]{10}$/', $telephone)) {
            $erreurs[] = "Le numéro de téléphone doit comporter 10 chiffres.";
            $errorFields['telephone'] = true;
        }

        // 6. Adresse
        if (empty($adresse)) {
            $erreurs[] = "Le champ 'Adresse' est requis.";
            $errorFields['adresse'] = true;
        }

        // 7. Code Postal
        if (empty($codePostal) || !preg_match('/^[0-9]{5}|2[AB]$/', $codePostal)) {
            $erreurs[] = "Le code postal est invalide. Il doit comporter 5 chiffres ou être 2A ou 2B";
            $errorFields['code-postal'] = true;
        }

        // 8. Ville
        if (empty($ville)) {
            $erreurs[] = "Le champ 'Ville' est requis.";
            $errorFields['ville'] = true;
        } elseif (!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ' -]+$/", $ville)) {
            $erreurs[] = "Le nom de la ville ne doit contenir que des lettres, espaces, ou apostrophes.";
            $errorFields['ville'] = true;
        }

        // 9. Mot de passe
        if ($password !== $confirmPassword) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
            $errorFields['password'] = true;
        }

        // 10. CGU
        if (!$cgu) {
            $erreurs[] = "Vous devez accepter les conditions générales d'utilisation.";
            $errorFields['cgu'] = true;
        }

        // Vérifie s'il y a des erreurs
        if (empty($erreurs)) {
            // Pas d'erreurs, on peut procéder au traitement (inscription, enregistrement, etc.)
            echo "Le formulaire est valide. Compte créé avec succès.";
            // Database insertion logic goes here
        } else {
            // Affiche les erreurs
            foreach ($erreurs as $erreur) {
                echo "<div style='color: red;'>$erreur</div>";
            }
        }
    }
    ?>

    <style>
        <?php if (isset($errorFields)) : ?>
            fieldset {
                border: 1px solid #ccc; /* Default border */
            }

            <?php if (isset($errorFields['prenom'])) : ?>
                fieldset:has(#prenom) {
                    border: 2px solid red; /* Red border for Prenom */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['nom'])) : ?>
                fieldset:has(#nom) {
                    border: 2px solid red; /* Red border for Nom */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['pseudo'])) : ?>
                fieldset:has(#pseudo) {
                    border: 2px solid red; /* Red border for Pseudo */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['email'])) : ?>
                fieldset:has(#email) {
                    border: 2px solid red; /* Red border for Email */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['telephone'])) : ?>
                fieldset:has(#telephone) {
                    border: 2px solid red; /* Red border for Telephone */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['adresse'])) : ?>
                fieldset:has(#adresse) {
                    border: 2px solid red; /* Red border for Adresse */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['code-postal'])) : ?>
                fieldset:has(#code-postal) {
                    border: 2px solid red; /* Red border for Code Postal */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['ville'])) : ?>
                fieldset:has(#ville) {
                    border: 2px solid red; /* Red border for Ville */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['password'])) : ?>
                fieldset:has(#password) {
                    border: 2px solid red; /* Red border for Password */
                }
            <?php endif; ?>

            <?php if (isset($errorFields['cgu'])) : ?>
                /* Additional styles for CGU can be added if needed */
            <?php endif; ?>
        <?php endif; ?>
    </style>
</body>

</html>