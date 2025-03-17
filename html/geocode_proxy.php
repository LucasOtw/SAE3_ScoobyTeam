<?php
require_once (__DIR__ . "/../.security/leaflet.php"); // Charge la clé API

if (!isset($_GET['address'])) {
    http_response_code(400);
    echo json_encode(["error" => "Adresse manquante"]);
    exit;
}

$adresse_enc = urlencode($_GET['address']);
$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$adresse_enc&key=$api_key";

$response = file_get_contents($url);
header('Content-Type: application/json');
echo $response;
?>
