<?php
echo "<h1>Test OTP</h1>";

// Générer un secret OTP
echo "<h2>1️⃣ Génération du secret OTP</h2>";
$response = file_get_contents("../authenticator/generation_secret.php");
echo "<pre>$response</pre>";

// Afficher le QR Code
echo "<h2>2️⃣ QR Code (Scanne-le avec Google Authenticator)</h2>";
echo '<img src="../authenticator/qrcode.php" alt="QR Code OTP">';

// Formulaire pour tester la vérification OTP
echo '<h2>3️⃣ Tester un code OTP</h2>';
?>
<form method="POST" action="../authenticator/verification_otp.php">
    <input type="text" name="otp" placeholder="Entrez votre code OTP" required>
    <button type="submit">Vérifier</button>
</form>
