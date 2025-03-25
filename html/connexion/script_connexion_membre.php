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

        if($existeMembre){
            // si le membre existe, on vérifie le mot de passe

            $checkMDP = $dbh->prepare("SELECT mdp FROM tripenarvor._compte WHERE code_compte = :code_compte");
            $checkMDP->bindValue(':code_compte',$existeUser['code_compte']);
            $checkMDP->execute();

            $mdp_compte = $checkMDP->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password,$mdp_compte)){
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

    // ⚠️ Exemple : Vérifier dans la base de données (Remplace par ta logique)
/*     if ($email === "membre@gmail.com" && $password === "test") {
        echo json_encode(["success" => true, "message" => "Connexion réussie."]);
    } else {
        echo json_encode(["success" => false, "message" => "Identifiants incorrects."]);
    } */
} else {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
}
?>