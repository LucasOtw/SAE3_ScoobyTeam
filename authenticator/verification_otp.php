<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../otphp-11.4.x/src/TOTP.php';
require_once __DIR__ . '/../otphp-11.4.x/src/HOTP.php';

use OTPHP\TOTP;
use PDO;

session_start();

// Inclusion de la configuration (connexion BDD)
require_once __DIR__ . '/../.security/config.php';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer l’OTP envoyé par l’utilisateur
$otp = $_POST['otp'] ?? '';
$code_compte = "test_user"; // Remplace par l'authentification réelle

// Protection brute-force : Limite d’essais (5 max)
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}
if ($_SESSION['attempts'] >= 5) {
    die("Trop d'essais. Réessayez plus tard.");
}

// Récupérer le secret OTP de l’utilisateur
$stmt = $pdo->prepare("SELECT code_OTP FROM compte_otp WHERE code_compte = :code_compte");
$stmt->execute(['code_compte' => $code_compte]);
$secret = $stmt->fetchColumn();

if (!$secret) {
    die("Utilisateur non trouvé ou OTP non configuré.");
}

// Vérifier l’OTP
$totp = TOTP::create($secret);
if ($totp->verify($otp)) {
    echo "Code OTP valide";
    $_SESSION['attempts'] = 0; // Réinitialiser les essais après une réussite
} else {
    $_SESSION['attempts']++;
    echo "Code OTP invalide. Tentative " . $_SESSION['attempts'] . "/5";
}
?>
