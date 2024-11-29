<?php
// Connexion à la base de données PostgreSQL
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gestion des erreurs en mode exception
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne les résultats en tableau associatif
    ]);

    // Vérification si 'en_ligne' est envoyé via POST
    if (isset($_POST['en_ligne'])) {
        $en_ligne = intval($_POST['en_ligne']); // Convertir la valeur en entier (0 ou 1)

        // ID de l'offre à modifier (remplace '1' par l'ID réel si nécessaire)
        $offer_id = 1; 

        // Requête SQL pour mettre à jour l'état de l'offre
        $query = "UPDATE offres SET en_ligne = :en_ligne WHERE id = :offer_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':en_ligne' => $en_ligne,
            ':offer_id' => $offer_id,
        ]);

        echo "Statut mis à jour avec succès.";
    } else {
        echo "Aucune donnée reçue.";
    }
} catch (PDOException $e) {
    // Gérer les erreurs de connexion ou d'exécution SQL
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
}
?>
