<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler un avis</title>
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
