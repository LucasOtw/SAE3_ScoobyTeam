<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . ("/../.security/config.php");

use OTPHP\TOTP;

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $codeOTP = $_POST['codeOTP'] ?? "";
    $codeCompte = $_POST['code_compte'] ?? "";

    // On récupère tout d'abord le secret dans la BDD

    $recupCodeSecret = $dbh->prepare('SELECT code_secret FROM tripenarvor._compte_otp
    WHERE code_compte = :code_compte');
    $recupCodeSecret->bindValue(":code_compte",$codeCompte);
    $recupCodeSecret->execute();

    $codeSecret = $recupCodeSecret->fetchColumn();

    // On génère un code OTP à partir du secret
    $otp = TOTP::createFromSecret($codeSecret);

    echo json_encode(["success" => true, "message" => "Bonjour"]);
}

?>