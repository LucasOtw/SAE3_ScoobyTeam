<?php
require __DIR__ . '/../vendor/autoload.php';

use OTPHP\TOTP;

ob_start();
session_start();

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['active2FA'])){
        
        // A random secret will be generated from this.
        // You should store the secret with the user for verification.
        $otp = TOTP::create();
        $otp->setLabel("test");
        $secret = $otp->getSecret();

        $otp_uri = $otp->getProvisioningUri();
        var_dump($otp_uri);


        echo "The OTP secret is: {$otp->getSecret()}\n";
    } else {
        /*
            AJOUTER DANS LE LOG
        */
    }
} else {
    // Sinon, l'utilisateur ne devrait pas être là...
    /*
        AJOUTER DANS LE LOG
    */
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>TOTP</title>
    </head>
    <body>
        <img src='https://api.qrserver.com/v1/create-qr-code/?data="<?php echo urlencode($otp_uri) ?>"&size=200x200' alt='QR Code OTP'>
    </body>
</html>