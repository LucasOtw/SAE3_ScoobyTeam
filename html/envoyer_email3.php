<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.scoobyteam@gmail.com';
        $mail->Password = 'yejz rjye ntfh ryjv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('noreply.scoobyteam@gmail.com', 'ScoobyTeam');
        $mail->addAddress($email);
        $mail->Subject = 'Bienvenue à la Newsletter PACT !';
        $mail->isHTML(true);
        $mail->Body = "
            <h1>Bienvenue à notre Newsletter PACT</h1>
            <p>Merci de vous être inscrit à notre newsletter !</p>
            <p>À bientôt,</p>
            <p><strong>L'équipe PACT</strong></p>
        ";

        try {
            if ($mail->send()) {
                // Inscription réussie, afficher la popup
                echo "<script>showNewsletterConfirmation();</script>";
            }
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    } else {
        echo 'Veuillez saisir une adresse email valide.';
    }
}
?>
