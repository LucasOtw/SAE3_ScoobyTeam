<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers nécessaires de PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
    $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8');
    $theme = htmlspecialchars($_POST['theme'], ENT_QUOTES, 'UTF-8');
    $question = htmlspecialchars($_POST['textAreaAvis'], ENT_QUOTES, 'UTF-8');

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
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Définir l'expéditeur et le destinataire
            $mail->setFrom('noreply.scoobyteam@gmail.com', 'ScoobyTeam');
            $mail->addAddress('noreply.scoobyteam@gmail.com' , 'Destinataire');

            // Construire le contenu de l'e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Votre question a bien été reçue';

            // Corps HTML du message
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

            // Version texte alternative
            $mail->AltBody = "Bonjour {$prenom} {$nom},\n\n" .
                "Voici les informations que nous avons reçues :\n" .
                "- Thème : {$theme}\n" .
                "- Question : {$question}\n\n" .
                "Nous vous confirmons que votre demande a bien été prise en compte.\n" .
                "Cordialement,\nL’équipe ScoobyTeam";

            // Envoyer l'e-mail
            if ($mail->send()) {
                echo 'Le message a été envoyé avec succès !';
            }
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi du message : {$mail->ErrorInfo}";
        }
    } else {
        echo "Adresse e-mail invalide ou non fournie.";
    }
}
?>
