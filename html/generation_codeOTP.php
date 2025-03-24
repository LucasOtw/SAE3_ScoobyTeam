<?php
require __DIR__ . '/../vendor/autoload.php';

use OTPHP\TOTP;

ob_start();
session_start();

include_once __DIR__ . '/recupInfosCompte.php';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if(isset($_POST['active2FA'])){

        // On génère le code secret

        $otp = TOTP::create();
        $otp->setLabel("Scooby-Team");
        $secret = $otp->getSecret();

        // Le code secret est généré.
        // On vérifie son existence AU CAS OÙ

        $checkCodeSecret = $dbh->prepare('SELECT COUNT(*) FROM tripenarvor._compte_otp
        WHERE code_compte = :code_compte');
        $checkCodeSecret->bindValue(':code_compte',$compte['code_compte']);
        $checkCodeSecret->execute();

        $existeCode = $checkCodeSecret->fetchColumn();
        var_dump($existeCode);

        // $otp_uri = $otp->getProvisioningUri();
        // var_dump($otp_uri);


        // echo "The OTP secret is: {$otp->getSecret()}\n";
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