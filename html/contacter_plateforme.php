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
                <a href="<?php echo $_SERVER['HTTP_REFERER']?>" style="position: absolute; top: 50px; left: 5vw; margin-top: 3vh;">
                        <img src="images/Bouton_retour.png" alt="bouton retour">
                </a>
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
                        <option value="Demande d'information générale">Demande d'information générale</option>
                        <option value="Problème avec mon compte">Problème avec mon compte</option>
                        <option value="Problème de paiement ou facturation">Problème de paiement ou facturation</option>
                        <option value="Demande de support technique">Demande de support technique</option>
                        <option value="Suggestion ou amélioration">Suggestion ou amélioration</option>
                        <option value="Partenariat ou collaboration">Partenariat ou collaboration</option>
                        <option value="Problème avec un servic">Problème avec un service</option>
                        <option value="Demande d'annulation ou suppression de compte">Demande d'annulation ou suppression de compte</option>
                        <option value="Question sur les conditions d'utilisation">Question sur les conditions d'utilisation</option>
                        <option value="Problème lié à la sécurité">Problème lié à la sécurité</option>
                        <option value="Question sur les offres et promotions">Question sur les offres et promotions</option>
                        <option value="Demande d'assistance pour une fonctionnalité spécifique">Demande d'assistance pour une fonctionnalité spécifique</option>
                        <option value="Retour sur une expérience utilisateur">Retour sur une expérience utilisateur</option>
                        <option value="Réclamation ou insatisfaction">Réclamation ou insatisfaction</option>
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
            <p>découvrez la Bretagne !</p>
            <form class="newsletter-form" id="newsletterForm">
                <input type="email" id="newsletterEmail" placeholder="Votre adresse mail" required>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
        <div class="newsletter-image">
            <img src="images/Boiteauxlettres.png" alt="Boîte aux lettres">
        </div>
    </div>

    <div id="newsletterConfirmBox" style="display: none;">
        <div class="popup-content">
            <p class="popup-message"></p>
            <button id="closeNewsletterPopup">Fermer</button>
        </div>
    </div>

        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
                    <li><a href="cgu.php">GGU</a></li>
                    <li><a href="cgv.php">CGV</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["membre"]) && !empty($_SESSION["membre"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_membre.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_membre.php">Se connecter</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Nous Connaitre</a></li>
                    <li><a href="obtenir_aide.php">Obtenir de l'aide</a></li>
                    <li><a href="contacter_plateforme.php">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <!--<li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>-->
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
<script>
document.addEventListener('DOMContentLoaded', () => {
    const newsletterForm = document.getElementById('newsletterForm');
    const emailInput = document.getElementById('newsletterEmail');
    const newsletterPopup = document.getElementById('newsletterConfirmBox');
    const closePopupButton = document.getElementById('closeNewsletterPopup');

    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();
        if (email) {
            fetch('envoyer_email3.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email=${encodeURIComponent(email)}`
            })
                .then(() => {
                    afficherPopup("Votre inscription à la newsletter a bien été prise en compte !");
                    
                })
                .catch(() => {
                    afficherPopup("Votre inscription à la newsletter a bien été prise en compte !");
                });
        } else {
            alert("Veuillez entrer une adresse email valide.");
        }
    });

    function afficherPopup(message) {
        newsletterPopup.querySelector('.popup-message').innerText = message;
        newsletterPopup.style.display = 'block';
    }

    closePopupButton.addEventListener('click', () => {
        newsletterPopup.style.display = 'none';
    });
});

</script>
