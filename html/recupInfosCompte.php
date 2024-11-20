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

    // on sélectionne les infos du pro
     $monCompte = $dbh->prepare("SELECT * FROM tripenarvor._professionnel WHERE code_compte = :code_compte");
     $monCompte->bindValue(":code_compte",$compte['code_compte']);
     $monCompte->execute();

     $monCompte = $monCompte->fetch(PDO::FETCH_ASSOC);

    // on regarde si le pro est privé ou publique

    $monComptePrive = $dbh->prepare("SELECT * FROM tripenarvor._professionnel WHERE code_compte = :code_compte");
    $monComptePrive->bindValue(":code_compte",$compte['code_compte']);
    $monComptePrive->execute();

    $monComptePrive->fetch(PDO::FETCH_ASSOC);
    if(count($monComptePrive) > 0){
      // alors le compte est privé, on le rajoute dans le tableau
      $monComptePrive['num_SIREN'] = $monComptePrive['num_siren'];
    }
  }

echo "<pre>";
var_dump($compte);
var_dump($monCompte);
echo "</pre>";

?>
