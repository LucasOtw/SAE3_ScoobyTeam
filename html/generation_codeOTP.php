<?php
use OTPHP\TOTP;

ob_start();
session_start();

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['active2FA'])){

        $clock = new MyClock(); // Your own implementation of a PSR-20 Clock
        
        // A random secret will be generated from this.
        // You should store the secret with the user for verification.
        $otp = TOTP::generate($clock);
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