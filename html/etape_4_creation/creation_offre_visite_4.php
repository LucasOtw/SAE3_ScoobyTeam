<?php

ob_start();
session_start();

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);

include("../recupInfosCompte.php");

if(!isset($_SESSION['aCreeUneOffre'])){
    $_SESSION['aCreeUneOffre'] = false;
}

if(!isset($_SESSION['ajoutOption'])){
    $_SESSION['ajoutOption'] = null; // cette session sert uniquement à éviter l'ajout d'une option si on l'a déjà fait
}

if(!isset($_SESSION['pro'])){
    // si on tente d'accéder à la page sans être connecté à un compte pro, on
    header('location: ../connexion_pro.php');
    exit;
}

echo "<pre>";
var_dump($_SESSION['crea_offre']);
var_dump($_SESSION['crea_offre2']);
var_dump($_SESSION['crea_offre3']);
echo "</pre>";

if(!isset($_POST['valider']) && !isset($_POST['valider_plus_tard'])){
    if(isset($_SESSION['crea_offre']) && isset($_SESSION['crea_offre2']) && isset($_SESSION['crea_offre3'])){
        echo "DOBBY HAS NO MASTER YOU SON OF A BITCH !";
    } else {
        header('location: ../creation_offre.php');
        exit;
    }
}

$infosCB = null;

// on vérifie si le pro a un compte bancaire
if($monComptePro['code_compte_bancaire']){
    // si le pro a un code de compte bancaire, on récupère ses infos
    $recupInfosCB = $dbh->prepare('SELECT * FROM tripenarvor._compte_bancaire WHERE code_compte_bancaire = :code_cb');
    $recupInfosCB->bindValue(":code_cb",$monComptePro['code_compte_bancaire']);
    $recupInfosCB->execute();

    $infosCB = $recupInfosCB->fetch(PDO::FETCH_ASSOC);
}

// si le formulaire est envoyé...

if(isset($_POST['valider'])){
    $code_iban = $_POST['IBAN'];
    $code_BIC = $_POST['BIC'];

    if(validerIBAN($code_iban) && validerBIC($code_BIC)){
        /*
        * VERIFICATION DE L'ADRESSE
        */

        if($_SESSION['aCreeUneOffre'] === false){
            echo "test";
            $adresse_postal = $_SESSION['crea_offre']['adresse'];
            $complement_adresse = $_SESSION['crea_offre']['complementAdresse'];
            $code_postal = $_SESSION['crea_offre']['codePostal'];

            // on fait la vérification dans la base de données
    
            $adresseCorrespondante = $dbh->prepare("SELECT code_adresse FROM tripenarvor._adresse
            WHERE
            adresse_postal = :adresse AND
            (complement_adresse = :complement OR complement_adresse IS NULL) AND
            code_postal = :code_postal AND
            ville = :ville
            ");
            $adresseCorrespondante->bindValue(":adresse",trim($adresse_postal));
            $adresseCorrespondante->bindValue(":complement",trim($complement_adresse));
            $adresseCorrespondante->bindValue(":code_postal",trim($code_postal));
            $adresseCorrespondante->bindValue(":ville",trim($ville));
    
            $adresseCorrespondante->execute();
            $adresseCorrespondante = $adresseCorrespondante->fetch(PDO::FETCH_ASSOC);
    
            if($adresseCorrespondante){
                // si on trouve une adresse exactement similaire dans la base de données
                $code_adresse = $adresseCorrespondante['code_adresse'];
            } else {
                // sinon, on l'insère...
                $ajoutAdresse = $dbh->prepare("INSERT INTO tripenarvor._adresse (adresse_postal,complement_adresse,code_postal,ville) VALUES
                (:adresse,:complement,:code_postal,:ville)");
                $ajoutAdresse->bindValue(":adresse",trim($adresse_postal));
                $ajoutAdresse->bindValue(":complement",trim($complement_adresse));
                $ajoutAdresse->bindValue(":code_postal",trim($code_postal));
                $ajoutAdresse->bindValue(":ville",trim($ville));
    
                $ajoutAdresse->execute();
                // et on récupère le dernier id inséré, dans notre cas, c'est FORCEMENT le code_adresse ! :D
                $code_adresse = $dbh->lastInsertId();
            }

            echo "Bonjour";
            
    
            /*
            * VERIFICATION DES HORAIRES
            */
    
            $tab_horaires = $_SESSION['crea_offre3'];
            // pour chaque jour
            foreach($tab_horaires as $jour => $horaire){
                /* On cherche d'abord le code horaire. */
                $horaireCorrespondante = $dbh->prepare("SELECT code_horaire FROM tripenarvor._horaire
                WHERE
                ouverture = :ouverture AND
                fermeture = :fermeture");
                $horaireCorrespondante->bindValue(":ouverture",$horaire['ouverture']);
                $horaireCorrespondante->bindValue(":fermeture",$horaire['fermeture']);
    
                $horaireCorrespondante->execute();
                $horaireCorrespondante = $horaireCorrespondante->fetch(PDO::FETCH_ASSOC);
    
                if($horaireCorrespondante){
                    echo "HAHA <br>";
                    // si une horraire correspond, on récupère son code
                    $code_horaire[strtolower($jour)] = $horaireCorrespondante['code_horaire'];
                } else {
                    echo "HOHO <br>";
                    // sinon, on l'insère
                    $ajoutHoraire = $dbh->prepare("INSERT INTO tripenarvor._horaire (ouverture,fermeture)
                    VALUES (:ouverture,:fermeture)");
                    $ajoutHoraire->bindValue(":ouverture",$horaire['ouverture']);
                    $ajoutHoraire->bindValue(":fermeture",$horaire['fermeture']);
    
                    $ajoutHoraire->execute();
                    // on récupère le dernier id enregistré, celui du code horaire.
                    $code_horaire[strtolower($jour)] = $dbh->lastInsertId();
                }
            }

            /*
            * INSERTION DES IMAGES
            * Si des images sont présentes dans un dossier au même nom que l'offre, on doit les insérer dans la bdd
            */

            $nom_doss = str_replace(' ','',$_SESSION['crea_offre']['titre_offre']);
            $chemin = "../images/offres/{$nom_doss}";
            echo $chemin;

            if(file_exists($chemin)){
                // si le chemin existe, on récupère tous les fichiers images
                $fichiers = scandir($chemin);
    
                // Filtrer uniquement les images
                $images = array_filter($fichiers, function($fichier) use ($chemin) {
                    $extensions_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
                    return in_array($extension, $extensions_valides) && is_file("$chemin/$fichier");
                });

                $ajout_image = $dbh->prepare("INSERT INTO tripenarvor._image (url_image) VALUES (:url_image)");

                // on insère chaque image (parce-que WHY NOT)

                $id_image = [];
                foreach($images as $image){
                    $url_image = "$chemin/$image";
                    $ajout_image->execute([
                        ":url_image" => $url_image
                    ]);
                    $id_image[] = $dbh->lastInsertId();
                }
            } else {
                die('Le chemin n existe pas');
            }
    
            /*
            * DERNIERS AJOUTS
            */
    
            // on initialise la date actuelle
            $date_offre = date('Y-m-d');
            echo $date_offre;
        }
    }
} else {
    echo "J'aime les enfants";
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="creation_offre3.css">
</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="../images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="../mes_offres.php">Accueil</a></li>
                <li><a href="../creation_offre1.php" class="active">Publier</a></li>
                <li><a href="../connexion_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <?php

    if(isset($_SESSION['crea_offre'])){
        ?>
            <div class="fleche_retour">
                <div>
                    <a href="../etape_3_boost/creation_offre_visite_3.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
                </div>
            </div>
        
            <main class="main-creation-offre3">
        
                <h1>Publier une offre</h1>
                <h2>Ajouter une nouvelle carte</h2>
        
                <div class="form_carte">
                    <form action="#" method="POST">
                        <!-- Numero -->
                        <div class="row">
                            <div class="col">
                                <fieldset>
                                    <legend>IBAN *</legend>
                                    <input type="text" id="IBAN" name="IBAN" value="<?php echo ($infosCB) ? $infosCB['iban'] : ""; ?>" placeholder="IBAN *" required>
                                </fieldset>
                            </div>
                        </div>
        
                        <!-- BIC -->
                        <div class="row">
                            <div class="col">
                                <fieldset>
                                    <legend>BIC *</legend>
                                    <input type="text" id="BIC" name="BIC" value="<?php echo ($infosCB) ? $infosCB['bic'] : ""; ?>" placeholder="BIC *" required>
                                </fieldset>
                            </div>
                        </div>
        
                        <!-- Nom -->
                        <div class="row">
                            <div class="col">
                                <fieldset>
                                    <legend>Nom du compte *</legend>
                                    <input type="text" id="nom" name="nom" value="<?php echo ($infosCB) ? $infosCB['nom_compte'] : ""; ?>" placeholder="Nom du compte *" required>
                                </fieldset>
                            </div>
                        </div>
        
                        <div class="checkbox">
                            <input type="checkbox" id="cgu" name="cgu" required>
                            <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                        </div>
        
                        <div class="boutons">
                            <button type="submit" name="valider" class="btn-primary">Valider</button>
                        </div>
                    </form>
        
                    <div class="carte">
                        <img src="../images/carte_bancaire.png" alt="carte">
                    </div>
                </div>
        
                <p class="terms">En publiant votre offre, vous acceptez les conditions générales d'utilisation (CGU).</p>
        
            </main>
        <?php
    } else {
        ?>
        <h1>Votre offre a été créee avec succès !</h1>
        <a href="../mes_offres.php">Retourner à "Mes offres"</a>
        <?php
    }
    
    ?>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
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
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="creation_offre1.php">Publier</a></li>
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
        </div>

        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><img src="../images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="../images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="../images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="../images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </footer>
</body>
</html>
