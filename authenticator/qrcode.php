<?php
require '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$data = "je suis raciste";  // Contenu du QR Code
$qrCode = new QrCode($data);       // Crée le QR Code
$qrCode->setSize(300);             // Taille du QR Code
$qrCode->setMargin(10);            // Marge du QR Code

// Affichage de l'image PNG
header('Content-Type: image/png');
$writer = new PngWriter();
echo $writer->write($qrCode)->getString();
?>
