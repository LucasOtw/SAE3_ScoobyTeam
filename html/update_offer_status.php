<?php
    // Connexion à la base de données
    try {
        // Vérifie si le formulaire a été soumis    
        require_once __DIR__ . ("/../.security/config.php");
    
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
        
        $query = $dbh->prepare("
        SELECT en_ligne, date_publication
        FROM tripenarvor._offre
        WHERE code_offre = :code_offre
        ");
        $query->execute([':code_offre' => $code_offre]);
        $currentRow = $query->fetch(PDO::FETCH_ASSOC);
    
        if (!$currentRow) {
            echo "Aucune offre trouvée avec le code fourni.";
        }

        $dateTime = (new DateTime())->format('Y-m-d H:i:s'); // Format SQL pour timestamp
        $date = (new DateTime())->format('Y-m-d');          // Format SQL pour date
        
        if ($en_ligne == 1) {
            print_r("///////////////////////////ok///////////////////////////");
            
            // Si l'offre passe en ligne, mettre à jour date_publication et date_derniere_modif
            $stmt = $dbh->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne,
                    date_publication = :date_publication,
                    date_derniere_modif = :date_derniere_modif
                WHERE code_offre = :code_offre
                RETURNING titre_offre, en_ligne, date_publication
            ");
            $stmt->execute([
                ':en_ligne' => true,
                ':date_publication' => $dateTime,    // Valeur timestamp
                ':date_derniere_modif' => $date,    // Valeur date
                ':code_offre' => $code_offre
            ]);
        } else {
            // Si l'offre est hors ligne, ne pas toucher à date_publication
            $stmt = $dbh->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne
                WHERE code_offre = :code_offre
                RETURNING titre_offre, en_ligne, date_publication
            ");
            $stmt->execute([
                ':en_ligne' => false,
                ':code_offre' => $code_offre
            ]);
        }

        
        // Récupérer le nombre de lignes modifiées
        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($updatedRow) {
            // Étape 4 : Comparer les champs pour compter les modifications
            $fieldsModified = 0;
    
            if ($currentRow['en_ligne'] != $updatedRow['en_ligne']) {
                $fieldsModified++;
            }
            if (isset($updatedRow['date_publication']) && $en_ligne == 1) { // Si en ligne, date modifiée
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
