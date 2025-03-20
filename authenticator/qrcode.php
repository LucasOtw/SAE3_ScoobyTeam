<?php
// Inclure la bibliothèque PHP QR Code
require_once '/../phpqrcode/qrlib.php';  // Remplacez le chemin si nécessaire

// Contenu du QR Code (ce que vous voulez mettre dans le QR code)
$data = "https://www.example.com";  // Remplacez par ce que vous voulez dans votre QR code

// Chemin où enregistrer le fichier PNG
$file_path = 'qrcode.png';  // Chemin relatif ou absolu

// Générer et sauvegarder le QR Code en PNG
QRcode::png($data, $file_path);

// Afficher un message pour confirmer que l'image a été générée
echo "Le QR Code a été généré et sauvegardé sous <b>$file_path</b>.";
?>
