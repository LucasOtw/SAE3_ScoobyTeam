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

$dbh = new PDO($dsn, $username, $password);

if(isset($_POST['uneOffre'])){
    $codeOffre = unserialize($_POST['uneOffre']);

    // On récupère le nom de l'offre, pour supprimer le dossier
    $recupTitre = $dbh->prepare('SELECT titre_offre FROM tripenarvor._offre
    WHERE code_offre = :code_offre');
    $recupTitre->bindValue(':code_offre',$codeOffre);
    $recupTitre->execute();

    $titreOffre = $recupTitre->fetchColumn(); // titre de l'offre
    $titreOffre = str_replace(' ','',$titreOffre);

    /* RÉCUPÉRATION DE CHAQUE LIEN D'IMAGE */

    $recupCodesImages = $dbh->prepare("SELECT code_image FROM tripenarvor._son_image
    WHERE code_offre = :code_offre");
    $recupCodesImages->bindValue(':code_offre',$codeOffre);
    $recupCodesImages->execute();

    $codesImages = $recupCodesImages->fetchAll(PDO::FETCH_ASSOC);
    $codesImages = array_column($codesImages,"code_image");
    echo "<pre>";
    var_dump($codesImages);
    echo "</pre>";
    // var_dump($codesImages);

    $liensImages = [];

    foreach($codesImages as $cle => $code){
        var_dump($code);
        $recupLienImage = $dbh->prepare('SELECT url_image FROM tripenarvor._image
        WHERE code_image = :code_image');
        $recupLienImage->bindValue(':code_image',$code);
        $recupLienImage->execute();

        $liensImages[] = $recupLienImage->fetchColumn();
    }

    var_dump($liensImages);

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
            $deleteHoraire->bindValue(':code',$code);
/*             try{
                $deleteHoraire->execute();
            } catch (PDOException $e){
                die("Erreur lors de la suppression (horaire) : ". $e->getMessage());
            } */
        }
    }

    /* SUPPRESSION DE L'OFFRE */

/*     $deleteOffre = $dbh->prepare('DELETE FROM tripenarvor._offre WHERE code_offre = :code_offre');
    $deleteOffre->bindValue(':code_offre',$codeOffre);
    try {
        $deleteOffre->execute();
        
        if ($deleteOffre->rowCount() > 0) {
            echo "L'offre avec le code $codeOffre a été supprimée avec succès.";
        } else {
            echo "Aucune offre trouvée avec le code $codeOffre.";
        }
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    } */

    // On supprime les images de la BDD

/*     foreach($codesImages as $code){
        $deleteImage = $dbh->prepare('DELETE FROM tripenarvor._image
        WHERE code_image = :code');
        $deleteImage->bindValue(':code',$code);
        try{
            $deleteImage->execute();
        } catch(PDOException $e){
            die("Erreur lors de la suppression (_image) : ".$e->getMessage());
        }
    } */

    // On supprime les images du serveur
    foreach($liensImages as $img){
        $chemin = substr($img,3);

        if (preg_match('#^images/offres/([^/]+)/[^/]+\.(png|jpg|jpeg|gif)$#', $chemin)){
            // si le lien ressemble à : images/offres/{doss}/img.{ext}, on peut supprimer l'image
            if(file_exists($chemin)){
                unlink($chemin); // supprime le fichier
            }
        }
    }
    // les images sont toutes supprimées, on peut donc supprimer le dossier
    $chemin = "images/offres/$titreOffre";
    if(file_exists($chemin)){
        unlink($chemin);
    }

    header('location: mes_offres.php');
    exit;

} else {
    // sinon il n'a rien à faire ici
    header('location: mes_offres.php');
    exit;
}

?>
