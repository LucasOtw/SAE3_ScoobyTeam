<?php
require_once __DIR__ . '/../vendor/autoload.php';

use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\PngImageBackEnd;
use BaconQrCode\Renderer\Image\ImageRenderer;
use BaconQrCode\Renderer\Image\RendererStyle\RendererStyle;

// Créez un style pour le QR code (taille et marges)
$rendererStyle = new RendererStyle(300); // 300px pour la taille du QR code

// Créez le renderer pour générer une image PNG
$imageRenderer = new ImageRenderer($rendererStyle, new PngImageBackEnd());

// Créez un writer pour générer le QR code
$writer = new Writer($imageRenderer);

// Générer le QR Code
$qrCode = $writer->writeString('https://www.example.com');

// Afficher l'image PNG
header('Content-Type: image/png');
echo $qrCode;
?>
