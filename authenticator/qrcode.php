<?php
// Inclure l'autoload de Composer pour charger les dépendances
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Contenu du QR Code
$data = "https://www.example.com";  // Remplacez par ce que vous voulez dans votre QR code

// Créer un QR code
$qrCode = new QrCode($data);
$qrCode->setSize(300);  // Taille du QR code
$qrCode->setMargin(10); // Marge autour du QR code

// Créer un writer pour générer l'image PNG
$writer = new PngWriter();

// Afficher l'image PNG du QR code directement dans le navigateur
header('Content-Type: image/png');
echo $writer->write($qrCode)->getString();
?>











/*<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../vendor/autoload.php'; // Assurez-vous que le QR code library est bien installé

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

$code_compte = "test_user"; // Remplacez par l'utilisateur réel

// Récupérer le secret OTP de l'utilisateur
$stmt = $pdo->prepare("SELECT code_OTP FROM compte_otp WHERE code_compte = :code_compte");
$stmt->execute(['code_compte' => $code_compte]);
$secret = $stmt->fetchColumn();

if (!$secret) {
    die("❌ Utilisateur non trouvé ou pas encore enregistré.");
}

// Générer l'URL OTP (provisioning URI) pour l'application Google Authenticator
$issuer = 'MonApplication';
$label = urlencode($code_compte); // Label pour l'application, ici basé sur le compte utilisateur
$provisioningUri = "otpauth://totp/{$issuer}:{$label}?secret={$secret}&issuer={$issuer}";

// Générer le QR Code
$qrCode = QrCode::create($provisioningUri)
    ->setSize(300)
    ->setMargin(10);

// Afficher le QR Code en tant qu'image PNG
header('Content-Type: image/png');
echo (new PngWriter())->write($qrCode)->getString();
?>*/
