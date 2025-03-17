<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Récupérer les données envoyées depuis le fetch
      $data = json_decode(file_get_contents('php://input'), true);
      $compteId = $data['id'];
  
      require_once __DIR__ . ("/../.security/config.php");
      
      // Créer une instance PDO
      $dbh = new PDO($dsn, $username, $password);
    
      // Supprimer le compte
      $stmt = $dbh->prepare('DELETE FROM tripenarvor._compte WHERE code_compte = :code_compte');
      $stmt->bindValue(':code_compte', $compteId, PDO::PARAM_INT);
  
      if ($stmt->execute()) {
          http_response_code(200);
          echo json_encode(['success' => true, 'message' => 'Compte supprimé']);
      } else {
          http_response_code(500);
          echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
      }
  }
?>
