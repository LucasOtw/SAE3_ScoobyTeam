<?php
session_start();
header("Content-Type: application/json");

require_once __DIR__ . ("/../../.security/config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["mail"] ?? "";
    $password = $_POST["pwd"] ?? "";

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
        exit;
    }

    // Vérifier si l'utilisateur existe
    $stmt = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail = :email");
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects 1"]);
        exit;
    }

    // Vérifier si l'utilisateur est un membre
    $stmt = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
    $stmt->bindValue(":code_compte", $user['code_compte'], PDO::PARAM_INT);
    $stmt->execute();
    $membre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$membre) {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects 2"]);
        exit;
    }

    // Vérifier le mot de passe
    if (!password_verify($password, $user['mdp'])) {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects 3"]);
        exit;
    }

    // Authentification réussie
    $_SESSION['membre'] = $user;
    echo json_encode(["success" => true, "message" => "Identification autorisée"]);
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
}
?>
