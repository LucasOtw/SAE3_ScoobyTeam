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
var_dump($_SESSION);
echo "lol";
echo "</pre>";

if (!isset($_POST['valider']) && !isset($_POST['valider_plus_tard'])) {
    // Vérifier si une ou plusieurs sessions nécessaires ne sont pas définies
    if (!isset($_SESSION['crea_offre']) || !isset($_SESSION['crea_offre2']) || !isset($_SESSION['crea_offre3'])) {

        // Cas où SEULEMENT $_SESSION['crea_offre3'] manque
        if (!isset($_SESSION['crea_offre3']) 
            && isset($_SESSION['crea_offre']) 
            && isset($_SESSION['crea_offre2'])) {

            // Vérifier si l'utilisateur est un professionnel privé
            if (isset($monComptePro['num_siren'])) {
                // Redirection obligatoire vers la page des boosts pour un professionnel privé
                header('location: ../etape_3_boost/creation_offre_attraction_3.php');
                exit;
            }
        } else {
            header('location: ../etape_3_boost/creation_offre_attraction_3.php');
        }
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

if(isset($_POST['valider']) || isset($_POST['passer_cb']) || isset($_POST['creer_offre_gratuite'])){

    if(!isset($_POST['passer_cb']) && !isset($_POST['creer_offre_gratuite'])){
        $code_iban = $_POST['IBAN'];
        $code_BIC = $_POST['BIC'];
        $nom_compte = $_POST['nom'];
        if(empty($code_iban) || empty($code_BIC) || empty($nom_compte)){
            echo "Des informations sont manquantes !";
            exit;
        } else {
            if(!validerIBAN($code_iban) || !validerBIC($code_BIC)){
                echo "IBAN ou BIC incorrect !";
                exit;
            }
        }
    }

        if($_SESSION['aCreeUneOffre'] === false){
            $adresse_postal = $_SESSION['crea_offre']['adresse'];
            $complement_adresse = $_SESSION['crea_offre']['complementAdresse'];
            $code_postal = $_SESSION['crea_offre']['codePostal'];
            $ville = $_SESSION['crea_offre']['ville'];
            

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
            
    
            /*
            * VERIFICATION DES HORAIRES
            */
    
            $tab_horaires = $_SESSION['crea_offre2'];
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
                    
                    // si une horraire correspond, on récupère son code
                    $code_horaire[strtolower($jour)] = $horaireCorrespondante['code_horaire'];
                } else {
                    
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

            if (file_exists($chemin)) {
                // Récupérer tous les fichiers images
                $fichiers = scandir($chemin);
            
                // Filtrer uniquement les images
                $images = array_filter($fichiers, function($fichier) use ($chemin) {
                    $extensions_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
                    return in_array($extension, $extensions_valides) && is_file("$chemin/$fichier");
                });
            
                // Préparer les requêtes
                $verifier_image = $dbh->prepare("SELECT code_image FROM tripenarvor._image WHERE url_image = :url_image");
                $ajout_image = $dbh->prepare("INSERT INTO tripenarvor._image (url_image) VALUES (:url_image)");
            
                // Tableau pour stocker les IDs des images insérées
                $id_image = [];
                
                foreach ($images as $image) {
                    $url_image = "$chemin/$image";
            
                    // Vérifier si l'image existe déjà
                    $verifier_image->execute([
                        ":url_image" => $url_image
                    ]);
                    $code_image = $verifier_image->fetch(PDO::FETCH_ASSOC);
            
                    if ($code_image) {
                        // L'image existe déjà, on peut ignorer ou effectuer une autre action
                        $id_image[] = $code_image['code_image'];
                    } else {
                        // Insérer l'image si elle n'existe pas
                        $ajout_image->execute([
                            ":url_image" => $url_image
                        ]);
                        $id_image[] = $dbh->lastInsertId();
                    }
                }
            } else {
                die('Le chemin n existe pas');
            }
    
            /*
            * DERNIERS AJOUTS
            */
    
            // on initialise la date actuelle
            $date_offre = date('Y-m-d');

            // on vérifie si le pro a choisi une option (SI PRIVE)
            if(isset($monComptePro['num_siren'])){
                $prix_option = null;
                switch($_SESSION['crea_offre3']['option']){
                    case "aucune":
                        break;
                    case "en_relief":
                        $prix_option = 10.00;
                        break;
                    case "a_la_une":
                        $prix_option = 20.00;
                        break;
                }
    
                /*
                * INSERTIONS
                */
    
                if($prix_option !== null){
                    // si il y a un prix, il y a une option
                    $nbSemaines_option = $_SESSION['crea_offre3']['duree_option'];
                    $champ_option = "option_".$_SESSION['crea_offre3']['option'];
    
                    // Calcul de la date de fin
                    $date_fin = new DateTime($date_offre);
                    $date_fin->modify("+{$nbSemaines_option} weeks");
                    $date_fin_option = $date_fin->format('Y-m-d');
    
                    // on peut ajouter l'option
    
                    if($_SESSION['ajoutOption'] === null){
                        // si la session est null, alors on n'a pas rajouté d'option
                        $ajoutOption = $dbh->prepare("INSERT INTO tripenarvor._option (nb_semaines,date_debut,date_fin,prix) VALUES
                        (:nb_semaines,:date_debut,:date_fin,:prix)");
                        $ajoutOption->bindValue(":nb_semaines",$nbSemaines_option);
                        $ajoutOption->bindValue(":date_debut",$date_offre);
                        $ajoutOption->bindValue(":date_fin",$date_fin_option);
                        $ajoutOption->bindValue(":prix",$prix_option);
    
                        if($ajoutOption->execute()){
                            $_SESSION['ajoutOption'] = $dbh->lastInsertId(); // on récupère l'id de l'option ajoutée
                        }
                    }
                } else {
                    $champ_option = "";
                }
    
                // on récupère aussi le nom du type de l'offre
                $champ_type_offre = null;
                switch($_SESSION['crea_offre3']['offre']){
                    case "gratuite":
                        $champ_type_offre = "Offre Gratuite";
                        break;
                    case "standard":
                        $champ_type_offre = "Offre Standard";
                        break;
                    case "premium":
                        $champ_type_offre = "Offre Premium";
                        break;
                }
                if($champ_type_offre === null){
                    echo "Erreur : le type de l'offre ne peut pas être null !";
                    exit;
                }
            } else {
                $champ_type_offre = "Offre Gratuite";
            }

            // on crée un tableau pour stocker les données dynamiquement

            $mon_offre = [
                'titre_offre' => $_SESSION['crea_offre']['titre_offre'],
                'date_publication' => $date_offre,
                'date_derniere_modif' => $date_offre,
                '_resume' => $_SESSION['crea_offre']['resume'],
                '_description' => $_SESSION['crea_offre']['description'],
                'note_moyenne' => null,
                'tarif' => $_SESSION['crea_offre']['prix'],
                'en_ligne' => false,
                'nb_blacklister' => 0,
                'code_adresse' => $code_adresse,
                'professionnel' => $_SESSION['pro']['code_compte'],
                'nom_type' => $champ_type_offre,
            ];

            if(isset($_SESSION['crea_offre']['lien']) && !empty($_SESSION['crea_offre']['lien'])){
                $mon_offre['site_web'] = $_SESSION['crea_offre']['lien'];
            }
            if(isset($_SESSION['crea_offre']['accessibilite']) && !empty($_SESSION['crea_offre']['accessibilite'])){
                $mon_offre['accessibilite'] = $_SESSION['crea_offre']['accessibilite'];
            }

            if(isset($_SESSION['crea_offre3']['option']) && !empty($_SESSION['crea_offre3']['option'])){
                if(trim($_SESSION['crea_offre3']['option']) == "en_relief"){
                    $mon_offre['option_en_relief'] = $_SESSION['ajoutOption'];
                } else if (trim($_SESSION['crea_offre3']['option']) == "a_la_une"){
                    $mon_offre['option_a_la_une'] = $_SESSION['ajoutOption'];
                }
            }

            $mon_offre = array_merge($mon_offre,$code_horaire);

            $mon_offre['en_ligne'] = $mon_offre['en_ligne'] ? 'true' : 'false';

            // on passe à l'exécution

            $columns = implode(", ", array_keys($mon_offre)); // liste des colonnes
            $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($mon_offre))); // liste des placeholders

            $creation_offre_req = "INSERT INTO tripenarvor._offre ($columns) VALUES ($placeholders)";
            $creation_offre = $dbh->prepare($creation_offre_req);
            
            if($creation_offre->execute($mon_offre)){
                // on récupère son id
                $id_offre = $dbh->lastInsertId();
                // on lui ajoute ses images
                $son_image = $dbh->prepare("INSERT INTO tripenarvor._son_image VALUES (:code_image,:code_offre)");
                foreach($id_image as $code_image){
                    $son_image->execute([
                        ":code_image" => $code_image,
                        ":code_offre" => $id_offre
                    ]);
                }
                // cette offre est un parc d'attraction
                if (empty($_SESSION['crea_offre']['nb_attractions'])) {
                    $nb_attractions = null;
                } else {
                    $nb_attractions = $_SESSION['crea_offre']['nb_attractions'];
                }

                // l'âge requis EST requis (0 min)
                $age_requis = $_SESSION['crea_offre']['age_requis'];
                
                // Préparation de la requête
                $ajoutVisite = $dbh->prepare("
                    INSERT INTO tripenarvor._offre_parc_attractions
                    (code_offre, nombre_attractions, age_requis)
                    VALUES (:code_offre,:nb_attractions,:age_requis)
                ");
                
                $ajoutVisite->bindValue(":code_offre", $id_offre);
                $ajoutVisite->bindValue(":nb_attractions", $nb_attractions);
                $ajoutVisite->bindValue(":age_requis", $age_requis);
                
                $ajoutVisite->execute();

                if(isset($_SESSION['crea_offre']['tags']) && is_array($_SESSION['crea_offre']['tags'])){
                    foreach($_SESSION['crea_offre']['tags'] as $tag){
                        try {
                            $ajoutTag = $dbh->prepare("INSERT INTO tripenarvor._son_tag (code_tag, code_offre) VALUES (:code_tag, :code_offre)");
                            $ajoutTag->bindValue(":code_tag",$tag);
                            $ajoutTag->bindValue(":code_offre",$id_offre);
                            $ajoutTag->execute();
                        } catch (PDOException $e){
                            echo "Erreur lors de l'ajout du tag : " . $e->getMessage();
                        }
                    }
                }
                $_SESSION['aCreeUneOffre'] = true;
                unset($_SESSION['crea_offre']);
                unset($_SESSION['crea_offre2']);
                unset($_SESSION['crea_offre3']);
                unset($_SESSION['ajoutOption']);
            }
        }
    }


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre - Visite</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="../images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
        <nav>
            <ul>
                <li><a href="../mes_offres.php">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="../consulter_compte_pro.php">Mon compte</a></li>
            </ul>
        </nav>
    </header>
    <?php
    if(isset($_SESSION['crea_offre'])){
    ?>
        <div class="fleche_retour">
            <div>
                <a href="../etape_3_boost/creation_offre_attraction_2.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
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
                        <button type="submit" name="passer_cb" class="btn-secondary">Plus tard...</button>
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
    <div class="button-container">
        <img src="../images/verifier.png" alt="Succès" class="success-icon">
        <h1 class="success-message">Votre offre a été créée avec succès !</h1>
        <div class="buttons">
            <a href="../mes_offres.php" class="back-link-offres">Retourner à "Mes offres"</a>
            <a href="../telecharger_facture.php" class="back-link-facture">Télécharger ma facture</a>
        </div>
    </div>




    <?php
}    
?>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="../images/logo_blanc_pro.png" alt="PACT Logo">
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