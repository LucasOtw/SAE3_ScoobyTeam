<?php
ob_start();
session_start();

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

// Créer une instance PDO avec les bons paramètres
$dbh = new PDO($dsn, $username, $password);

include("recupInfosCompte.php");

if (isset($_GET["deco"])) {
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}
if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}

// Définir le filtre par défaut
$filter = "all";
if (isset($_GET["filter"])) {
    $filter = $_GET["filter"];
}

if(isset($_SESSION['aCreeUneOffre'])){
    unset($_SESSION['aCreeUneOffre']);
}

if (isset($_POST['envoiOffre'])) {
    $_SESSION['modif_offre'] = unserialize($_POST['uneOffre']);
    $offre = $_SESSION['modif_offre'];
}

$offre = $_SESSION['modif_offre'];

// On récupère les infos de l'offre en fonction de son "type"

$tables = [
    '_offre_activite',
    '_offre_parc_attractions',
    '_offre_restauration',
    '_offre_spectacle',
    '_offre_visite'
];

$infos_offre = null;

foreach($tables as $table){
    // on cherche les infos de l'offre dans chaque table, SI elle est présente
    
    $requete = "SELECT t.* FROM tripenarvor.$table t JOIN tripenarvor._offre o
    ON o.code_offre = t.code_offre
    WHERE t.code_offre = :code_offre";

    $stmt = $dbh->prepare($requete);
    $stmt->bindValue(":code_offre",$offre['code_offre']);
    $stmt->execute();

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($res)){
        $infos_offre = $res;
        break;
    }
}

if($infos_offre !== null){
    echo "<pre>";
    var_dump($infos_offre);
    echo "</pre>";
}

if (isset($_POST['envoi_modif'])){

    $erreurs = [];

    if(empty($_POST['_titre_modif']) || empty($_POST['_resume_modif']) || empty($_POST['_desc_modif'])){
        $erreurs[] = "Des champs obligatoires ne sont pas remplis !";
    }

    /* VÉRIFICATION DU CODE POSTAL */

    if(!empty($_POST['_ville_modif']) && !empty($_POST['_code_postal_modif'])){

        $ville = $_POST['_ville_modif'];
        $codePostal = $_POST['_code_postal_modif'];
        
        $api_codePostal = 'http://api.zippopotam.us/fr/'.$codePostal;
    
        $api_codePostal = file_get_contents($api_codePostal);
        if($api_codePostal === FALSE){
            $erreurs[] = "Erreur lors de l'accès à l'API";
            exit();
        }
        
        $data = json_decode($api_codePostal,true);
        $isValid = false;
        
        if($data && isset($data['places'])){
            foreach($data['places'] as $place){
                if(stripos($place['place name'], $ville) === 0){
                    $isValid = true;
                    break;
                }
            }
        }
        if(!$isValid){
            $erreurs[] = "La ville ne correspond pas au code postal";
        }
    }
    
    if(empty($erreurs)){
        
        // table "_offre"

        $tab_offre = array(
            "titre_offre" => $_POST['_titre_modif'],
            "_resume" => $_POST['_resume_modif'],
            "_description" => $_POST['_desc_modif'],
            "accessibilite" => $_POST['_access_modif']
        );
        

        // table "_adresse"
        
        $tab_adresse = array(
            "code_postal" => $_POST['_code_postal_modif'],
            "ville" => $_POST['_ville_modif'],
            "adresse_postal" => $_POST['_adresse_modif'],
            "complement_adresse" => $_POST['_comp_adresse_modif']
        );
        
    
        foreach($tab_offre as $att => $val){
            $requete = "UPDATE tripenarvor._offre SET $att = :value WHERE code_offre = :code_offre";
            $stmt = $dbh->prepare($requete);
            print_r($requete);
    
            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_offre",$offre['code_offre']);
    
            try {
                $stmt->execute();
                echo "Champ $att mis à jour avec succès.<br>";
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour du champ $att: " . $e->getMessage() . "<br>";
            }
        }
        foreach($tab_adresse as $att => $val){
            $requete = "UPDATE tripenarvor._adresse SET $att = :value WHERE code_adresse = :code_adresse";
            $stmt = $dbh->prepare($requete);

            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_adresse",$offre['code_adresse']);

            try {
                $stmt->execute();
                echo "Champ $att mis à jour avec succès. <br>";
            } catch (PDOException $e){
                echo "Erreur lors de la mise à jour du champ $att : " . $e->getMessage() . "<br>";
            }
        }
    } else {
        foreach($erreurs as $err){
            echo $err."<br>";
        }
    }
    header('location: detail_offre_pro.php');
    exit;
}

echo "<pre>";
var_dump($offre);
echo "</pre>";
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Mes offres</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
</head>
<body>
<header class="header-pc header_pro">
    <div class="logo">
    <a href="mes_offres.php">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
    </a>
    </div>
    <nav class="nav">
        <ul>
            <li><a href="mes_offres.php" class="active">Accueil</a></li>
            <li><a href="creation_offre.php">Publier</a></li>
            <li><a href="consulter_compte_pro.php">Mon Compte</a></li>
        </ul>
    </nav>
</header>
<body>
    <main>
        <form id="modif_offre" action="#" method="POST">
            <!-- Infos. Générales-->
            <div>
                <fieldset>
                    <legend>Titre</legend>
                    <h1 id="titre" contenteditable="true" data-sync="titre_modif"><?php echo $offre['titre_offre']; ?></h1>
                    <input type="hidden" id="titre_modif" name="_titre_modif">
                </fieldset>
                <fieldset>
                    <legend>Infos Générales</legend>
                    <label for="resume">Résumé (*)</label>
                    <span contenteditable="true" id="resume" data-sync="resume_modif"><?php echo $offre['_resume']; ?></span>
                    <label for="description">Description (*)</label>
                    <span contenteditable="true" id="description" data-sync="desc_modif"><?php echo $offre['_description'] ?></span>
                    <label for="accessibilite">Accessibilité</label>
                    <span contenteditable="true" placeholder="Accessibilité" id="accessibilite" data-sync="access_modif"><?php echo $offre['accessibilite'] ?? ""; ?></span>

                    <input type="hidden" id="resume_modif" name="_resume_modif">
                    <input type="hidden" id="desc_modif" name="_desc_modif">
                    <input type="hidden" id="access_modif" name="_access_modif">
                </fieldset>
                <fieldset>
                    <legend>Adresse</legend>
                    <label for="code_postal">Code Postal</label>
                    <input id="code_postal" type="text" data-sync="code_postal_modif" value="<?php echo $offre['code_postal']; ?>" maxlength="5" required>
                    <label for="ville">Ville</label>
                    <input id="ville" type="text" data-sync="ville_modif" value="<?php echo $offre['ville']; ?>" maxlength="30" required>
                    <label for="adresse">Adresse Postale</label>
                    <input type="text" id="adresse" data-sync="adresse_modif" value="<?php echo $offre['adresse_postal'] ?>" maxlength="64" required>
                    <label for="complement_adresse">Complément d'Adresse</label>
                    <input type="text" id="complement_adresse" data-sync="comp_adresse_modif" placeholder="Complément d'adresse" value="<?php echo $offre['complement_adresse']; ?>" maxlength="64">

                    <input type="hidden" id="code_postal_modif" name="_code_postal_modif">
                    <input type="hidden" id="ville_modif" name="_ville_modif">
                    <input type="hidden" id="adresse_modif" name="_adresse_modif">
                    <input type="hidden" id="comp_adresse_modif" name="_comp_adresse_modif">
                </fieldset>
            </div>
            <input type="submit" name="envoi_modif" value="Modifier">
        </form>
    </main>
    <footer class="footer footer_pro">
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
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
                    <li><a href="connexion_pro.php">Publier</a></li>
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
    <script defer>
        const form = document.getElementById('modif_offre');
        
        // Au lieu de gérer chaque champ individuellement, récupère tous les éléments avec `data-sync`
        form.addEventListener('submit', (event) => {
            const elementsToSync = document.querySelectorAll('[data-sync]');
            elementsToSync.forEach((element) => {
                // Récupère l'id du champ de destination
                const targetId = element.getAttribute('data-sync');
                const target = document.getElementById(targetId);
        
                if (target) {
                    // Utilise innerHTML pour les champs éditables ou value pour les inputs
                    target.value = element.contentEditable === "true" ? element.innerHTML : element.value;
                }
            });
        });
    </script>
</body>
</html>
