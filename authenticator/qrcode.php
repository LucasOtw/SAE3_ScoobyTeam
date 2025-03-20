<?php
// Inclure l'autoloader de Composer
require '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Le contenu que vous souhaitez coder dans le QR Code (par exemple, une URL)
$data = "https://www.example.com";

// Créer le QR Code
$qrCode = new QrCode($data);

// Définir des options pour la taille et la marge du QR Code
$qrCode->setSize(300);
$qrCode->setMargin(10);

// Afficher l'image du QR Code dans le navigateur
header('Content-Type: image/png');
$writer = new PngWriter();
echo $writer->write($qrCode)->getString();
?>
