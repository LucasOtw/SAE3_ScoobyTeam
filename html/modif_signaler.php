<?php
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id'])) {
        $id_avis = intval($data['id']);

        require_once __DIR__ . ("/../.security/config.php");
      
        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        // Modification dans la base de données
        $stmt = $dbh->prepare('UPDATE tripenarvor._avis SET signaler = TRUE WHERE code_avis = :id');
        $stmt->bindValue(':id', $id_avis, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID invalide.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
