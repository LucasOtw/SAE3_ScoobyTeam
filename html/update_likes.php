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

    // Récupérer l'état actuel des likes/dislikes pour cet avis
    $stmt = $dbh->prepare("SELECT pouce_positif, pouce_negatif FROM tripenarvor._avis WHERE code_avis = :code_avis");
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();
    $avis = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$avis) {
        echo json_encode(["status" => "error", "message" => "Avis introuvable."]);
        exit;
    }

    // Définir les ajustements en fonction de l'action
    $adjustPositive = 0;
    $adjustNegative = 0;

    switch ($action) {
        case 'like':
            $adjustPositive = 1;
            if ($avis['pouce_negatif'] > 0) $adjustNegative = -1; // Si "dislike" était actif, le retirer
            break;
        case 'unlike':
            $adjustPositive = -1;
            break;
        case 'dislike':
            $adjustNegative = 1;
            if ($avis['pouce_positif'] > 0) $adjustPositive = -1; // Si "like" était actif, le retirer
            break;
        case 'undislike':
            $adjustNegative = -1;
            break;
    }

    // Mettre à jour les compteurs
    $stmt = $dbh->prepare("
        UPDATE tripenarvor._avis 
        SET 
            pouce_positif = pouce_positif + :adjustPositive, 
            pouce_negatif = pouce_negatif + :adjustNegative 
        WHERE code_avis = :code_avis
    ");
    $stmt->bindValue(':adjustPositive', $adjustPositive, PDO::PARAM_INT);
    $stmt->bindValue(':adjustNegative', $adjustNegative, PDO::PARAM_INT);
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier si la mise à jour a été effectuée
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Mise à jour réussie."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aucune mise à jour effectuée."]);
    }
} catch (PDOException $e) {
    // En cas d'erreur SQL
    echo json_encode(["status" => "error", "message" => "Erreur SQL : " . $e->getMessage()]);
}
