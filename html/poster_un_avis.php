<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

if(isset($_GET['logout'])){
   session_unset();
   session_destroy();
   header('location: connexion_membre.php');
   exit;
}

if(!isset($_SESSION['membre'])){
   header('location: connexion_membre.php');
   exit;
}

// Vérifie si le formulaire a été soumis    
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

// Créer une instance PDO avec les bons paramètres
$dbh = new PDO($dsn, $username, $password);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster un avis</title>
    <link rel="stylesheet" href="poster_un_avis.css">
</head>

<body>
    <header class="header-pc">
        <div class="logo-pc">
            <img src="images/logoBlanc.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="connexion_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
      </header>
        
        <header class="header-tel">
            <div class="logo-tel">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </div>
        </header>
        
        <div class="poster_un_avis_container">
            <div class="poster_un_avis_back_button">
            <a href="" class="back-button">&larr;</a>
            <h1 class="titre_poster_un_avis_format_tel">Publier un avis</h1>
            </div>
            <h1 class="poster_un_avis_titre">Récapitulatif</h1>
            <div class="poster_un_avis_recap_card">
                <div class="poster_un_avis_info">
                    <h2 class="poster_un_avis_nom">Ti Al Lannec - Hôtel & Restaurant</h2>
                    <p class="poster_un_avis_location">📍 Trébeurden, Bretagne 22300</p>
                    <button class="poster_un_avis_btn_offre">Voir l'offre →</button>
                </div>
                <div class="poster_un_avis_images">
                    <img src="images/tiallannec1.png" alt="Image 1" class="poster_un_avis_image">
                    <img src="images/tiallannec3.png" alt="Image 2" class="poster_un_avis_image">
                </div>
            </div>

            <form action="poster_un_avis.php" method="POST">
               <div class="poster_un_avis_section">
                   <h2 class="poster_un_avis_section_titre">Votre avis</h2>
                  
                   <textarea placeholder="Écrivez votre avis ici..." class="poster_un_avis_textarea" name="textAreaAvis" id="textAreaAvis"></textarea>
                  
                   <div class="poster_un_avis_footer">

                       <div class="poster_un_avis_note">
                           <h2 class="poster_un_avis_note_titre">Votre note</h2>
                           <div class="poster_un_avis_stars">
                               <span class="poster_un_avis_star">⭐</span>
                               <span class="poster_un_avis_star">⭐</span>
                               <span class="poster_un_avis_star">⭐</span>
                               <span class="poster_un_avis_star">⭐</span>
                               <span class="poster_un_avis_star">⭐</span>
                               <figure class="notation">
                            <span role="img" aria-labelledby="rating-67471c1e61e38" style="width: 66%"></span>
                            <figcaption class="screen-readers" id="rating-67471c1e61e38">noté 3,3 sur 5</figcaption>
                            </figure>

                           </div>
                           

                           <p class="poster_un_avis_disclaimer">
                               En publiant votre avis, vous acceptez les conditions générales d'utilisation (CGU).
                           </p>
                       </div>
                      
                       <div class="poster_un_avis_buttons">
                           <button class="poster_un_avis_btn_annuler">Annuler</button>
                           <button class="poster_un_avis_btn_publier">Publier →</button>
                       </div>
                      
                    </div>
                  </div>
               </div>
            </form>



   
        <nav class="nav-bar">
            <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
            <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
            <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
            <a href="
                <?php
                    if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                        echo "consulter_compte_membre.php";
                    } else {
                        echo "connexion_membre.php";
                    }
                ?>">
                <img src="images/icones/User icon.png" alt="image de Personne"></a>
        </nav>
    </main>
    <?php
        if(!empty($_POST)){
            $prenom = trim(isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '');
        }
    ?>
                    
    
    <footer class="footer_poster_avis">
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
