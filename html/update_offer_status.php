<?php
// update_offer_status.php

// Connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit;
}

// Vérifier si les données POST sont présentes
if (isset($_POST['en_ligne'], $_POST['id'])) {
    // Récupérer et valider les données
    $en_ligne = $_POST['en_ligne'] == 1 ? 1 : 0;
    $offer_id = (int) $_POST['id']; // Assurez-vous que l'ID est un entier

    try {
        // Préparer et exécuter la requête
        $query = "UPDATE tripenarvor._offre SET en_ligne = :en_ligne WHERE id = :id";
        $stmt = $dbh->prepare($query);
        $stmt->execute([
            ':en_ligne' => $en_ligne,
            ':id' => $offer_id,
        ]);

        echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
}
?>
