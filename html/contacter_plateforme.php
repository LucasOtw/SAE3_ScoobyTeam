<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter Plateforme</title>
    <link rel="stylesheet" href="styles.css?">
</head>

<body>
    <header class="header-pc header_membre">
        <div class="logo-pc">
            <a href="voir_offres.php">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="consulter_compte_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <header class="header-tel header_membre">
        <div class="logo-tel">
            <a href="voir_offres.php"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
        </div>
    </header>

        <main class="main_poster_avis">
        <div class="poster_un_avis_container">
            <div class="poster_un_avis_back_button">
                <a href="detail_offre.php"><img src="images/Bouton_retour.png" class="back-button"></a>
                <h1 class="titre_contacter_nous">Contactez nous !</h1>
            </div>

            <<form action="poster_un_avis.php" method="POST">
                <div class="poster_un_avis_section">
                    <h2 class="poster_un_avis_section_titre">Vos informations</h2>
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" class="nom_contacter_plateforme" placeholder="Votre nom" required>
                
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" class="prenom_contacter_plateforme" placeholder="Votre prénom" required>
                
                    <label for="theme">Thème de votre question :</label>
                        <select id="theme" name="theme" class="theme_contacter_plateforme" required>
                            <option value="">Choisir un thème</option>
                            <option value="general">Général</option>
                            <option value="compte">Mon Compte</option>
                            <option value="offres">Offres</option>
                            <option value="partenaires">Partenaires</option>
                        </select>
                </div>
            </form>



                <div class="poster_un_avis_section">
                    <h2 class="poster_un_avis_section_titre">Votre question</h2>
                    <textarea placeholder="Écrivez votre avis ici..." class="poster_un_avis_textarea" name="textAreaAvis" id="textAreaAvis" maxlength="500" required></textarea>
                    <p class="message-erreur avis-vide">Vous devez remplir ce champ</p>
                    <p class="message-erreur avis-trop-long">La question ne doit pas dépasser 500 caractères.</p>
                    <div class="poster_un_avis_footer">
                        <p class="poster_un_avis_disclaimer">En nous envoyant votre question, vous acceptez les conditions générales d'utilisation (CGU).</p>
                        <div class="poster_un_avis_buttons">
                            <button class="poster_un_avis_btn_publier" type="submit" name="publier">Envoyer →</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <nav class="nav-bar">
            <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="Accueil"></a>
            <a href="#"><img src="images/icones/Recent icon.png" alt="Historique"></a>
            <a href="#"><img src="images/icones/Croix icon.png" alt="Ajouter"></a>
            <a href="consulter_compte_membre.php"><img src="images/icones/User icon.png" alt="Mon compte"></a>
        </nav>
    </main>

    <footer class="footer footer_membre">
        <div class="newsletter">
            <div class="newsletter-content">
                <h2>Inscrivez-vous à notre Newsletter</h2>
                <p>PACT</p>
                <p>Redécouvrez la Bretagne !</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Votre adresse mail" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
            <div class="newsletter-image">
                <img src="images/Boiteauxlettres.png" alt="Boîte aux lettres">
            </div>
        </div>

        <div class="footer-links">
            <div class="logo\avis">
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
