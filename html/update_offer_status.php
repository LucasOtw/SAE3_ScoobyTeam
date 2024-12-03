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


    print_r($_POST);

    // Récupérer les données envoyées en POST
    $en_ligne = $_POST['en_ligne'];
    
    // Vérifier que l'identifiant de l'offre est défini
    $code_offre = $_POST['code_offre'];

    if ($code_offre === null) {
        http_response_code(400);
        echo "Code offre manquant.";
        exit;
    }
    
    // Mettre à jour la table _offre
    try {
        if ($en_ligne === 1) {
            // Si l'offre passe en ligne, mettre à jour la date_publication
            $stmt = $dbh->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne,
                    date_publication = NOW()
                WHERE code_offre = :code_offre
                RETURNING titre_offre, en_ligne
            ");
        } else {
            // Si l'offre est hors ligne, ne pas toucher à date_publication
            $stmt = $dbh->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne
                WHERE code_offre = :code_offre
                RETURNING titre_offre, en_ligne
            ");
        }
        if ($stmt->execute([':code_offre' => $code_offre, ':en_ligne' => ($en_ligne == 1) ? true : 0 ]))
        {
            echo "La mise à jour a été effectuée avec succès.";
            
            $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($updatedRow))
            {
                print_r("videeeee");
            } else {
                print_r($updatedRow);
            }
        }
        else
        {
            $errorInfo = $stmt->errorInfo();
            echo "Erreur lors de l'exécution de la requête : " . $errorInfo[2];
        }
        
        

    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
?>
