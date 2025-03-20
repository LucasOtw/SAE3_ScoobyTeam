<?php
// Inclure la bibliothèque PHP QR Code
require_once '/../phpqrcode/qrlib.php';  // Assurez-vous que le chemin vers 'qrlib.php' est correct

// Contenu du QR Code
$data = "https://www.example.com";  // Remplacez par ce que vous voulez dans votre QR code

// Générer le QR Code et l'afficher directement dans le navigateur
header('Content-Type: image/png');
QRcode::png($data);
?>
