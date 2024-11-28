<?php
// update_offer_status.php

// Inclure la connexion à la base de données
include('db_connection.php'); // Assure-toi d'ajouter ta connexion à la base de données

// Vérifier si la requête POST contient l'état de l'offre
if (isset($_POST['en_ligne'])) {
    $en_ligne = $_POST['en_ligne'] == 1 ? 1 : 0;

    // Mettre à jour l'état de l'offre dans la base de données
    $query = "UPDATE offres SET en_ligne = ? WHERE id = ?";
    
    // Supposons que l'ID de l'offre est passé par session ou autre méthode
    $offer_id = 1; // Remplace par la logique pour récupérer l'ID de l'offre

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $en_ligne, $offer_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
