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

$details_offre = unserialize($_POST["uneOffre"]); // on récupère son contenu

echo "<pre>";
var_dump($details_offre);
echo "</pre>";



echo "<pre>";
var_dump($compte);
echo "</pre>";

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
                    <h2 class="poster_un_avis_nom"><?php echo $details_offre["titre_offre"]; ?><!-- - <?php // echo $details_offre["titre_offre"]; ?>--></h2>
                    <p class="poster_un_avis_location">📍 <?php echo $details_offre["ville"]; ?>, <?php echo $details_offre["code_postal"]; ?></p>
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
                          
                              <fieldset class="notation">
                                         <input type="radio" id="star5" name="note" value="5" />
                                         <label for="star5" title="5 étoiles"></label>
            
                                          <input type="radio" id="star4" name="note" value="4" />
                                          <label for="star4" title="4 étoiles"></label>
            
                                          <input type="radio" id="star3" name="note" value="3" />
                                          <label for="star3" title="3 étoiles"></label>
            
                                          <input type="radio" id="star2" name="note" value="2" />
                                          <label for="star2" title="2 étoiles"></label>
            
                                          <input type="radio" id="star1" name="note" value="1" />
                                          <label for="star1" title="1 étoile"></label>
                              </fieldset>
                      
                            </div>

                           

                           <p class="poster_un_avis_disclaimer">
                               En publiant votre avis, vous acceptez les conditions générales d'utilisation (CGU).
                           </p>
                       </div>
                      
                       <div class="poster_un_avis_buttons">
                           <button class="poster_un_avis_btn_annuler">Annuler</button>
                           <input type="hidden" name="uneOffre" value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
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
            $texte_avis = trim(isset($_POST['textAreaAvis']) ? htmlspecialchars($_POST['textAreaAvis']) : '');
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            
            $compte = $_SESSION['membre'];
            $code_compte = $compte['code_compte'];

           
            $code_offre = $details_offre["code_offre"];
           
            $erreurs = [];
            if (empty($texte_avis)) {
                $erreurs[] = "Vous devez remplir ce champ";
            } elseif (strlen($texte_avis)>500) {
                $erreurs[] = "L'avis ne doit pas dépasser 500 caractères.";
            }


            $creerAvis = $dbh->prepare("INSERT INTO tripenarvor._avis (txt_avis, note, code_compte, code_offre) VALUES (:texte_avis, :note, :code_compte, :code_offre)");

            // Liez les valeurs aux paramètres
            $creerAvis->bindParam(':texte_avis', $texte_avis);
            $creerAvis->bindParam(':note', $note);
            $creerAvis->bindParam(':code_offre', $code_offre);
           
            $creerAvis->bindParam(':code_compte', $code_compte);

           $creerAvis->execute()
        }
        foreach($erreurs as $erreur){
              echo $erreur;
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
