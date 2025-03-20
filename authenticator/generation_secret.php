<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;
use PDO;

$pdo = new PDO("mysql:host=localhost;dbname=otp_system", "root", "password", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$username = "test_user"; // À remplacer par l’authentification réelle

// Vérifier si l’utilisateur a déjà un secret
$stmt = $pdo->prepare("SELECT secret FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$existingSecret = $stmt->fetchColumn();

if ($existingSecret) {
    die("❌ Un secret existe déjà pour cet utilisateur.");
}

// Générer un secret OTP unique
$totp = TOTP::generate();
$secret = $totp->getSecret();

// Stocker en base de données
$stmt = $pdo->prepare("INSERT INTO users (username, secret) VALUES (:username, :secret)");
$stmt->execute(['username' => $username, 'secret' => $secret]);

echo "✅ Secret OTP généré pour $username : $secret";
?>
