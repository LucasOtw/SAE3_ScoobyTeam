<?php
ob_start(); // bufferisation
session_start();

if(!isset($_SESSION['pro'])){
   header('location: connexion_pro.php');
   exit;
}

// Vérifie si le formulaire a été soumis    
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

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
            
                $compte = $_SESSION['pro'];
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
    <link rel="stylesheet" href="poster_reponse_pro.css">
</head>

<body>
   <header>
            <div class="logo">
                <a href="mes_offres.php">
                  <img src="images/logo_blanc_pro.png" alt="PACT Logo">
                </a>
            </div>
            <nav>
                <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
            </ul>
            </nav>
        </header>


        <header class="header-tel header_pro">
            <div class="logo-tel">
                <a href="voir_offres.php"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
            </div>
        </header>

        <main class="main_repondre_avis">
        <div class="repondre_avis_container">
            <div class="repondre_avis_back_button">
            <h1 class="titre_repondre_avis_format_tel">Répondre à un avis</h1>
            </div>
            <h1 class="repondre_avis_titre">Récapitulatif</h1>
            <div class="repondre_avis_recap">
               <div class="repondre_avis_utilisateur"><?php echo $avis['prenom'] . " " . $avis['nom']; ?></div>
               <div class="repondre_avis_texte"><?php echo $avis["txt_avis"]; ?></div>
            </div>

            <form action="poster_reponse_pro.php" method="POST">
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
                    if(isset($_SESSION["pro"]) || !empty($_SESSION["pro"])){
                        echo "consulter_compte_pro.php";
                    } else {
                        echo "connexion_pro.php";
                    }
                ?>">
                <img src="images/icones/User icon.png" alt="image de Personne"></a>
        </nav>
    </main> 

    <footer class="footer footer_pro">
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
