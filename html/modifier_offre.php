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
}

$offre = $_SESSION['modif_offre'];

if (isset($_POST['envoi_modif'])){

    $erreurs = [];

    if(empty($_POST['_titre_modif'])){
        $erreurs[] = "Des champs obligatoires ne sont pas remplis !";
    }
    
    if(empty($erreurs)){
        $tab_offre = array(
            "titre_offre" => $_POST['_titre_modif']
        );
    
        foreach($tab_offre as $att => $val){
            $requete = "UPDATE tripenarvor._offre SET $att = :value WHERE code_offre = :code_offre";
            $stmt = $dbh->prepare($requete);
    
            $stmt->bindValue(":value",$val);
            $stmt->bindValue(":code_offre",$offre['code_offre']);
    
            try {
                $stmt->execute();
                echo "Champ $att mis à jour avec succès.<br>";
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour du champ $att: " . $e->getMessage() . "<br>";
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
                    <h1 id="titre" contenteditable="true"><?php echo $offre['titre_offre']; ?></h1>
                    <input type="hidden" id="titre_modif" name="_titre_modif">
                </fieldset>
                <fieldset>
                    <legend>Adresse</legend>
                    <label for="code_postal">Code Postal</label>
                    <input id="code_postal" type="text" name="_code_postal" value="<?php echo $offre['code_postal']; ?>" maxlength="5" required>
                    <label for="ville">Ville</label>
                    <input id="ville" type="text" name="_ville" value="<?php echo $offre['ville']; ?>" required>
                    <label for="adresse">Adresse Postale</label>
                    <input type="text" id="adresse" name="_adresse" value="<?php echo $offre['adresse_postal'] ?>" required>
                    <label for="complement_adresse">Complément d'Adresse</label>
                    <input type="text" id="complement_adresse" name="_complement_adresse" placeholder="Complément d'adresse" value="<?php echo $offre['complement_adresse']; ?>">
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
    <script>
        const form = document.getElementById('modif_offre');
        const champTitre = document.getElementById('titre');
        const nouveauTitre = document.getElementById('titre_modif');

        form.addEventListener('submit', (event) => {
            nouveauTitre.value = champTitre.innerHTML;
        });
    </script>
</body>
</html>
