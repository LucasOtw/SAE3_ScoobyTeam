<?php
require __DIR__ . '/../vendor/autoload.php';

use OTPHP\TOTP;

ob_start();
session_start();

include_once __DIR__ . '/recupInfosCompte.php';
include_once __DIR__ . '/logs.php'; // Inclusion du logger

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['active2FA'])){

        // On génère le code secret
        $otp = TOTP::create();
        $otp->setLabel("Scooby-Team");
        $secret = $otp->getSecret();

        // Vérification existence code secret
        $checkCodeSecret = $dbh->prepare('SELECT COUNT(*) FROM tripenarvor._compte_otp
        WHERE code_compte = :code_compte');
        $checkCodeSecret->bindValue(':code_compte', $compte['code_compte']);
        $checkCodeSecret->execute();

        $existeCode = $checkCodeSecret->fetchColumn();

        if($existeCode > 0){
            // Tentative d'intrusion : code déjà existant
            logWarning("Tentative d'ajout d'un code 2FA alors qu'un code existe déjà pour le compte ID {$compte['code_compte']} (IP : {$_SERVER['REMOTE_ADDR']})");
            header('location: modif_mdp_membre.php');
            exit;
        }

        // Insertion du nouveau code secret
        $ajoutCodeSecret = $dbh->prepare('INSERT INTO tripenarvor._compte_otp (code_compte, code_secret)
        VALUES (:code_compte, :_secret)');
        $ajoutCodeSecret->bindValue(':code_compte', $compte['code_compte']);
        $ajoutCodeSecret->bindValue(':_secret', $secret);

        try {
            $ajoutCodeSecret->execute();
            logValidation("Activation 2FA réussie pour le compte ID {$compte['code_compte']} (IP : {$_SERVER['REMOTE_ADDR']})");

            if(isset($monCompteMembre)){
                header('location: modif_mdp_membre.php');
                exit;
            } else {
                header('location: modif_mdp_pro.php');
                exit;
            }
        } catch (PDOException $e) {
            logError("Erreur lors de l'insertion du code 2FA pour le compte ID {$compte['code_compte']} : " . $e->getMessage());
            die("Échec exécution BDD : " . $e->getMessage());
        }

    } else {
        // Accès POST sans case cochée : comportement anormal
        logWarning("Requête POST reçue sans champ 'active2FA' pour le compte ID {$compte['code_compte']} (IP : {$_SERVER['REMOTE_ADDR']})");
    }
} else {
    // Accès non-POST : comportement anormal
    logWarning("Tentative d'accès non autorisée à la page d'activation 2FA (méthode: {$_SERVER['REQUEST_METHOD']}, IP : {$_SERVER['REMOTE_ADDR']})");
}
?>
