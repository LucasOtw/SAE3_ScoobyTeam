<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers nécessaires de PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

include_once('recupInfosCompte.php');

/* echo "<pre>";
var_dump($compte);
var_dump($monCompteMembre);
var_dump($_adresse);
echo "</pre>"; */

// Récupérez les données du formulaire
$email = filter_var($compte['mail'], FILTER_VALIDATE_EMAIL);
$telephone = $compte['telephone'];
$adresse_postale = $_adresse['adresse_postal'];
$code_postal = $_adresse['code_postal'];
$pseudo = $monCompteMembre['pseudo'];
$nom = $monCompteMembre['nom'];
$prenom = $monCompteMembre['prenom'];
$ville = $_adresse['ville'];

if ($email) {
    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.scoobyteam@gmail.com'; // Remplacez par votre e-mail
        $mail->Password = 'yejz rjye ntfh ryjv'; // Utilisez un mot de passe d'application
        $mail->Username = 'noreply.scoobyteam@gmail.com'; // Remplacez par votre e-mail
        $mail->Password = 'yejz rjye ntfh ryjv'; // Utilisez un mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Définir l'expéditeur et le destinataire
        //$mail->setFrom('harry.rajic56@gmail.com', 'ScoobyTeam');
        $mail->addAddress($email, $prenom . ' ' . $nom);

        // Construire le contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Vos données personnelles';

        // Corps HTML du message
        $mail->Body = "
            <h1>Demande prise en compte</h1>
            <p>Bonjour <strong>{$prenom} {$nom}</strong>,</p>
            <p>Voici les informations que nous avons reçues :</p>
            <ul>
                <li><strong>Email :</strong> {$email}</li>
                <li><strong>Téléphone :</strong> {$telephone}</li>
                <li><strong>Adresse postale :</strong> {$adresse_postale}</li>
                <li><strong>Code postal :</strong> {$code_postal}</li>
                <li><strong>Ville :</strong> {$ville}</li>
                <li><strong>Pseudo :</strong> {$pseudo}</li>
            </ul>
            <p>Nous vous confirmons que votre demande a bien été prise en compte.</p>
            <p>Nous restons à votre disposition pour toute question ou assistance supplémentaire.</p>
            <p>Cordialement,</p>
            <p><strong>L’équipe ScoobyTeam</strong></p>
        ";

        // Version texte alternative
        $mail->AltBody = "Bonjour {$prenom} {$nom},\n\n" .
            "Voici les informations que nous avons reçues :\n" .
            "- Email : {$email}\n" .
            "- Téléphone : {$telephone}\n" .
            "- Adresse postale : {$adresse_postale}\n" .
            "- Code postal : {$code_postal}\n" .
            "- Ville : {$ville}\n" .
            "- Pseudo : {$pseudo}\n\n" .
            "Nous vous confirmons que votre demande a bien été prise en compte.\n" .
            "Cordialement,\nL’équipe ScoobyTeam";

        // Envoyer l'e-mail
        if ($mail->send()) {
            //header("Location: consulter_compte_membre.php");
            //exit;
            echo "HAHAHA";
        }
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi du message : {$mail->ErrorInfo}";
    }
} else {
    echo "Adresse e-mail invalide ou non fournie.";
}
?>
