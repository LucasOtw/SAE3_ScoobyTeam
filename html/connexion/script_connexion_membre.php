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
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        exit;
    }

    // Vérifier si l'utilisateur est un membre
    $stmt = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
    $stmt->bindValue(":code_compte", $user['code_compte'], PDO::PARAM_INT);
    $stmt->execute();
    $membre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$membre) {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        exit;
    }

    // Vérifier le mot de passe
    if (!password_verify($password, $user['mdp'])) {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        exit;
    }

    // Authentification réussie
    if(!isset($_SESSION['membre'])){
        $_SESSION['membre'] = $user;
    }

    // on vérifie si il a l'authentification à 2 facteurs

    $checkOTP = $dbh->prepare("SELECT COUNT(*) FROM tripenarvor._compte_otp
    WHERE code_compte = :code_compte AND isActive = true");
    $checkOTP->bindValue(":code_compte",$user['code_compte']);
    $checkOTP->execute();

    $isOTP = $checkOTP->fetchColumn();
    $is2FA = false;

    if($isOTP > 0){
        $is2FA = true;
    }

    echo json_encode([
    "success" => true,
    "message" => "Identification autorisée",
    "otp" => $is2FA,
    "code_compte" => $user['code_compte']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
}
?>
