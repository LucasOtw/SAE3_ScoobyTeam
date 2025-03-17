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
    $code_avis = $_POST['code_avis'];
    $code_offre = $_POST['code_offre'];
    $tps_ban =  new DateTime('00:03');

    if ($code_offre === null) {
        http_response_code(400);
        echo "Code offre manquant.";
        exit;
    } else if ($code_avis === null) {
        http_response_code(400);
        echo "Code avis manquant.";
        exit;
    }
    
    // Mettre à jour la table _avis
    try {
        
        $query = $dbh->prepare("
        SELECT blacklister, date_blacklister
        FROM tripenarvor._avis
        WHERE code_avis = :code_avis
        ");
        $query->execute([':code_avis' => $code_avis]);
        $currentRow = $query->fetch(PDO::FETCH_ASSOC);
    
        if (!$currentRow) {
            echo "Aucune offre trouvée avec le code fourni.";
        }

        $dateTime = (new DateTime())->format('Y-m-d H:i:s'); // Format SQL pour timestamp
        
        print_r("///////////////////////////ok///////////////////////////");

        $stmt = $dbh->prepare("
            UPDATE tripenarvor._offre
            SET nb_blacklister = nb_blacklister + 1
            WHERE code_offre = :code_offre
            RETURNING titre_offre, blacklister, date_blacklister
        ");
        $stmt->execute([ ':code_offre' => $code_offre ]);

        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($updatedRow);
      
        
        // Si l'offre passe en ligne, mettre à jour date_publication et date_derniere_modif
        $stmt = $dbh->prepare("
            UPDATE tripenarvor._avis
            SET blacklister = :blacklister,
                date_blacklister = :date
            WHERE code_avis = :code_avis
            RETURNING code_avis, blacklister, date_blacklister
        ");
        $stmt->execute([
            ':blacklister' => true,
            ':date_blacklister' => $dateTime,    // Valeur timestamp
            ':code_avis' => $code_avis
        ]);
      
        // Récupérer le nombre de lignes modifiées
        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($updatedRow) {
            // Étape 4 : Comparer les champs pour compter les modifications
            $fieldsModified = 0;
    
            if ($currentRow['blacklister'] != $updatedRow['blacklister']) {
                $fieldsModified++;
            }
            if (isset($updatedRow['date_blacklister'])) { // Si en ligne, date modifiée
                $fieldsModified++;
            }
    
            // Afficher les résultats
            echo "La mise à jour a été effectuée avec succès.";
            echo " Nombre de champs modifiés : $fieldsModified.";
            print_r($updatedRow);
        } else {
            echo "Aucune mise à jour effectuée.";
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
?>
