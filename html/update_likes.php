<?php
session_start();

// Activer le rapport d'erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Forcer la réponse en JSON
header('Content-Type: application/json');

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
$username = "sae";  // Remplacez par vos informations PostgreSQL
$password = "philly-Congo-bry4nt";  // Remplacez par vos informations PostgreSQL
try {
    $dbh = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de connexion à la base de données : ' . $e->getMessage(),
    ]);
    exit;
}

// Vérifie si la requête est une méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données POST
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

        // Vérifier si un vote existe déjà pour cet avis et cet utilisateur
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

        // Insérer ou mettre à jour le vote
        if ($currentVote === false) {
            // Insérer un nouveau vote
            $stmt = $dbh->prepare("INSERT INTO tripenarvor._pouce (code_avis, code_compte, pouce) VALUES (:code_avis, :code_compte, :pouce)");
            $stmt->execute([':code_avis' => $codeAvis, ':code_compte' => $codeCompte, ':pouce' => $newVote]);
        } elseif ($currentVote !== $newVote) {
            // Mettre à jour le vote existant
            $stmt = $dbh->prepare("UPDATE tripenarvor._pouce SET pouce = :pouce WHERE code_avis = :code_avis AND code_compte = :code_compte");
            $stmt->execute([':pouce' => $newVote, ':code_avis' => $codeAvis, ':code_compte' => $codeCompte]);
        }

        // Calculer les nouveaux compteurs pour la table _pouce
        $stmt = $dbh->prepare("
            SELECT 
                SUM(CASE WHEN pouce = 1 THEN 1 ELSE 0 END) AS pouce_positif,
                SUM(CASE WHEN pouce = -1 THEN 1 ELSE 0 END) AS pouce_negatif
            FROM tripenarvor._pouce
            WHERE code_avis = :code_avis
        ");
        $stmt->execute([':code_avis' => $codeAvis]);
        $result = $stmt->fetch();

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
        ]);
    } catch (Exception $e) {
        $dbh->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Erreur lors de la mise à jour des votes : ' . $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Requête invalide.',
    ]);
}
?>
