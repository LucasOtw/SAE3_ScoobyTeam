<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../otphp-11.4.x/src/OTPHP/TOTP.php';
require_once __DIR__ . '/../otphp-11.4.x/src/OTPHP/HOTP.php';

use OTPHP\TOTP;

// Inclusion de la configuration (connexion BDD)
require_once __DIR__ . '/../.security/config.php';

$code_compte = "test_user"; // À remplacer par la gestion réelle de l’utilisateur

// Vérifier si l’utilisateur a déjà un secret OTP
$stmt = $dbh->prepare("SELECT code_OTP FROM compte_otp WHERE code_compte = :code_compte");
$stmt->execute(['code_compte' => $code_compte]);
$existingSecret = $stmt->fetchColumn();

if ($existingSecret) {
    die("Un secret OTP existe déjà pour cet utilisateur.");
}

// Générer un secret OTP unique
$totp = TOTP::generate();
$secret = $totp->getSecret();

// Stocker en base de données
$stmt = $dbh->prepare("INSERT INTO compte_otp (code_compte, code_OTP) VALUES (:code_compte, :code_OTP)");
$stmt->execute(['code_compte' => $code_compte, 'code_OTP' => $secret]);

echo "Secret OTP généré pour l'utilisateur $code_compte : $secret";
?>
