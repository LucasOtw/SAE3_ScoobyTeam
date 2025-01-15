<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre_offre = $_POST['titre_offre']; // Récupérer le titre de l'offre
    $uploadedPhotos = [];

    // Vérifier si des fichiers sont envoyés
    if (isset($_FILES['photos'])) {
        $files = $_FILES['photos'];
        $uploadDir = "../images/offres/" . str_replace(' ', '', $titre_offre) . "/";

        // Parcourir les fichiers et les enregistrer
        for ($i = 0; $i < count($files['name']); $i++) {
            $tmpName = $files['tmp_name'][$i];
            $name = basename($files['name'][$i]);
            $destination = $uploadDir . $name;

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedPhotos[] = $destination; // Ajouter le chemin de la photo uploadée
            }
        }

        // Retourner une réponse JSON
        echo json_encode([
            'success' => true,
            'photos' => $uploadedPhotos,
        ]);
        exit;
    }

    // Pas de fichiers envoyés
    echo json_encode(['success' => false, 'message' => 'Aucune photo envoyée.']);
}
?>
