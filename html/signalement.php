<?php
session_start();
$compte = $_SESSION['membre'];
// Connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'ID d'avis est passé dans l'URL
    if (isset($_GET['id_avis']) && !empty($_GET['id_avis'])) {
        // Sécuriser l'ID en le convertissant en entier
        $idAvis = intval($_GET['id_avis']);

        // Requête préparée avec un paramètre dynamique :id
        $stmt = $dbh->prepare("
            SELECT * 
            FROM tripenarvor._avis 
            NATURAL JOIN tripenarvor._compte 
            WHERE code_compte=:code_compte AND code_avis = :id
        ");

        // Exécuter la requête avec le paramètre :id
        $stmt->execute([':id' => $idAvis]);
        $stmt->execute([':code_compte' => $compte['code_compte']]);

        // Récupérer l'avis correspondant
        $avis = $stmt->fetch();

        if ($avis) {
            // Afficher les informations de l'avis trouvé
            echo "<h3>" . htmlspecialchars($avis['note']) . ".0 | " . htmlspecialchars($avis['prenom']) . " " . htmlspecialchars($avis['nom']) . "</h3>";
            echo "<p>" . htmlspecialchars($avis['txt_avis']) . "</p>";
        } else {
            // Aucun avis trouvé avec cet ID
            echo "Aucun avis trouvé pour cet ID.";
        }
    } else {
        // Aucun ID passé dans l'URL
        echo "Aucun ID d'avis spécifié.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs
    echo "Erreur de connexion ou d'exécution : " . $e->getMessage();
}
?>


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
                    <?php
                        if(isset($_SESSION["membre"]) || !empty($_SESSION["membre"])){
                           ?>
                           <li>
                               <a href="consulter_compte_membre.php"><?php echo "Mon Compte" /*$compte['nom'] . substr($compte['prenom'], 0, 0)*/;?> </a>
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
            </nav>
        </header>
        <header class="header-tel">
            <div class="logo-tel">
                <img src="images/LogoCouleur.png" alt="PACT Logo">
            </div>
            
        </header>
    </div>
    <div class="container">
        <h1>Signaler un avis</h1>
        <?php if (isset($erreur)): ?>
            <div class="erreur">
                <p><?php echo $erreur; ?></p>
            </div>
        <?php else: ?>
            <div class="avis">
                <h3><?php echo $note; ?>.0 | <?php echo $prenom . " " . $nom; ?></h3>
                <p><?php echo $texte; ?></p>
            </div>
            <form method="POST" action="process_signalement.php">
                <input type="hidden" name="id_avis" value="<?php echo $idAvis; ?>">
                <button type="submit">Confirmer le signalement</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>