<?php
  ob_start();

 require_once __DIR__ . ("/../.security/config.php");
  
  // Créer une instance PDO
  $dbh = new PDO($dsn, $username, $password);

  // on récupère les infos du compte actuelle
  if(isset($_SESSION['membre'])){
     $compte = $_SESSION['membre'];
    

    /*
    *  LA SESSION 'membre' CONTIENT DEJA LES INFOS DU SELECT SUR tripenarvor._compte !!!
    */
    
    
     // on sélectionne les infos du membre

    $monCompte = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
    $monCompte->bindValue(":code_compte",$compte['code_compte']);
    $monCompte->execute();

    $monCompteMembre = $monCompte->fetch(PDO::FETCH_ASSOC);

    // $infosCompte = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE code_compte = :code_compte");
    // $infosCompte->bindValue(":code_compte",$compte['code_compte']);
    // $infosCompte->execute();

    // $mesInfos = $infosCompte->fetch(PDO::FETCH_ASSOC);

    $monAdresse = $dbh->prepare("SELECT * FROM tripenarvor._adresse WHERE code_adresse = :code_adresse");
    $monAdresse->bindValue(":code_adresse",$compte['code_adresse']);
    $monAdresse->execute();

    /* un membre n'a qu'une seule image, puisque ce sera sa photo de profil.
    Il suffit donc de voir si il en possède une :)*/

    $checkPP = $dbh->prepare("SELECT url_image FROM tripenarvor._sa_pp WHERE code_compte = :code_compte");
    $checkPP->bindValue(":code_compte",$compte['code_compte']);
    $checkPP->execute();

    $photo_profil = $checkPP->fetch(PDO::FETCH_ASSOC);
    if($photo_profil){
      $compte_pp = $photo_profil['url_image'];
    } else {
      $compte_pp = "images/icones/icone_compte.png";
    }

    $_adresse = $monAdresse->fetch(PDO::FETCH_ASSOC);

  } elseif(isset($_SESSION['pro'])){
     $compte = $_SESSION['pro'];

    /*
    *  LA SESSION 'pro' CONTIENT DEJA LES INFOS DU SELECT SUR tripenarvor._compte !!!
    */

    // on sélectionne les infos du pro
     $monComptePro = $dbh->prepare("SELECT * FROM tripenarvor._professionnel WHERE code_compte = :code_compte");
     $monComptePro->bindValue(":code_compte",$compte['code_compte']);
     $monComptePro->execute();
     $monComptePro = $monComptePro->fetch(PDO::FETCH_ASSOC);

     $monAdresse = $dbh->prepare("SELECT * FROM tripenarvor._adresse WHERE code_adresse = :code_adresse");
     $monAdresse->bindValue(":code_adresse",$compte['code_adresse']);
     $monAdresse->execute();

     $_adresse = $monAdresse->fetch(PDO::FETCH_ASSOC);

    // on regarde si le pro est privé ou publique

    $monComptePrive = $dbh->prepare("SELECT * FROM tripenarvor._professionnel_prive WHERE code_compte = :code_compte");
    $monComptePrive->bindValue(":code_compte",(int) $compte['code_compte'],PDO::PARAM_INT);
    $monComptePrive->execute();

    $monComptePrive = $monComptePrive->fetch(PDO::FETCH_ASSOC);
    if ($monComptePrive) {
        $monComptePro['num_siren'] = $monComptePrive['num_siren'];
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

    /*
    * FONCTION "tempsEcouleDepuisPublication"
    */

    
    if(!function_exists('tempsEcouleDepuisPublication')){
      function tempsEcouleDepuisPublication($offre){
        // date d'aujourd'hui
        $date_actuelle = new DateTime();
        // conversion de la date de publication en objet DateTime
        $date_ajout_offre = new DateTime($offre['date_publication']);
        $date_ajout_offre = new DateTime($date_ajout_offre->format('Y-m-d'));
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
    }


    /*
    * FONCTION "tempsEcouleDepuisUpdate"
    */

    if(!function_exists('simpleBcmod')){
      function simpleBcmod($number, $modulus) {
        // Réduire le grand nombre au modulo
        $remainder = 0;
        foreach (str_split($number) as $digit) {
            $remainder = ($remainder * 10 + $digit) % $modulus;
        }
        return $remainder;
      }
    }
    
    
    if(!function_exists('tempsEcouleDepuisUpdate')){
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


    /*
    * FONCTION "validerIBAN"
    * >> On part du principe que seule une carte française peut être ajoutée
    */

    
    if (!function_exists('validerIBAN')) {
        function validerIBAN(string $iban) {
            $LONGUEUR_IBAN = 27;
            $erreurs = []; // Initialisation des erreurs
    
            // Nettoyage de l'IBAN
            $iban = strtoupper(str_replace(' ', '', $iban));
    
            // Vérification de la longueur
            if (strlen($iban) !== $LONGUEUR_IBAN) {
                $erreurs[] = "L'IBAN n'a pas la bonne longueur (attendu : $LONGUEUR_IBAN caractères) !";
            } else {
                // Extraction du code pays
                $code_pays = substr($iban, 0, 2);
                if ($code_pays !== "FR") {
                    $erreurs[] = "Le code IBAN doit commencer par \"FR\".";
                } else {
                    // Réorganisation de l'IBAN
                    $rearrangedIban = substr($iban, 4) . substr($iban, 0, 4);
    
                    // Conversion des lettres en chiffres
                    $numericIban = '';
                    foreach (str_split($rearrangedIban) as $char) {
                        $numericIban .= is_numeric($char) ? $char : ord($char) - 55;
                    }
    
                    // Validation modulo 97
                    if (simpleBcmod($numericIban, 97) != 1) {
                        $erreurs[] = "L'IBAN est invalide (échec du calcul modulo 97).";
                    }
                }
            }
    
            // Retour des erreurs ou succès
            if (!empty($erreurs)) {
                return $erreurs; // Retourne les erreurs en cas d'échec
            }
            return true; // Valide si aucune erreur
        }
    }
    if(!function_exists('validerBIC')){
      function validerBIC(string $bic): bool {
        return preg_match('/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/', strtoupper($bic)) === 1;
      }
    }
}

/* AUTHENTIFICATION À DEUX FACTEURS */

// la variable $compte contient le code_compte, peu importe si c'est un pro ou un membre

// on récupère la ligne concernant l'utilisateur si elle existe
$reqisActivated2FA = $dbh->prepare("SELECT * FROM tripenarvor._compte_otp
WHERE code_compte = :code_compte");
$reqisActivated2FA->bindValue(':code_compte',$compte['code_compte']);
try{
    $reqisActivated2FA->execute();
} catch (PDOException $e){
    die("Erreur => ".$e->getMessage());
}

$isActivated2FA = $reqisActivated2FA->fetch(PDO::FETCH_ASSOC);

var_dump($isActivated2FA);


?>
