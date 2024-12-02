<?php
    // Connexion à la base de données
    try {
        $pdo = new PDO('pgsql:host=localhost;dbname=tripenarvor', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
    
    // Récupérer les données envoyées en POST
    $en_ligne = isset($_POST['en_ligne']) ? intval($_POST['en_ligne']) : null;
    
    // Vérifier que l'identifiant de l'offre est défini
    $code_offre = isset($_GET['code_offre']) ? intval($_GET['code_offre']) : null;
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
                SET en_ligne = :en_ligne,
                    date_publication = NOW()
                WHERE code_offre = :code_offre
            ");
        } else {
            // Si l'offre est hors ligne, ne pas toucher à date_publication
            $stmt = $pdo->prepare("
                UPDATE tripenarvor._offre
                SET en_ligne = :en_ligne
                WHERE code_offre = :code_offre
            ");
        }
        $stmt->execute([
            ':en_ligne' => $en_ligne,
            ':code_offre' => $code_offre
        ]);
        echo "Mise à jour réussie.";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
?>
