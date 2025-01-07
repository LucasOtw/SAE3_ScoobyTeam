<?php
// Démarrer la session et gérer les erreurs
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la connexion à la base de données
require_once 'config.php'; // Remplacez 'config.php' par votre fichier de configuration

// Vérifier si 'id_avis' est présent dans l'URL
if (isset($_GET['id_avis']) && !empty($_GET['id_avis'])) {
    $idAvis = intval($_GET['id_avis']); // Convertir en entier pour éviter les injections SQL

    // Rechercher l'avis dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM avis WHERE id = :id");
    $stmt->execute(['id' => $idAvis]);
    $avis = $stmt->fetch();

    if ($avis) {
        // L'avis a été trouvé
        $note = htmlspecialchars($avis['note']);
        $texte = htmlspecialchars($avis['txt_avis']);
        $prenom = htmlspecialchars($avis['prenom']);
        $nom = htmlspecialchars($avis['nom']);
    } else {
        // Aucun avis trouvé
        $erreur = "Aucun avis trouvé avec cet ID.";
    }
} else {
    // ID non spécifié
    $erreur = "Aucun ID d'avis spécifié.";
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