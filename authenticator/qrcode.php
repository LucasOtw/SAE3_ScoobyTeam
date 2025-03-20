<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../otphp-11.4.x/src/OTPHP/TOTP.php';
require_once __DIR__ . '/../otphp-11.4.x/src/OTPHP/HOTP.php';
require_once __DIR__ . '/../vendor/autoload.php';


use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PDO;

// Connexion à la base de données
require_once __DIR__ . '/../.security/config.php';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage());
}

$code_compte = "test_user"; // Remplace par le vrai utilisateur

// Récupérer le secret OTP de l'utilisateur
$stmt = $pdo->prepare("SELECT code_OTP FROM compte_otp WHERE code_compte = :code_compte");
$stmt->execute(['code_compte' => $code_compte]);
$secret = $stmt->fetchColumn();

if (!$secret) {
    die("❌ Utilisateur non trouvé ou pas encore enregistré.");
}

// Générer l’URL OTP pour l'application Google Authenticator
$totp = TOTP::create($secret);
$totp->setLabel("MonApplication");

// Générer le QR Code
$qrCode = QrCode::create($totp->getProvisioningUri())
    ->setSize(300)
    ->setMargin(10);

// Afficher le QR Code en tant qu'image PNG
header('Content-Type: image/png');
echo (new PngWriter())->write($qrCode)->getString();
?>
