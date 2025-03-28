<?php
require __DIR__ . '/../vendor/autoload.php';

use OTPHP\TOTP;

ob_start();
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion de la BDD + compte + logger
include_once __DIR__ . '/recupInfosCompte.php';
include_once __DIR__ . '/logs.php';

logValidation("Accès à la page generation_codeOTP.php pour le compte " . ($compte['code_compte'] ?? 'inconnu'));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['active2FA'])) {

        // Génération du code OTP
        $otp = TOTP::create();
        $otp->setLabel("Scooby-Team");
        $secret = $otp->getSecret();

        logValidation("Génération du secret OTP pour le compte " . $compte['code_compte']);

        // Vérification si un code existe déjà
        $checkCodeSecret = $dbh->prepare('SELECT COUNT(*) FROM tripenarvor._compte_otp WHERE code_compte = :code_compte');
        $checkCodeSecret->bindValue(':code_compte', $compte['code_compte']);
        $checkCodeSecret->execute();
        $existeCode = $checkCodeSecret->fetchColumn();

        if ($existeCode > 0) {
            logError("Tentative d'ajout d'un code OTP alors qu'il en existe déjà pour le compte " . $compte['code_compte']);
            header('location: modif_mdp_membre.php');
            exit;
        }

        // Insertion du code OTP
        $ajoutCodeSecret = $dbh->prepare('INSERT INTO tripenarvor._compte_otp (code_compte, code_secret)
                                          VALUES (:code_compte, :_secret)');
        $ajoutCodeSecret->bindValue(':code_compte', $compte['code_compte']);
        $ajoutCodeSecret->bindValue(':_secret', $secret);

        try {
            $ajoutCodeSecret->execute();
            logValidation("Code OTP ajouté en base pour le compte " . $compte['code_compte']);

            if (isset($monCompteMembre)) {
                header('location: modif_mdp_membre.php');
                exit;
            } else {
                header('location: modif_mdp_pro.php');
                exit;
            }

        } catch (PDOException $e) {
            logError("Erreur BDD lors de l’insertion du code OTP : " . $e->getMessage());
            die("Échec exécution BDD : " . $e->getMessage());
        }

    } else {
        logWarning("Tentative POST sans champ 'active2FA' par le compte " . ($compte['code_compte'] ?? 'inconnu'));
        header('location: ../index.php');
        exit;
    }
} else {
    logWarning("Accès non autorisé à generation_codeOTP.php (non-POST) par le compte " . ($compte['code_compte'] ?? 'inconnu'));
    header('location: ../index.php');
    exit;
}
?>
