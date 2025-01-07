<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Récupérer les données envoyées depuis le fetch
      $data = json_decode(file_get_contents('php://input'), true);
      $compteId = $data['id'];
  
      $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
      $username = "sae";
      $password = "philly-Congo-bry4nt";
      
      // Créer une instance PDO
      $dbh = new PDO($dsn, $username, $password);
    
      // Supprimer le compte
      $stmt = $dbh->prepare('DELETE FROM tripenarvor._compte WHERE code_compte = :code_compte');
      $stmt->bindValue(':code_compte', $compteId, PDO::PARAM_INT);
  
      if ($stmt->execute()) {
          // Succès
          http_response_code(200);
          echo json_encode(['message' => 'Compte supprimé']);
          header('location: voir_offres.php');
      } else {
          // Échec
          http_response_code(500);
          echo json_encode(['message' => 'Erreur lors de la suppression']);
      }
  }
?>
