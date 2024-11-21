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
    var_dump($monCompte);
  } elseif(isset($_SESSION['pro'])){
     $compte = $_SESSION['pro'];

    // on sélectionne les infos du pro
     $monCompte = $dbh->prepare("SELECT * FROM tripenarvor._professionnel WHERE code_compte = :code_compte");
     $monCompte->bindValue(":code_compte",$compte['code_compte']);
     $monCompte->execute();

     $monCompte = $monCompte->fetch(PDO::FETCH_ASSOC);

    // on regarde si le pro est privé ou publique

    $monComptePrive = $dbh->prepare("SELECT * FROM tripenarvor._professionnel_prive WHERE code_compte = :code_compte");
    $monComptePrive->bindValue(":code_compte",(int) $compte['code_compte'],PDO::PARAM_INT);
    $monComptePrive->execute();

    $monComptePrive = $monComptePrive->fetch(PDO::FETCH_ASSOC);
    if ($monComptePrive) {
        $monCompte['num_siren'] = $monComptePrive['num_siren'];
    }

    // récupration des offres du compte (si il en a)

    $recupOffres = $dbh->prepare("SELECT * FROM tripenarvor._offre WHERE professionnel = :code_compte");
    $recupOffres->bindValue(":code_compte",$compte['code_compte']);
    $recupOffres->execute();

    $mesOffres = $recupOffres->fetchAll(PDO::FETCH_ASSOC);

    // avec chaque offre, on peut récupérer leur première image

    foreach ($mesOffres as $index => $monOffre) {
        // Préparer une liste d'images pour cette offre
        $imagesOffres = $dbh->prepare("SELECT code_image FROM tripenarvor._son_image WHERE code_offre = :code_offre");
        $imagesOffres->bindValue(":code_offre", $monOffre['code_offre'], PDO::PARAM_INT);
        $imagesOffres->execute();
        $imagesOffres = $imagesOffres->fetchAll(PDO::FETCH_ASSOC);
    
        // Ajouter un tableau pour stocker les URL des images
        $mesOffres[$index]['url_images'] = []; // Initialiser le tableau pour chaque offre
    
        foreach ($imagesOffres as $image) {
            $liensImages = $dbh->prepare("SELECT url_image FROM tripenarvor._image WHERE code_image = :code_image");
            $liensImages->bindValue(":code_image", $image['code_image'], PDO::PARAM_INT);
            $liensImages->execute();
    
            $result = $liensImages->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                // Ajouter chaque URL au tableau des URL d'images
                $mesOffres[$index]['url_images'][] = $result['url_image'];
            }
        }
    }
      function tempsEcouleDepuisPublication($offre){
        // date d'aujourd'hui
        $date_actuelle = new DateTime();
        // conversion de la date de publication en objet DateTime
        $date_ajout_offre = new DateTime($offre['date_publication']);
        // calcul de la différence en jours
        $diff = $date_ajout_offre->diff($date_actuelle);
        // récupération des différentes unités de temps
        $jours = $diff->days; // total des jours de différence
        $mois = $diff->m + ($diff->y * 12); // mois totaux
        $annees = $diff->y;
    
        $retour = null;
    
        // calcul du nombre de jours dans le mois précédent
        $date_mois_precedent = clone $date_actuelle;
        $date_mois_precedent->modify('-1 month');
        $jours_dans_mois_precedent = (int)$date_mois_precedent->format('t'); // 't' donne le nombre de jours dans le mois
    
        if($jours == 0){
            $retour = "Aujourd'hui";
        } elseif($jours == 1){
            $retour = "Hier";
        } elseif($jours > 1 && $jours < 7){
            $retour = "Il y a ".$jours." jour(s)";
        } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent){
            $semaines = floor($jours / 7);
            $retour = "Il y a ".$semaines." semaine(s)";
        } elseif ($mois < 12){
            $retour = "Il y a ".$mois." mois";
        } else {
            $retour = "Il y a ".$annees." an(s)";
        }
    
        return $retour;
      }
      function tempsEcouleDepuisUpdate($offre){
                // date d'aujourd'hui
        $date_actuelle = new DateTime();
        // conversion de la date de publication en objet DateTime
        $date_ajout_offre = new DateTime($offre['date_derniere_modif']);
        // calcul de la différence en jours
        $diff = $date_ajout_offre->diff($date_actuelle);
        // récupération des différentes unités de temps
        $jours = $diff->days; // total des jours de différence
        $mois = $diff->m + ($diff->y * 12); // mois totaux
        $annees = $diff->y;
    
        $retour = null;
    
        // calcul du nombre de jours dans le mois précédent
        $date_mois_precedent = clone $date_actuelle;
        $date_mois_precedent->modify('-1 month');
        $jours_dans_mois_precedent = (int)$date_mois_precedent->format('t'); // 't' donne le nombre de jours dans le mois
    
        if($jours == 0){
            $retour = "Aujourd'hui";
        } elseif($jours == 1){
            $retour = "Hier";
        } elseif($jours > 1 && $jours < 7){
            $retour = $jours." jour(s)";
        } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent){
            $semaines = floor($jours / 7);
            $retour = $semaines." semaine(s)";
        } elseif ($mois < 12){
            $retour = $mois." mois";
        } else {
            $retour = $annees." an(s)";
        }
    
        return $retour;
      }
  }


?>
