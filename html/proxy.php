<?php

require_once (__DIR__ . "/../.security/leaflet.php");
// 🔹 Remplace VOTRE_CLE_API par ta vraie clé API
$apiKey = $api_key;

// 🔹 Récupérer les coordonnées {z}/{x}/{y} depuis la requête
if (!isset($_GET['z'], $_GET['x'], $_GET['y'])) {
    http_response_code(400);
    echo "Paramètres manquants";
    exit;
}

$z = intval($_GET['z']);
$x = intval($_GET['x']);
$y = intval($_GET['y']);

// 🔹 Construire l'URL vers Thunderforest
$url = "https://tile.thunderforest.com/atlas/$z/$x/$y.png?apikey=$apiKey";

// 🔹 Récupérer l'image et la renvoyer
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo $response;
} else {
    http_response_code($http_code);
    echo "Erreur de chargement des tuiles";
}
?>

