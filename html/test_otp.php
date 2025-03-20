<?php
echo "<h1>Test OTP</h1>";

// Générer un secret OTP
echo "<h2>1️⃣ Génération du secret OTP</h2>";
include_once __DIR__ . "/../authenticator/generation_secret.php";

// Afficher le QR Code
echo "<h2>2️⃣ QR Code (Scanne-le avec Google Authenticator)</h2>";
echo '<img src="qrcode.png" alt="QR Code">';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Formulaire pour tester la vérification OTP
echo '<h2>3️⃣ Tester un code OTP</h2>';
?>
<form method="POST" action="/../authenticator/verification_otp.php">
    <input type="text" name="otp" placeholder="Entrez votre code OTP" required>
    <button type="submit">Vérifier</button>
</form>
