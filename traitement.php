<?php
// Connexion à la base de données (mettre les vrais informations)
$servername = "localhost";
$username = "root";
$password = "motdepasse";
$dbname = "votre_base_de_donnees";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Préparer la requête SQL
$sql = "INSERT INTO utilisateurs (nom, prenom, email, telephone, adresse, code_postal, ville) 
        VALUES ('$nom', '$prenom', '$email', '$telephone', '$adresse', '$code_postal', '$ville')";

// Exécuter la requête
if ($conn->query($sql) === TRUE) {
    echo "Nouvel enregistrement créé avec succès.";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

// Fermer la connexion
$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et sécuriser les données envoyées
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $code_postal = htmlspecialchars($_POST['code-postal']);
    $ville = htmlspecialchars($_POST['ville']);
    $cgu_accepte = isset($_POST['cgu']) ? true : false;

    // Vérifier que toutes les informations requises sont présentes
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($telephone) &&
        !empty($adresse) && !empty($code_postal) && !empty($ville) && $cgu_accepte) {

        // Vous pouvez maintenant traiter ces données (enregistrement dans une base de données, etc.)

        // Exemple : afficher les données
        echo "Nom: " . $nom . "<br>";
        echo "Prenom: " . $prenom . "<br>";
        echo "Email: " . $email . "<br>";
        echo "Téléphone: " . $telephone . "<br>";
        echo "Adresse: " . $adresse . "<br>";
        echo "Code Postal: " . $code_postal . "<br>";
        echo "Ville: " . $ville . "<br>";

        // Redirection ou confirmation
        echo "Merci d'avoir soumis le formulaire.";
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
} else {
    echo "Erreur: le formulaire n'a pas été soumis correctement.";
}
?>
