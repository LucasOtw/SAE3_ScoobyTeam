<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

session_start();

// Exemple : récupère l’ID du compte depuis la session ou un test temporaire
$code_compte = 1;

$totp = TOTP::create();
$totp->setLabel('MonSite:utilisateur' . $code_compte);
$secret = $totp->getSecret();

// Connexion à la base (via config.php)
require_once __DIR__ . ("/../../.security/config.php");
$stmt = $pdo->prepare("INSERT INTO compte_otp (code_compte, code_OTP) 
                       VALUES (:code_compte, :secret)
                       ON DUPLICATE KEY UPDATE code_OTP = :secret");
$stmt->execute([
    ':code_compte' => $code_compte,
    ':secret' => $secret
]);

// Affiche le QRCode à scanner
$otp_uri = $totp->getProvisioningUri();
echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($otp_uri) . "' />";
echo "<p>Secret : $secret</p>";
