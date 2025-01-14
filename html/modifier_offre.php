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


/* TABLEAU DES JOURS */

$jours = [
    'Lundi',
    'Mardi',
    'Mercredi',
    'Jeudi',
    'Vendredi',
    'Samedi',
    'Dimanche'
];


/* RÉCUPÉRATION DU "TYPE DE L'OFFRE" */

$tables = [
    '_offre_activite',
    '_offre_parc_attractions',
    '_offre_restauration',
    '_offre_spectacle',
    '_offre_visite'
];

$infos_offre = null;
$type_offre = null;

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
        $type_offre = str_replace("_offre_",'',$table);
        break;
    }
}

echo "<h1>".$type_offre."</h1>";



/* RÉCUPÉRATION DES TAGS */



$req_tags = $dbh->prepare("SELECT * FROM tripenarvor._tags WHERE $type_offre = true");
$req_tags->execute();
$tags_offre = $req_tags->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($tags_offre);
echo "</pre>";

/* RÉCUPÉRATION DES TAGS DE L'OFFRE */

$req_tags_offre = $dbh->prepare("SELECT code_tag FROM tripenarvor._son_tag WHERE code_offre = :code_offre");
$req_tags_offre->bindValue(":code_offre",$offre['code_offre']);
$req_tags_offre->execute();

$mes_tags = $req_tags_offre->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($mes_tags);
echo "</pre>";


// Si on envoie le script de modification...

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

    // Si il n'y a pas d'erreurs...
    
    if(empty($erreurs)){

        if(!empty($_POST['tags'])){
            $valeurs_tags = array_column($mes_tags, 'code_tag');
            var_dump($valeurs_tags);
            
            // Première boucle : suppression des tags non sélectionnés
            foreach($valeurs_tags as $un_tag){
                if(in_array($un_tag, $_POST['tags'])){
                    echo $un_tag."<br>";
                } else {
                    // Si un tag de la table n'est pas dans le tableau $_POST['tags'], on le supprime
                    $req_del_tag = $dbh->prepare("DELETE FROM tripenarvor._son_tag WHERE code_tag = :code_tag AND code_offre = :code_offre");
                    $req_del_tag->bindValue(":code_tag", $un_tag);
                    $req_del_tag->bindValue(":code_offre", $offre['code_offre']);
                    $req_del_tag->execute();
                }
            }
            
            // Deuxième boucle : ajout des nouveaux tags non présents dans la table
            foreach($_POST['tags'] as $tab_tag){
                // Si un tag de $_POST['tags'] n'est pas dans les tags existants
                if(!in_array($tab_tag, $valeurs_tags)){
                    $req_add_tag = $dbh->prepare("INSERT INTO tripenarvor._son_tag (code_tag, code_offre) VALUES (:code_tag, :code_offre)");
                    $req_add_tag->bindValue(":code_tag", $tab_tag);
                    $req_add_tag->bindValue(":code_offre", $offre['code_offre']);
                    $req_add_tag->execute();
                }
            }
        } else {
            // si c'est vide, alors on supprime tous les tags pour l'offre
            $req_del_all = $dbh->prepare("DELETE FROM tripenarvor._son_tag WHERE code_offre = :code_offre");
            $req_del_all->bindValue(":code_offre",$offre['code_offre']);
            $req_del_all->execute();
        }
        
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
}

echo "<pre>";
var_dump($offre);
echo "</pre>";

if($infos_offre !== null){
    echo "<pre>";
    var_dump($infos_offre);
    $infos_offre = $infos_offre[0];
    echo "</pre>";
}
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Mes offres</title>
    <link rel="stylesheet" href="styles.css?d">
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
        <h1 id="titre_modif_offre">Modifiez votre offre</h1>
        <form id="modif_offre" action="#" method="POST">
            <!-- Infos. Générales-->
            <section class="tabs">
                <ul>
                    <li><a href="#" class="active" data-tab="general">Informations générales</a></li>
                    <li><a href="#" data-tab="services">Services et horaires</a></li>
                    <li><a href="#" data-tab="photos">Photos</a></li>
                </ul>
            </section>
            <div class="tab-content active" id="general">
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
                
                <fieldset>
                    <legend>Tags</legend>

                    <table>
                        <thead>
                            <tr>
                                <th>Tags</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Extraire seulement les valeurs de 'code_tag' de $mes_tags
                                $mes_tags_values = array_column($mes_tags, 'code_tag');
                            
                                foreach($tags_offre as $tag){
                                    // On vérifie si le code_tag est déjà détenu par l'offre
                                    $isChecked = in_array($tag['code_tag'], $mes_tags_values);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="tags[]" value="<?php echo $tag['code_tag']; ?>"<?php echo $isChecked ? ' checked' : ''; ?>>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($tag['nom_tag']) ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <div class="tab-content" id="services">
                <fieldset>
                    <legend> Services </legend>
                    <?php

                    switch($type_offre){
                        case "restauration":
                            ?>
                            <div class="price-options">
                                <label for="prix">Prix</label>
                                <div class="radio-group">
                                    <div>
                                        <input type="radio" id="moins_25" name="prix" value="€" required>
                                        <label class="label-check" for="moins_25">€ (menu à moins de 25€)</label>
                                    </div>
                                    <div>
                                        <input class="label-check" type="radio" id="entre_25_40" name="prix" value="€€" required>
                                        <label class="label-check" for="entre_25_40">€€ (entre 25€ et 40€)</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="plus_40" name="prix" value="€€€" required>
                                        <label class="label-check" for="plus_40">€€€ (au-delà de 40€)</label>
                                    </div>
                                </div>
                            </div>
    
                   <!-- Tarif -->
        
                            <div class="tarif-option">
                              <label for="tarif">
                                 Tarif
                              </label>
                              <input type="number" id="tarif" name="_tarif" placeholder="00.00€" min="0" step="0.01" required>
                           </div>
        
                        <!-- Meal Options -->
                            <div class="meal-options">
                                <label>Options de repas</label>
                                <div class="checkbox-group">
                                    <div>
                                        <input type="checkbox" id="petit_dejeuner" name="repas[]" value="Petit déjeuner">
                                        <label class="label-check" for="petit_dejeuner">Petit-Déjeuner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="brunch" name="repas[]" value="Brunch">
                                        <label class="label-check" for="brunch">Brunch</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="dejeuner" name="repas[]" value="Déjeuner" checked>
                                        <label class="label-check" for="dejeuner">Déjeuner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="diner" name="repas[]" value="Dîner" checked>
                                        <label class="label-check" for="diner">Dîner</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="boissons" name="repas[]" value="Boissons">
                                        <label class="label-check" for="boissons">Boissons</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                        case "spectacle" :
                            ?>
                                <fieldset>
                                    <legend>?</legend>
                                    <label for="date">Date du spectacle (*)</label>
                                    <input type="date" id="date" data-sync="date_modif" value="<?php echo htmlspecialchars($infos_offre['date_spectacle']) ?>" required>

                                    <label for="duree">Durée (*)</label>
                                    <input type="time" class="duree" id="duree" data-sync="duree_modif" value="<?php echo htmlspecialchars($infos_offre['duree']) ?>" required>

                                    <label for="heure_spectacle">Heure du spectacle (*)</label>
                                    <input type="time" class="duree" id="heure_spectacle" data-sync="heure_spect_modif" value="<?php echo htmlspecialchars($infos_offre['heure_spectacle']) ?>" required>
                                </fieldset>
                            <?php
                    }

                    ?>
                </fieldset>
              <fieldset>
                <legend>Horaires</legend>
                <?php
                    foreach($jours as $jour){
                ?>
                <div class="horaires-row">
                    <div class="col">
                        <fieldset>
                            <legend>Jour</legend>
                            <input type="text" id="jour" name="jour" placeholder="<?php echo $jour ?>" disabled>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Ouverture</legend>
                            <input type="time" id="ouverture" name="<?php echo "ouverture".$jour; ?>" placeholder="Ouverture">
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset>
                            <legend>Fermeture</legend>
                            <input type="time" id="fermeture" name="<?php echo "fermeture".$jour; ?>" placeholder="Fermeture">
                        </fieldset>
                    </div>
                </div>
                <?php
                    }
                ?>
            </fieldset>



            </div>
            <div class="tab-content" id="photos">
                <h1>Pas de photos</h1>
            </div>
            <div class="btn_modif_offre">
                <input type="submit" name="envoi_modif" value="Modifier">
            </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.tabs a');
            const sections = document.querySelectorAll('.tab-content');
    
            tabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
    
                    // Supprime la classe active de tous les onglets
                    tabs.forEach(t => t.classList.remove('active'));
    
                    // Ajoute la classe active à l'onglet cliqué
                    tab.classList.add('active');
    
                    // Récupère l'ID de la section associée
                    const tabId = tab.getAttribute('data-tab');
    
                    // Masque toutes les sections
                    sections.forEach(section => section.classList.remove('active'));
    
                    // Affiche la section correspondante
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
    


</body>
</html>
