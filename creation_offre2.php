<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier une offre</title>
    <link rel="stylesheet" href="creation_offre2.css">
</head>
<header>
    <div class="logo">
        <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </div>
    <nav>
        <ul>
            <li><a href="#" class="active">Accueil</a></li>
            <li><a href="#">Publier</a></li>
            <li><a href="#">Mon Compte</a></li>
        </ul>
    </nav>
</header>
<body>
    <div class="container">
        <form action="#" method="POST">
            
            <h2>Publier une offre</h2>

            <!-- Nom de l'établissement et type d'établissement (sur la même ligne) -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nom-etablissement">Nom de l'établissement</label>
                    <input type="text" id="nom-etablissement" name="nom-etablissement" placeholder="TI Al Lannec">
                </div>
                <div class="form-group">
                    <label for="type-etablissement">Type d'établissement</label>
                    <input type="text" id="type-etablissement" name="type-etablissement" placeholder="Restauration">
                </div>
            </div>

            <!-- Email et téléphone (sur la même ligne) -->
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="antoineprieur@gmail.com">
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" placeholder="0766542283">
                </div>
            </div>

            <!-- Lieu de l'offre -->
            <label for="lieu-offre">Où se trouve l’offre?</label>
            <input type="text" id="lieu-offre" name="lieu-offre" placeholder="Pleumeur Bodou">

            <!-- Tags -->
            <label for="tags">Tags</label>
            <input type="text" id="tags" name="tags" placeholder="Restaurant, côte">

            <!-- Section pour les photos, carte et liens -->
            <div class="file-input">
                <label>Photos (facultatif)</label>
                <input type="file" name="photos" multiple>
            </div>

            <div class="file-input">
                <label>Carte du restaurant</label>
                <input type="file" name="menu">
            </div>

            <label for="site">Lien du site</label>
            <input type="url" id="site" name="site" placeholder="Ajouter un lien">

            <!-- Tarification -->
            <div class="tarification">
                <label>Tarification</label>
                <div>
                    <input type="radio" id="prix1" name="tarif" value="moins25">
                    <label for="prix1">€ (menu à moins de 25€)</label>
                </div>
                <div>
                    <input type="radio" id="prix2" name="tarif" value="entre25_40">
                    <label for="prix2">€€ (entre 25€ et 40€)</label>
                </div>
                <div>
                    <input type="radio" id="prix3" name="tarif" value="plus40">
                    <label for="prix3">€€€ (au-delà de 40€)</label>
                </div>
            </div>

            <!-- Services proposés -->
            <div class="services">
                <label>Services proposés</label>
                <div>
                    <input type="checkbox" id="petit-dejeuner" name="services" value="Petit-déjeuner">
                    <label for="petit-dejeuner">Petit-déjeuner</label>
                </div>
                <div>
                    <input type="checkbox" id="brunch" name="services" value="Brunch">
                    <label for="brunch">Brunch</label>
                </div>
                <div>
                    <input type="checkbox" id="dejeuner" name="services" value="Déjeuner" checked>
                    <label for="dejeuner">Déjeuner</label>
                </div>
                <div>
                    <input type="checkbox" id="diner" name="services" value="Dîner" checked>
                    <label for="diner">Dîner</label>
                </div>
                <div>
                    <input type="checkbox" id="boissons" name="services" value="Boissons" checked>
                    <label for="boissons">Boissons</label>
                </div>
            </div>

            <!-- Résumé -->
            <label for="resume">Résumé</label>
            <textarea id="resume" name="resume" placeholder="Équipés des dernières technologies, tout a été pensé pour votre confort."></textarea>

            <!-- Description -->
            <label for="description">Description</label>
            <textarea id="description" name="description">Côté mer, des balcons et terrasses, on est saisi par la force et la beauté du panorama à portée de vue.</textarea>

            <!-- Horaires d'ouverture -->
            <label>Horaires d'ouverture</label>
            <div class="hours">
                <!-- Lundi -->
                <div class="day">
                    <span>Lundi</span>
                    <div class="hour-group">
                        <span>Matin</span>
                        <input type="text" value="Fermé" readonly>
                    </div>
                    <div class="hour-group">
                        <span>Après-midi</span>
                        <input type="text" value="Fermé" readonly>
                    </div>
                </div>
                <!-- Mardi -->
                <div class="day">
                    <span>Mardi</span>
                    <div class="hour-group">
                        <span>Matin</span>
                        <input type="text" value="8 h 30 à 12 h 00" readonly>
                    </div>
                    <div class="hour-group">
                        <span>Après-midi</span>
                        <input type="text" value="13 h 30 à 17 h 30" readonly>
                    </div>
                </div>
                <!-- Répéter le bloc pour les autres jours... -->
            </div>

            <!-- Type d'offre -->
            <label>Type d'offre</label>
            <div class="offer-type">
                <div>
                    <input type="radio" id="offre-gratuite" name="offre" value="Offre Gratuite">
                    <label for="offre-gratuite">Offre Gratuite</label>
                </div>
                <div>
                    <input type="radio" id="offre-standard" name="offre" value="Offre Standard" checked>
                    <label for="offre-standard">Offre Standard</label>
                </div>
                <div>
                    <input type="radio" id="offre-premium" name="offre" value="Offre Premium">
                    <label for="offre-premium">Offre Premium</label>
                </div>
            </div>

            <!-- Durée de mise en ligne -->
            <label>Durée de mise en ligne</label>
            <div class="publish-duration">
                <div>
                    <input type="radio" id="une-semaine" name="duree" value="1-semaine">
                    <label for="une-semaine">1 semaine</label>
                </div>
                <div>
                    <input type="radio" id="deux-semaines" name="duree" value="2-semaines">
                    <label for="deux-semaines">2 semaines</label>
                </div>
                <div>
                    <input type="radio" id="trois-semaines" name="duree" value="3-semaines">
                    <label for="trois-semaines">3 semaines</label>
                </div>
                <div>
                    <input type="radio" id="quatre-semaines" name="duree" value="4-semaines" checked>
                    <label for="quatre-semaines">4 semaines</label>
                </div>
            </div>

            <!-- Bouton de validation -->
            <button type="submit">Valider</button>
        </form>
    </div>
    <footer class="footer_detail_avis">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo PACT">
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
