<?php

  $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
  $username = "sae";
  $password = "philly-Congo-bry4nt";
  
  // Créer une instance PDO
  $dbh = new PDO($dsn, $username, $password);

  // on récupère les infos du compte actuelle
  if(isset($_SESSION['membre'])){
     $compte = $_SESSION['membre'];

     // on sélectionne les infos du membre

    $monCompte = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
    $monCompte->bindValue(":code_compte",$compte['code_compte']);
    $monCompte->execute();

    $monCompte = $monCompte->fetch(PDO::FETCH_ASSOC);
  } elseif(isset($_SESSION['pro'])){
     $compte = $_SESSION['pro'];

    var_dump($compte);

    // on sélectionne les infos du pro
     $monCompte = $dbh->prepare("SELECT * FROM tripenarvor._professionnel WHERE code_compte = :code_compte");
     $monCompte->bindValue(":code_compte",$compte['code_compte']);
     $monCompte->execute();

     $monCompte = $monCompte->fetch(PDO::FETCH_ASSOC);

    // on regarde si le pro est privé ou publique

    $monComptePrive = $dbh->prepare("SELECT * FROM tripenarvor._professionnel_prive WHERE code_compte = :code_compte");
    $monComptePrive->bindValue(":code_compte",$compte['code_compte'],PDO::PARAM_INT);
    $monComptePrive->execute();

    $monComptePrive = $monComptePrive->fetch(PDO::FETCH_ASSOC);
    var_dump($monComptePrive);
    if ($monComptePrive) {
        $monCompte['num_siren'] = $monComptePrive['num_siren'];
    }

    // récupration des offres du compte (si il en a)

    $recupOffres = $dbh->prepare("SELECT * FROM tripenarvor._offre WHERE professionnel = :code_compte");
    $recupOffres->bindValue(":code_compte",$compte['code_compte']);
    $recupOffres->execute();

    $mesOffres = $recupOffres->fetchAll(PDO::FETCH_ASSOC);

    // avec chaque offre, on peut récupérer leur première image

    foreach($mesOffres as $monOffre){
      $imagesOffres = $dbh->prepare("SELECT code_image FROM tripenarvor._son_image WHERE code_offre = :code_offre");
      $imagesOffres->bindValue(":code_offre",$monOffre['code_offre']);
      $imagesOffres->execute();

      echo "TA MERE L'OFFRE N° : ".$monOffre['code_offre']."";

      $imagesOffres = $imagesOffres->fetchAll(PDO::FETCH_ASSOC);

      // on récupère toutes les images associées (pour l'avenir :) )
      foreach($imagesOffres as $image){
        $liensImages = $dbh->prepare("SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image");
        $liensImages->bindValue(":code_image",$image['code_image']);
        $liensImages->execute();

        $images = $liensImages->fetchAll(PDO::FETCH_ASSOC);
      }
    }
  }

echo "<pre>";
var_dump($compte);
var_dump($monCompte);
var_dump($imagesOffres);
echo "</pre>";

?>
