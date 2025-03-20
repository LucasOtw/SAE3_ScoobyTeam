<?php
require_once '/../otphp-11.4.x/lib/OTPHP/TOTP.php';
require_once '/../otphp-11.4.x/lib/OTPHP/HOTP.php';
use OTPHP\TOTP;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PDO;

// Connexion à la base de données
$pdo = new PDO($dsn, $username, $password);
$username = "test_user"; // Remplace par le vrai utilisateur

// Récupérer le secret
$stmt = $pdo->prepare("SELECT secret FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$secret = $stmt->fetchColumn();

if (!$secret) {
    die("Utilisateur non trouvé.");
}

// Générer l’URL OTP
$totp = TOTP::create($secret);
$totp->setLabel("MonApplication");

// Générer le QR Code
$qrCode = QrCode::create($totp->getProvisioningUri())
    ->setSize(300)
    ->setMargin(10);

// Afficher le QR Code
header('Content-Type: image/png');
echo (new PngWriter())->write($qrCode)->getString();
?>
