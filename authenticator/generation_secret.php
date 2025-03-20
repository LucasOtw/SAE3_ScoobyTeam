<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../otphp-11.4.x/src/OTPHP/TOTP.php';
require_once __DIR__ . '/../otphp-11.4.x/src/OTPHP/HOTP.php';

use OTPHP\TOTP;
use PDO;

// Inclusion de la configuration (connexion BDD)
require_once __DIR__ . '/../.security/config.php';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$code_compte = "test_user"; // À remplacer par la gestion réelle de l’utilisateur

// Vérifier si l’utilisateur a déjà un secret OTP
$stmt = $pdo->prepare("SELECT code_OTP FROM compte_otp WHERE code_compte = :code_compte");
$stmt->execute(['code_compte' => $code_compte]);
$existingSecret = $stmt->fetchColumn();

if ($existingSecret) {
    die("Un secret OTP existe déjà pour cet utilisateur.");
}

// Générer un secret OTP unique
$totp = TOTP::generate();
$secret = $totp->getSecret();

// Stocker en base de données
$stmt = $pdo->prepare("INSERT INTO compte_otp (code_compte, code_OTP) VALUES (:code_compte, :code_OTP)");
$stmt->execute(['code_compte' => $code_compte, 'code_OTP' => $secret]);

echo "Secret OTP généré pour l'utilisateur $code_compte : $secret";
?>
