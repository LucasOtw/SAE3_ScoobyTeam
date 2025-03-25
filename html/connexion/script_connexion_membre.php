<?php
header("Content-Type: application/json");

require_once __DIR__ . ("/../.security/config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["mail"] ?? "";
    $password = $_POST["pwd"] ?? "";

    // Vérifier si les champs sont remplis
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
        exit;
    }

    // si tous les champs sont remplis, on peut passer à la vérification

    $existeUser = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail = :email");
    $existeUser->bindValue(":email",$email);
    $existeUser->execute();

    $existeUser = $existeUser->fetch(PDO::FETCH_ASSOC);

    if($existeUser){
        // si l'utilisateur existe, on vérifie si c'est un membre
        $existeMembre = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
        $existeMembre->bindValue(":code_compte",$existeUser['code_compte']);
        $existeMembre->execute();

        $existeMembre = $existeMembre->fetch(PDO::FETCH_ASSOC);

        if($existeMembre){
            // si le membre existe, on vérifie le mot de passe

            $mdp_compte = $existeUser['mdp'];

            if(password_verify($password,$mdp_compte['mdp'])){
                // si les mots de passe correspondent
                // l'utilisateur peut être connecté
                $_SESSION['membre'] = $existeUser;
                echo json_encode(["success" => true, "message" => "Identification autorisée"]);
                exit;
            }

        } else {
            echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
}
?>