<?php
// Connexion à la base de données (replacer par les vrais dats)
$servername = "localhost";
$username = "root";
$password = "motdepasse";
$dbname = "votre_base_de_donnees";

$conn = new mysqli($servername, $username, $password, $dbname); //adater comme on utilise un base pgsql

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Récupérer l'ID de l'utilisateur via la session ou via un paramètre GET
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id > 0) {
    // Préparer la requête pour récupérer les données de l'utilisateur
    $sql = "SELECT nom, prenom, email, telephone, adresse, code_postal, ville FROM utilisateurs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Récupérer les données de l'utilisateur
        $user = $result->fetch_assoc();
        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $email = $user['email'];
        $telephone = $user['telephone'];
        $adresse = $user['adresse'];
        $code_postal = $user['code_postal'];
        $ville = $user['ville'];
    } else {
        echo "Utilisateur non trouvé.";
    }

    $stmt->close();
} else {
    echo "ID utilisateur invalide.";
}

// Enregistrement des modifications si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécuriser les données
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $code_postal = htmlspecialchars($_POST['code-postal']);
    $ville = htmlspecialchars($_POST['ville']);
    $cgu_accepte = isset($_POST['cgu']) ? true : false;

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($telephone) &&
        !empty($adresse) && !empty($code_postal) && !empty($ville) && $cgu_accepte) {

        // Mettre à jour les données de l'utilisateur
        $sql = "UPDATE utilisateurs SET nom=?, prenom=?, email=?, telephone=?, adresse=?, code_postal=?, ville=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $nom, $prenom, $email, $telephone, $adresse, $code_postal, $ville, $user_id);

        if ($stmt->execute()) {
            echo "Les informations ont été mises à jour avec succès.";
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
}

$conn->close();
?>
