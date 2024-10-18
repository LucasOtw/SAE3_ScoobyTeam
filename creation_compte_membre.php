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
<header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        
        <nav>
            <ul>
                <li><a href="#" class="active">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#">Mon Compte</a></li>
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
                <form action="#" method="POST">
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
</body>

</html>