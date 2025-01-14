<?php

// Vérifie si le formulaire a été soumis    
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env
$dbh = new PDO($dsn, $username, $password);

header("Content-Type: application/json"); // Réponse en JSON

// Vérifier si les données POST sont présentes
if (!isset($_POST['action']) || !isset($_POST['code_avis'])) {
    echo json_encode(["status" => "error", "message" => "Données manquantes."]);
    exit;
}

// Récupérer les données POST
$action = $_POST['action'];
$codeAvis = (int) $_POST['code_avis'];

try {
    // Vérifier que l'action est valide
    if (!in_array($action, ['like', 'unlike', 'dislike', 'undislike'])) {
        echo json_encode(["status" => "error", "message" => "Action non valide."]);
        exit;
    }

    // Construire la requête SQL en fonction de l'action
    if ($action === 'like') {
        $sql = "UPDATE tripenarvor._avis SET pouce_positif = pouce_positif + 1 WHERE code_avis = :code_avis";
    } elseif ($action === 'unlike') {
        $sql = "UPDATE tripenarvor._avis SET pouce_positif = pouce_positif - 1 WHERE code_avis = :code_avis";
    } elseif ($action === 'dislike') {
        $sql = "UPDATE tripenarvor._avis SET pouce_negatif = pouce_negatif + 1 WHERE code_avis = :code_avis";
    } elseif ($action === 'undislike') {
        $sql = "UPDATE tripenarvor._avis SET pouce_negatif = pouce_negatif - 1 WHERE code_avis = :code_avis";
    }

    // Préparer et exécuter la requête
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier si la mise à jour a réussi
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Mise à jour réussie."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aucune ligne mise à jour. Code avis invalide ?"]);
    }
} catch (PDOException $e) {
    // En cas d'erreur SQL
    echo json_encode(["status" => "error", "message" => "Erreur SQL : " . $e->getMessage()]);
}
?>

