<?php
require 'vendor/autoload.php';
use OTPHP\TOTP;

// Simuler un compte utilisateur (à remplacer par la session réelle)
$code_compte = 1;

// Récupération du code OTP envoyé par le client
$otp = $_POST['otp_code'] ?? '';

if (empty($otp)) {
    echo json_encode(['success' => false, 'message' => 'Code OTP manquant']);
    exit;
}

// Connexion à la base de données
require_once __DIR__ . ("/../../.security/config.php");;

try {
    // Récupérer le secret OTP du compte
    $stmt = $pdo->prepare("SELECT code_OTP FROM compte_otp WHERE code_compte = ?");
    $stmt->execute([$code_compte]);
    $secret = $stmt->fetchColumn();

    if (!$secret) {
        echo json_encode(['success' => false, 'message' => 'Aucun secret OTP trouvé pour ce compte']);
        exit;
    }

    // Vérification avec otphp
    $totp = \OTPHP\TOTP::create($secret);
    $isValid = $totp->verify($otp);

    if ($isValid) {
        echo json_encode(['success' => true, 'message' => '✅ Code OTP valide']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Code OTP invalide']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
}
