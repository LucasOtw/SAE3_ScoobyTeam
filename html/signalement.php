<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Offres PACT</title>
    <link rel="stylesheet" href="signalement.css">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

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

    <script>
        // Fonction pour afficher la modale
        function showConfirmation(event) {
            event.preventDefault(); // Empêche le comportement par défaut du bouton
            document.getElementById('confirmationModal').style.display = 'block'; // Affiche la modale
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
</head>
<body>
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
                <?php if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])): ?>
                    <li><a href="consulter_compte_membre.php">Mon Compte</a></li>
                <?php else: ?>
                    <li><a href="connexion_membre.php">Se connecter</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</div>
<div class="container">
    <h2 class="titre_signalement_1">Signaler un avis</h2>
    <?php if (isset($erreur)): ?>
        <div class="erreur">
            <p><?php echo $erreur; ?></p>
        </div>
    <?php else: ?>
        <div class="avis">
            <?php 
                if ($avis) {
                    echo "<h3>" . htmlspecialchars($avis['note']) . ".0 | " . htmlspecialchars($avis['prenom']) . " " . htmlspecialchars($avis['nom']) . "</h3>";
                    echo "<p>" . htmlspecialchars($avis['txt_avis']) . "</p>";
                } else {
                    echo "Aucun avis trouvé pour cet ID.";
                }
            ?>
        </div>
    <?php endif; ?>
    <h2 class="titre_signalement_2">Cause du signalement</h2>
    <div class="type_offre_select_button">
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
    <form method="POST" action="signalement_2.html">
        <input type="hidden" name="id_avis" value="<?php echo $idAvis; ?>">
        <button type="submit" onclick="showConfirmation(event)">Confirmer le signalement</button>
    </form>
</div>

<!-- Modale de confirmation -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="popup-message">
            <p>Votre signalement a bien été envoyé et pris en compte !</p>
        </div>
    </div>
</div>
</body>
</html>
