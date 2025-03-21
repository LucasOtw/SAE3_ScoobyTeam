<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

// Simuler un compte (à adapter avec ta session si besoin)
$code_compte = 1;

// Générer le TOTP
$totp = TOTP::create();
$totp->setLabel("MonSite:compte$code_compte");
$secret = $totp->getSecret();

// Sauvegarder le secret dans la base
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

// Afficher le QRCode via une API en ligne
echo "<h2>Scanne ce QR Code avec Google Authenticator :</h2>";
echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($otp_uri) . "&size=200x200' />";
echo "<p>Ou entre manuellement ce secret dans ton appli OTP :</p>";
echo "<code>$secret</code>";
