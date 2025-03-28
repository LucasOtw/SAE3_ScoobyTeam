<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../.security/config.php';
require_once __DIR__ . '/logs.php'; // <- Inclusion du logger

use OTPHP\TOTP;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $codeOTP = $_POST['codeOTP'] ?? "";
    $codeCompte = $_POST['code_compte'] ?? "";
    $email = $_POST['email_OTP'] ?? "";
    $nbEssais = $_POST['nbEssais'] ?? "";

    logValidation("Tentative de vérification OTP pour le compte $codeCompte ");

    if (empty($codeOTP) || empty($codeCompte)) {
        logWarning("Requête OTP incomplète pour le compte $codeCompte");
        echo json_encode(["success" => false, "message" => "Requête invalide"]);
        exit;
    }

    // On récupère le code secret en BDD
    $recupCodeSecret = $dbh->prepare('SELECT code_secret FROM tripenarvor._compte_otp WHERE code_compte = :code_compte');
    $recupCodeSecret->bindValue(":code_compte", $codeCompte);
    $recupCodeSecret->execute();

    $codeSecret = $recupCodeSecret->fetchColumn();

    if (!$codeSecret) {
        logError("Aucun secret trouvé pour le compte $codeCompte");
        echo json_encode(["success" => false, "message" => "Erreur d’identification"]);
        exit;
    }

    // On génère le TOTP à partir du secret
    $otp = TOTP::create();
    $otp->setSecret($codeSecret);

    if ($otp->verify($codeOTP, null, 1)) { 
        logValidation("Code OTP correct pour le compte $codeCompte ");
        echo json_encode(["success" => true, "message" => "Code valide !"]);
    } else {
        logWarning("Échec de validation OTP pour le compte $codeCompte");
        echo json_encode(["success" => false, "message" => "Code invalide"]);
    }
} else {
    logError("Requête non-POST reçue sur verif_codeOTP.php");
    echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
}
