<?php

session_start();
header("Content-Type: application/json");

require_once __DIR__ . ("/../../.security/config.php");

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = $_POST["mail"] ?? "";
    $password =  $_POST["pwd"] ?? "";

    if(empty($email) || empty($password)){
        echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
        exit;
    }

    $stmt = $dbh->prepare("SELECT * FROM tripenarvor._compte
    WHERE mail :email");
    $stmt->bindValue(":email",$email,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        exit;
    }

    // Vérifier si l'utilisateur est un pro
    $checkPro = $dbh->prepare("SELECT * FROM tripenarvor._professionnel
    WHERE code_compte = :code_compte");
    $checkPro->bindValue(":code_compte",$user['code_compte'],PDO::PARAM_INT);
    $checkPro->execute();
    
    $pro = $scheckPro->fetch(PDO::FETCH_ASSOC);

    if(!$pro){
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        exit;
    }

    if(!password_verify($password, $user['mdp'])){
        echo json_encode(['success' => false, "message" => "Identifiants incorrects"]);
        exit;
    }

    if(!isset($_SESSION['pro'])){
        $_SESSION['pro'] = $pro;
    }

    $checkOTP = $dbh->prepare("SELECT COUNT(*) FROM tripenarvor._compte_otp
    WHERE code_compte = :code_compte");
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