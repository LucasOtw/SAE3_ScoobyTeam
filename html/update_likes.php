<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['membre']['code_compte'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Vous devez être connecté pour voter.',
    ]);
    exit;
}

// Connexion à la base de données
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";
$dbh = new PDO($dsn, $username, $password);

// Vérifie si la requête est une méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $codeAvis = $_POST['code_avis'] ?? null;
    $codeCompte = $_SESSION['membre']['code_compte'];

    if (!$action || !$codeAvis) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Données manquantes.',
        ]);
        exit;
    }

    try {
        $dbh->beginTransaction();

        // Vérifie si un vote existe déjà pour cet avis et cet utilisateur
        $stmt = $dbh->prepare("SELECT pouce FROM tripenarvor._pouce WHERE code_avis = :code_avis AND code_compte = :code_compte");
        $stmt->execute([':code_avis' => $codeAvis, ':code_compte' => $codeCompte]);
        $currentVote = $stmt->fetchColumn();

        // Déterminer le nouvel état du vote
        $newVote = match ($action) {
            'like' => 1,
            'unlike' => 0,
            'dislike' => -1,
            'undislike' => 0,
            default => $currentVote,
        };

        // Si le vote est modifié ou non existant, effectuer l'insert ou la mise à jour
        if ($currentVote === false) {
            $stmt = $dbh->prepare("INSERT INTO tripenarvor._pouce (code_avis, code_compte, pouce) VALUES (:code_avis, :code_compte, :pouce)");
            $stmt->execute([':code_avis' => $codeAvis, ':code_compte' => $codeCompte, ':pouce' => $newVote]);
        } elseif ($currentVote !== $newVote) {
            $stmt = $dbh->prepare("UPDATE tripenarvor._pouce SET pouce = :pouce WHERE code_avis = :code_avis AND code_compte = :code_compte");
            $stmt->execute([':pouce' => $newVote, ':code_avis' => $codeAvis, ':code_compte' => $codeCompte]);
        }

        // Calculer les nouveaux compteurs de pouces pour l'avis
        $stmt = $dbh->prepare("
            SELECT 
                SUM(CASE WHEN pouce = 1 THEN 1 ELSE 0 END) AS pouce_positif,
                SUM(CASE WHEN pouce = -1 THEN 1 ELSE 0 END) AS pouce_negatif
            FROM tripenarvor._pouce
            WHERE code_avis = :code_avis
        ");
        $stmt->execute([':code_avis' => $codeAvis]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $poucePositif = $result['pouce_positif'] ?? 0;
        $pouceNegatif = $result['pouce_negatif'] ?? 0;

        // Mettre à jour les compteurs dans la table _avis
        $stmt = $dbh->prepare("UPDATE tripenarvor._avis SET pouce_positif = :pouce_positif, pouce_negatif = :pouce_negatif WHERE code_avis = :code_avis");
        $stmt->execute([
            ':pouce_positif' => $poucePositif,
            ':pouce_negatif' => $pouceNegatif,
            ':code_avis' => $codeAvis,
        ]);

        $dbh->commit();

        // Réponse au frontend avec les nouveaux compteurs
        echo json_encode([
            'status' => 'success',
            'pouce_positif' => $poucePositif,
            'pouce_negatif' => $pouceNegatif,
            'current_vote' => $newVote, // Envoyer l'état actuel du vote pour afficher les bonnes images
        ]);
    } catch (Exception $e) {
        $dbh->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Erreur lors de la mise à jour des votes.',
        ]);
    }
}
?>
