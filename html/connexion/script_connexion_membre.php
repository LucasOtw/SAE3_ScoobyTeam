<?php
header("Content-Type: application/json"); // 🔹 Assure que la réponse est en JSON

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["mail"] ?? "";
    $password = $_POST["pwd"] ?? "";

    // Vérifier si les champs sont remplis
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
        exit;
    }

    // ⚠️ Exemple : Vérifier dans la base de données (Remplace par ta logique)
    if ($email === "membre@gmail.com" && $password === "test") {
        echo json_encode(["success" => true, "message" => "Connexion réussie."]);
    } else {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
}
?>