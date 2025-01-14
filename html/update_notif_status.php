<?php
    // Connexion à la base de données
    try {
        // Vérifie si le formulaire a été soumis    
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";  // Utilisateur PostgreSQL défini dans .env
        $password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env
    
        // Créer une instance PDO avec les bons paramètres
        $dbh = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    $code_avis = $_POST['code_avis'];

    if ($code_avis === null) {
        http_response_code(400);
        echo "Code avis manquant.";
        exit;
    }
    
    try { 
        $stmt = $dbh->prepare("
            UPDATE tripenarvor._notification
            SET consulter_notif = FALSE
            WHERE code_avis = :code_avis
        ");
        
        $stmt->execute([':code_avis' => $code_avis]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
?>
