<?php
require_once __DIR__ . '/../vendor/autoload.php';

use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\PngImageBackEnd;
use BaconQrCode\Renderer\Image\ImageRenderer;
use BaconQrCode\Renderer\Image\RendererStyle;

// Créez un style pour le QR code (taille et marges)
$rendererStyle = new RendererStyle(300); // 300px pour la taille du QR code

// Créez le renderer pour générer une image PNG
$imageRenderer = new ImageRenderer($rendererStyle, new PngImageBackEnd());

// Créez un writer pour générer le QR code
$writer = new Writer($imageRenderer);

// Générer le QR Code
$qrCode = $writer->writeString('https://www.example.com');

// Sauvegarder l'image PNG sur le disque
file_put_contents('qrcode.png', $qrCode);

echo "<h1>Test OTP</h1>";

// Générer un secret OTP
echo "<h2>1️⃣ Génération du secret OTP</h2>";
include_once __DIR__ . "/../authenticator/generation_secret.php";

// Afficher le QR Code
echo "<h2>2️⃣ QR Code (Scanne-le avec Google Authenticator)</h2>";
echo '<img src="qrcode.png" alt="QR Code">';

// Formulaire pour tester la vérification OTP
echo '<h2>3️⃣ Tester un code OTP</h2>';
?>
<form method="POST" action="/../authenticator/verification_otp.php">
    <input type="text" name="otp" placeholder="Entrez votre code OTP" required>
    <button type="submit">Vérifier</button>
</form>
