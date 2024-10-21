<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
        <div class="logo">
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
    <div class="container">
        


        <div class="tabs">
            <div class="tab">Informations</div>
            <div class="tab">Mot de passe et sécurité</div>
            <div class="tab">Historique</div>
            <div class="tab active">Compte Bancaire</div>
        </div>
    </div>
    <form action="#" method="POST">
    <div class="IBAN">
        <fieldset>
            <legend>IBAN</legend>
            <input type="text" id="IBAN" name="IBAN" placeholder="IBAN  (obligatoire)" required>
        </fieldset>

    </div>

    <div class="date_et_CVC">
        <fieldset>
            <legend>Date</legend>
            <input type="text" id="date" name="date" placeholder="JJ/MM/AAAA" required 
                pattern="\d{2}/\d{2}/\d{4}" title="Veuillez entrer une date au format JJ/MM/AAAA">
            </fieldset>


    </div>

    <fieldset>
        <legend>Adresse Postale *</legend>
        <input type="text" id="adresse" name="adresse" placeholder="Adresse Postale" required>
    </fieldset>

    <fieldset>
        <legend>Code Postal *</legend>
        <input type="text" id="code-postal" name="code-postal" placeholder="Code Postal" required>
    </fieldset>

    <fieldset>
        <legend>Ville *</legend>
        <input type="text" id="ville" name="ville" placeholder="Ville" required>
    </fieldset>

    <div class="checkbox">
        <input type="checkbox" id="cgu" name="cgu" required>
        <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
    </div>
    <div class="compte_membre_save_delete">
    <button type="submit" class="submit-btn1">Supprimer mon compte</button>
    <button type="submit" class="submit-btn2">Enregistrer</button>
</div>

</form>

    <footer class="footer_detail_avis">
        
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PACT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Publier</a></li>
                    <li><a href="#">Historique</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Signaler un problème</a></li>
                    <li><a href="#">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </footer>
</body>
</html>
