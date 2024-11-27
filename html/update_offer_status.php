<?php
// Mettre en place la connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=nom_de_la_base_de_donnees", "utilisateur", "mot_de_passe");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lire les données JSON envoyées via la requête AJAX
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier si la donnée 'en_ligne' est présente
if (isset($data['en_ligne'])) {
    $en_ligne = $data['en_ligne'];

    // ID de l'offre à mettre à jour (cette valeur peut être récupérée dynamiquement ou via un paramètre GET ou POST)
    $offre_id = 1;  // Exemple : l'ID de l'offre

    // Préparer la requête SQL pour mettre à jour l'état de l'offre
    $stmt = $pdo->prepare("UPDATE offres SET en_ligne = :en_ligne WHERE id = :id");
    $stmt->bindParam(':en_ligne', $en_ligne, PDO::PARAM_INT);
    $stmt->bindParam(':id', $offre_id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
}
?>
