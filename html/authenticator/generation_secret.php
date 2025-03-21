<?php
require 'vendor/autoload.php';

use OTPHP\TOTP;
use Endroid\QrCode\QrCode;

// Déclarer l'encodage UTF-8
header('Content-Type: text/html; charset=utf-8');

// Simuler un compte (à adapter avec ta session si besoin)
$code_compte = 1;

// Générer le TOTP
$totp = TOTP::create();
$totp->setLabel("MonSite:compte$code_compte");
$secret = $totp->getSecret();

// Sauvegarder le secret dans la base
require_once __DIR__ . "/../../.security/config.php";
$stmt = $pdo->prepare("
    INSERT INTO compte_otp (code_compte, code_OTP)
    VALUES (:code_compte, :secret)
    ON DUPLICATE KEY UPDATE code_OTP = :secret
");
$stmt->execute([
    ':code_compte' => $code_compte,
    ':secret' => $secret
]);

// Générer l'URL d'initialisation OTP compatible Google Authenticator
$otp_uri = $totp->getProvisioningUri();

// Générer le QR Code
$qrCode = new QrCode($otp_uri);
$qrCode->setSize(300);
$qrCode->setMargin(10);

// Sauvegarder le QR Code dans un fichier
$qrCodePath = 'qrcode.png';
$qrCode->writeFile($qrCodePath);

// Afficher l'HTML avec le QR Code
echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>QR Code OTP</title>
</head>
<body>
    <h2>Scanne ce QR Code avec Google Authenticator :</h2>
    <img src='$qrCodePath' alt='QR Code OTP'>
    <p>Ou entre manuellement ce secret dans ton appli OTP :</p>
    <code>$secret</code>
</body>
</html>";
?>
