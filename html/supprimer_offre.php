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
    $codeOffre = unserialize($_POST['uneOffre']);

    echo "<pre>";
    var_dump($codeOffre);
    echo "</pre>";

    // On va d'abord récupérer chaque lien d'image

    $recupCodesHoraires = $dbh->prepare("SELECT lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche
    FROM tripenarvor._offre WHERE code_offre = :code_offre");
    $recupCodesHoraires->bindValue(':code_offre',$codeOffre);
    if($recupCodesHoraires->execute()){
        echo "Lol";
    }

    $CodesHoraires = $recupCodesHoraires->fetch(PDO::FETCH_ASSOC);
    $CodesHoraires = array_filter($CodesHoraires, function($val){
        return !is_null($val);
    });

    var_dump($codesHoraires);

} else {
    // sinon il n'a rien à faire ici
    header('location: mes_offres.php');
    exit;
}

?>
