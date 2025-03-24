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