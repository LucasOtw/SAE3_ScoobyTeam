<?php
// Connexion à la base de données
session_start();
require_once __DIR__ . ("/../.security/config.php");

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'ID d'avis est passé dans l'URL
    if (isset($_GET['id_avis']) && !empty($_GET['id_avis'])) {
        $idAvis = $_GET['id_avis'];

        // Vérifier que l'ID est un entier valide
        if (is_numeric($idAvis) && $idAvis > 0) {
            // Requête préparée avec un paramètre dynamique :id
            $stmt = $dbh->prepare("
                SELECT * 
                FROM tripenarvor._avis 
                NATURAL JOIN tripenarvor._membre
                WHERE code_compte=2 AND code_avis = :id
            ");
            
            // Lier le paramètre :id et exécuter la requête
            $stmt->execute([':id' => $idAvis]);

            // Récupérer l'avis correspondant
            $avis = $stmt->fetch();
            var_dump($avis);
        } else {
            // Si l'ID n'est pas un nombre valide
            $erreur = "L'ID d'avis est invalide.";
        }
    } else {
        // Aucun ID passé dans l'URL
        $erreur = "Aucun ID d'avis spécifié.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs
    $erreur = "Erreur de connexion ou d'exécution : " . $e->getMessage();
}
?>

<script>
    // Fonction pour afficher la modale
    function showConfirmation(event) {
        event.preventDefault(); // Empêche l'envoi immédiat du formulaire
        document.getElementById('confirmationModal').style.display = 'block'; // Affiche la modale

        const avisId = <?php echo json_encode($idAvis); ?>;

        fetch('/modif_signaler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: avisId })
        })
        .then(response => response.json()) // Parse la réponse JSON
        .then(data => {
            if (data.success) {
                alert('Avis signalé avec succès.');
                window.location.reload(); // Recharge la page ou redirigez si nécessaire
            } else {
                alert(data.message || 'Erreur lors du signalement de l\'avis.');
            }
        })
        .catch(error => {
            console.error('Erreur réseau ou serveur :', error);
            alert('Impossible de signaler l\'avis.');
        });
    }

    // Fonction pour fermer la modale
    function closeModal() {
        document.getElementById('confirmationModal').style.display = 'none'; // Cache la modale
    }

    // Ferme la modale si l'utilisateur clique en dehors de la fenêtre de la modale
    window.onclick = function(event) {
        var modal = document.getElementById('confirmationModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalement d'un avis</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<body style=" align-items: center;">
<div class="header-membre">
        <header class="header-pc">
        
            <div class="logo-pc" style="z-index: 1">
                <a href="voir_offres.php">
                    <img src="images/logoBlanc.png" alt="PACT Logo">
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="voir_offres.php" class="active">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                        if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                           ?>
                           <li>
                               <a href="consulter_compte_membre.php"><?php echo "Mon Compte"; ?></a>
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
            </nav>
        </header>
        <header class="header-tel">
        <svg
            width="428"
            height="202"
            viewBox="0 0 428 202"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M0 126.87L0 0H428V134.241C374.61 176.076 300.465 202 218.5 202C131.823 202 53.891 173.01 0 126.87Z"
                fill="#E8E8E8"
            ></path>
        </svg>
            <div class="logo-tel">
                <img src="images/LogoCouleur.png" alt="PACT Logo">
            </div>
        </header>
    </div>
        <div class="signalement-container">
            <a href="<?php echo $_SERVER['HTTP_REFERER']?>" style="position: absolute; top: 50px; left: 5vw;">
                    <img src="images/Bouton_retour.png" alt="bouton retour">
            </a>
            <h2 class="titre_signalement_1">Signaler un avis</h2>
            <?php if (isset($erreur)): ?>
                <div class="erreur">
                    <p><?php echo $erreur; ?></p>
                </div>
            <?php else: ?>
                <div class="signalement-avis">
                    <?php 
                        if ($avis) {
                            // Afficher les informations de l'avis trouvé
                            if (htmlspecialchars($avis['note']) == 0)
                            {
                                $note_avis='Réponse';
                            } else {
                                $note_avis=htmlspecialchars($avis['note']).".0";
                            }
                            
                            echo "<h3>" . $note_avis . " | " . htmlspecialchars($avis['prenom']) . " " . htmlspecialchars($avis['nom']) . "</h3>";
                            echo "<p>" . $avis['txt_avis'] . "</p>";
                        } else {
                            // Aucun avis trouvé avec cet ID
                            echo "Aucun avis trouvé pour cet ID.";
                        }
                    ?>
                </div>
                
            <?php endif; ?>
            <h2 class="titre_signalement_2">Cause du signalement</h2>
            <div class="signalement-type_offre_select_button">
                        <select id="offre" name="offreChoisie" data-placeholder="Sélectionnez..." required>
                            <option value="" hidden selected>Sélectionnez...</option>
                            <option value="restaurant">Language déplacé</option>
                            <option value="spectacle">Harcèlement</option>
                            <option value="visite">Diffamation</option>
                            <option value="attraction">Spam</option>
                            <option value="activite">Autre</option>
                        </select>
    
            </div>
            <h2 class="titre_signalement_3">Description (facultatif)</h2>
            <textarea placeholder="Écrivez votre avis ici..." class="signaler_un_avis_textarea" name="textAreaAvis" id="textAreaAvis"></textarea>
            <form method="POST" action="signalement.php" class="signalement-fom">
                <input type="hidden" name="id_avis" value="<?php echo $idAvis; ?>">
                <button class="signalement" type="submit" onclick="showConfirmation(event)">Confirmer le signalement</button>
            </form>
    
        </div>
        <!-- Modale de confirmatiosn -->
    <div id="confirmationModal" class="modal">
        <div class="signalement-modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="popup-message">
                <p>Votre signalement a bien été envoyé et pris en compte !</p>
            </div>
        </div>
    </div>
    
    <!-- Style pour la modale -->
    <style>
        /* Styles pour la modale */
        .modal {
            display: none; /* Masquer la modale par défaut */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5); /* Fond sombre */
            overflow: auto;
            padding-top: 60px;
        }
    
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }
    
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
    
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    
        .popup-message {
            text-align: center;
            font-size: 18px;
            color: green;
        }
    </style>
    <nav class="nav-bar">
        <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
        <a href="consulter_mes_avis.php"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
        <a href="incitation.php"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
        <a href="
        <?php
        if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
            echo "compte_membre_tel.php";
        } else {
            echo "connexion_membre.php";
        }
        ?>">
            <img src="images/icones/User icon.png" alt="image de Personne"></a>
    </nav>
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
            <button class="signalement" id="closeNewsletterPopup">Fermer</button>
        </div>
    </div>

        <div class="footer-links">
        <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.php">Mentions Légales</a></li>
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

</body>
</html>
