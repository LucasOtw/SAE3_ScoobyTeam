<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Dossier de destination
        $fileName = basename($_FILES['image']['name']);
        $uploadPath = $uploadDir . $fileName;

        // Déplacer le fichier uploadé
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // Retourner le chemin de l'image
            echo json_encode([
                'success' => true,
                'image_url' => $uploadPath
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Impossible de déplacer le fichier.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Aucun fichier ou erreur lors de l’upload.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Requête non valide.'
    ]);
}
?>
