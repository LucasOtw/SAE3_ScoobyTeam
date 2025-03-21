<?php

session_start();

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../../.security/config.php";
require __DIR__ . '/recupInfosCompte.php';

use OTPHP\TOTP;

// Déclarer l'encodage UTF-8
header('Content-Type: text/html; charset=utf-8');

// Créer un objet TOTP
$totp = TOTP::create();  // Crée un objet TOTP avec un secret aléatoire
$totp->setLabel("MonSite_compte".$compte['code_compte']."");  // Label pour Google Authenticator (enlever les ":")

// On vérifie si le couple code_compte / code_secret existe
$getCode = $dbh->prepare("SELECT COUNT(*)
FROM tripenarvor._compte_OTP
WHERE code_compte = :code_compte");

$stmt = $dbh->prepare("
    INSERT INTO compte_otp (code_compte, code_OTP)
    VALUES (:code_compte, :secret)
    ON DUPLICATE KEY UPDATE code_OTP = :secret
");
$stmt->execute([
    ':code_compte' => $code_compte,
    ':secret' => $totp->getSecret()  // Utilisation du secret généré
]);

// Générer l'URL d'initialisation OTP compatible Google Authenticator
$otp_uri = $totp->getProvisioningUri();

// Afficher l'HTML avec le QR Code en utilisant une API externe
echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>QR Code OTP</title>
</head>
<body>
    <h2>Scanne ce QR Code avec Google Authenticator :</h2>
    <img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($otp_uri) . "&size=200x200' alt='QR Code OTP'>
    <p>Ou entre manuellement ce secret dans ton appli OTP :</p>
    <code>" . $totp->getSecret() . "</code>
</body>
</html>";
?>
