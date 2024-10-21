<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête PACT</title>
    <link rel="stylesheet" href="creation_offre2.css">
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

    <div>
        <label class="switch">
            <input type="checkbox">
            <span class="slider"></span>
        </label>
        <span>Hors Ligne/En Ligne</span>
    </div>
    </div>


    <!-- Main Section -->
    <main class="main-creation-offre2">

        <div class="form-container2">
            <h1>Publier une offre</h1>

            <!-- Form Fields -->
            <form action="#" method="post">
                <!-- Establishment Name & Type -->
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

                <!-- Email & Phone -->
                <div class="row">
                    <div class="col">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="col">                     
                        <fieldset>
                            <legend>Telephone *</legend>
                            <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *">
                        </fieldset>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="row">
                    <div class="col">
                        <label for="location">Où se trouve l’offre ?</label>
                        <input type="text" id="location" name="location">
                    </div>
                </div>

                <!-- Tags -->
                <div class="row">
                    <div class="col">
                        <label for="tags">Tags</label>
                        <input type="text" id="tags" name="tags" placeholder="Restaurant, côte">
                    </div>
                </div>

                <!-- Photos -->
                <div class="row">
                    <div class="col">
                        <label for="photos">Photos (facultatif)</label>
                        <input type="file" id="photos" name="photos" multiple>
                    </div>
                </div>

                <!-- Carte du restaurant -->
                <div class="row">
                    <div class="col">
                        <label for="carte">Ajouter la carte du restaurant (facultatif)</label>
                        <input type="file" id="carte" name="carte">
                    </div>
                </div>

                <!-- Lien du site -->
                <div class="row">
                    <div class="col">
                        <label for="lien">Ajouter un lien (facultatif)</label>
                        <input type="url" id="lien" name="lien" placeholder="https://">
                    </div>  
                </div>

                <!-- Price Options -->
                <div class="price-options">
                    <label for="prix">Prix</label>
                        <div class="radio-group">
                            <div>
                                <input type="radio" id="moins_25" name="prix" value="moins_25">
                                <label class="label-check" for="moins_25">€ (menu à moins de 25€)</label>
                            </div>
                            <div>
                                <input class="label-check" type="radio" id="entre_25_40" name="prix" value="entre_25_40">
                                <label class="label-check" for="entre_25_40">€€ (entre 25€ et 40€)</label>
                            </div>
                            <div>
                                <input type="radio" id="plus_40" name="prix" value="plus_40">
                                <label class="label-check" for="plus_40">€€€ (au-delà de 40€)</label>
                            </div>
                        </div>
                </div>

                <!-- Meal Options -->
                <div class="meal-options">
                    <label>Options de repas</label>
                    <div class="checkbox-group">
                        <div>
                            <input type="checkbox" id="petit_dejeuner" name="repas" value="petit_dejeuner">
                            <label class="label-check" for="petit_dejeuner">Petit-Déjeuner</label>
                        </div>
                        <div>
                            <input type="checkbox" id="brunch" name="repas" value="brunch">
                            <label class="label-check" for="brunch">Brunch</label>
                        </div>
                        <div>
                            <input type="checkbox" id="dejeuner" name="repas" value="dejeuner" checked>
                            <label class="label-check" for="dejeuner">Déjeuner</label>
                        </div>
                        <div>
                            <input type="checkbox" id="diner" name="repas" value="diner" checked>
                            <label class="label-check" for="diner">Dîner</label>
                        </div>
                        <div>
                            <input type="checkbox" id="boissons" name="repas" value="boissons">
                            <label class="label-check" for="boissons">Boissons</label>
                        </div>
                    </div>
                </div>

                <!-- Résumé -->
                <div class="row">
                    <div class="col">
                        <label for="resume">Résumé (facultatif)</label>
                        <input type="text" id="resume" name="resume">
                    </div>
                </div>

                <!-- Description -->
                <div class="row">
                    <div class="col">
                        <label for="description">Description (facultatif)</label>
                        <input type="text" id="description" name="description">
                    </div>
                </div>


                <!-- Offre Options -->
                <div class="offre-options">
                    <label>Choix de l'offre</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" id="offre_gratuite" name="offre" value="gratuite">
                            <label class="label-check" for="offre_gratuite">Offre Gratuite (pour le secteur public ou associatif)</label>
                        </div>
                        <div>
                            <input type="radio" id="offre_standard" name="offre" value="standard">
                            <label class="label-check" for="offre_standard">Offre Standard</label>
                        </div>
                        <div>
                            <input type="radio" id="offre_premium" name="offre" value="premium" checked>
                            <label class="label-check" for="offre_premium">Offre Premium</label>
                        </div>
                        <div class="info-icon">
                            <span>i</span>
                            <label>Plus d'informations sur les offres</label>
                        </div>
                    </div>
                </div>

                <!-- Boost Options -->
                <div class="boost-options">
                    <label>Options de boost</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" id="no_boost" name="boost" value="no_boost">
                            <label class="label-check" for="no_boost">Ne pas booster mon offre</label>
                        </div>
                        <div>
                            <input type="radio" id="relief" name="boost" value="relief">
                            <label class="label-check" for="relief">Mettre mon offre "en Relief"</label>
                        </div>
                        <div>
                            <input type="radio" id="a_la_une" name="boost" value="a_la_une" checked>
                            <label class="label-check" for="a_la_une">Mettre mon offre "À la Une"</label>
                        </div>
                        <div class="info-icon">
                            <span>i</span>
                            <label>Plus d'informations sur les offres</label>
                        </div>
                    </div>
                </div>

                <!-- Duration Options -->
                <div class="duration-options">
                    <label>Durée</label>
                    <div class="radio-group">
                        <div>
                            <input type="radio" id="1_semaine" name="duree" value="1_semaine">
                            <label class="label-check" for="1_semaine">1 semaine</label>
                        </div>
                        <div>
                            <input type="radio" id="2_semaines" name="duree" value="2_semaines">
                            <label class="label-check" for="2_semaines">2 semaines</label>
                        </div>
                        <div>
                            <input type="radio" id="3_semaines" name="duree" value="3_semaines" checked>
                            <label class="label-check" for="3_semaines">3 semaines</label>
                        </div>
                        <div>
                            <input type="radio" id="4_semaines" name="duree" value="4_semaines">
                            <label class="label-check" for="4_semaines">4 semaines</label>
                        </div>
                        <div class="info-icon">
                            <span>i</span>
                            <label>Plus d'informations sur les offres</label>
                        </div>
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
