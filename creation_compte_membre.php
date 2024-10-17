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
    <header class="header_pro">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
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
    </main>
</body>

</html>