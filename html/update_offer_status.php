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

    echo "<pre>";
    var_dump($_POST);
    echo"</pre>";

    // Récupérer les données envoyées en POST
    $en_ligne = isset($_POST['en_ligne']) ? intval($_POST['en_ligne']) : null;
    
    // Vérifier que l'identifiant de l'offre est défini
    $code_offre = isset($_POST['code_offre']) ? intval($_POST['code_offre']) : null;

    if ($code_offre === null) {
        http_response_code(400);
        echo "Code offre manquant.";
        exit;
    }
    
    // Mettre à jour la table _offre
    try {
        if ($en_ligne === 1) {
            // Si l'offre passe en ligne, mettre à jour la date_publication
            $stmt = $pdo->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = true,
                    date_publication = NOW()
                WHERE code_offre = :code_offre
            ");
        } else {
            // Si l'offre est hors ligne, ne pas toucher à date_publication
            $stmt = $pdo->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = false
                WHERE code_offre = :code_offre
            ");
        }
        $stmt->execute([':code_offre' => $code_offre ]);
        echo "Mise à jour réussie.";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
?>
