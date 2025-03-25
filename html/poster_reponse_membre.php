<?php
ob_start(); // bufferisation
session_start();

if(!isset($_SESSION['membre'])){
   header('location: connexion_membre.php');
   exit;
}

// Vérifie si le formulaire a été soumis    
require_once __DIR__ . ("/../.security/config.php");

// Créer une instance PDO avec les bons paramètres
$dbh = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['repondreAvis'])){
        // Désérialise les données de l'avis
        $avis = unserialize($_POST['unAvis']);
        $_SESSION['avis'] = $avis;
    } else {
        if(isset($_POST['publier'])){
            if((isset($_POST['textAreaAvis']) && !empty($_POST['textAreaAvis']))){
                $texte_avis = trim(isset($_POST['textAreaAvis']) ? htmlspecialchars($_POST['textAreaAvis']) : '');
            
                $compte = $_SESSION['membre'];
                $code_compte = $compte['code_compte'];           
                $code_offre = $_SESSION['detail_offre']['code_offre'];
            
                $erreurs = [];
                $erreur_a_afficher = [];
                if (empty($texte_avis)) {
                    $erreurs[] = "Vous devez remplir ce champ";
                    $erreur_a_afficher[] = "avis-vide";
                } elseif (strlen($texte_avis)>500) {
                    $erreurs[] = "L'avis ne doit pas dépasser 500 caractères.";
                    $erreur_a_afficher[] = "avis-trop-long";
                }
            
                if (empty($erreurs)) {
                    // Crée l'avis dans la table _avis
                    $creerAvis = $dbh->prepare("INSERT INTO tripenarvor._avis (txt_avis, note, code_compte, code_offre) VALUES (:texte_avis, :note, :code_compte, :code_offre)");
                    $note = 0;  // Valeur par défaut de la note (0)
                    $creerAvis->bindParam(':texte_avis', $texte_avis);
                    $creerAvis->bindParam(':note', $note, PDO::PARAM_INT);
                    $creerAvis->bindParam(':code_offre', $code_offre);
                    $creerAvis->bindParam(':code_compte', $code_compte);
                    $creerAvis->execute();
                    
                    // Récupère la dernière valeur de la séquence pour le champ `code_avis`
                    $code_avis = $dbh->lastInsertId('tripenarvor._avis_code_avis_seq');

                    $creerNotif = $dbh->prepare("INSERT INTO tripenarvor._notification (code_avis) values (:code_avis);");
                    $creerNotif->bindParam(':code_avis', $code_avis);
                    $creerNotif->execute();
                    
                    // Insère la réponse dans la table _reponse
                    if (isset($_SESSION['avis']) && !empty($_SESSION['avis']['code_avis'])) {
                        $avis = $_SESSION['avis'];
                        $code_reponse = $dbh->prepare("SELECT currval('tripenarvor._avis_code_avis_seq')");
                        $code_reponse->execute();
                        $code_reponse = $code_reponse->fetchColumn();
                    
                        // Insère la réponse en associant le code_avis à la réponse
                        $creerReponse = $dbh->prepare("INSERT INTO tripenarvor._reponse (code_avis, code_reponse) VALUES (:code_avis, :code_reponse)");
                        $creerReponse->bindParam(':code_avis', $avis['code_avis']);
                        $creerReponse->bindParam(':code_reponse', $code_reponse);
                        $creerReponse->execute();
                    } else {
                        // Si le code_avis n'est pas valide dans la session
                        echo "Erreur : L'avis n'est pas disponible ou mal formé.";
                        exit;
                    }
                    
                    // Redirige vers la page des détails de l'offre
                    header('location: detail_offre.php');
                    exit;
                } else {
                    // Affiche les erreurs s'il y en a
                    foreach($erreur_a_afficher as $classe_erreur){
                        ?> 
                        <style>
                            main.main_poster_avis .<?php echo $classe_erreur ?> {
                                display: block;
                            }
                        </style> 
                        <?php
                    }
                }
            } else {
                echo "Vous ne pouvez pas poster d'avis sans notation ou de message !";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster un avis</title>
    <link rel="stylesheet" href="styles.css">
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
         <?php         

          if ($avis['code_compte'] == $_SESSION['membre']['code_compte']){
              $prenom = "Moi";
              $nom = "";
              $color = "--vert-clair";
          } elseif (!empty($avis['raison_sociale_pro'])) {
              // Si c'est un professionnel
              $prenom = $avis['raison_sociale_pro'];
              $nom = "";
              $color = "--orange";
          } elseif (!empty($avis['prenom']) && !empty($avis['nom'])) {
              // Si c'est un membre classique
              $prenom = $avis['prenom'];
              $nom = $avis['nom'];
              $color = "--vert-clair";    
          } else {
              // Si l'utilisateur est supprimé
              $prenom = "Utilisateur";
              $nom = "supprimé";
          }
?>

        <main class="main_repondre_avis">
        <div class="repondre_avis_container">
            <div class="repondre_avis_back_button">
            <h1 class="titre_repondre_avis_format_tel">Répondre à un avis</h1>
            </div>
            <h1 class="repondre_avis_titre">Récapitulatif</h1>
            <div class="repondre_avis_recap">
               <div class="repondre_avis_utilisateur"><?php echo $prenom . " " . $nom;?></div>
               <div class="repondre_avis_texte"><?php echo $avis["txt_avis"]; ?></div>
            </div>

            <form action="poster_reponse_membre.php" method="POST">
               <div class="repondre_avis_section">
                   <h2 class="repondre_avis_section_titre">Votre avis</h2>

                   <textarea placeholder="Répondez ici..." class="repondre_avis_textarea" name="textAreaAvis" id="textAreaAvis"></textarea>
                   <p class="message-erreur avis-vide">Vous devez remplir ce champ</p>
                   <p class="message-erreur avis-trop-long">L'avis ne doit pas dépasser 500 caractères.</p>                  
                  
                       <div class="repondre_avis_buttons">
                           <!--<button class="repondre_avis_btn_annuler">Annuler</button>-->
                           <button class="repondre_avis_btn_publier" type="submit"- name="publier">Publier →</button>
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


