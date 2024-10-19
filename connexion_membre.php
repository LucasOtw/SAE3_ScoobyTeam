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
    <header class="header_membre">
        <div class="logo">
            <img src="images/logo_blanc_membre.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Publier</a></li>
                <li><a href="#" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <main class="connexion_membre_main">
        <div class="connexion_membre_container">
            <div class="connexion_membre_form-container">
                <div class="connexion_membre_h2_p">
                    <h2>Se connecter</h2>
                    <p>Se connecter pour accéder à vos favoris</p>
                </div>
                <form action="#">
                    <fieldset>
                        <legend>E-mail</legend>
                        <div class="connexion_membre_input-group">
                            <input type="email" id="email" placeholder="E-mail" required>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Mot de passe</legend>
                        <div class="connexion_membre_input-group">
                            <input type="password" id="password" placeholder="Mot de passe" required>
                        </div>
                    </fieldset>
                <!--
                    <div class="connexion_membre_remember-group">
                        <div>
                            <input type="checkbox" id="remember" checked>
                            <label class="connexion_membre_lab_enreg" for="remember">Enregistrer</label>
                        </div>
                        <a href="#">Mot de passe oublié ?</a>
                    </div>
                -->
                    <button type="submit">Se connecter</button>
                    <div class="connexion_membre_additional-links">
                        <p><span class="pas_de_compte">Pas de compte ?<a href="#">Inscription</a></p>

                        <p class="compte_membre"><a href="#">Un compte Pro&nbsp?</a></p>
                    </div>
                </form>

            </div>
            <div class="connexion_membre_image-container">
                <img src="images/imageConnexionProEau.png" alt="Image de maison en pierre avec de l'eau">
            </div>
        </div>
    </main>
</body>

</html>