<?php
    try {
        require_once __DIR__ . ("/../.security/config.php");
        $dbh = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode(["success" => false, "message" => "Erreur de connexion : " . $e->getMessage()]));
    }

    // Debug: Afficher les données envoyées en POST
    print_r($_POST);

    // Récupérer et convertir les valeurs
    $en_ligne = isset($_POST['en_ligne']) ? (int)$_POST['en_ligne'] : 0;
    $code_offre = $_POST['code_offre'] ?? null;

    if (!$code_offre) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Code offre manquant."]);
        exit;
    }

    try {
        // Vérifier si l'offre existe
        $query = $dbh->prepare("SELECT en_ligne, date_publication FROM tripenarvor._offre WHERE code_offre = :code_offre");
        $query->execute([':code_offre' => $code_offre]);
        $currentRow = $query->fetch(PDO::FETCH_ASSOC);

        if (!$currentRow) {
            echo json_encode(["success" => false, "message" => "Aucune offre trouvée avec le code fourni."]);
            exit;
        }

        // Définir les nouvelles valeurs
        $dateTime = (new DateTime())->format('Y-m-d H:i:s');
        $date = (new DateTime())->format('Y-m-d');

        // Effectuer la mise à jour
        if ($en_ligne === 1) {
            $stmt = $dbh->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne,
                    date_publication = :date_publication,
                    date_derniere_modif = :date_derniere_modif
                WHERE code_offre = :code_offre
            ");
            $stmt->execute([
                ':en_ligne' => (int) $en_ligne,
                ':date_publication' => $dateTime,
                ':date_derniere_modif' => $date,
                ':code_offre' => $code_offre
            ]);
        } else {
            $stmt = $dbh->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne
                WHERE code_offre = :code_offre
            ");
            $stmt->execute([
                ':en_ligne' => (int) $en_ligne,
                ':code_offre' => $code_offre
            ]);
        }

        // Vérifier si une mise à jour a été faite
        if ($stmt->rowCount() > 0) {
            $query = $dbh->prepare("SELECT titre_offre, en_ligne, date_publication FROM tripenarvor._offre WHERE code_offre = :code_offre");
            $query->execute([':code_offre' => $code_offre]);
            $updatedRow = $query->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "success" => true,
                "message" => "Mise à jour effectuée.",
                "data" => $updatedRow
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucune modification effectuée."]);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $e->getMessage()]);
    }
?>
