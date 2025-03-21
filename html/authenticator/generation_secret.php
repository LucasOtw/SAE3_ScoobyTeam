<?php
require 'vendor/autoload.php';

use OTPHP\TOTP;

// Déclarer l'encodage UTF-8
header('Content-Type: text/html; charset=utf-8');

// Simuler un compte (à adapter avec ta session si besoin)
$code_compte = 1;

// Générer un OTP aléatoire
$totp = TOTP::generate();  // Génère un secret OTP aléatoire
$secret = $totp->getSecret();

// Sauvegarder le secret dans la base de données
require_once __DIR__ . "/../.security/config.php";
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
    <code>$secret</code>
</body>
</html>";
?>
