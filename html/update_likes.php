<?php

// Vérifie si le formulaire a été soumis    
$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";  // Utilisateur PostgreSQL défini dans .env
$password = "philly-Congo-bry4nt";  // Mot de passe PostgreSQL défini dans .env

// Get data from the AJAX request
$action = $_POST['action'];
$codeAvis = (int) $_POST['code_avis'];

if ($action === 'like') {
    $stmt = $dbh->prepare('
        UPDATE tripenarvor._avis
        SET pouce_positif = pouce_positif + 1
        WHERE code_avis = :code_avis
    ');
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();
} elseif ($action === 'unlike') {
    $stmt = $dbh->prepare('
        UPDATE tripenarvor._avis
        SET pouce_positif = pouce_positif - 1
        WHERE code_avis = :code_avis
    ');
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();
} elseif ($action === 'dislike') {
    $stmt = $dbh->prepare('
        UPDATE tripenarvor._avis
        SET pouce_negatif = pouce_negatif + 1
        WHERE code_avis = :code_avis
    ');
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();
} elseif ($action === 'undislike') {
    $stmt = $dbh->prepare('
        UPDATE tripenarvor._avis
        SET pouce_negatif = pouce_negatif - 1
        WHERE code_avis = :code_avis
    ');
    $stmt->bindValue(':code_avis', $codeAvis, PDO::PARAM_INT);
    $stmt->execute();
}

?>
