<?php
  // Définir le chemin vers le dossier "leaflet" pour inclure les fichiers CSS et JS
  $leaflet_path = '../leaflet/';
  
  // Vérifier si les fichiers nécessaires existent
  if (!file_exists($leaflet_path . 'leaflet.css') || !file_exists($leaflet_path . 'leaflet.js')) {
    die('Les fichiers Leaflet nécessaires sont manquants.');
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carte Interactive avec Leaflet</title>
  
  <!-- Inclure le fichier CSS de Leaflet -->
  <?php require_once($leaflet_path . 'leaflet.css'); ?>

  <style>
    /* Style pour afficher correctement la carte */
    #map {
      height: 500px;
      width: 100%;
      border: 1px solid #ccc; /* Ajouter une bordure pour mieux visualiser la carte */
    }
  </style>
</head>
<body>

  <h1>Ma Carte Interactive</h1>
  <div id="map"></div>

  <!-- Inclure le fichier JS de Leaflet -->
  <?php require_once($leaflet_path . 'leaflet.js'); ?>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Vérifier si Leaflet est bien chargé
      if (typeof L === "undefined") {
        console.error("Leaflet n'a pas été chargé correctement !");
        return;
      }

      console.log("Leaflet version:", L.version); // Debug

      // Initialisation de la carte centrée sur Paris
      var map = L.map('map').setView([48.8566, 2.3522], 13);

      // Ajouter un fond de carte OpenStreetMap
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Ajouter un marker sur Paris
      L.marker([48.8566, 2.3522]).addTo(map)
        .bindPopup('<b>Paris</b><br>Ville lumière!')
        .openPopup();
    });
  </script>

</body>
</html>
