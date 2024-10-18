
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PACT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="header-controls">
        <div>
            <img id="etapes" src="images/FilArianne2.png" alt="Étapes">
        </div>

        <div class="toggle-container">
            <input type="checkbox" id="toggle-button" class="toggle-button">
            <label for="toggle-button" class="toggle-label">
                <span class="toggle-switch"></span>
                <span class="toggle-text">Hors Ligne/En Ligne</span>
            </label>
            </div>
    </div>


    <!-- Main Section -->
    <main class="main-creation-offre2">

        <div class="form-container2">
            <h1>Publier une offre</h1>

            <!-- Form Fields -->
            <form action="#" method="post">
                <!-- First Row: Establishment Name & Type -->
                <div class="row">
                    <div class="col">
                        <label for="nom_etablissement">Nom de l'établissement</label>
                        <input type="text" id="nom_etablissement" name="nom_etablissement">
                    </div>
                    <div class="col">
                        <label for="type_etablissement">Type d'établissement</label>
                        <input type="text" id="type_etablissement" name="type_etablissement" value="Restauration">
                    </div>
                </div>

                <!-- Second Row: Email & Phone -->
                <div class="row">
                    <div class="col">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="col">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone">
                    </div>
                </div>

                <!-- Third Row: Location -->
                <div class="row">
                    <div class="col">
                        <label for="location">Où se trouve l’offre ?</label>
                        <input type="text" id="location" name="location">
                    </div>
                </div>

                <!-- Fourth Row: Tags -->
                <div class="row">
                    <div class="col">
                        <label for="tags">Tags</label>
                        <input type="text" id="tags" name="tags" placeholder="Restaurant, côte">
                    </div>
                </div>

                <!-- Photo & Links Section -->
                <div class="row">
                    <div class="col">
                        <a href="#" class="add-photo">+ Ajouter des photos (facultatif)</a>
                    </div>
                    <div class="col">
                        <a href="#" class="add-link">+ Ajouter la carte du restaurant</a>
                    </div>
                </div>

                <!-- Price Options -->
                <div class="row">
                    <div class="col">
                        <label for="prix">Prix</label>
                        <input type="radio" id="moins_25" name="prix" value="moins_25"> Moins de 25€
                        <input type="radio" id="entre_25_40" name="prix" value="entre_25_40"> Entre 25€ et 40€
                        <input type="radio" id="plus_40" name="prix" value="plus_40"> Plus de 40€
                    </div>
                </div>

                <!-- Offer Times -->
                <h2>Horaires</h2>
                <div class="row">
                    <!-- Repeat this structure for each day (Monday to Sunday) -->
                    <div class="col day">
                        <label for="lundi_matin">Lundi Matin</label>
                        <input type="text" id="lundi_matin" name="lundi_matin" value="Fermé">
                    </div>
                    <div class="col day">
                        <label for="lundi_aprem">Lundi Après-midi</label>
                        <input type="text" id="lundi_aprem" name="lundi_aprem" value="Fermé">
                    </div>
                    <div class="col switch">
                        <label for="lundi_switch">Ouvert</label>
                        <input type="checkbox" id="lundi_switch" name="lundi_switch">
                    </div>
                </div>

                <!-- Final Submit Button -->
                <button type="submit" id="button_valider">
                    Valider
                    <img src="images/fleche.png" alt="Fleche">
                </button>
            </form>

        </div>

    </main>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
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
           
    </footer>
</body>
</html>

