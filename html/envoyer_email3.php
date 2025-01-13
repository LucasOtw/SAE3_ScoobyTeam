<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers nécessaires de PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'adresse email du formulaire
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Créer une instance de PHPMailer
        $mail = new PHPMailer;

        // Configuration de PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.scoobyteam@gmail.com'; // Ton adresse email
        $mail->Password = 'yejz rjye ntfh ryjv'; // Ton mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Définir l'expéditeur et le destinataire
        $mail->setFrom('noreply.scoobyteam@gmail.com', 'ScoobyTeam');
        $mail->addAddress($email); // Envoyer à l'adresse email récupérée

        // Sujet de l'email
        $mail->Subject = 'Bienvenue à la Newsletter PACT !';

        // Construire le contenu de l'e-mail
        $mail->isHTML(true);

        // Corps du message
        $mail->Body = "
            <h1>Bienvenue à notre Newsletter PACT</h1>
            <p>Merci de vous être inscrit à notre newsletter !</p>
            <p>Restez à l'écoute pour découvrir nos dernières offres et nos informations sur la Bretagne.</p>
            <br>
            <p>À bientôt,</p>
            <p><strong>L'équipe PACT</strong></p>
        ";
}
?>
