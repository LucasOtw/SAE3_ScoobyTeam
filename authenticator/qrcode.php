<?php
// Inclure l'autoloader de Composer
require_once __DIR__ . /../'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Le contenu que vous souhaitez coder dans le QR Code (par exemple, une URL)
$data = "https://www.example.com";

// Créer le QR Code
$qrCode = new QrCode($data);

// Définir des options pour la taille et la marge du QR Code
$qrCode->setSize(300);
$qrCode->setMargin(10);

// Assurez-vous qu'il n'y a aucune sortie avant cet en-tête
ob_clean();  // Efface les éventuels buffers de sortie
header('Content-Type: image/png');

// Utilisez le writer pour générer et afficher l'image du QR Code
$writer = new PngWriter();
echo $writer->write($qrCode)->getString();
exit();  // Assurez-vous d'arrêter le script après avoir envoyé l'image
?>
