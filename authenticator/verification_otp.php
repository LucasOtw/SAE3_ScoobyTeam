<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;
use PDO;

session_start();

$pdo = new PDO($dsn, $username, $password);

// Récupérer l’OTP envoyé par l’utilisateur
$otp = $_POST['otp'] ?? '';
$username = "test_user";  // Remplace par l'authentification réelle

// Limite d’essais pour éviter les attaques brute-force
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}
if ($_SESSION['attempts'] >= 5) {
    die("Trop d'essais. Réessayez plus tard.");
}

$_SESSION['attempts']++;

// Récupérer le secret de l’utilisateur
$stmt = $pdo->prepare("SELECT secret FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$secret = $stmt->fetchColumn();

if (!$secret) {
    die("❌ Utilisateur non trouvé.");
}

// Vérifier l’OTP
$totp = TOTP::create($secret);
if ($totp->verify($otp)) {
    echo "✅ Code OTP valide";
    $_SESSION['attempts'] = 0; // Réinitialiser les essais après une réussite
} else {
    echo "❌ Code OTP invalide";
}
?>
