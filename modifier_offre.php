
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une offre - PACT</title>
    <link rel="stylesheet" href="modifier_offre.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@100;300;400;500;700&display=swap" rel="stylesheet">
</head>
<body class="body_modif_offre">
    <!-- Header -->
    <header>
        <div class="logo">
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

    <!-- Main content -->
    <main class= "modifier_offre_corps">
        <section>
            <h1>Modifier une offre</h1>
            <div class="steps_modif_offre">
                <span>Étape 1</span>
                <span class="active">Étape 2</span>
                <span>Étape 3</span>
            </div>
            <form action="#" method="post">
                <div class="info_etablissement_modif_offre">
                    <!-- Nom de l'établissement -->
                    <div class="form-group_modif_offre">
                        <label for="nom-etablissement">Nom de l'établissement</label>
                        <input type="text" id="nom-etablissement" name="nom-etablissement" value="Ti Al Lannec">
                    </div>
            
                    <!-- Type d'établissement -->
                    <div class="form-group_modif_offre">
                        <label for="type-etablissement">Type d'établissement</label>
                        <input type="text" id="type-etablissement" name="type-etablissement" value="Restauration">
                    </div>
            
                    <!-- Coordonnées -->
                    <div class="form-group_modif_offre">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="antoineprieur@gmail.com">
                    </div>
            
                    <div class="form-group_modif_offre">
                        <label for="telephone">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" value="0765542323">
                    </div>
                </div>
            </form>
            

                <!-- Localisation -->
                <div class="form-group_modif_offre">
                    <label for="localisation">Où se trouve l'offre ?</label>
                    <input type="text" id="localisation" name="localisation" value="Pleumeur Bodou">
                </div>

                <!-- Tags -->
                <div class="form-group_modif_offre">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" value="Restaurant, côte">
                </div>

                <!-- Photos -->
                <div class="form-group_modif_offre">
                    <label for="photos">Photos</label>
                    <button type="button" id="add-photos">+ Ajouter des photos</button>
                </div>

                <!-- Carte du restaurant -->
                <div class="form-group_modif_offre">
                    <label for="carte-restaurant">Carte du restaurant</label>
                    <button type="button" id="add-carte">+ Ajouter la carte du restaurant</button>
                </div>

                <!-- Lien du site -->
                <div class="form-group_modif_offre">
                    <label for="lien-site">Lien du site</label>
                    <button type="button" id="add-lien">+ Ajouter un lien</button>
                </div>

                <!-- Tarifs -->
                <div class="form-group_modif_offre">
                    <label>Tarif :</label>
                    <input type="radio" name="tarif" value="moins25"> € (moins de 25€)
                    <input type="radio" name="tarif" value="25-40"> €€ (entre 25€ et 40€)
                    <input type="radio" name="tarif" value="plus40"> €€€ (au-delà de 40€)
                </div>

                <!-- Repas -->
                <div class="form-group_modif_offre">
                    <label>Repas :</label>
                    <input type="checkbox" name="petit-dejeuner"> Petit-Déjeuner
                    <input type="checkbox" name="brunch"> Brunch
                    <input type="checkbox" name="dejeuner"> Déjeuner
                    <input type="checkbox" name="diner"> Diner
                    <input type="checkbox" name="boissons"> Boissons
                </div>

                <!-- Résumé et Description -->
                <div class="form-group_modif_offre">
                    <label for="resume">Résumé</label>
                    <textarea id="resume" name="resume">Équipées des dernières technologies, tout a été pensé pour votre confort.</textarea>
                </div>

                <div class="form-group_modif_offre">
                    <label for="description">Description</label>
                    <textarea id="description" name="description">Côté mer, des balcons et terrasses, on est saisi par la force et la beauté du panorama à perte de vue.</textarea>
                </div>

                <!-- Horaires -->
                <h2>Horaires</h2>
                <table class="horaires_table_modif_offre">
                    <thead>
                        <tr>
                            <th>Jours</th>
                            <th>Matin</th>
                            <th>Après-midi</th>
                            <th>Ouvert/Fermé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lundi</td>
                            <td><input type="text" id="lundi-matin" name="lundi-matin" value="09:00 - 12:00" ></td>
                            <td><input type="text" id="lundi-apres-midi" name="lundi-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="lundi-ouvert">
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Mardi</td>
                            <td><input type="text" id="mardi-matin" name="mardi-matin" value="09:00 - 12:00"></td>
                            <td><input type="text" id="mardi-apres-midi" name="mardi-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="mardi-ouvert" checked>
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Mercredi</td>
                            <td><input type="text" id="Mercredi-matin" name="Mercredi-matin" value="09:00 - 12:00"></td>
                            <td><input type="text" id="Mercredi-apres-midi" name="Mercredi-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="Mercredi-ouvert" checked>
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Jeudi</td>
                            <td><input type="text" id="Jeudi-matin" name="Jeudi-matin" value="09:00 - 12:00"></td>
                            <td><input type="text" id="Jeudi-apres-midi" name="Jeudi-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="Jeudi-ouvert" checked>
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Vendredi</td>
                            <td><input type="text" id="Vendredi-matin" name="Vendredi-matin" value="09:00 - 12:00"></td>
                            <td><input type="text" id="Vendredi-apres-midi" name="Vendredi-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="Vendredi-ouvert" checked>
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Samedi</td>
                            <td><input type="text" id="Samedi-matin" name="Samedi-matin" value="09:00 - 12:00"></td>
                            <td><input type="text" id="Samedi-apres-midi" name="Samedi-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="Samedi-ouvert" checked>
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Dimanche</td>
                            <td><input type="text" id="Dimanche-matin" name="Dimanche-matin" value="09:00 - 12:00"></td>
                            <td><input type="text" id="Dimanche-apres-midi" name="Dimanche-apres-midi" value="14:00 - 18:00"></td>
                            <td>
                                <label class="switch_modif_offre">
                                    <input type="checkbox" name="Dimanche-ouvert" checked>
                                    <span class="slider_modif_offre"></span>
                                </label>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                


                <!-- Type d'offre -->
                <h2>Type d'offre</h2>
                <div class="form-group_modif_offre">
                    <input type="radio" name="offre" value="offre-gratuite"> Offre Gratuite
                    <input type="radio" name="offre" value="offre-standard"> Offre Standard
                    <input type="radio" name="offre" value="offre-premium"> Offre Premium
                </div>

                <!-- Options supplémentaires -->
                <div class="form-group_modif_offre">
                    <label>Options supplémentaires :</label>
                    <div>
                        <label><input type="radio" name="booster"> Ne pas booster mon offre</label>
                        <label><input type="radio" name="en-relief"> Mettre mon offre "en Relief"</label>
                        <label><input type="radio" name="a-la-une"> Mettre mon offre "À la Une"</label>
                    </div>
                </div>

                <!-- Durée -->
                <div class="form-group_modif_offre">
                    <label>Durée</label>
                    <div>
                        <label><input type="radio" name="duree" value="1 heure" checked> 1 semaine</label>
                        <label><input type="radio" name="duree" value="2 heures"> 2 semaines</label>
                        <label><input type="radio" name="duree" value="3 heures"> 3 semaines</label>
                        <label><input type="radio" name="duree" value="4 heures"> 4 semaines</label>
                    </div>
                </div>

                <!-- Bouton Valider -->
                <button type="submit" class="bouton_valider_modif_offre">Valider -></button>
                
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>     
        <footer class="footer_modif_offre">
            
            
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
