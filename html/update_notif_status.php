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
    
    try
    {
        
        $stmt = $dbh->prepare("
            UPDATE tripenarvor._notification
            SET consulter_notif = FALSE
            WHERE code_avis = :code_avis
            RETURNING code_avis, consulter_notif
        ");
    
        $params = [':code_avis' => $code_avis];
        error_log("Params utilisés : " . print_r($params, true)); // Ajoute un log
    
        $stmt->execute($params);
    
        $rowsAffected = $stmt->rowCount();
        error_log("Lignes affectées : $rowsAffected"); // Log du nombre de lignes affectées
    
        if ($rowsAffected > 0) {
            echo "Mise à jour réussie : $rowsAffected ligne(s) modifiée(s).";
        } else {
            echo "Aucune mise à jour effectuée. Vérifiez si le code_avis existe.";
        }

        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($updatedRow);
        
    }
    catch (PDOException $e)
    {
        error_log("Erreur lors de la mise à jour : " . $e->getMessage());
        http_response_code(500);
        echo "Erreur lors de la mise à jour.";
    }
?>
