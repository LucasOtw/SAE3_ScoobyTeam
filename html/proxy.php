<?php
require_once (__DIR__ . "/../.security/leaflet.php"); // Vérifie que la clé est bien incluse

header("Content-Type: text/plain");

echo "Clé API chargée : " . ($api_key ?? "Non trouvée") . "\n";

$test_url = "https://tile.thunderforest.com/cycle/10/512/340.png?apikey=$api_key";
echo "URL test : $test_url \n";

$response = file_get_contents($test_url);
if ($response === false) {
    echo "Erreur lors du chargement des tuiles !";
} else {
    echo "Tuiles chargées avec succès !";
}
?>
