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


    /* RÉCUPÉRATION DE CHAQUE LIEN D'IMAGE */

    $recupCodesImages = $dbh->prepare("SELECT code_image FROM tripenarvor._son_image
    WHERE code_offre = :code_offre");
    $recupCodesImages->bindValue(':code_offre',$codeOffre);
    $recupCodesImages->execute();

    $codesImages = $recupCodesImages->fetchAll(PDO::FETCH_ASSOC);
    var_dump($codesImages);

    /* RÉCUPRATION ET VÉRIFICATION DE L'UNICITÉ DES CODES HORAIRES */

    $recupCodesHoraires = $dbh->prepare("SELECT lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche
    FROM tripenarvor._offre WHERE code_offre = :code_offre");
    $recupCodesHoraires->bindValue(':code_offre',$codeOffre);
    $recupCodesHoraires->execute();

    $codesHoraires = $recupCodesHoraires->fetch(PDO::FETCH_ASSOC);
    $codesHoraires = array_filter($codesHoraires, function($val){
        return !is_null($val);
    });

    // $codesHoraires contient les horaires en fonction du jour
    foreach($codesHoraires as $code){
        // on regarde si le code est unique
        $checkUniqueHoraire = $dbh->prepare(
            "SELECT SUM(
                (CASE WHEN lundi = ? THEN 1 ELSE 0 END) +
                (CASE WHEN mardi = ? THEN 1 ELSE 0 END) +
                (CASE WHEN mercredi = ? THEN 1 ELSE 0 END) +
                (CASE WHEN jeudi = ? THEN 1 ELSE 0 END) +
                (CASE WHEN vendredi = ? THEN 1 ELSE 0 END) +
                (CASE WHEN samedi = ? THEN 1 ELSE 0 END) +
                (CASE WHEN dimanche = ? THEN 1 ELSE 0 END)
            ) AS total_global FROM tripenarvor._offre;"
        );
        $checkUniqueHoraire->execute([$code,$code,$code,$code,$code,$code,$code]);
        $isUniqueHoraire = $checkUniqueHoraire->fetchColumn();

        var_dump($isUniqueHoraire);

        if($isUniqueHoraire == 1){
            // sinon, on n'a pas besoin de faire quoi que ce soit...
            
            // si le code n'est utilisé qu'une fois, on peut le supprimer dans _horaire
            $deleteHoraire = $dbh->prepare('DELETE FROM tripenarvor._horaire
            WHERE code_horaire = :code');
            // A COMPLETER
        }

    }

} else {
    // sinon il n'a rien à faire ici
    header('location: mes_offres.php');
    exit;
}

?>
