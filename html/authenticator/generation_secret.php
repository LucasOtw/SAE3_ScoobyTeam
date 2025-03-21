<?php
// Fonction pour générer la clé secrète TOTP
function generateSecret($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $secret = '';
    for ($i = 0; $i < $length; $i++) {
        $secret .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $secret;
}

// Fonction pour générer un code TOTP à partir de la clé secrète
function generateTOTP($secret) {
    // L'heure actuelle, en secondes depuis l'époque Unix
    $time = floor(time() / 30); // TOTP utilise des intervalles de 30 secondes

    // Convertir l'heure en chaîne de 8 octets (big endian)
    $time = pack('N*', 0) . pack('N*', $time); // Pad pour correspondre à 8 octets

    // Clé secrète en base32
    $secret = base32_decode($secret);

    // Calculer HMAC-SHA1
    $hmac = hash_hmac('sha1', $time, $secret, true);

    // Extraire un code de 6 chiffres à partir du hmac
    $offset = ord($hmac[19]) & 0x0F;
    $code = unpack('N', substr($hmac, $offset, 4))[1] & 0x7FFFFFFF; // Masque pour obtenir un nombre positif

    // Limiter le code à 6 chiffres
    $code = $code % 1000000;
    return str_pad($code, 6, '0', STR_PAD_LEFT);
}

// Fonction pour décoder une clé secrète Base32
function base32_decode($base32) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $base32 = strtoupper($base32);
    $padding = strlen($base32) % 8;
    if ($padding > 0) {
        $base32 .= str_repeat('=', 8 - $padding);
    }

    $binary = '';
    for ($i = 0; $i < strlen($base32); $i++) {
        $binary .= str_pad(decbin(strpos($alphabet, $base32[$i])), 5, '0', STR_PAD_LEFT);
    }

    $bytes = '';
    for ($i = 0; $i < strlen($binary); $i += 8) {
        $bytes .= chr(bindec(substr($binary, $i, 8)));
    }

    return $bytes;
}

// Exemple d'utilisation
$secret = generateSecret(); // Génère une clé secrète
echo "Secret: " . $secret . "\n";

$code = generateTOTP($secret); // Génère un code TOTP basé sur le secret
echo "Code TOTP: " . $code . "\n";

// Vous pouvez ensuite valider le code TOTP en comparant le code généré avec celui que l'utilisateur soumet.
?>
