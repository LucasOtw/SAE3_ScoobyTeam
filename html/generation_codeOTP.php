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

        if($existeCode > 0){
            // si le code existe déjà, l'utilisateur n'a rien à faire là...
            /*
                AJOUTER INTRUSION DANS LE LOG
            */
            header('location: modif_mdp_membre.php');
            exit;
        }

        // Si le code continue de s'exécuter, c'est qu'il n'y a pas de ligne liée à l'utilisateur dans _compte_otp
        // On peut donc la rajouter

        $ajoutCodeSecret = $dbh->prepare('INSERT INTO tripenarvor._compte_otp (code_compte,code_secret)
        VALUES (:code_compte,:_secret)');
        $ajoutCodeSecret->bindValue(':code_compte',$compte['code_compte']);
        $ajoutCodeSecret->bindValue(':_secret',$secret);

        try{
            $ajoutCodeSecret->execute();

/*             header('location: modif_mdp_membre.php');
            exit; */
        } catch (PDOException $e){
            die("Échec exécution BDD : " . $e->getMessage());
        }

        // $otp_uri = $otp->getProvisioningUri();
        // var_dump($otp_uri);


        // echo "The OTP secret is: {$otp->getSecret()}\n";
    } else {
        /*
            AJOUTER INTRUSION DANS LE LOG
        */
    }
} else {
    // Sinon, l'utilisateur ne devrait pas être là...
    /*
        AJOUTER INTRUSION DANS LE LOG
    */
}

?>