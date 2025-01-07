<?php
// Connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'ID d'avis est passé dans l'URL
    if (isset($_GET['id_avis']) && !empty($_GET['id_avis'])) {
        $idAvis = $_GET['id_avis'];

        // Vérifier que l'ID est un entier valide
        if (is_numeric($idAvis) && $idAvis > 0) {
            // Requête préparée avec un paramètre dynamique :id
            $stmt = $dbh->prepare("
                SELECT * 
                FROM tripenarvor._avis 
                NATURAL JOIN tripenarvor._compte 
                WHERE code_compte=2 AND id = :id
            ");
            
            // Lier le paramètre :id et exécuter la requête
            $stmt->execute([':id' => $idAvis]);

            // Récupérer l'avis correspondant
            $avis = $stmt->fetch();

            if ($avis) {
                // L'avis a été trouvé, on assigne les variables
                $note = htmlspecialchars($avis['note']);
                $texte = htmlspecialchars($avis['txt_avis']);
                $prenom = htmlspecialchars($avis['prenom']);
                $nom = htmlspecialchars($avis['nom']);
            } else {
                // Aucun avis trouvé avec cet ID
                $erreur = "Aucun avis trouvé pour cet ID.";
            }
        } else {
            // Si l'ID n'est pas un nombre valide
            $erreur = "L'ID d'avis est invalide.";
        }
    } else {
        // Aucun ID passé dans l'URL
        $erreur = "Aucun ID d'avis spécifié.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs de connexion ou d'exécution
    $erreur = "Erreur de connexion ou d'exécution : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Offres PACT</title>
    <link rel="stylesheet" href="signalement.css">
</head>
<body>
    <div class="container">
        <h1>Signaler un avis</h1>

        <?php if (isset($erreur)): ?>
            <div class="erreur">
                <p><?php echo $erreur; ?></p>
            </div>
        <?php else: ?>
            <div class="avis">
                <h3><?php echo $note . ".0 | " . $prenom . " " . $nom; ?></h3>
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
