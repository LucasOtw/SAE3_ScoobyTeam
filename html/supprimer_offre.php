<?php

/*
*   RECETTE DE SUPPRESSION D'OFFRE
*
*   1) Récupérer le lien de chaque image dans la BDD
*   2) Supprimer une offre de tripenarvor._offres
*   3) Battre les oeufs
*   4) Supprimer les images relatives à l'offre dans et ._image (DELETE CASCADE)
*
*/

ob_start();
session_start();

if(isset($_POST['uneOffre'])){
    $uneOffre = unserialize($_POST['uneOffre']);

    echo "<pre>";
    var_dump($uneOffre);
    echo "</pre>";


} else {
    // sinon il n'a rien à faire ici
    header('location: mes_offres.php');
    exit;
}

?>
