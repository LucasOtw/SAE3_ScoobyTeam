<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers nécessaires de PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $theme = $_POST['theme'];
    $question = $_POST['textAreaAvis'];

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
    $mail->addAddress('noreply.scoobyteam@gmail.com' , 'Destinataire');

    // Sujet de l'email
    $mail->Subject = 'Nouvelle question soumise';

    // Corps du message (avec toutes les infos)
    $mail->Body = "
                <h1>Demande prise en compte</h1>
                <p>Bonjour <strong>{$prenom} {$nom}</strong>,</p>
                <p>Voici les informations que nous avons reçues :</p>
                <ul>
                    <li><strong>Thème :</strong> {$theme}</li>
                    <li><strong>Question :</strong> {$question}</li>
                </ul>
                <p>Nous vous confirmons que votre demande a bien été prise en compte.</p>
                <p>Nous restons à votre disposition pour toute question ou assistance supplémentaire.</p>
                <p>Cordialement,</p>
                <p><strong>L’équipe ScoobyTeam</strong></p>
            ";


    // Si tout va bien, envoi de l'email
    if(!$mail->send()) {
        echo 'Erreur : ' . $mail->ErrorInfo;
    } else {
        echo 'Le message a été envoyé avec succès !';
    }
}
?>

