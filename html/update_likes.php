<?php
include 'db_connection.php';  // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['code_avis'])) {
    $action = $_POST['action'];
    $codeAvis = (int)$_POST['code_avis'];

    // Préparer la requête SQL en fonction de l'action
    switch ($action) {
        case 'like':
            $query = "UPDATE Avis SET pouce_positif = pouce_positif + 1, pouce_negatif = GREATEST(0, pouce_negatif - 1) WHERE code_avis = ?";
            break;
        case 'dislike':
            $query = "UPDATE Avis SET pouce_negatif = pouce_negatif + 1, pouce_positif = GREATEST(0, pouce_positif - 1) WHERE code_avis = ?";
            break;
        case 'unlike':
            $query = "UPDATE Avis SET pouce_positif = GREATEST(0, pouce_positif - 1) WHERE code_avis = ?";
            break;
        case 'undislike':
            $query = "UPDATE Avis SET pouce_negatif = GREATEST(0, pouce_negatif - 1) WHERE code_avis = ?";
            break;
        default:
            echo "Invalid action";
            exit;
    }

    // Exécution de la requête préparée
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $codeAvis);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Vote updated successfully";
    } else {
        echo "No changes made";
    }

    $stmt->close();
    $conn->close();
}
?>
