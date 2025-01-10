<!DOCTYPE html>
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
                <h1 class="titre_contacter_nous">Contactez nous !</h1>
            </div>

            <form id="contactForm" action="envoyer_email.php" method="POST">
                <div class="poster_un_avis_section">
                    <h2 class="poster_un_avis_section_titre">Vos informations</h2>
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom_contacter_plateforme" name="nom" placeholder="Votre nom" required>
                
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom_contacter_plateforme" name="prenom" placeholder="Votre prénom" required>
                
                    <label for="theme">Thème de votre question :</label>
                    <select id="theme" name="theme" class="theme_contacter_plateforme" required>
                        <option value="">Choisir un motif</option>
                        <option value="information">Demande d'information générale</option>
                        <option value="compte">Problème avec mon compte</option>
                        <option value="paiement">Problème de paiement ou facturation</option>
                        <option value="technique">Demande de support technique</option>
                        <option value="suggestion">Suggestion ou amélioration</option>
                        <option value="partenariat">Partenariat ou collaboration</option>
                        <option value="produit">Problème avec un service</option>
                        <option value="annulation">Demande d'annulation ou suppression de compte</option>
                        <option value="CGU">Question sur les conditions d'utilisation</option>
                        <option value="securite">Problème lié à la sécurité</option>
                        <option value="offres">Question sur les offres et promotions</option>
                        <option value="fonctionnalite">Demande d'assistance pour une fonctionnalité spécifique</option>
                        <option value="avis">Retour sur une expérience utilisateur</option>
                        <option value="reclamation">Réclamation ou insatisfaction</option>
                    </select>
                </div>
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
        <!-- Popup de confirmation -->
            <div class="custom-confirm" id="customConfirmBox">
                <div class="custom-confirm-content">
                    <p>Votre message a été envoyé avec succès !</p>
                    <button id="confirmButton">Fermer</button>
                </div>
            </div>
        <style>
            /* Style de la boîte de dialogue personnalisée */
            .custom-confirm {
                display: none; /* Masquer par défaut */
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }
            
            .custom-confirm-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                border: 3px solid var(--vert-clair);
            }
            
            .custom-confirm-content p {
                margin-bottom: 20px;
                font-size: 16px;
            }
            
            .custom-confirm-content button {
                background-color: var(--vert-clair);
                color: white;
                border: none;
                border-radius: 16px;
                border: 1px #BDC426 solid;
                cursor: pointer;
                margin-right: 1em;
                box-shadow: 0px 6px 19px rgba(0, 56, 255, 0.24);
                transition: transform 0.2s ease;
                text-decoration: none;
                width: 10em;
                height: 2em;
            }
            
            .custom-confirm-content button:hover {
                background-color: #FFF;
                color: black;
                border: 1px #BDC426 solid;
                transform: translateY(-2px);
            }
            
            .custom-confirm-content button:last-child {
                background-color: #FFF;
                color: black;
                border: none;
                border-radius: 16px;
                cursor: pointer;
                margin-right: 1em;
                box-shadow: 0px 6px 19px rgba(0, 56, 255, 0.24);
                transition: transform 0.2s ease;
                text-decoration: none;
            }
            
            .custom-confirm-content button:last-child:hover {
                background-color: var(--vert-clair);
                color: white;
                transform: translateY(-2px);
            }

        </style>

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

    <!-- Ajouter jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault(); // Empêcher la soumission classique du formulaire

        var formData = $(this).serialize(); // Sérialiser les données du formulaire

        $.ajax({
            type: 'POST',
            url: 'envoyer_email.php',
            data: formData,
            success: function(response) {
                // Afficher la popup de confirmation
                $('#customConfirmBox').fadeIn();
            },
            error: function() {
                alert('Une erreur est survenue lors de l\'envoi du message.');
            }
        });
    });

    // Fermer la popup quand l'utilisateur clique sur "Fermer"
    $('#confirmButton').on('click', function() {
        $('#customConfirmBox').fadeOut();
    });

});
    </script>

</body>
</html>
