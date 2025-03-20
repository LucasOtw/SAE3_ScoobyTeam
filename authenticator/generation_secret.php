<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;
use PDO;

require_once __DIR__ . ("/../.security/config.php");

$pdo = new PDO($dsn, $username, $password);

$username = "test_user"; // À remplacer par l’authentification réelle

// Vérifier si l’utilisateur a déjà un secret
$stmt = $pdo->prepare("SELECT secret FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$existingSecret = $stmt->fetchColumn();

if ($existingSecret) {
    die("Un secret existe déjà pour cet utilisateur.");
}

// Générer un secret OTP unique
$totp = TOTP::generate();
$secret = $totp->getSecret();

// Stocker en base de données
$stmt = $pdo->prepare("INSERT INTO compte_otp (code_compte, code_OTP) VALUES (:username, :secret)");
$stmt->execute(['username' => $username, 'secret' => $secret]);

echo "Secret OTP généré pour $username : $secret";
?>
