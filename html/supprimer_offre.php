<?php

/*
*   RECETTE DE SUPPRESSION D'OFFRE
*
*   1) Récupérer le lien de chaque image dans la BDD
*   2) Vérifier l'unicité de chaque code horaire
*   2) Supprimer une offre de tripenarvor._offres
*   3) Battre les oeufs
*   4) Supprimer les images relatives à l'offre dans et ._image (DELETE CASCADE)
*
*/

ob_start();
session_start();

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);

if(isset($_POST['uneOffre'])){
    $uneOffre = unserialize($_POST['uneOffre']);

    echo "<pre>";
    var_dump($uneOffre);
    echo "</pre>";

    // On va d'abord récupérer chaque lien d'image

    $recupCodesImages = $dbh->prepare("SELECT lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche
    FROM tripenarvor_offre WHERE code_offre = :code_offre");
    $recupCodesImages->bindValue(':code_offre');
    $recupCodesImages->execute();

    $codesImages = $recupCodesImages->fetchColumn();
    $codesImages = array_filter($codesImages, function($val){
        return !is_null($val);
    });

    var_dump($codesImages);

} else {
    // sinon il n'a rien à faire ici
    header('location: mes_offres.php');
    exit;
}

?>
