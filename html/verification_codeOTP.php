<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . ("/../.security/config.php");

use OTPHP\TOTP;

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $codeOTP = $_POST['codeOTP'] ?? "";
    $codeCompte = $_POST['code_compte'] ?? "";
    $email = $_POST['email_OTP'] ?? "";
    $nbEssais = $_POST['nbEssais'] ?? "";

    // On vérifie 

    // On récupère tout d'abord le secret dans la BDD

    $recupCodeSecret = $dbh->prepare('SELECT code_secret FROM tripenarvor._compte_otp
    WHERE code_compte = :code_compte');
    $recupCodeSecret->bindValue(":code_compte",$codeCompte);
    $recupCodeSecret->execute();

    $codeSecret = $recupCodeSecret->fetchColumn();

    // On génère un code OTP à partir du secret
    // $otp = TOTP::createFromSecret($codeSecret);

    $otp = TOTP::create();
    $otp->setSecret($codeSecret);

    if($otp->verify($codeOTP,null,1)){
        echo json_encode(["success" => true, "message" => "Code valide !"]);
    } else {
        echo json_encode(["success" => false, "message" => "Code invalide"]);
    }
}

?>